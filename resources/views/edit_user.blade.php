<x-guest-layout>
    <form method="POST" action="/users_database/{{$user->id}}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- User Id -->
        <div>
            <x-input-label for="id" :value="__('User Id')" />
            <x-text-input id="id" class="block mt-1 w-full" type="text" name="id" value="{{ $user->id }}" required autofocus autocomplete="id" />
            <x-input-error :messages="$errors->get('id')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" value="{{ $user->username }}" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role" :value="__('Role')" />
            <x-select-input id="role" class="block mt-1 w-full" type="text" name="role" value="{{ $user->role }}" required autofocus autocomplete="role" />
           <option>{{ $user->role }}</option>
           <option>ADMIN</option>
           <option>EXECUTIVE</option>
           <option>FINXAM</option>
           <option>FINANCE</option>
           <option>EXAM</option>
           <option>TEACHER</option>
           <option>ASSISTANT</option>
           <option>DAN T.P</option>
           <option>GUARDIAN</option>
        </select>
            <x-input-error :messages="$errors->get('role')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" value="{{ $user->email }}" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- <div>
            <x-input-label for="password" :value="__('New Password')" />
            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
            <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full" autocomplete="new-password" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
        </div> --}}
        <br/>
            <x-primary-button class="ml-4">
                {{ __('Update') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
