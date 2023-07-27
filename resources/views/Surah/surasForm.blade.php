<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Surah Entry Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

                        <form method="POST" action="{{ url('surasForm') }}" enctype="multipart/form-data">
                            @csrf
                    
                            <!-- Surah -->
                            <div>
                                <x-input-label for="sura" :value="__('Surah')" />
                                <x-text-input id="sura" class="block mt-1 w-full" type="text" name="sura" :value="old('sura')" autofocus autocomplete="sura" />
                                <x-input-error :messages="$errors->get('sura')" class="mt-2" />
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
