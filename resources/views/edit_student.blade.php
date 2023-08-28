<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Edit Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>
                        
                        <form method="POST" action="/students_database/{{$student->id}}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            @can('isAdmin')

                            <!-- Student Id -->
                            <div>
                                <x-input-label for="id" :value="__('Student Id')" />
                                <x-text-input id="id" class="block mt-1 w-full" type="text" name="id" value="{{$student->id}}" required autofocus autocomplete="id" />
                                <x-input-error :messages="$errors->get('id')" class="mt-2" />
                            </div>
                    
                            <!-- Fullname -->
                            <div>
                                <x-input-label for="fullname" :value="__('Fullname')" />
                                <x-text-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" value="{{$student->fullname}}" required autofocus autocomplete="fullname" />
                                <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
                            </div>

                            @endcan
                    
                            <!-- Class -->

                            <div>
                                <x-input-label for="class" :value="__('Class')" />
                                <select name="select_class" id="select_class" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                                    <option>{{$student->class}}</option>
                                @foreach ($classes as $class)
                                <option value="{{ $class->id }}">{{ $class->class }}</option>
                                @endforeach
                                </select>
                            </div>

                            {{-- Set --}}
                            
                            <div>
                                <x-input-label for="set" :value="__('Set')" />
                                <select name="dynamic_select" id="dynamic_select" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                                    <option>{{ $student->set }}</option>
                                @foreach ($sets as $set)
                                <option value="{{ $set->id }}">{{ $set->set }}</option>
                                @endforeach
                                </select>
                            </div>

                            @can('isAdmin')
                            
                           <!-- Gender -->
                           <div>
                            <x-input-label for="gender" :value="__('Gender')" />
                            <x-select-input id="gender" class="block mt-1 w-full" type="text" name="gender" value="{{$student->gender}}" required autofocus autocomplete="gender" />
                                <option>{{$student->gender}}</option>
                                <option>MALE</option>
                                <option>FEMALE</option>
                            </select>
                            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
                        </div>
                    
                           <!-- DOB -->
                           <div>
                            <x-input-label for="dob" :value="__('Date Of Birth')" />
                            <x-text-input id="dob" class="block mt-1 w-full" type="text" name="dob" value="{{$student->dob}}" required autofocus autocomplete="dob" />
                            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
                        </div>
                    
                           <!-- DOA -->
                           <div>
                            <x-input-label for="doa" :value="__('Date Of Admission')" />
                            <x-text-input id="doa" class="block mt-1 w-full" type="text" name="doa" value="{{$student->doa}}" required autofocus autocomplete="doa" />
                            <x-input-error :messages="$errors->get('doa')" class="mt-2" />
                        </div>
                    
                           <!-- Registration Fee -->
                           <div>
                            <x-input-label for="reg_fee" :value="__('Registration Fee')" />
                            <x-text-input id="reg_fee" class="block mt-1 w-full" type="text" name="reg_fee" value="{{$student->reg_fee}}" required autofocus autocomplete="reg_fee" />
                            <x-input-error :messages="$errors->get('reg_fee')" class="mt-2" />
                        </div>
                    
                        <!-- Address -->
                        <div>
                            <x-input-label for="address" :value="__('Address')" />
                            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" value="{{$student->address}}" required autofocus autocomplete="address" />
                            <x-input-error :messages="$errors->get('address')" class="mt-2" />
                        </div>
                    
                           <!-- Status -->
                           <div>
                            <x-input-label for="status" :value="__('Status')" />
                            <x-select-input id="status" class="block mt-1 w-full" type="text" name="status" value="{{$student->status}}" required autofocus autocomplete="status" />
                                <option>{{$student->status}}</option>
                                <option>IN SCHOOL</option>
                                <option>GRADUATE</option>
                                <option>LEFT</option>
                                <option>SUSPENDED</option>
                                <option>EXPELLED</option>
                                </select>
                            <x-input-error :messages="$errors->get('status')" class="mt-2" />
                        </div>
                    
                            <!-- Guadian Id -->
                            <div>
                                <x-input-label for="guardian_id" :value="__('Guardian Id')" />
                                <x-text-input id="guardian_id" class="block mt-1 w-full" type="text" name="guardian_id" value="{{$student->guardian_id}}" required autofocus autocomplete="guardian_id" />
                                <x-input-error :messages="$errors->get('guardian_id')" class="mt-2" />
                            </div>
                            
                            <!-- Relationship -->
                            <div>
                                <x-input-label for="relationship" :value="__('Relationship')" />
                                <x-text-input id="relationship" class="block mt-1 w-full" type="text" name="relationship" value="{{$student->relationship}}" required autofocus autocomplete="relationship" />
                                <x-input-error :messages="$errors->get('relationship')" class="mt-2" />
                            </div>

                            @if ($student->status === 'GRADUATE')
                    
                            <!-- Graduation Type -->
                            <div>
                                <x-input-label for="grad_type" :value="__('Graduation Type')" />
                                <x-select-input id="grad_type" class="block mt-1 w-full" type="text" name="grad_type" value="{{$student->grad_type}}" autofocus autocomplete="grad_type" />
                                    <option>{{$student->grad_type}}</option>
                                    <option>HADDA ZALLA</option>
                                    <option>TARTEEL ZALLA</option>
                                    <option>HADDA DA TARTEEL</option>
                                    </select>
                                <x-input-error :messages="$errors->get('grad_type')" class="mt-2" />
                            </div>
                    
                            <!-- Mock Fee -->
                            <div>
                                <x-input-label for="mock_fee" :value="__('Mock Fee')" />
                                <x-text-input id="mock_fee" class="block mt-1 w-full" type="text" name="mock_fee" value="{{$student->mock_fee}}" autofocus autocomplete="mock_fee" />
                                <x-input-error :messages="$errors->get('mock_fee')" class="mt-2" />
                            </div>
                    
                            <!-- Graduation Fee -->
                            <div>
                                <x-input-label for="grad_fee" :value="__('Graduation Fee')" />
                                <x-text-input id="grad_fee" class="block mt-1 w-full" type="text" name="grad_fee" value="{{$student->grad_fee}}" autofocus autocomplete="grad_fee" />
                                <x-input-error :messages="$errors->get('grad_fee')" class="mt-2" />
                            </div>
                    
                            <!-- Graduation Date -->
                            <div>
                                <x-input-label for="grad_date" :value="__('Graduation Date')" />
                                <x-text-input id="grad_date" class="block mt-1 w-full" type="text" name="grad_date" value="{{$student->grad_date}}" autofocus autocomplete="grad_date" />
                                <x-input-error :messages="$errors->get('grad_date')" class="mt-2" />
                            </div>
                    
                            <!-- Year Of Graduation -->
                            <div>
                                <x-input-label for="grad_yr" :value="__('Year Of Graduation')" />
                                <x-text-input id="grad_yr" class="block mt-1 w-full" type="text" name="grad_yr" value="{{$student->grad_yr}}" autofocus autocomplete="grad_yr" />
                                <x-input-error :messages="$errors->get('grad_yr')" class="mt-2" />
                            </div>

                        @endif
                    
                            <!-- Photo Upload -->
                            <div>
                                <x-input-label for="photo" :value="__('Photo')" />
                                <x-text-input id="file" class="block mt-1 w-full" type="file" name="photo" autofocus autocomplete="photo" />
                                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                                <img class="hidden w-48 mr-6 md:block" src="{{ asset('storage/' . $student->photo) }}" alt="" />
                            </div>

                            @endcan

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
