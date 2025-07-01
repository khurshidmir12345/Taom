<?php

namespace Database\Seeders;

use App\Models\DailyReminder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DailyReminderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reminders = [
            ['day' => 'monday', 'message' => 'Dushanba tongi — yangi hafta, yangi ta\'m! Tushlikda nima yesak ekan? 😋'],
            ['day' => 'tuesday', 'message' => 'Seshanba kuniga mos taom tanlaylikmi? 🍛 Keling, birga qaraymiz!'],
            ['day' => 'wednesday', 'message' => 'Bugun haftaning o\'rtasi! Charchoqni yaxshi ovqat bilan yengamizmi? 🍲'],
            ['day' => 'thursday', 'message' => 'Payshanba yengil tushlikka taklif bor! Qani, nima chiqarkan? 🥗'],
            ['day' => 'friday', 'message' => 'Juma muborak! Bugungi tushlikka o\'ziga xos taom tanlaylik 🕌🍽️'],
            ['day' => 'saturday', 'message' => 'Dam olish kunini yaxshi ovqat bilan boshlasakchi? 🍕'],
            ['day' => 'sunday', 'message' => 'Yakshanba — rohat va dam olish kuni. Qani, nimani yesak ekan? 🧑‍🍳'],
        ];

        foreach ($reminders as $reminder) {
            DailyReminder::create([
                'day' => $reminder['day'],
                'message' => $reminder['message'],
                'time' => '10:00:00',
                'is_active' => true
            ]);
        }
    }
}
