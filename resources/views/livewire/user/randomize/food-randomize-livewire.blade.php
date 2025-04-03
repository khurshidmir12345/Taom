<div class="food-randomizer">
    @if(!$isStarted)
        <div class="welcome-screen">
            <h1 class="welcome-title">Taom Tanlovchiga Xush Kelibsiz!</h1>
            <p class="welcome-text">Bugungi taomingizni tasodifiy tanlash uchun tayyormisiz?</p>
            <button class="start-btn" wire:click="startApp">Boshlash</button>
        </div>
    @else
        <div class="food-display">
            @if($isRandomizing)
                <div class="randomizing-placeholder">
                    <span class="spinner"></span>
                    <p>Tanlanmoqda...</p>
                </div>
            @elseif($food)
                <div class="food-image-container">
                    <img src="{{ asset('storage/' . $food['image']) }}" alt="{{ $food['name'] }}" class="food-image">
                    <div class="food-category">
                        <span>{{ $food['category'] ?? 'Uzbek' }}</span>
                    </div>
                </div>
                <div class="food-info">
                    <h2 class="food-name">{{ $food['name'] }}</h2>
                    <p class="food-description">{{ $food['description'] ?? 'Traditional Uzbek cuisine' }}</p>
                    <button class="details-btn" wire:click="viewDetails">Batafsil ko‘rish</button>
                </div>
            @else
                <div class="empty-card">
                    <svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="food-placeholder-svg">
                        <path d="M12 2c-3 0-5 2-5 5v10c0 3 2 5 5 5s5-2 5-5V7c0-3-2-5-5-5z"></path>
                        <path d="M7 7h10"></path>
                        <path d="M9 12h6"></path>
                    </svg>
                    <p>Taom tanlash uchun "Yangi Taom" tugmasini bosing</p>
                </div>
            @endif
        </div>

        <div class="controls">
            <button class="randomize-btn {{ $isRandomizing || $cooldown ? 'disabled' : '' }}"
                    wire:click="startRandomizing"
                {{ $isRandomizing || $cooldown ? 'disabled' : '' }}>
                @if($isRandomizing)
                    Tanlanmoqda...
                @elseif($cooldown)
                    <svg class="clock-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    {{ $cooldownSeconds }} s
                @else
                    Yangi Taom
                @endif
            </button>
        </div>
    @endif
</div>

<script>
    document.addEventListener('start-randomizing', function () {
        setTimeout(() => {
            @this.selectFood();
        }, 2000); // 2 soniya loader ko'rsatish
    });

    document.addEventListener('start-cooldown', function () {
        let interval = setInterval(() => {
            let seconds = @this.cooldownSeconds;
            seconds--;
            @this.set('cooldownSeconds', seconds);

            if (seconds <= 0) {
                clearInterval(interval);
                @this.set('cooldown', false);
            }
        }, 1000); // Har 1 soniyada yangilash
    });
</script>


