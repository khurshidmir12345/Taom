<?php

namespace App\Livewire\User\Randomize;

use App\Models\Food;
use App\Models\FoodUser;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class FoodRandomizeLivewire extends Component
{
    public $food = null;
    public $isStarted = false;
    public $isRandomizing = false;
    public $cooldown = false;
    public $cooldownSeconds = 0;
    public $foods = [];

    public function mount()
    {
        $this->loadFoodsFromDatabase();
    }

    protected function loadFoodsFromDatabase()
    {
        $user = Auth::user();
        $allFoods = Food::with(['media', 'category'])->get();

        $shownFoodIds = FoodUser::where('user_id', $user->id)
            ->pluck('food_id')
            ->toArray();

        $remainingFoods = $allFoods->filter(function ($food) use ($shownFoodIds) {
            return !in_array($food->id, $shownFoodIds);
        });

        if ($remainingFoods->isEmpty()) {
            $remainingFoods = $allFoods;
            FoodUser::where('user_id', $user->id)->delete();
        }

        $this->foods = $remainingFoods->map(function ($food) {
            return [
                'id' => $food->id,
                'name' => $food->name_uz,
                'image' => $food->getFirstMediaUrl('foods') ?: $food->image,
                'category' => $food->category ? $food->category->name : 'N/A',
                'description' => $food->description
            ];
        })->toArray();
    }

    public function startApp()
    {
        $this->isStarted = true;
    }

    public function startRandomizing()
    {
        if (!$this->isRandomizing && !$this->cooldown) {
            $this->isRandomizing = true;
            $this->food = null; // Loader ko'rsatish uchun taomni o'chiramiz
            $this->dispatch('start-randomizing');
        }
    }

    public function selectFood()
    {
        if (count($this->foods) > 0) {
            $this->food = $this->foods[array_rand($this->foods)];

            $user = Auth::user();
            $shownCount = FoodUser::where('user_id', $user->id)->count();

            if ($shownCount >= 5) {
                $oldest = FoodUser::where('user_id', $user->id)
                    ->orderBy('created_at', 'asc')
                    ->first();
                $oldest->delete();
            }

            FoodUser::create([
                'user_id' => $user->id,
                'food_id' => $this->food['id'],
            ]);

            $this->loadFoodsFromDatabase();
        } else {
            $this->food = [
                'name' => 'Taom topilmadi',
                'image' => '/images/food-placeholder.jpg',
                'category' => 'N/A',
                'description' => 'Iltimos, taomlarni bazaga qo‘shing.'
            ];
        }

        $this->isRandomizing = false;
        $this->cooldown = true;
        $this->cooldownSeconds = 15;
        $this->dispatch('start-cooldown');
    }

    public function viewDetails()
    {
        return redirect()->to('/food/' . $this->food['id']);
    }

    public function render()
    {
        return view('livewire.user.randomize.food-randomize-livewire');
    }
}
