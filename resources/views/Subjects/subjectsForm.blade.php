<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subjects Entry Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

                        <form method="POST" action="{{ url('subjectsForm') }}" enctype="multipart/form-data">
                            @csrf
                    
                            <!-- Subject -->
                            <div>
                                <x-input-label for="subject" :value="__('Subject')" />
                                <x-text-input id="subject" class="block mt-1 w-full" type="text" name="subject" :value="old('subject')" autofocus autocomplete="subject" />
                                <x-input-error :messages="$errors->get('subject')" class="mt-2" />
                            </div>

                            <!-- Marks Obtainable -->
                            <div>
                                <x-input-label for="marks_obtainable" :value="__('Marks Obtainable')" />
                                <x-text-input id="marks_obtainable" class="block mt-1 w-full" type="text" name="marks_obtainable" :value="old('marks_obtainable')" autofocus autocomplete="marks_obtainable" />
                                <x-input-error :messages="$errors->get('marks_obtainable')" class="mt-2" />
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
