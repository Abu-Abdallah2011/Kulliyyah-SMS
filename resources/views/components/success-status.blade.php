@props(['status'])

@if ($status)
<div x-data="{show: true}" x-init="setTimeout(() => show = false, 3000)" x-show="show" class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="bg-green-500 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100 text-center">
    <div {{ $attributes->merge(['class' => 'font-bold text-sm text-white dark:text-green-400']) }}>
        {{ $status }}
            </div>
        </div>
    </div>
</div>
@endif
