<?php

namespace App\Livewire\Admin\Categories;

use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class IndexLivewire extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $name;
    public $category_id;
    public $isOpen = false;
    public $isDeleteConfirmationOpen = false;
    public $categoryToDelete = null;
    public $search = '';
    public $formMode = 'create'; // 'create' or 'edit'

    protected $rules = [
        'name' => 'required|min:3|max:255',
    ];

    protected $listeners = [
        'openModal' => 'create',
        'closeModal' => 'closeModal',
        'saveCategory' => 'store',
        'editCategory' => 'edit',
        'updateCategory' => 'update',
        'confirmDelete' => 'openDeleteConfirmation',
        'closeDeleteModal' => 'closeDeleteConfirmation',
        'deleteCategory' => 'delete'
    ];

    public function render()
    {
        $categories = Category::where('name', 'like', '%' . $this->search . '%')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('livewire.admin.categories.index-livewire', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $this->resetInputFields();
        $this->formMode = 'create';
        $this->openModal();
    }

    public function openModal()
    {
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
    }

    public function openDeleteConfirmation($id)
    {
        $this->categoryToDelete = $id;
        $this->isDeleteConfirmationOpen = true;
    }

    public function closeDeleteConfirmation()
    {
        $this->isDeleteConfirmationOpen = false;
        $this->categoryToDelete = null;
    }

    private function resetInputFields()
    {
        $this->name = '';
        $this->category_id = null;
        $this->resetErrorBag();
    }

    public function store()
    {
        $this->validate();

        Category::create([
            'name' => $this->name,
        ]);

        session()->flash('message', 'Category created successfully.');
        $this->closeModal();
        $this->resetInputFields();
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $this->category_id = $id;
        $this->name = $category->name;
        $this->formMode = 'edit';
        $this->openModal();
    }

    public function update()
    {
        $this->validate();

        if ($this->category_id) {
            $category = Category::findOrFail($this->category_id);
            $category->update([
                'name' => $this->name,
            ]);
            session()->flash('message', 'Category updated successfully.');
            $this->closeModal();
            $this->resetInputFields();
        }
    }

    public function delete()
    {
        if ($this->categoryToDelete) {
            $category = Category::findOrFail($this->categoryToDelete);

            // Check if category has related foods
            if ($category->foods()->count() > 0) {
                session()->flash('error', 'Cannot delete category because it has associated foods.');
            } else {
                $category->delete();
                session()->flash('message', 'Category deleted successfully.');
            }

            $this->closeDeleteConfirmation();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
