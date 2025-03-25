<div class="flex flex-col gap-6">
    <x-auth-header :title="__('Lara-taom * Yangi Hisob *')" :description="__('Yangi Hisob yaratish uchun malumotlarni kiriting')" />

    <!-- Session Status -->
    <x-auth-session-status class="text-center" :status="session('status')" />

    <form wire:submit="register" class="flex flex-col gap-6">
        <!-- Name -->
        <flux:input
            wire:model="name"
            type="text"
            required
            autofocus
            autocomplete="name"
            :placeholder="__('Ism va Familya')"
        />

        <!-- Email Address -->
        <flux:input
            wire:model="email"
            type="email"
            required
            autocomplete="email"
            placeholder="email@example.com"
        />

        <!-- Password -->
        <flux:input
            wire:model="password"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Parol')"
        />

        <!-- Confirm Password -->
        <flux:input
            wire:model="password_confirmation"
            type="password"
            required
            autocomplete="new-password"
            :placeholder="__('Parolni takrorlang')"
        />

        <div class="flex items-center justify-end">
            <flux:button type="submit" variant="primary" class="w-full">
                {{ __('Ro\'yhatga qo\'shish') }}
            </flux:button>
        </div>
    </form>

    <div class="space-x-1 text-center text-sm text-zinc-600 dark:text-zinc-400">
        {{ __('Sizda hison mavjudmi ?') }}
        <flux:link :href="route('login')" wire:navigate>{{ __('Kirish') }}</flux:link>
    </div>
</div>
