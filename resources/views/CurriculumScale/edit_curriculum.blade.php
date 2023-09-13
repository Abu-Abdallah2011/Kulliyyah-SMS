<!--===========================================================================================
                              CODES FOR CURRICULUM SCALE EDIT RECORD 
      ==========================================================================================-->

      <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Curriculum Edit Form') }}
            </h2>
        </x-slot>
    
        <div class="py-12">
    
    
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    
                        <section>
    
                <form method="POST" action="/curriculum_scale/{{$curriculum->id}}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
            
                
                {{-- Select Date --}}
                <div>
                    <x-input-label class="font-bold" for="date" :value="__('Select a Date')" />
                    <x-date-picker id="date" class="block mt-1 w-full" type="text" name="date" value="{{$curriculum->date}}" required/>
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>
                {{-- Surah --}}
                <div>
                    <x-input-label for="sura" :value="__('Select a Surah')" />
                    <select name="dynamic_select" id="dynamic_select" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                        <option>{{$curriculum->sura}}</option>
                    @foreach ($sura as $sura)
                    <option value="{{ $sura->id }}">{{ $sura->sura }}</option>
                    @endforeach
                    </select>
                </div>
                {{-- From --}}
                <div>
                    <x-input-label class="font-bold" for="from" :value="__('From')" />
                    <x-text-input id="from" class="block mt-1 w-full" type="text" name="from" value="{{$curriculum->from}}"/>
                    <x-input-error :messages="$errors->get('from')" class="mt-2" />
                </div>
                {{-- To --}}
                <div>
                    <x-input-label class="font-bold" for="to" :value="__('To')" />
                    <x-text-input id="to" class="block mt-1 w-full" type="text" name="to" value="{{$curriculum->to}}"/>
                    <x-input-error :messages="$errors->get('to')" class="mt-2" />
                </div>
                 {{-- Sau Nawa aka bita Karin? --}}
                 <div>
                    <x-input-label class="font-bold" for="times" :value="__('Sau Nawa aka bita Karin?')" />
                    <x-text-input id="times" class="block mt-1 w-full" type="text" name="times" value="{{$curriculum->times}}"/>
                    <x-input-error :messages="$errors->get('times')" class="mt-2" />
                </div>
                {{-- Bita --}}
                <div>
                    <x-input-label class="font-bold" for="bita" :value="__('Izu nawa aka Bita?')" />
                    <x-text-input id="bita" class="block mt-1 w-full" type="text" name="bita" value="{{$curriculum->bita}}"/>
                    <x-input-error :messages="$errors->get('bita')" class="mt-2" />
                </div>
                {{-- Grade --}}
                <div>
                    <x-input-label class="font-bold" for="grade" :value="__('Select a Grade')" />
                    <select id="grade" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="grade">
                        <option>{{$curriculum->grade}}</option>
                        <option>ممتاز</option>
                        <option>جيدجدا</option>
                        <option>جيد</option>
                        <option>لابأس</option>
                    </select>
                    <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                </div>
                {{-- An Karbi Hadda? --}}
                <div>
                    <x-input-label class="font-bold" for="hadda" :value="__('An Karbi Hadda?')" />
                    <select id="hadda" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="hadda" required>
                    <option>{{$curriculum->hadda}}</option>
                    <option>Yes</option>
                    <option>No</option>
                    </select>
                    <x-input-error :messages="$errors->get('hadda')" class="mt-2" />
                </div>
                {{-- Comment --}}
                <div>
                    <x-input-label class="font-bold" for="comment" :value="__('Comment')" />
                    <textarea id="comment" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="comment">
                        {{$curriculum->comment}}
                    </textarea>
                    <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                </div>
              
                <br/>
                <div>
                    <x-primary-button class="ml-3">
                        {{ __('Update') }}
                    </x-primary-button>
                </div>
        </form>

    </section>
</div>
</div>
</div>
</x-app-layout>
    <!--======================================================================================
                          END OF CURRICULUM SCALE EDIT RECORD CODES
      ========================================================================================-->