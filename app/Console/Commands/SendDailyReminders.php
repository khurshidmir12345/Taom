<?php

namespace App\Console\Commands;

use App\Models\DailyReminder;
use App\Models\User;
use Illuminate\Console\Command;
use Telegram\Bot\Laravel\Facades\Telegram;
use Telegram\Bot\Keyboard\Keyboard;
use Illuminate\Support\Facades\Log;

class SendDailyReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send-daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send daily reminders to all users with telegram chat IDs';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $currentDay = strtolower(now()->format('l'));
            $currentTime = now()->format('H:i:00');

            $this->info("Checking for reminders for {$currentDay} at {$currentTime}");

            // Get today's reminder
            $reminder = DailyReminder::active()
                ->forDay($currentDay)
                ->first();

            if (!$reminder) {
                $this->info("No active reminder found for {$currentDay} at {$currentTime}");
                return 0;
            }

            $this->info("Found reminder: {$reminder->message}");

            // Get all users with telegram chat IDs
            $users = User::whereNotNull('telegram_chat_id')
                ->where('is_telegram_verified', true)
                ->get();

            $this->info("Found {$users->count()} users with telegram chat IDs");

            $successCount = 0;
            $errorCount = 0;

            foreach ($users as $user) {
                try {
                    $keyboard = Keyboard::make()
                        ->inline()
                        ->row([
                            Keyboard::inlineButton(['text' => '🍽 Ovqat tanlash', 'callback_data' => 'select_food']),
                            Keyboard::inlineButton(['text' => '📋 Ovqat tarixi', 'callback_data' => 'food_history'])
                        ]);

                    $response = Telegram::sendMessage([
                        'chat_id' => $user->telegram_chat_id,
                        'text' => $reminder->message,
                        'parse_mode' => 'Markdown',
                        'reply_markup' => $keyboard
                    ]);

                    $successCount++;
                    $this->info("Sent reminder to user {$user->name} (ID: {$user->id})");

                    // Update user's last message ID
                    $user->updateLastMessageId($response->getMessageId());

                } catch (\Exception $e) {
                    $errorCount++;
                    $this->error("Failed to send reminder to user {$user->name} (ID: {$user->id}): {$e->getMessage()}");
                    Log::error("Failed to send daily reminder to user {$user->id}: " . $e->getMessage(), [
                        'user_id' => $user->id,
                        'chat_id' => $user->telegram_chat_id,
                        'error' => $e->getMessage()
                    ]);
                }

                // Add a small delay to avoid hitting rate limits
                sleep(1);
            }

            $this->info("Reminder sending completed. Success: {$successCount}, Errors: {$errorCount}");

            return 0;

        } catch (\Exception $e) {
            $this->error("Error in SendDailyReminders command: " . $e->getMessage());
            Log::error("Error in SendDailyReminders command: " . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
}
