<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Food;
use App\Models\FoodUser;
use App\Models\FoodHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\FileUpload\InputFile;

class TelegramController extends Controller
{
    public function webhook(Request $request)
    {
        try {
            $update = Telegram::getWebhookUpdate();
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $username = $message->getChat()->getUsername();
            $firstName = $message->getChat()->getFirstName();
            $lastName = $message->getChat()->getLastName();
            $text = $message->getText();

            // Handle /start command
            if ($text === '/start') {
                $user = User::where('telegram_chat_id', $chatId)->first();

                if (!$user) {
                    // Create new user if not exists
                    $user = User::create([
                        'name' => $firstName . ' ' . $lastName,
                        'email' => $username ? $username . '@telegram.user' : 'user_' . $chatId . '@telegram.user',
                        'password' => bcrypt(Str::random(16)),
                        'telegram_chat_id' => $chatId,
                        'telegram_username' => $username,
                        'telegram_first_name' => $firstName,
                        'telegram_last_name' => $lastName,
                        'is_telegram_verified' => true,
                    ]);
                }

                $keyboard = Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => '🍽 Ovqat tanlash', 'callback_data' => 'select_food']),
                        Keyboard::inlineButton(['text' => '📋 Ovqat tarixi', 'callback_data' => 'food_history'])
                    ]);

                $response = Telegram::sendMessage([
                    'chat_id' => $chatId,
                    'text' => "👋 *Assalomu alaykum!*\n\nMen sizga random (tasodifiy) ovqat tanlab berishda yordam beraman. Quyidagi tugmalardan birini tanlang:",
                    'parse_mode' => 'Markdown',
                    'reply_markup' => $keyboard
                ]);

                $user->updateLastMessageId($response->getMessageId());
                return response()->json(['status' => 'success']);
            }

            // Handle callback queries (button clicks)
            if ($update->has('callback_query')) {
                $callbackQuery = $update->getCallbackQuery();
                $callbackData = $callbackQuery->getData();
                $chatId = $callbackQuery->getMessage()->getChat()->getId();
                $messageId = $callbackQuery->getMessage()->getMessageId();
                $user = User::where('telegram_chat_id', $chatId)->first();

                if (!$user) {
                    return response()->json(['status' => 'error', 'message' => 'User not found']);
                }

                switch ($callbackData) {
                    case 'select_food':
                        $this->sendRandomFood($user, $messageId);
                        break;
                    case 'accept_food':
                        $this->handleFoodAcceptance($user, $callbackQuery);
                        break;
                    case 'reject_food':
                        $this->sendRandomFood($user, $messageId);
                        break;
                    case 'food_history':
                        $this->showFoodHistory($user, $messageId);
                        break;
                }
            }

            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error('Telegram webhook error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

    private function sendRandomFood(User $user, $messageId = null)
    {
        try {
            // Send loading message if no messageId provided
            if (!$messageId) {
                $response = Telegram::sendMessage([
                    'chat_id' => $user->telegram_chat_id,
                    'text' => "⏳ Ovqat tanlanmoqda..."
                ]);
                $messageId = $response->getMessageId();
                $user->updateLastMessageId($messageId);
            }

            // Get the last 5 food IDs from user's food_user
            $recentFoodIds = FoodUser::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->pluck('food_id')
                ->toArray();

            // Get a random food that's not in the recent history
            $food = Food::whereNotIn('id', $recentFoodIds)
                ->inRandomOrder()
                ->first();

            if (!$food) {
                Telegram::editMessageText([
                    'chat_id' => $user->telegram_chat_id,
                    'message_id' => $messageId,
                    'text' => "😔 Kechirasiz, hozircha ovqatlar mavjud emas."
                ]);
                return;
            }

            // Check if we need to remove the oldest food_user entry
            $historyCount = FoodUser::where('user_id', $user->id)->count();

            if ($historyCount >= 5) {
                // Delete the oldest entry
                FoodUser::where('user_id', $user->id)
                    ->orderBy('created_at', 'asc')
                    ->first()
                    ->delete();
            }

            // Add new food to food_user
            FoodUser::create([
                'user_id' => $user->id,
                'food_id' => $food->id
            ]);

            $keyboard = Keyboard::make()
                ->inline()
                ->row([
                    Keyboard::inlineButton(['text' => '✅ Qabul qilish', 'callback_data' => 'accept_food']),
                    Keyboard::inlineButton(['text' => '❌ Rad etish', 'callback_data' => 'reject_food'])
                ]);

            // Send food image if exists
            if ($food->image) {
                $imagePath = storage_path('app/public/' . $food->image);
                if (file_exists($imagePath)) {
                    // Delete the loading message
                    Telegram::deleteMessage([
                        'chat_id' => $user->telegram_chat_id,
                        'message_id' => $messageId
                    ]);

                    // Send new photo message
                    $response = Telegram::sendPhoto([
                        'chat_id' => $user->telegram_chat_id,
                        'photo' => InputFile::create($imagePath, basename($food->image)),
                        'caption' => "🍽 *{$food->name_uz}*\n\n📝 {$food->description}",
                        'parse_mode' => 'Markdown',
                        'reply_markup' => $keyboard
                    ]);
                    $user->updateLastMessageId($response->getMessageId());
                } else {
                    Telegram::editMessageText([
                        'chat_id' => $user->telegram_chat_id,
                        'message_id' => $messageId,
                        'text' => "🍽 *{$food->name_uz}*\n\n📝 {$food->description}",
                        'parse_mode' => 'Markdown',
                        'reply_markup' => $keyboard
                    ]);
                }
            } else {
                Telegram::editMessageText([
                    'chat_id' => $user->telegram_chat_id,
                    'message_id' => $messageId,
                    'text' => "🍽 *{$food->name_uz}*\n\n📝 {$food->description}",
                    'parse_mode' => 'Markdown',
                    'reply_markup' => $keyboard
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error sending random food: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            Telegram::sendMessage([
                'chat_id' => $user->telegram_chat_id,
                'text' => "😔 Kechirasiz, xatolik yuz berdi. Iltimos, qaytadan urinib ko'ring."
            ]);
        }
    }

    private function handleFoodAcceptance(User $user, $callbackQuery)
    {
        try {
            $message = $callbackQuery->getMessage();
            $caption = $message->getCaption();

            if (!$caption) {
                throw new \Exception("No caption found in message");
            }

            // Extract food name more reliably
            $lines = explode("\n", $caption);
            $foodName = trim(str_replace("🍽", "", $lines[0]));
            $foodName = str_replace("*", "", $foodName); // Remove markdown bold

            if (empty($foodName)) {
                throw new \Exception("Could not extract food name from caption");
            }

            $food = Food::where('name_uz', $foodName)->first();

            if (!$food) {
                throw new \Exception("Food not found: {$foodName}");
            }

            // Send confirmation message
            $response = Telegram::sendMessage([
                'chat_id' => $user->telegram_chat_id,
                'text' => "🎉 *Ovqat tanlandi!*\n\n*{$food->name_uz}* - Mazza qilib iste'mol qiling! 😋\n\nYana bir marta tanlamoqchi bo'lsangiz, quyidagi tugmani bosing:",
                'parse_mode' => 'Markdown',
                'reply_markup' => Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => '🍽 Ovqat tanlash', 'callback_data' => 'select_food'])
                    ])
            ]);
            $user->updateLastMessageId($response->getMessageId());

        } catch (\Exception $e) {
            Log::error('Error handling food acceptance: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            Telegram::sendMessage([
                'chat_id' => $user->telegram_chat_id,
                'text' => "😔 Kechirasiz, xatolik yuz berdi. Iltimos, qaytadan urinib ko'ring."
            ]);
        }
    }

    private function showFoodHistory(User $user, $messageId)
    {
        try {
            // Get user's food history with food details
            $foodHistory = FoodHistory::with('food')
                ->where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->take(5)
                ->get();

            if ($foodHistory->isEmpty()) {
                Telegram::editMessageText([
                    'chat_id' => $user->telegram_chat_id,
                    'message_id' => $messageId,
                    'text' => "📋 *Ovqat tarixi*\n\nHozircha tanlangan ovqatlar mavjud emas.",
                    'parse_mode' => 'Markdown',
                    'reply_markup' => Keyboard::make()
                        ->inline()
                        ->row([
                            Keyboard::inlineButton(['text' => '🍽 Ovqat tanlash', 'callback_data' => 'select_food']),
                            Keyboard::inlineButton(['text' => '📋 Ovqat tarixi', 'callback_data' => 'food_history'])
                        ])
                ]);
                return;
            }

            // Format food history message
            $message = "📋 *So'nggi tanlangan ovqatlar:*\n\n";
            foreach ($foodHistory as $index => $history) {
                $food = $history->food;
                $date = $history->created_at->format('d.m.Y H:i');

                $message .= ($index + 1) . ". *{$food->name_uz}*\n";
                $message .= "   📅 {$date}\n";
                $message .= "   ⏱️ {$history->meal_type}\n\n";
            }

            Telegram::editMessageText([
                'chat_id' => $user->telegram_chat_id,
                'message_id' => $messageId,
                'text' => $message,
                'parse_mode' => 'Markdown',
                'reply_markup' => Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => '🍽 Ovqat tanlash', 'callback_data' => 'select_food']),
                        Keyboard::inlineButton(['text' => '📋 Ovqat tarixi', 'callback_data' => 'food_history'])
                    ])
            ]);
        } catch (\Exception $e) {
            Log::error('Error showing food history: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            Telegram::editMessageText([
                'chat_id' => $user->telegram_chat_id,
                'message_id' => $messageId,
                'text' => "😔 Kechirasiz, xatolik yuz berdi. Iltimos, qaytadan urinib ko'ring.",
                'reply_markup' => Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => '🍽 Ovqat tanlash', 'callback_data' => 'select_food']),
                        Keyboard::inlineButton(['text' => '📋 Ovqat tarixi', 'callback_data' => 'food_history'])
                    ])
            ]);
        }
    }
}
