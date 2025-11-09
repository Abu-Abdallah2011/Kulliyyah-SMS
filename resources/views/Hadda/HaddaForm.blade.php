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
                    
                            <!--From Surah -->
                            <div>
                                <x-input-label for="from_sura" :value="__('From Surah')" />
                                <select name="from_surah" id="from_surah" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                                    
                                @foreach ($sura as $sura)
                                <option value="{{ $sura->id }}">{{ $sura->sura }}</option>
                                @endforeach
                                </select>
                            </div>
                           
                           <!-- From Verse -->
                           <div>
                            <x-input-label for="from" :value="__('From Verse')" />
                            <x-text-input id="from" class="block mt-1 w-full" type="text" name="from" :value="old('from')" required autofocus autocomplete="from" />
                            <x-input-error :messages="$errors->get('from')" class="mt-2" />
                        </div>

                        <!--To Surah -->
                            <div>
                                <x-input-label for="to_surah" :value="__('To Surah')" />
                                <select name="to_surah" id="to_surah" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                                    
                                @foreach ($surah as $sura)
                                <option value="{{ $sura->id }}">{{ $sura->sura }}</option>
                                @endforeach
                                </select>
                            </div>
                    
                           <!-- To Verse -->
                           <div>
                            <x-input-label for="to" :value="__('To Verse')" />
                            <x-text-input id="to" class="block mt-1 w-full" type="text" name="to" :value="old('to')" required autofocus autocomplete="to" />
                            <x-input-error :messages="$errors->get('to')" class="mt-2" />
                        </div>

                        <!-- Score -->
                           <div>
                            <x-input-label for="score" :value="__('Score')" />
                            <x-text-input id="score" class="block mt-1 w-full" type="text" name="score" :value="old('score')" required autofocus autocomplete="score" />
                            <x-input-error :messages="$errors->get('score')" class="mt-2" />
                        </div>
                    
                           <!-- Grade -->
                           <div>
                            <x-input-label for="grade" :value="__('Select a Grade')" />
                            <x-text-select id="grade" class="block mt-1 w-full" type="text" name="grade"/>
                            <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                        </div>
                    
                        {{-- Comment --}}
            <div>
                <x-input-label for="comment" :value="__('Comment')" />
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


<!-- Add this script at the bottom of your Blade file -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const fromSurah = document.getElementById('from_surah');
    const toSurah = document.getElementById('to_surah');

    fromSurah.addEventListener('change', function() {
        // Get selected value
        const selectedValue = fromSurah.value;
        // Set same value to the "To Surah" field
        toSurah.value = selectedValue;
    });
});
</script>