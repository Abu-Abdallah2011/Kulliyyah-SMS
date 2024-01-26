<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Guardians Database') }}
            <a href="guardians_reg_form">
                <x-primary-button class="absolute top-15 right-9 bg-green-500">
                    <i class="fa-solid fa-user-plus"> </i>
                </x-primary-button> 
                </a>
        </h2>
    </x-slot>
            <!-- Session Status -->
        <x-success-status class="mb-4" :status="session('message')" />
        <x-search-guardians />
        
        <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
            @unless (count($guardians) == 0)
                
            @foreach ($guardians as $guardian)

            <div class="bg-blue-100 border border-gray-200 rounded p-6">
                <div class="flex">

                    <div class="font-bold">
                        <h3 class="text-2xl">
                            {{$guardian->id}}
                        </h3>

                    <div class="font-bold">
                        <h3 class="text-2xl">
                            <a href="{{ url('/guardians_database/' . $guardian->id) }}">{{$guardian->fullname}}</a>
                        </h3>
                            <div class="text-xl mb-4">
                            Phone Number: {{$guardian->phone}}
                                <div class="text-xl mb-4">
                                Email Address: {{$guardian->email}}
                            <div class="text-lg mt-4">
                                <i class="fa-solid fa-location-dot"></i>
                                {{$guardian->address}}
                                <div class="text-sm mt-4">
                                    Added by: {{ $guardian->created_by }} at: {{ $guardian->created_at }}
                                     <br/>
                                    Edited by: {{ $guardian->edited_by }} at: {{ $guardian->updated_at }}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
                    </div>
               
            </div>
            @endforeach
            @endunless
                </div>
                

                <div class="mt-6 p-4">
                    {{$guardians->Links()}}
                </div>
            {{-- </div>
            
        </div> --}}
    
</x-app-layout>
