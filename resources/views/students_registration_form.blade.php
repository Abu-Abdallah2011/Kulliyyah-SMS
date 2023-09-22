<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Registration Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

                        <form method="POST" action="{{ url('students_registration_form') }}" enctype="multipart/form-data">
                            @csrf
                    
                            <!-- Fullname -->
                            <div>
                                <x-input-label for="fullname" :value="__('Fullname')" />
                                <x-text-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" :value="old('fullname')" required autofocus autocomplete="fullname" />
                                <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
                            </div>
                    
                            <!-- Class -->
                            
                            <div>
                                <x-input-label for="class" :value="__('Class')" />
                                <select name="select_class" id="select_class" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                                    
                                @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class }}</option>
                                @endforeach
                                </select>
                            </div>

                            {{-- Set --}}

                            <div>
                                <x-input-label for="set" :value="__('Set')" />
                                <select name="dynamic_select" id="dynamic_select" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                                    <option></option>
                                @foreach ($sets as $set)
                                <option value="{{ $set->id }}">{{ $set->set }}</option>
                                @endforeach
                                </select>
                            </div>

                           <!-- Gender -->
                           <div>
                            <x-input-label for="gender" :value="__('Gender')" />
                            <x-select-input id="gender" class="block mt-1 w-full" type="text" name="gender" :value="old('gender')" required autofocus autocomplete="gender" />
                            <option></option>
                                <option>MALE</option>
                                <option>FEMALE</option>
                           </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>
                    
                           <!-- DOB -->
                           <div>
                            <x-input-label for="dob" :value="__('Date Of Birth')" />
                            <x-text-input id="dob" class="block mt-1 w-full" type="text" name="dob" :value="old('dob')" required autofocus autocomplete="dob" />
                            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
                        </div>
                    
                           <!-- DOA -->
                           <div>
                            <x-input-label for="doa" :value="__('Date Of Admission')" />
                            <x-text-input id="doa" class="block mt-1 w-full" type="text" name="doa" :value="old('doa')" required autofocus autocomplete="doa" />
                            <x-input-error :messages="$errors->get('doa')" class="mt-2" />
                        </div>
                    
                           <!-- Registration Fee -->
                           <div>
                            <x-input-label for="reg_fee" :value="__('Registration Fee')" />
                            <x-text-input id="reg_fee" class="block mt-1 w-full" type="text" name="reg_fee" :value="old('reg_fee')" required autofocus autocomplete="reg_fee" />
                            <x-input-error :messages="$errors->get('reg_fee')" class="mt-2" />
                        </div>
                    
                        <!-- Address -->
                        <div>
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" list="datalistOptions" required autofocus autocomplete="address" />
                            <datalist id="datalistOptions">
                                <option value="Makama New Extension Federal Low-Cost, Bauchi.">
                                <option value="Dutsen Tanshi, Bauchi.">
                                <option value="Danjuma Goje Street, Bauchi.">
                                <option value="Railway, Bauchi.">
                                <option value="Sabuwar Unguwar Railway, Bauchi.">
                              </datalist>
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    
                           <!-- Status -->
                           <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <x-select-input id="status" class="block mt-1 w-full" type="text" name="status" :value="old('status')" required autofocus autocomplete="status" />
                            <option selected>IN SCHOOL</option>
                            <option>GRADUATE</option>
                            <option>LEFT</option>
                            <option>SUSPENDED</option>
                            <option>EXPELLED</option>
                            </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    
                            <!-- Guardian Id -->
                            <div>
                                <x-input-label for="guardian_id" :value="__('Guardian Id')" />
                                <x-text-input id="guardian_id" class="block mt-1 w-full" type="text" name="guardian_id" :value="old('guardian_id')" required autofocus autocomplete="guardian_id" />
                                <x-input-error :messages="$errors->get('guardian_id')" class="mt-2" />
                            </div>
                            
                             <!-- Relationship -->
                             <div>
                                <x-input-label for="relationship" :value="__('Relationship')" />
                                <x-text-input id="relationship" class="block mt-1 w-full" type="text" name="relationship" :value="old('relationship')" required autofocus autocomplete="relationship" />
                                <x-input-error :messages="$errors->get('relationship')" class="mt-2" />
                            </div>
                    
                            <!-- Photo Upload -->
                            <div>
                                <x-input-label for="photo" :value="__('Photo')" />
                                <x-text-input id="file" class="block mt-1 w-full" type="file" name="photo" :value="old('photo')" autofocus autocomplete="photo" />
                                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
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
