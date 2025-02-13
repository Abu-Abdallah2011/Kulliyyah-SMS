{{-- @props(['status'])

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
@endif --}}

@props(['status'])

@if ($status)
    <div {{ $attributes->merge(['class' => 'fixed inset-0 flex items-center justify-center z-50']) }}
         x-data="{ show: true, progress: 100 }"
         x-show="show"
         x-init="setTimeout(() => show = false, 5000);
                 $watch('progress', value => {
                     if (value <= 0) show = false;
                 });
                 let interval = setInterval(() => {
                     progress = Math.max(progress - 2, 0);
                     if (progress <= 0) clearInterval(interval);
                 }, 100);"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-90"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-90">
        
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-black/20 backdrop-blur-sm" x-show="show"></div>

        <!-- Toast Container -->
        <div class="relative bg-white dark:bg-gray-800 rounded-lg shadow-xl max-w-sm w-full mx-4 overflow-hidden transform transition-all">
            <!-- Close Button -->
            <button type="button" 
                    @click="show = false"
                    class="absolute top-2 right-2 text-gray-400 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:hover:text-gray-300">
                <span class="sr-only">Close</span>
                <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                </svg>
            </button>

            <!-- Main Content -->
            <div class="p-6">
                <div class="flex items-center justify-center">
                    <!-- Success Icon -->
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-green-100 dark:bg-green-900 flex items-center justify-center mb-4">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-300" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>

                <!-- Message -->
                <div class="text-center mt-3">
                    <p class="text-sm font-medium text-gray-900 dark:text-white">
                        {{ $status }}
                    </p>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gray-100 dark:bg-gray-700">
                <div class="h-full bg-green-500 transition-all duration-100 ease-out"
                     :style="{ width: `${progress}%` }">
                </div>
            </div>
        </div>
    </div>
@endif

