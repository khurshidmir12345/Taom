<x-layouts.app :title="__('Dashboard')">
    <a href="{{ route('home') }}" style="
    margin: 20px auto;
    display: block;
    background-color: #DEB887;
    color: #3F2A1D;
    font-weight: 600;
    text-align: center;
    padding: 12px 24px;
    border-radius: 8px;
    text-decoration: none;
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
">
        go to home
    </a>
{{--    <div class="flex h-full w-full flex-1 flex-col gap-4 rounded-xl">--}}
{{--        <div class="grid auto-rows-min gap-4 md:grid-cols-3">--}}
{{--            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">--}}
{{--                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />--}}
{{--            </div>--}}
{{--            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">--}}
{{--                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />--}}
{{--            </div>--}}
{{--            <div class="relative aspect-video overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">--}}
{{--                <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />--}}
{{--            </div>--}}
{{--        </div>--}}
{{--        <div class="relative h-full flex-1 overflow-hidden rounded-xl border border-neutral-200 dark:border-neutral-700">--}}
{{--            <x-placeholder-pattern class="absolute inset-0 size-full stroke-gray-900/20 dark:stroke-neutral-100/20" />--}}
{{--        </div>--}}
{{--    </div>--}}
</x-layouts.app>
