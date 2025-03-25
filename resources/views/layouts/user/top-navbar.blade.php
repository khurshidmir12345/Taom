<!-- Top Category Navigation -->
<div class="top-category-navbar">
    <div class="category-tabs">
        <a href="{{ route('users.randomize.index', ['category' => 'foods']) }}" class="category-tab {{ request()->query('category') == 'foods' || !request()->query('category') ? 'active' : '' }}">
            <span class="tab-text">Taomlar</span>
        </a>
        <a href="{{ route('users.randomize.index', ['category' => 'desserts']) }}" class="category-tab {{ request()->query('category') == 'desserts' ? 'active' : '' }}">
            <span class="tab-text">Shirinliklar</span>
        </a>
        <a href="{{ route('users.randomize.index', ['category' => 'salads']) }}" class="category-tab {{ request()->query('category') == 'salads' ? 'active' : '' }}">
            <span class="tab-text">Salatlar</span>
        </a>
    </div>
</div>
