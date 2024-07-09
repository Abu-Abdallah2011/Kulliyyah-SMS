<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teachers Edit Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

        <form method="POST" action="/teachers_database/{{$teacher->id}}" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @can('isAdmin')
    
            <!-- Teacher Id -->
            <div>
                <x-input-label for="id" :value="__('Teacher Id')" />
                <x-text-input id="id" class="block mt-1 w-full" type="text" name="id" value="{{ $teacher->id }}"  autofocus autocomplete="id" />
                <x-input-error :messages="$errors->get('id')" class="mt-2" />
            </div>

            <!-- User Id -->
            <div>
                <x-input-label for="user_id" :value="__('User Id')" />
                <x-text-input id="user_id" class="block mt-1 w-full" type="text" name="user_id" value="{{ $teacher->user_id }}"  autofocus autocomplete="user_id" />
                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
            </div>
    
            <!-- Fullname -->
            <div>
                <x-input-label for="fullname" :value="__('Fullname')" />
                <x-text-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" value="{{ $teacher->fullname }}" required autofocus autocomplete="fullname" />
                <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
            </div>

            {{-- @endcan --}}
    
            <!-- Class -->
            <div>
                <x-input-label for="class" :value="__('Class')" />
                <select name="select_class" id="select_class" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                    <option>{{$teacher->class}}</option>
                @foreach ($classes as $class)
                <option value="{{ $class->id }}">{{ $class->class }}</option>
                @endforeach
                </select>
            </div>

            {{-- Set --}}
            <div>
                <x-input-label for="set" :value="__('Set')" />
                <select name="dynamic_select" id="dynamic_select" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                    <option>{{ $teacher->set }}</option>
                @foreach ($sets as $set)
                <option value="{{ $set->id }}">{{ $set->set }}</option>
                @endforeach
                </select>
            </div>

            {{-- @can('isAdmin') --}}

            <!-- Gender -->
        <div>
            <x-input-label for="gender" :value="__('Gender')" />
            <x-select-input id="gender" class="block mt-1 w-full" type="text" name="gender" value="{{ $teacher->gender }}" required autofocus autocomplete="gender" />
            <option>{{ $teacher->gender }}</option>
                <option>MALE</option>
                <option>FEMALE</option>
            </select>
            <x-input-error :messages="$errors->get('gender')" class="mt-2" />
        </div>

        
            <!-- DOB -->
            <div>
            <x-input-label for="dob" :value="__('Date Of Birth')" />
            <x-text-input id="dob" class="block mt-1 w-full" type="text" name="dob" value="{{ $teacher->dob }}" required autofocus autocomplete="dob" />
            <x-input-error :messages="$errors->get('dob')" class="mt-2" />
        </div>

            <!-- Marital Status -->
            <div>
            <x-input-label for="marital_status" :value="__('Marital Status')" />
            <x-select-input id="marital_status" class="block mt-1 w-full" type="text" name="marital_status" value="{{ $teacher->marital_status }}" required autofocus autocomplete="marital_status" />
            <option>{{ $teacher->marital_status }}</option>
                <option>Single</option>
                <option>Married</option>
                <option>Divorced</option>
                <option>Widow/Widower</option>
            </select>
            <x-input-error :messages="$errors->get('marital_status')" class="mt-2" />
        </div>
    
            <!-- DOFA -->
            <div>
            <x-input-label for="doa" :value="__('Date Of First Appointment')" />
            <x-text-input id="dofa" class="block mt-1 w-full" type="text" name="dofa" value="{{ $teacher->dofa }}" required autofocus autocomplete="dofa" />
            <x-input-error :messages="$errors->get('dofa')" class="mt-2" />
        </div>
    
        <!-- Address -->
        <div>
            <x-input-label for="address" :value="__('Address')" />
            <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" value="{{ $teacher->address }}" list="datalistOptions" required autofocus autocomplete="address" />
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
            <x-select-input id="status" class="block mt-1 w-full" type="text" name="status" value="{{ $teacher->status }}" required autofocus autocomplete="status" />
            <option>{{ $teacher->status }}</option>
            <option>IN SCHOOL</option>
            <option>NOT IN SCHOOL</option>
            </select>
            <x-input-error :messages="$errors->get('status')" class="mt-2" />
        </div>
    
            <!-- Rank -->
            <div>
                <x-input-label for="rank" :value="__('Rank')" />
                <select id="rank" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="rank" value="{{$teacher->rank}}" required autofocus autocomplete="rank">
                    <option value="{{$teacher->rank}}">{{$teacher->rank}}</option>
                    <option value="CLASS TEACHER">CLASS TEACHER</option>
                    <option value="ASSISTANT CLASS TEACHER">ASSISTANT CLASS TEACHER</option>
                    <option value="TEACHING PRACTICE">TEACHING PRACTICE</option>
                    </select>
                <x-input-error :messages="$errors->get('rank')" class="mt-2" />
            </div>

            <!-- Year OF promotion -->
            <div>
                <x-input-label for="promotion_yr" :value="__('Year Of Promotion')" />
                <x-text-input id="promotion_yr" class="block mt-1 w-full" type="text" name="promotion_yr" value="{{ $teacher->promotion_yr }}" autofocus autocomplete="promotion_yr" />
                <x-input-error :messages="$errors->get('promotion_yr')" class="mt-2" />
            </div>
    
            <!-- Contact Number -->
            <div>
            <x-input-label for="contact_no" :value="__('Contact Number')" />
            <x-text-input id="contact_no" class="block mt-1 w-full" type="text" name="contact_no" value="{{ $teacher->contact_no }}" required autofocus autocomplete="contact_no" />
            <x-input-error :messages="$errors->get('contact_no')" class="mt-2" />
        </div>
    
            <!-- Bank Branch -->
            <div>
                <x-input-label for="bank_branch" :value="__('Bank Branch')" />
                <x-text-input id="bank_branch" class="block mt-1 w-full" type="text" name="bank_branch" value="{{ $teacher->bank_branch }}" autofocus autocomplete="bank_branch" />
                <x-input-error :messages="$errors->get('bank_branch')" class="mt-2" />
            </div>
    
            <!-- Account Name -->
            <div>
                <x-input-label for="acct_name" :value="__('Account Name')" />
                <x-text-input id="acct_name" class="block mt-1 w-full" type="text" name="acct_name" value="{{ $teacher->acct_name }}" autofocus autocomplete="acct_name" />
                <x-input-error :messages="$errors->get('acct_name')" class="mt-2" />
            </div>
    
            <!-- Account Number -->
            <div>
                <x-input-label for="acct_no" :value="__('Account Number')" />
                <x-text-input id="acct_no" class="block mt-1 w-full" type="text" name="acct_no" value="{{ $teacher->acct_no }}" autofocus autocomplete="acct_no" />
                <x-input-error :messages="$errors->get('acct_no')" class="mt-2" />
            </div>
    
            <!-- Monthly Allowance -->
            <div>
                <x-input-label for="allowance" :value="__('Monthly Allowance')" />
                <x-text-input id="allowance" class="block mt-1 w-full" type="text" name="allowance" value="{{ $teacher->allowance }}" autofocus autocomplete="allowance" />
                <x-input-error :messages="$errors->get('allowance')" class="mt-2" />
            </div>
    
            <!-- Hometown -->
            <div>
                <x-input-label for="hometown" :value="__('Hometown')" />
                <x-text-input id="hometown" class="block mt-1 w-full" type="text" name="hometown" value="{{ $teacher->hometown }}" autofocus autocomplete="hometown" />
                <x-input-error :messages="$errors->get('hometown')" class="mt-2" />
            </div>

            <!-- Next Of Kin -->
            <div>
                <x-input-label for="nok" :value="__('Next Of Kin')" />
                <x-text-input id="nok" class="block mt-1 w-full" type="text" name="nok" value="{{ $teacher->nok }}" autofocus autocomplete="nok" />
                <x-input-error :messages="$errors->get('nok')" class="mt-2" />
            </div>

            <!-- Relationship -->
            <div>
                <x-input-label for="relationship" :value="__('Relationship')" />
                <x-text-input id="relationship" class="block mt-1 w-full" type="text" name="relationship" value="{{ $teacher->relationship }}" autofocus autocomplete="relationship" />
                <x-input-error :messages="$errors->get('relationship')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div>
                <x-input-label for="contact" :value="__('Contact')" />
                <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" value="{{ $teacher->contact }}" autofocus autocomplete="contact" />
                <x-input-error :messages="$errors->get('contact')" class="mt-2" />
            </div>
    
            <!-- Photo Upload -->
            <div>
                <x-input-label for="photo" :value="__('Photo')" />
                <x-text-input id="file" class="block mt-1 w-full" type="file" name="photo" value="{{ asset('storage/' . $teacher->photo) }}" autofocus autocomplete="photo" />
                <x-input-error :messages="$errors->get('photo')" class="mt-2" />
                    <img class="hidden w-48 mr-6 md:block" src="{{ asset('storage/' . $teacher->photo) }}" alt="" />
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
