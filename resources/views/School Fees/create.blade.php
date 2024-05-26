<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('School Fees Data Entry Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

                        <form method="POST" action="{{ url('/fees_record/' . $student->id) }}" enctype="multipart/form-data">
                            @csrf
                    
                            <!-- Date -->
                            <div>
                                <x-input-label for="date" :value="__('Date')" />
                                <x-date-picker id="date" class="block mt-1 w-full" type="text" name="date" :value="old('date')" required autocomplete="date" />
                                <x-input-error :messages="$errors->get('date')" class="mt-2" />
                            </div>
                    
                            <!-- Surah -->
                            {{-- <div>
                                <x-input-label for="sura" :value="__('Select a Surah')" />
                                <select name="dynamic_select" id="dynamic_select" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                                    
                                @foreach ($sura as $sura)
                                <option value="{{ $sura->id }}">{{ $sura->sura }}</option>
                                @endforeach
                                </select>
                            </div> --}}
                           
                           <!-- Name -->
                           <div>
                            <x-input-label class="font-bold" for="name" :value="__('Name')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                             required autocomplete="name" value="{{ $student->fullname }}" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Class -->
                        <div>
                        <x-input-label class="font-bold" for="class" :value="__('Class')" />
                        <x-text-input id="class" class="block mt-1 w-full" type="text" name="class" :value="old('class')"
                            required autocomplete="class" value="{{ $student->class }}" />
                        <x-input-error :messages="$errors->get('class')" class="mt-2" />
                    </div>
                    
                        <!-- Status -->
                        <div>
                            <x-input-label class="font-bold" for="status" :value="__('Status')" />
                            <x-select-input id="status" class="block mt-1 w-full" type="text" name="status" :value="old('status')" required autocomplete="status" />
                            <option selected>PAID</option>
                            <option>PART</option>
                            <option>FREE</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    
                           <!-- Amount -->
                           <div>
                            <x-input-label class="font-bold" for="amount" :value="__('Amount Paid')" />
                            <x-text-input id="amount" class="block mt-1 w-full" type="text" name="amount" :value="old('amount')" list="datalistOptions" required autocomplete="amount" />
                            <datalist id="datalistOptions">
                                <option value="500">
                                <option value="1000">
                                <option value="1500">
                                <option value="2000">
                                <option value="2500">
                                <option value="3000">
                                <option value="3500">
                              </datalist>
                            <x-input-error :messages="$errors->get('amount')" class="mt-2" />
                        </div>

                        <!-- Paid For -->
                        <div>
                            <x-input-label class="font-bold" for="paid_for" :value="__('Paid For')" />
                            <x-text-input id="paid_for" class="block mt-1 w-full" type="text" name="paid_for"/>
                            <x-input-error :messages="$errors->get('paid_for')" class="mt-2" />
                        </div>

                        <!-- Balance -->
                        <div>
                            <x-input-label class="font-bold" for="balance" :value="__('Balance')" />
                            <x-text-input id="balance" class="block mt-1 w-full" type="text" name="balance"/>
                            <x-input-error :messages="$errors->get('balance')" class="mt-2" />
                        </div>

                            <!-- Term -->
                            <div>
                            <x-input-label class="font-bold" for="term" :value="__('Term')" />
                            <x-text-input id="term" class="block mt-1 w-full" type="text" name="term"/>
                            <x-input-error :messages="$errors->get('term')" class="mt-2" />
                        </div>

                            <!-- Session -->
                           <div>
                            <x-input-label class="font-bold" for="session" :value="__('Session')" />
                            <x-text-input id="session" class="block mt-1 w-full" type="text" name="session"/>
                            <x-input-error :messages="$errors->get('session')" class="mt-2" />
                        </div>
                    
                {{-- Description --}}
            <div>
                <x-input-label class="font-bold" for="description" :value="__('Description')" />
                <textarea id="description" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full"
                 type="text" name="description">
                </textarea>
                <x-input-error :messages="$errors->get('description')" class="mt-2" />
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
