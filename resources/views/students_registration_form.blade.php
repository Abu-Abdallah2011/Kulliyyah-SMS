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
                                <x-select-input id="class" class="block mt-1 w-full" type="text" name="class" :value="old('class')" required autofocus autocomplete="class" />
                                <option></option>
                                <option>HADDA FOUR MALE</option>
                                <option>HADDA FOUR FEMALE</option>
                                <option>HADDA THREE MALE</option>
                                <option>HADDA THREE FEMALE</option>
                                <option>HADDA TWO MALE</option>
                                <option>HADDA TWO FEMALE</option>
                                <option>HADDA ONE MALE</option>
                                <option>HADDA ONE FEMALE</option>
                                <option>TARTEEL MALE</option>
                                <option>TARTEEL FEMALE</option>
                                <option>PRE-HADDA THREE MALE</option>
                                <option>PRE-HADDA THREE FEMALE</option>
                                <option>PRE-HADDA TWO MALE</option>
                                <option>PRE-HADDA TWO FEMALE</option>
                                <option>PRE-HADDA ONE MALE</option>
                                <option>PRE-HADDA ONE FEMALE</option>
                                </select>
                                <x-input-error :messages="$errors->get('class')" class="mt-2" />
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
                    
                            <!-- Graduation Type -->
                            {{-- <div>
                                <x-input-label for="grad_type" :value="__('Graduation Type')" />
                                <x-select-input id="grad_type" class="block mt-1 w-full" type="text" name="grad_type" :value="old('grad_type')" autofocus autocomplete="grad_type" />
                                <option></option>
                                <option>HADDA ZALLA</option>
                                <option>TARTEEL ZALLA</option>
                                <option>HADDA DA TARTEEL</option>
                                </select>
                                <x-input-error :messages="$errors->get('grad_type')" class="mt-2" />
                            </div> --}}
                    
                            <!-- Mock Fee -->
                            {{-- <div>
                                <x-input-label for="mock_fee" :value="__('Mock Fee')" />
                                <x-text-input id="mock_fee" class="block mt-1 w-full" type="text" name="mock_fee" :value="old('mock_fee')" autofocus autocomplete="mock_fee" />
                                <x-input-error :messages="$errors->get('mock_fee')" class="mt-2" />
                            </div> --}}
                    
                            <!-- Graduation Fee -->
                            {{-- <div>
                                <x-input-label for="grad_fee" :value="__('Graduation Fee')" />
                                <x-text-input id="grad_fee" class="block mt-1 w-full" type="text" name="grad_fee" :value="old('grad_fee')" autofocus autocomplete="grad_fee" />
                                <x-input-error :messages="$errors->get('grad_fee')" class="mt-2" />
                            </div> --}}
                    
                            <!-- Graduation Date -->
                            {{-- <div>
                                <x-input-label for="grad_date" :value="__('Graduation Date')" />
                                <x-text-input id="grad_date" class="block mt-1 w-full" type="text" name="grad_date" :value="old('grad_date')" autofocus autocomplete="grad_date" />
                                <x-input-error :messages="$errors->get('grad_date')" class="mt-2" />
                            </div> --}}
                    
                            <!-- Year Of Graduation -->
                            {{-- <div>
                                <x-input-label for="grad_yr" :value="__('Year Of Graduation')" />
                                <x-text-input id="grad_yr" class="block mt-1 w-full" type="text" name="grad_yr" :value="old('grad_yr')" autofocus autocomplete="grad_yr" />
                                <x-input-error :messages="$errors->get('grad_yr')" class="mt-2" />
                            </div> --}}
                    
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
