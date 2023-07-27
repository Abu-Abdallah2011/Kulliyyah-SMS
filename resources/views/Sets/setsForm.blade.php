<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sets Entry Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

                        <form method="POST" action="{{ url('setsForm') }}" enctype="multipart/form-data">
                            @csrf
                    
                            <!-- Set -->
                            <div>
                                <x-input-label for="set" :value="__('Set')" />
                                <x-text-input id="set" class="block mt-1 w-full" type="text" name="set" :value="old('set')" autofocus autocomplete="set" />
                                <x-input-error :messages="$errors->get('set')" class="mt-2" />
                            </div>
                           
                            <br/>
                        <div>
                            <x-primary-button class="ml-3">
                                {{ __('Save') }}
                            </x-primary-button>
                        </div>
                        </form>
                    </section>
            </div>
        </div>
    </div>
</x-app-layout>
