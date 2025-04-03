<div class="food-randomizer">
    <!-- Food display area -->
    <div class="food-display">
        <div class="food-image-container">
            <img id="food-image" src="{{ asset('storage/'. $food['image']) }}" alt="{{ $food['name'] }}" class="food-image">

            <!-- Food category badge -->
            <div class="food-category">
                <span>{{ $food['category'] ?? 'Uzbek' }}</span>
            </div>
        </div>

        <div class="food-info">
            <h2 id="food-name" class="food-name" style="font-weight: bold; color: darkcyan">{{ $food['name'] }}</h2>
            <p class="food-description">{{ $food['description'] ?? 'Traditional Uzbek cuisine' }}</p>
        </div>
    </div>

    <!-- Controls -->
    <div class="controls">
        <button class="randomize-btn {{ $isRunning ? 'disabled' : '' }}"
                wire:click="startRandomizing"
                id="start-button"
            {{ $isRunning ? 'disabled' : '' }}>
            <span class="btn-text">{{ $isRunning ? 'Randomizing...' : 'Find Food' }}</span>
            <span class="btn-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M5 12h14"></path>
                    <path d="M12 5l7 7-7 7"></path>
                </svg>
            </span>
        </button>
    </div>

    <!-- Result modal that appears after randomization -->
    <div id="result-modal" class="result-modal {{ $showResult ? 'show' : '' }}">
        <div class="result-content">
            <div class="result-image-container">
                <img src="{{ asset('storage/'. $food['image']) }}" alt="{{ $food['name'] }}" class="result-image">
            </div>
            <h2 class="result-title">Sizning taomingiz bugun . . .</h2>
            <h3 class="result-food-name">{{ $food['name'] }}</h3>
            <p class="result-description">{{ $food['description'] ?? 'Enjoy your delicious meal!' }}</p>
            <button class="close-result-btn" wire:click="resetRandomizer">Qayta urunish</button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('start-randomizing', function() {
        let foodImage = document.getElementById('food-image');
        let foodName = document.getElementById('food-name');
        let startButton = document.getElementById('start-button');
        let resultModal = document.getElementById('result-modal');

        let foods = @json($foods);
        let counter = 0;
        let maxIterations = 20; // More iterations for a longer animation
        let interval = setInterval(() => {
            counter++;
            let randomFood = foods[Math.floor(Math.random() * foods.length)];

            // Add a fade transition effect
            foodImage.classList.add('transitioning');
            foodName.classList.add('transitioning');

            setTimeout(() => {
                foodImage.src = randomFood.image;
                foodName.innerText = randomFood.name;

                foodImage.classList.remove('transitioning');
                foodName.classList.remove('transitioning');
            }, 100);

            // Gradually slow down the randomization
            if (counter > maxIterations * 0.7) {
                clearInterval(interval);

                // Start slowing down
                let slowInterval = setInterval(() => {
                    counter++;
                    let randomFood = foods[Math.floor(Math.random() * foods.length)];

                    foodImage.classList.add('transitioning');
                    foodName.classList.add('transitioning');

                    setTimeout(() => {
                        foodImage.src = randomFood.image;
                        foodName.innerText = randomFood.name;

                        foodImage.classList.remove('transitioning');
                        foodName.classList.remove('transitioning');
                    }, 100);

                    if (counter >= maxIterations) {
                        clearInterval(slowInterval);
                        startButton.disabled = false;
                        @this.stopRandomizing();
                    }
                }, 300); // Slower interval
            }
        }, 100);
    });
</script>

