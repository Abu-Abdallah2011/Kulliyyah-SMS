<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Hadda Entry Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

                        <form method="POST" action="{{ route('hadda_page.store', ['student_id' => $student->id]) }}" enctype="multipart/form-data">
                            @csrf
                    
                            <!-- Date -->
                            <div>
                                <x-input-label for="date" :value="__('Date')" />
                                <x-date-picker id="date" class="block mt-1 w-full" type="text" name="date" :value="old('date')" required autocomplete="date" />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                    
                            <!-- Surah -->
                            <div>
                                <x-input-label for="sura" :value="__('Select a Surah')" />
                                <select name="dynamic_select" id="dynamic_select" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                                    
                                @foreach ($sura as $sura)
                                <option value="{{ $sura->id }}">{{ $sura->sura }}</option>
                                @endforeach
                                </select>
                            </div>
                           
                           <!-- From -->
                           <div>
                            <x-input-label for="from" :value="__('From')" />
                            <x-text-input id="from" class="block mt-1 w-full" type="text" name="from" :value="old('from')" required autofocus autocomplete="from" />
                            <x-input-error :messages="$errors->get('from')" class="mt-2" />
                        </div>
                    
                           <!-- To -->
                           <div>
                            <x-input-label for="to" :value="__('To')" />
                            <x-text-input id="to" class="block mt-1 w-full" type="text" name="to" :value="old('to')" required autofocus autocomplete="to" />
                            <x-input-error :messages="$errors->get('to')" class="mt-2" />
                        </div>
                    
                           <!-- Grade -->
                           <div>
                            <x-input-label class="font-bold" for="grade" :value="__('Select a Grade')" />
                            <x-text-select id="grade" class="block mt-1 w-full" type="text" name="grade"/>
                            <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                        </div>
                    
                        {{-- Comment --}}
            <div>
                <x-input-label class="font-bold" for="comment" :value="__('Comment')" />
                <textarea id="comment" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="comment">
                </textarea>
                <x-input-error :messages="$errors->get('comment')" class="mt-2" />
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
