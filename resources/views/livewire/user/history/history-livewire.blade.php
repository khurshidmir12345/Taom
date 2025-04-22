<div class="food-history">
    <!-- Header with timeframe selector -->
    <div class="history-header">
        <h1 class="history-title">
            <span class="history-title-icon">📋</span> Taomlar Tarixi
        </h1>

        <div class="filter-controls">
            <button wire:click="$set('timeframe', 'all')"
                    class="filter-btn {{ $timeframe === 'all' ? 'active' : '' }}">
                Hammasi
            </button>
            <button wire:click="$set('timeframe', 'month')"
                    class="filter-btn {{ $timeframe === 'month' ? 'active' : '' }}">
                Oy
            </button>
            <button wire:click="$set('timeframe', 'week')"
                    class="filter-btn {{ $timeframe === 'week' ? 'active' : '' }}">
                Hafta
            </button>
        </div>
    </div>

    <!-- Empty state -->
    @if($isEmpty)
        <div class="empty-state">
            <div class="empty-icon-container">
                <svg class="empty-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="empty-title">Tarix bo'sh</h3>
            <p class="empty-text">Hali hech qanday taom tarixga yozilmagan.</p>
        </div>
    @else
        <div class="timeline-container">
            @foreach($groupedHistories as $date => $items)
                <div>
                    <!-- Date header -->
                    <div class="date-divider">
                        <div class="date-line"></div>
                        <h2 class="date-label">
                            {{ \Carbon\Carbon::parse($date)->translatedFormat('d F Y, l') }}
                        </h2>
                        <div class="date-line"></div>
                    </div>

                    <!-- Food items for this date -->
                    <div>
                        @foreach($items as $history)
                            @php
                                $mealTypeClass = match($history->meal_type) {
                                    'Nonushta' => 'breakfast',
                                    'Tushlik' => 'lunch',
                                    'Kechki ovqat' => 'dinner',
                                    default => ''
                                };
                            @endphp
                            <div class="food-item {{ $mealTypeClass }}">
                                <div class="food-item-content">
                                    <div class="food-item-info">
                                        <h3 class="food-item-name">{{ $history->food->name_uz }}</h3>
                                        <p class="food-item-category">{{ $history->food->category->name ?? 'Kategoriyasiz' }}</p>
                                    </div>
                                    <span class="meal-type-badge {{ $mealTypeClass }}">
                                        {{ $history->meal_type }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
