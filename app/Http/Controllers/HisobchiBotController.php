<?php

namespace App\Http\Controllers;

use App\Models\UserInvitation;
use App\Models\BotUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class HisobchiBotController extends Controller
{
    /**
     * @throws TelegramSDKException
     */
    public function handle(Request $request)
    {
        try {
            $token = config('telegram.bots.hisobchi_bot.token');
            $telegram = new Api($token);
            $update = $telegram->getWebhookUpdate();

            // Log the update for debugging
            Log::info('Received update:', ['update' => $update->toArray()]);

            // Handle /topinviters command
            if ($update->has('message') && $update->getMessage()->has('text')) {
                $text = $update->getMessage()->getText();
                Log::info('Received message:', ['text' => $text]);
                
                if ($text === '/topinviters') {
                    Log::info('Processing /topinviters command');
                    $this->handleTopInviters($telegram, $update);
                    return;
                }
            }

            // Handle new chat members
            if ($update->has('message') && $update->getMessage()->has('new_chat_members')) {
                $this->handleNewMembers($telegram, $update);
                return;
            }

            // Handle left chat member
            if ($update->has('message') && $update->getMessage()->has('left_chat_member')) {
                $this->handleLeftMember($telegram, $update);
                return;
            }
        } catch (\Exception $e) {
            Log::error('HisobchiBot error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function handleTopInviters($telegram, $update)
    {
        try {
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $userId = $message->getFrom()->getId();

            // Save or update user in BotUser model
            try {
                $user = $message->getFrom();
                Log::info('Attempting to save user:', [
                    'user_data' => [
                        'chat_id' => $userId,
                        'user_name' => $user->getUsername(),
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName()
                    ]
                ]);

                BotUser::firstOrCreate(
                    ['chat_id' => $userId],
                    [
                        'user_name' => $user->getUsername(),
                        'first_name' => $user->getFirstName(),
                        'last_name' => $user->getLastName(),
                        'step' => 'start'
                    ]
                );

                Log::info('User saved successfully');
            } catch (\Exception $e) {
                Log::error('Error saving user: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString(),
                    'user_id' => $userId
                ]);
            }

            Log::info('Starting top inviters command:', [
                'chat_id' => $chatId,
                'user_id' => $userId,
                'chat_type' => $message->getChat()->getType()
            ]);

            // Check if user is admin
            try {
                $chatMember = $telegram->getChatMember([
                    'chat_id' => $chatId,
                    'user_id' => $userId
                ]);

                // Get status from toArray() instead of getStatus()
                $chatMemberArray = $chatMember->toArray();
                $status = $chatMemberArray['status'] ?? null;

                Log::info('Chat member status:', [
                    'status' => $status,
                    'raw_chat_member' => $chatMemberArray
                ]);

                // Check if user is creator or administrator
                $isAdmin = in_array($status, ['creator', 'administrator']);

                Log::info('Admin check result:', [
                    'status' => $status,
                    'is_admin' => $isAdmin,
                    'status_comparison' => [
                        'is_creator' => ($status === 'creator'),
                        'is_administrator' => ($status === 'administrator')
                    ]
                ]);

                if (!$isAdmin) {
                    Log::info('User is not admin:', [
                        'user_id' => $userId,
                        'status' => $status
                    ]);
                    return;
                }

                Log::info('User is admin, proceeding with top inviters');

                // Get top 20 inviters for this chat
                $topInviters = UserInvitation::where('chat_id', $chatId)
                    ->selectRaw('inviter_id, COUNT(*) as invite_count')
                    ->groupBy('inviter_id')
                    ->orderByDesc('invite_count')
                    ->limit(20)
                    ->get();

                Log::info('Found inviters:', [
                    'count' => $topInviters->count(),
                    'inviters' => $topInviters->toArray()
                ]);

                if ($topInviters->isEmpty()) {
                    Log::info('No inviters found, sending empty message');
                    $telegram->sendMessage([
                        'chat_id' => $chatId,
                        'text' => '😔 Hozircha hech kim odam qo\'shmagan.',
                        'parse_mode' => 'HTML'
                    ]);
                    return;
                }

                // Build message
                $message = "🏆 *Top 20 ta odam qo'shgan foydalanuvchilar:*\n\n";
                $rank = 1;

                foreach ($topInviters as $inviter) {
                    $message .= "{$rank}. 👤 <a href=\"tg://user?id={$inviter->inviter_id}\">Foydalanuvchi</a> – {$inviter->invite_count} ta odam\n";
                    $rank++;
                }

                Log::info('Sending message:', ['message' => $message]);

                $response = $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => $message,
                    'parse_mode' => 'HTML'
                ]);

                Log::info('Message sent successfully:', [
                    'message_id' => $response->getMessageId()
                ]);

            } catch (\Exception $e) {
                Log::error('Error checking admin status: ' . $e->getMessage(), [
                    'exception' => $e,
                    'trace' => $e->getTraceAsString()
                ]);

                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => '❌ Xatolik yuz berdi. Iltimos, qaytadan urinib ko\'ring.',
                    'parse_mode' => 'HTML'
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling top inviters: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            // Try to send error message to chat
            try {
                $telegram->sendMessage([
                    'chat_id' => $chatId,
                    'text' => '❌ Xatolik yuz berdi. Iltimos, qaytadan urinib ko\'ring.',
                    'parse_mode' => 'HTML'
                ]);
            } catch (\Exception $e2) {
                Log::error('Failed to send error message: ' . $e2->getMessage());
            }
        }
    }

    private function handleNewMembers($telegram, $update)
    {
        try {
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $inviterId = $message->getFrom()->getId();
            $messageId = $message->getMessageId();
        

            // Delete the automatic "user added" message
            try {
                $deleteResponse = $telegram->deleteMessage([
                    'chat_id' => $chatId,
                    'message_id' => $messageId
                ]);
                Log::info('Message deletion response:', ['response' => $deleteResponse]);
            } catch (\Exception $e) {
                Log::error('Failed to delete message:', [
                    'error' => $e->getMessage(),
                    'chat_id' => $chatId,
                    'message_id' => $messageId
                ]);
            }

            // Process each new member
            foreach ($message->getNewChatMembers() as $newMember) {
                // Skip if the new member is the bot itself
                if ($newMember->getId() === $telegram->getMe()->getId()) {
                    Log::info('Skipping bot itself as new member');
                    continue;
                }
        
                // Create invitation record
                try {
                    UserInvitation::create([
                        'inviter_id' => $inviterId,
                        'invited_id' => $newMember->getId(),
                        'chat_id' => $chatId
                    ]);

                    Log::info('Created invitation record:', [
                        'inviter_id' => $inviterId,
                        'invited_id' => $newMember->getId(),
                        'chat_id' => $chatId
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error creating invitation record: ' . $e->getMessage(), [
                        'exception' => $e,
                        'trace' => $e->getTraceAsString(),
                        'inviter_id' => $inviterId,
                        'invited_id' => $newMember->getId(),
                        'chat_id' => $chatId
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error handling new members: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    private function handleLeftMember($telegram, $update)
    {
        try {
            $message = $update->getMessage();
            $chatId = $message->getChat()->getId();
            $messageId = $message->getMessageId();

            Log::info('Handling left member:', [
                'chat_id' => $chatId,
                'message_id' => $messageId,
                'left_member' => $message->getLeftChatMember()->toArray()
            ]);

            // Delete the "left the group" message
            try {
                $deleteResponse = $telegram->deleteMessage([
                    'chat_id' => $chatId,
                    'message_id' => $messageId
                ]);
                Log::info('Message deletion response:', ['response' => $deleteResponse]);
            } catch (\Exception $e) {
                Log::error('Failed to delete message:', [
                    'error' => $e->getMessage(),
                    'chat_id' => $chatId,
                    'message_id' => $messageId
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error handling left member: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
        }
    }
}
