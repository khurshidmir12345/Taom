<?php

namespace App\Http\Controllers;

use App\Models\Cafe;
use App\Models\BotUser;
use App\Models\Product;
use App\Models\ProductVote;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Telegram\Bot\Api;
use Telegram\Bot\Keyboard\Keyboard;
use Telegram\Bot\FileUpload\InputFile;
use Illuminate\Support\Facades\Log;

class MenuBotController extends Controller
{
    public function handle(Request $request, string $token)
    {
        try {
            $cafe = Cafe::query()->where('bot_token', $token)->firstOrFail();
            $telegram = new Api($token);
            $update = $telegram->getWebhookUpdate();
            
            // Handle callback query (inline button clicks)
            if ($update->has('callback_query')) {
                $callbackQuery = $update->getCallbackQuery();
                $chatId = $callbackQuery->getMessage()->getChat()->getId();
                $text = $callbackQuery->getData();
                $messageId = $callbackQuery->getMessage()->getMessageId();
                
                $botUser = BotUser::where('chat_id', $chatId)->firstOrFail();
                $botUser->update(['last_message_id' => $messageId]);
                
                if ($text === 'back') {
                    $this->handleBack($telegram, $botUser);
                    return;
                }

                if ($text === 'show_menu') {
                    $this->sendCategories($telegram, $botUser, $cafe);
                    return;
                }

                // Handle like/dislike votes
                if (str_starts_with($text, 'like_') || str_starts_with($text, 'dislike_')) {
                    $this->handleVote($telegram, $botUser, $text, $callbackQuery);
                    return;
                }

                // Handle product selection
                if (str_starts_with($text, 'product_')) {
                    $this->sendProductDetails($telegram, $botUser, $cafe, $text);
                    return;
                }

                // Handle category selection - directly send products
                $this->sendProducts($telegram, $botUser, $cafe, $text);
                return;
            }

            // Handle regular messages
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $text = $message->getText();
            
            // Find or create bot user
            $botUser = BotUser::firstOrCreate(
                ['chat_id' => $chatId],
                [
                    'cafe_id' => $cafe->id,
                    'username' => $message->getChat()->getUsername(),
                    'first_name' => $message->getChat()->getFirstName(),
                    'last_name' => $message->getChat()->getLastName(),
                    'step' => 'start'
                ]
            );

            // Handle /start command
            if ($text === '/start') {
                $this->sendWelcomeMessage($telegram, $botUser);
                return;
            }

            // Handle back button
            if ($text === '🔙 Back') {
                $this->handleBack($telegram, $botUser);
                return;
            }

            // Handle steps
            switch ($botUser->step) {
                case 'start':
                    $this->sendWelcomeMessage($telegram, $botUser);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('MenuBot error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function sendWelcomeMessage($telegram, $botUser)
    {
        try {
            // Try to delete previous message if exists
            if ($botUser->last_message_id) {
                try {
                    $telegram->deleteMessage([
                        'chat_id' => $botUser->chat_id,
                        'message_id' => $botUser->last_message_id
                    ]);
                } catch (\Exception $e) {
                    // Ignore if message not found
                    Log::info('Previous message not found, continuing...');
                }
            }

            $keyboard = Keyboard::make()
                ->inline()
                ->row([
                    Keyboard::inlineButton(['text' => '🍽 Menyuni ko\'rish', 'callback_data' => 'show_menu'])
                ]);

            $response = $telegram->sendMessage([
                'chat_id' => $botUser->chat_id,
                'text' => "👋 *Assalomu alaykum!*\n\nMen sizga kafe menyusini ko'rsatishda yordam beraman.",
                'parse_mode' => 'Markdown',
                'reply_markup' => $keyboard
            ]);

            $botUser->update([
                'last_message_id' => $response->getMessageId(),
                'step' => 'start'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in sendWelcomeMessage: ' . $e->getMessage());
            // Try to send a simple message if the main message fails
            try {
                $response = $telegram->sendMessage([
                    'chat_id' => $botUser->chat_id,
                    'text' => "👋 Assalomu alaykum! Menyuni ko'rish uchun /start buyrug'ini yuboring."
                ]);
                $botUser->update([
                    'last_message_id' => $response->getMessageId(),
                    'step' => 'start'
                ]);
            } catch (\Exception $e2) {
                Log::error('Error sending fallback message: ' . $e2->getMessage());
            }
        }
    }

    private function sendCategories($telegram, $botUser, $cafe)
    {
        try {
            // Delete previous message
            if ($botUser->last_message_id) {
                try {
                    $telegram->deleteMessage([
                        'chat_id' => $botUser->chat_id,
                        'message_id' => $botUser->last_message_id
                    ]);
                } catch (\Exception $e) {
                    Log::info('Previous message not found, continuing...');
                }
            }

            $categories = ProductCategory::where('cafe_id', $cafe->id)->get();
            
            if ($categories->isEmpty()) {
                $keyboard = Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => '🔙 Back', 'callback_data' => 'back'])
                    ]);

                $response = $telegram->sendMessage([
                    'chat_id' => $botUser->chat_id,
                    'text' => "😔 Kechirasiz, hozircha kategoriyalar mavjud emas.",
                    'reply_markup' => $keyboard
                ]);

                $botUser->update([
                    'last_message_id' => $response->getMessageId(),
                    'step' => 'category',
                    'previous_step' => 'start'
                ]);
                return;
            }
            
            $keyboard = Keyboard::make()
                ->inline();
                
            foreach ($categories as $category) {
                $keyboard->row([
                    Keyboard::inlineButton(['text' => $category->name, 'callback_data' => $category->name])
                ]);
            }
            
            $keyboard->row([
                Keyboard::inlineButton(['text' => '🔙 Back', 'callback_data' => 'back'])
            ]);

            $response = $telegram->sendMessage([
                'chat_id' => $botUser->chat_id,
                'text' => "🍽 *Kategoriyalar*\n\nIltimos, kategoriyani tanlang:",
                'parse_mode' => 'Markdown',
                'reply_markup' => $keyboard
            ]);

            $botUser->update([
                'last_message_id' => $response->getMessageId(),
                'step' => 'category',
                'previous_step' => 'start'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in sendCategories: ' . $e->getMessage());
            // If something goes wrong, try to recover by sending welcome message
            $botUser->update([
                'step' => 'start',
                'previous_step' => null
            ]);
            $this->sendWelcomeMessage($telegram, $botUser);
        }
    }

    private function sendProducts($telegram, $botUser, $cafe, $categoryName)
    {
        try {
            Log::info('Sending products for category: ' . $categoryName, [
                'cafe_id' => $cafe->id,
                'bot_user_id' => $botUser->id,
                'step' => $botUser->step
            ]);

            // Delete previous message
            if ($botUser->last_message_id) {
                try {
                    $telegram->deleteMessage([
                        'chat_id' => $botUser->chat_id,
                        'message_id' => $botUser->last_message_id
                    ]);
                } catch (\Exception $e) {
                    Log::info('Previous message not found, continuing...');
                }
            }

            // Find category by name
            $category = ProductCategory::where('cafe_id', $cafe->id)
                ->where('name', $categoryName)
                ->first();

            if (!$category) {
                Log::error('Category not found: ' . $categoryName, [
                    'cafe_id' => $cafe->id
                ]);
                
                $keyboard = Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => '🔙 Back', 'callback_data' => 'back'])
                    ]);

                $response = $telegram->sendMessage([
                    'chat_id' => $botUser->chat_id,
                    'text' => "😔 Kechirasiz, kategoriya topilmadi.",
                    'reply_markup' => $keyboard
                ]);

                $botUser->update([
                    'last_message_id' => $response->getMessageId(),
                    'step' => 'category',
                    'previous_step' => 'start'
                ]);
                return;
            }
                
            $products = Product::where('category_id', $category->id)->get();
            
            Log::info('Found products count: ' . $products->count(), [
                'category_id' => $category->id
            ]);
            
            if ($products->isEmpty()) {
                $keyboard = Keyboard::make()
                    ->inline()
                    ->row([
                        Keyboard::inlineButton(['text' => '🔙 Back', 'callback_data' => 'back'])
                    ]);

                $response = $telegram->sendMessage([
                    'chat_id' => $botUser->chat_id,
                    'text' => "😔 Kechirasiz, bu kategoriyada mahsulotlar mavjud emas.",
                    'reply_markup' => $keyboard
                ]);

                $botUser->update([
                    'last_message_id' => $response->getMessageId(),
                    'step' => 'product',
                    'previous_step' => 'category'
                ]);
                return;
            }
            
            $keyboard = Keyboard::make()
                ->inline();
                
            foreach ($products as $product) {
                $keyboard->row([
                    Keyboard::inlineButton([
                        'text' => "{$product->name} - {$product->price} so'm",
                        'callback_data' => "product_{$product->id}"
                    ])
                ]);
            }
            
            $keyboard->row([
                Keyboard::inlineButton(['text' => '🔙 Back', 'callback_data' => 'back'])
            ]);

            $response = $telegram->sendMessage([
                'chat_id' => $botUser->chat_id,
                'text' => "🍽 *{$category->name}*\n\nIltimos, mahsulotni tanlang:",
                'parse_mode' => 'Markdown',
                'reply_markup' => $keyboard
            ]);

            $botUser->update([
                'last_message_id' => $response->getMessageId(),
                'step' => 'product',
                'previous_step' => 'category'
            ]);

            Log::info('Products sent successfully', [
                'category_id' => $category->id,
                'products_count' => $products->count()
            ]);
        } catch (\Exception $e) {
            Log::error('Error in sendProducts: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString(),
                'category_name' => $categoryName,
                'cafe_id' => $cafe->id
            ]);
            // If something goes wrong, try to recover by sending categories
            $botUser->update([
                'step' => 'category',
                'previous_step' => 'start'
            ]);
            $this->sendCategories($telegram, $botUser, $cafe);
        }
    }

    private function handleVote($telegram, $botUser, $voteData, $callbackQuery)
    {
        $parts = explode('_', $voteData);
        $voteType = $parts[0]; // like or dislike
        $productId = $parts[1];

        // Validate vote type
        if (!in_array($voteType, ProductVote::getTypes())) {
            return;
        }

        // Check if user already voted
        $existingVote = ProductVote::where('bot_user_id', $botUser->id)
            ->where('product_id', $productId)
            ->first();

        if ($existingVote) {
            $telegram->answerCallbackQuery([
                'callback_query_id' => $callbackQuery->getId(),
                'text' => 'Siz allaqachon ovoz berdingiz!',
                'show_alert' => true
            ]);
            return;
        }

        // Create new vote
        ProductVote::create([
            'bot_user_id' => $botUser->id,
            'product_id' => $productId,
            'type' => $voteType
        ]);

        // Show updated product details
        $this->sendProductDetails($telegram, $botUser, $botUser->cafe, "product_{$productId}");
    }

    private function sendProductDetails($telegram, $botUser, $cafe, $productId)
    {
        try {
            // Extract product ID from callback data
            $productId = str_replace('product_', '', $productId);
            
            // Eager load relationships to avoid N+1 queries
            $product = Product::with(['category', 'votes'])
                ->whereHas('category', function($query) use ($cafe) {
                    $query->where('cafe_id', $cafe->id);
                })
                ->findOrFail($productId);

            // Get vote counts from the loaded relationship
            $likesCount = $product->votes->where('type', ProductVote::TYPE_LIKE)->count();
            $dislikesCount = $product->votes->where('type', ProductVote::TYPE_DISLIKE)->count();

            // Check if user already voted using the loaded relationship
            $userVote = $product->votes->where('bot_user_id', $botUser->id)->first();

            $keyboard = Keyboard::make()
                ->inline();

            // Add vote buttons only if user hasn't voted
            if (!$userVote) {
                $keyboard->row([
                    Keyboard::inlineButton(['text' => "👍 Layk", 'callback_data' => "like_{$productId}"]),
                    Keyboard::inlineButton(['text' => "👎 Dislayk", 'callback_data' => "dislike_{$productId}"])
                ]);
            }

            $keyboard->row([
                Keyboard::inlineButton(['text' => '🔙 Back', 'callback_data' => 'back'])
            ]);

            $message = "🍽 *{$product->name}*\n\n";
            $message .= "💰 Narxi: {$product->price} so'm\n";
            if ($product->description) {
                $message .= "\n📝 {$product->description}";
            }
            $message .= "\n\n👍 Layklar soni: {$likesCount} ta\n👎 Dislayklar soni: {$dislikesCount} ta";

            // Delete previous message
            if ($botUser->last_message_id) {
                try {
                    $telegram->deleteMessage([
                        'chat_id' => $botUser->chat_id,
                        'message_id' => $botUser->last_message_id
                    ]);
                } catch (\Exception $e) {
                    Log::info('Previous message not found, continuing...');
                }
            }

            if ($product->image) {
                $imagePath = storage_path('app/public/' . $product->image);
                if (file_exists($imagePath)) {
                    $response = $telegram->sendPhoto([
                        'chat_id' => $botUser->chat_id,
                        'photo' => InputFile::create($imagePath, basename($product->image)),
                        'caption' => $message,
                        'parse_mode' => 'Markdown',
                        'reply_markup' => $keyboard
                    ]);
                } else {
                    $response = $telegram->sendMessage([
                        'chat_id' => $botUser->chat_id,
                        'text' => $message . "\n\n⚠️ Rasm topilmadi",
                        'parse_mode' => 'Markdown',
                        'reply_markup' => $keyboard
                    ]);
                }
            } else {
                $response = $telegram->sendMessage([
                    'chat_id' => $botUser->chat_id,
                    'text' => $message,
                    'parse_mode' => 'Markdown',
                    'reply_markup' => $keyboard
                ]);
            }

            $botUser->update([
                'last_message_id' => $response->getMessageId(),
                'step' => 'product',
                'previous_step' => 'product'
            ]);
        } catch (\Exception $e) {
            Log::error('Error in sendProductDetails: ' . $e->getMessage());
            // If something goes wrong, try to recover by sending categories
            $botUser->update([
                'step' => 'category',
                'previous_step' => 'start'
            ]);
            $this->sendCategories($telegram, $botUser, $cafe);
        }
    }

    private function handleBack($telegram, $botUser)
    {
        try {
            // Delete current message
            if ($botUser->last_message_id) {
                try {
                    $telegram->deleteMessage([
                        'chat_id' => $botUser->chat_id,
                        'message_id' => $botUser->last_message_id
                    ]);
                } catch (\Exception $e) {
                    Log::info('Previous message not found, continuing...');
                }
            }

            // Get the current category name if we're in product view
            $currentCategory = null;
            if ($botUser->step === 'product') {
                $lastMessage = $telegram->getMessage([
                    'chat_id' => $botUser->chat_id,
                    'message_id' => $botUser->last_message_id
                ]);
                if ($lastMessage && $lastMessage->getText()) {
                    $text = $lastMessage->getText();
                    if (preg_match('/\*([^*]+)\*/', $text, $matches)) {
                        $currentCategory = $matches[1];
                    }
                }
            }

            // Update step and send appropriate message
            switch ($botUser->step) {
                case 'start':
                    // If we're at start, stay at start
                    $this->sendWelcomeMessage($telegram, $botUser);
                    break;
                    
                case 'category':
                    // If we're at category, go back to start
                    $botUser->update([
                        'step' => 'start',
                        'previous_step' => null
                    ]);
                    $this->sendWelcomeMessage($telegram, $botUser);
                    break;
                    
                case 'product':
                    // If we're at product details, go back to products list
                    if ($currentCategory) {
                        $botUser->update([
                            'step' => 'product',
                            'previous_step' => 'category'
                        ]);
                        $this->sendProducts($telegram, $botUser, $botUser->cafe, $currentCategory);
                    } else {
                        // If we can't get category, go back to categories
                        $botUser->update([
                            'step' => 'category',
                            'previous_step' => 'start'
                        ]);
                        $this->sendCategories($telegram, $botUser, $botUser->cafe);
                    }
                    break;
                    
                default:
                    // If we're in any other state, go back to start
                    $botUser->update([
                        'step' => 'start',
                        'previous_step' => null
                    ]);
                    $this->sendWelcomeMessage($telegram, $botUser);
                    break;
            }
        } catch (\Exception $e) {
            Log::error('Error in handleBack: ' . $e->getMessage());
            // If something goes wrong, try to recover by sending welcome message
            $botUser->update([
                'step' => 'start',
                'previous_step' => null
            ]);
            $this->sendWelcomeMessage($telegram, $botUser);
        }
    }
}
