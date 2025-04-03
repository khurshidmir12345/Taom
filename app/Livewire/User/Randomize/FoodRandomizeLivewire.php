<?php

namespace App\Livewire\User\Randomize;

use App\Models\Food;
use Livewire\Component;

class FoodRandomizeLivewire extends Component
{
    public $food;
    public $isRunning = false;
    public $showResult = false;

    public $foods = [

    ];

    public function mount()
    {
        $this->loadFoodsFromDatabase();
        $this->selectRandomFood();
    }
    protected function loadFoodsFromDatabase()
    {
        // Fetch foods from database with their media and category
        $dbFoods = Food::with(['media', 'category'])->get();

        $this->foods = [];

        foreach ($dbFoods as $dbFood) {
            // Get the first media item (image) for the food
            $imageUrl = $dbFood->getFirstMediaUrl('food');

            // Get the category name if available
            $categoryName = $dbFood->category ? $dbFood->category->name : 'categorya mavjud emas';

            // Add to the foods array
            $this->foods[] = [
                'id' => $dbFood->id,
                'name' => $dbFood->name_uz,
                'image' => $dbFood->image,
                'category' => $categoryName,
                'description' => $dbFood->description
            ];
        }
    }

    protected function selectRandomFood()
    {
        if (count($this->foods) > 0) {
            $this->food = $this->foods[array_rand($this->foods)];
        } else {
            $this->food = [
                'name' => 'No foods found',
                'image' => '/images/food-placeholder.jpg',
                'category' => 'N/A',
                'description' => 'Please add some foods to the database.'
            ];
        }
    }

    public function startRandomizing()
    {
        $this->isRunning = true;
        $this->showResult = false;
        $this->dispatch('start-randomizing');
    }

    public function stopRandomizing()
    {
        $this->isRunning = false;
        $this->food = $this->foods[array_rand($this->foods)];
        $this->showResult = true;
    }

    public function resetRandomizer()
    {
        $this->showResult = false;
    }
    public function render()
    {
        $this->food = $this->foods[array_rand($this->foods)];
        return view('livewire.user.randomize.food-randomize-livewire');
    }
}
