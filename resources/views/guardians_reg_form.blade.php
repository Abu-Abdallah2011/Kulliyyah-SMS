<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Guardians Registration Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

                        <form method="POST" action="{{ url('guardians_reg_form') }}" enctype="multipart/form-data">
                            @csrf

                              <!-- User Id -->
                              <div>
                                <x-input-label for="user_id" :value="__('User Id')" />
                                <x-text-input id="user_id" class="block mt-1 w-full" type="text" name="user_id" :value="old('user_id')" required autofocus autocomplete="user_id" />
                                <x-input-error :messages="$errors->get('user_id')" class="mt-2" />
                            </div>
                    
                            <!-- Fullname -->
                            <div>
                                <x-input-label for="fullname" :value="__('Fullname')" />
                                <x-text-input id="fullname" class="block mt-1 w-full" type="text" name="fullname" :value="old('fullname')" required autofocus autocomplete="fullname" />
                                <x-input-error :messages="$errors->get('fullname')" class="mt-2" />
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
                    
                    
                           <!-- Phone Number -->
                           <div>
                            <x-input-label for="phone" :value="__('Phone Number')" />
                            <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" required autofocus autocomplete="phone" />
                            <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                        </div>

                             <!-- Teacher Id -->
                             <div>
                                <x-input-label for="teacher_id" :value="__('Teacher Id')" />
                                <x-text-input id="teacher_id" class="block mt-1 w-full" type="text" name="teacher_id" placeholder="Only when the Guardian is also a Teacher" :value="old('teacher_id')"  autofocus autocomplete="teacher_id" />
                                <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
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
