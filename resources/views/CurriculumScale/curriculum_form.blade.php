<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Curriculum Registration Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

                        <form method="POST" action="{{ url('curriculum_form') }}" enctype="multipart/form-data">
                            @csrf
                        <div class="modal-body">
                            
                            {{-- Select Date --}}
                            <div>
                                <x-input-label class="font-bold" for="date" :value="__('Select a Date')" />
                                <x-date-picker id="date" class="block mt-1 w-full" type="text" name="date" required/>
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                            {{-- Surah --}}
                            <div>
                                <x-input-label for="sura" :value="__('Select a Surah')" />
                                <select name="dynamic_select" id="dynamic_select" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                                    <option></option>
                                @foreach ($sura as $sura)
                                <option value="{{ $sura->id }}">{{ $sura->sura }}</option>
                                @endforeach
                                </select>
                            </div>
                            {{-- From --}}
                            <div>
                                <x-input-label class="font-bold" for="from" :value="__('From')" />
                                <x-text-input id="from" class="block mt-1 w-full" type="text" name="from"/>
                                <x-input-error :messages="$errors->get('from')" class="mt-2" />
                            </div>
                            {{-- To --}}
                            <div>
                                <x-input-label class="font-bold" for="to" :value="__('To')" />
                                <x-text-input id="to" class="block mt-1 w-full" type="text" name="to"/>
                                <x-input-error :messages="$errors->get('to')" class="mt-2" />
                            </div>
                             {{-- Times --}}
                             <div>
                                <x-input-label class="font-bold" for="times" :value="__('Times')" />
                                <x-text-input id="times" class="block mt-1 w-full" type="text" name="times"/>
                                <x-input-error :messages="$errors->get('times')" class="mt-2" />
                            </div>
                            {{-- Bita --}}
                            <div>
                                <x-input-label class="font-bold" for="bita" :value="__('Izu nawa aka Bita Yau?')" />
                                <x-text-input id="bita" class="block mt-1 w-full" type="text" name="bita"/>
                                <x-input-error :messages="$errors->get('bita')" class="mt-2" />
                            </div>
                            {{-- Grade --}}
                            <div>
                                <x-input-label class="font-bold" for="grade" :value="__('Select a Grade')" />
                                <x-text-select id="grade" class="block mt-1 w-full" type="text" name="grade"/>
                                
                                <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                            </div>
                            {{-- An Karbi Hadda? --}}
                            <div>
                                <x-input-label class="font-bold" for="hadda" :value="__('An Karbi Hadda Yau?')" />
                                <select id="hadda" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="hadda" required>
                                <option>Yes</option>
                                <option>No</option>
                                </select>
                                <x-input-error :messages="$errors->get('hadda')" class="mt-2" />
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