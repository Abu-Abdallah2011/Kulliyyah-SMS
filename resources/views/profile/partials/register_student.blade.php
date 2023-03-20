<section>

    <form method="POST" action="{{-- route('register') --}}students_registration_form" enctype="multipart/form-data">
        @csrf

        <!-- S/N -->
        <div>
            <x-input-label for="S/N" :value="__('S/N')" />
            <x-text-input id="S/N" class="block mt-1 w-full" type="text" name="S/N" :value="old('S/N')" required autofocus autocomplete="S/N" />
            <x-input-error :messages="$errors->get('S/N')" class="mt-2" />
        </div>

        <!-- Fullname -->
        <div>
            <x-input-label for="fullname" :value="__('Fullname')" />
            <x-text-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" :value="old('fullname')" required autofocus autocomplete="fullname" />
            <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
        </div>

        <!-- Class -->
        <div>
            <x-input-label for="class" :value="__('Class')" />
            <x-text-input id="class" class="block mt-1 w-full" type="text" name="class" :value="old('class')" required autofocus autocomplete="class" />
            <x-input-error :messages="$errors->get('class')" class="mt-2" />
        </div>

       <!-- Gender -->
       <div>
        <x-input-label for="gender" :value="__('Gender')" />
        <x-text-input id="gender" class="block mt-1 w-full" type="text" name="gender" :value="old('gender')" required autofocus autocomplete="gender" />
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
        <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" required autofocus autocomplete="address" />
        <x-input-error :messages="$errors->get('address')" class="mt-2" />
    </div>

       <!-- Status -->
       <div>
        <x-input-label for="status" :value="__('Status')" />
        <x-text-input id="status" class="block mt-1 w-full" type="text" name="status" :value="old('status')" required autofocus autocomplete="status" />
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

        <!-- Name Of Guadian -->
        <div>
            <x-input-label for="name_of_guardian" :value="__('Name Of Guardian')" />
            <x-text-input id="name_of_guardian" class="block mt-1 w-full" type="text" name="name_of_guardian" :value="old('name_of_guardian')" required autofocus autocomplete="name_of_guardian" />
            <x-input-error :messages="$errors->get('name_of_guardian')" class="mt-2" />
        </div>

       <!-- Contact Number -->
       <div>
        <x-input-label for="contact" :value="__('Contact Number')" />
        <x-text-input id="contact" class="block mt-1 w-full" type="text" name="contact" :value="old('contact')" required autofocus autocomplete="contact" />
        <x-input-error :messages="$errors->get('contact')" class="mt-2" />
    </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Graduation Type -->
        <div>
            <x-input-label for="grad_type" :value="__('Graduation Type')" />
            <x-text-input id="grad_type" class="block mt-1 w-full" type="text" name="grad_type" :value="old('grad_type')" required autofocus autocomplete="grad_type" />
            <x-input-error :messages="$errors->get('grad_type')" class="mt-2" />
        </div>

        <!-- Mock Fee -->
        <div>
            <x-input-label for="mock_fee" :value="__('Mock Fee')" />
            <x-text-input id="mock_fee" class="block mt-1 w-full" type="text" name="mock_fee" :value="old('mock_fee')" required autofocus autocomplete="mock_fee" />
            <x-input-error :messages="$errors->get('mock_fee')" class="mt-2" />
        </div>

        <!-- Graduation Fee -->
        <div>
            <x-input-label for="grad_fee" :value="__('Graduation Fee')" />
            <x-text-input id="grad_fee" class="block mt-1 w-full" type="text" name="grad_fee" :value="old('grad_fee')" required autofocus autocomplete="grad_fee" />
            <x-input-error :messages="$errors->get('grad_fee')" class="mt-2" />
        </div>

        <!-- Graduation Date -->
        <div>
            <x-input-label for="grad_date" :value="__('Graduation Date')" />
            <x-text-input id="grad_date" class="block mt-1 w-full" type="text" name="grad_date" :value="old('grad_date')" required autofocus autocomplete="grad_date" />
            <x-input-error :messages="$errors->get('grad_date')" class="mt-2" />
        </div>

        <!-- Year Of Graduation -->
        <div>
            <x-input-label for="grad_yr" :value="__('Year Of Graduation')" />
            <x-text-input id="grad_yr" class="block mt-1 w-full" type="text" name="grad_yr" :value="old('grad_yr')" required autofocus autocomplete="grad_yr" />
            <x-input-error :messages="$errors->get('grad_yr')" class="mt-2" />
        </div>

        <!-- Photo Upload -->
        <div>
            <x-input-label for="photo" :value="__('Photo')" />
            <x-text-input id="file" class="block mt-1 w-full" type="file" name="photo" :value="old('photo')" required autofocus autocomplete="photo" />
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
