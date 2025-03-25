<?php

namespace App\Livewire\Admin\Meals;

use App\Models\Category;
use App\Models\Food;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class IndexLivewire extends Component
{
    use WithFileUploads;
    use WithPagination;
    protected $paginationTheme = 'bootstrap';


    public $name_uz;
    public $name_ru;
    public $description;
    public $categories;
    public $category_id;
    public $image;
    public $food_id;
    public $isOpen = false;
    public $isEdit = false;

    protected $rules = [
        'name_uz' => 'required|string|max:255',
        'name_ru' => 'required|string|max:255',
        'description' => 'nullable|string|max:255',
        'category_id' => 'required|integer',
        'image' => 'nullable|image|max:1024',
    ];

    public function render()
    {
        $this->categories = Category::query()->pluck('name', 'id');
        $foods = Food::query()->paginate(10);
        return view('livewire.admin.meals.index-livewire', compact('foods'));
    }

    public function create()
    {
        $this->resetInputFields();
        $this->openModal();
        $this->isEdit = false;
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function resetInputFields()
    {
        $this->name_uz = '';
        $this->name_ru = '';
        $this->description = '';
        $this->category_id = '';
        $this->image = null;
        $this->food_id = null;
        $this->isEdit = false;
    }

    public function store()
    {
        $this->validate();

        $food = Food::create([
            'name_uz' => $this->name_uz,
            'name_ru' => $this->name_ru,
            'description' => $this->description,
            'category_id' => $this->category_id,
        ]);

        if ($this->image) {
            $food->addMedia($this->image->getRealPath())
                ->usingName($this->image->getClientOriginalName())
                ->toMediaCollection('food');
        }

        session()->flash('message', 'Food created successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $food = Food::query()->findOrFail($id);
        $this->food_id = $id;
        $this->name_uz = $food->name_uz;
        $this->name_ru = $food->name_ru;
        $this->description = $food->description;
        $this->category_id = $food->category_id;
        $this->isEdit = true;

        $this->openModal();
    }

    public function update()
    {
        $this->validate([
            'name_uz' => 'required|string|max:255',
            'name_ru' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',
            'category_id' => 'required',
            'image' => 'nullable|image|max:1024',
        ]);

        $food = Food::query()->find($this->food_id);
        $food->update([
            'name_uz' => $this->name_uz,
            'name_ru' => $this->name_ru,
            'description' => $this->description,
            'category_id' => $this->category_id,
        ]);

        if ($this->image) {
            $food->clearMediaCollection('food');
            $food->addMedia($this->image->getRealPath())
                ->usingName($this->image->getClientOriginalName())
                ->toMediaCollection('food');
        }

        session()->flash('message', 'Food updated successfully.');

        $this->closeModal();
        $this->resetInputFields();
    }

    public function delete($id)
    {
        Food::find($id)->delete();
        session()->flash('message', 'Food deleted successfully.');
    }
}
