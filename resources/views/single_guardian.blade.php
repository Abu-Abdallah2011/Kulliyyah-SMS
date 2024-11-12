{{-- <x-app-layout>
    <x-slot name="header">
        
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                  
            
            <div class="bg-gray-50 border border-gray-200 rounded p-6">
                <div class="flex">
                    <div class="font-bold">
                        <div class="grid grid-flow-col grid-col-2 gap-12">
                        
                    <div>
                        <h3 class="text-2xl">
                            GUARDIAN ID: {{$guardian->id}}
                        </h3>

                        <div>
                            <h3 class="text-2xl">
                                @can('isAdmin') 
                                @if($guardian->user)
                                GUARDIANS' USER ID:<a href="/users_database/{{$guardian->user->id}}/edit_user"> {{$guardian->user->id}}</a>
                                @else
                                NO ID SPECIFIED
                                @endif
                                @endcan
                            </h3>
                            
                       <div>
                        <h3 class="text-2xl">
                           NAME: {{$guardian->fullname}}
                        </h3>

                        <div>
                            <h3 class="text-2xl">
                                @if($guardian->user)
                               USERNAME: {{$user->username}}
                               @else
                               NO SPECIFIED USERNAME
                               @endif
                            </h3>
                            
                        <div class="text-xl mb-4">
                            <h3 class="text-2xl">
                           PHONE NUMBER: {{$guardian->phone}}
                            </h3>
                            <div class="text-xl mb-4">
                                <h3 class="text-2xl">
                                    @if($guardian->user)
                            EMAIL ADDRESS: {{$user->email}}
                            @else
                            NO SPECIFIED USER EMAIL
                            @endif
                                </h3>
                                @can('isAssistant')
                            <div class="text-xl mb-4">
                                <h3 class="text-2xl">
                            TEACHER ID: {{$guardian->teacher_id}}
                                </h3>
                                     @endcan           
                            <div class="text-lg mt-4">
                                <i class="fa-solid fa-location-dot"></i>
                                {{$guardian->address}}
                            </div>
                            </div>
                        </div>  
            </div>
            </div>
            </div>
                    </div>
                </div>
            </div>
                    </div>
                
        </div>
        @can('isExecutive')
        <div class="bg-gray-50 border border-gray-200 rounded">
        <div class="mt-4 p-2 flex space-x-6"><a href="/guardians_database/{{$guardian->id}}/edit_guardian"><x-primary-button class="ml-3">
            <i class="fa-solid fa-pencil">  {{ __('') }} </i>
        </x-primary-button></a>

        <form method="POST" action="/guardians_database/{{$guardian->id}}">
            @csrf
            @method('DELETE')
            <x-danger-button onclick="return confirm('Are you sure you want to delete this record?')">
            <i class="fa-solid fa-trash"> 
                 {{ __('') }}
                 </i>
        </x-danger-button> 
</form>

<x-primary-button class="ml-3" onclick="window.print()">
    <i class="fa-solid fa-download">  {{ __('') }} </i>
</x-primary-button>

@can('isExecutive')
<a href="{{ url('/dashboard/guardians/' . $guardian->id) }}">
<x-primary-button>
        <i class="fa-solid fa-computer">{{ __('') }}</i>
</x-primary-button>
</a>
@endcan

    
    </div>


        </div>
        @endcan

    </div>
    
</x-app-layout> --}}


<x-app-layout>
    <x-slot name="header"></x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Guardian Information Card -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-sm p-6">
                <div class="space-y-4">
                    
                    <!-- Guardian ID and User ID -->
                    <div class="font-bold">
                        <h3 class="text-2xl text-gray-800">GUARDIAN ID: {{$guardian->id}}</h3>

                        @can('isAdmin')
                            <h3 class="text-2xl text-gray-800 mt-2">
                                @if($guardian->user)
                                    GUARDIAN'S USER ID: <a href="/users_database/{{$guardian->user->id}}/edit_user" class="text-blue-600">{{$guardian->user->id}}</a>
                                @else
                                    NO ID SPECIFIED
                                @endif
                            </h3>
                        @endcan
                    </div>

                    <!-- Guardian Full Name and Username -->
                    <div>
                        <h3 class="text-2xl text-gray-800">NAME: {{$guardian->fullname}}</h3>
                        <h3 class="text-2xl text-gray-800 mt-2">
                            @if($guardian->user)
                                USERNAME: {{$guardian->user->username}}
                            @else
                                NO SPECIFIED USERNAME
                            @endif
                        </h3>
                    </div>
                    
                    <!-- Guardian Phone and Email -->
                    <div>
                        <h3 class="text-2xl text-gray-800">PHONE NUMBER: {{$guardian->phone}}</h3>
                        <h3 class="text-2xl text-gray-800 mt-2">
                            @if($guardian->user)
                                EMAIL: {{$guardian->user->email}}
                            @else
                                NO SPECIFIED USER EMAIL
                            @endif
                        </h3>
                    </div>

                    <!-- Teacher ID (Only for Assistant role) -->
                    @can('isAssistant')
                        <div>
                            <h3 class="text-2xl text-gray-800">TEACHER ID: {{$guardian->teacher_id}}</h3>
                        </div>
                    @endcan

                    <!-- Guardian Address -->
                    <div class="text-lg text-gray-600 mt-4">
                        <i class="fa-solid fa-location-dot"></i> {{$guardian->address}}
                    </div>

                </div>
            </div>

            <!-- Metadata: Created and Updated By Information -->
            <div class="text-sm text-gray-500 mt-6">
                <p>Added by: {{ $guardian->created_by }} at: {{ $guardian->created_at }}</p>
                <p>Edited by: {{ $guardian->edited_by }} at: {{ $guardian->updated_at }}</p>
            </div>

            <!-- Action Buttons -->
            @can('isExecutive')
                <div class="bg-gray-50 border border-gray-200 rounded mt-6">
                    <div class="mt-4 p-4 flex space-x-6">
                        
                        <!-- Edit Button -->
                        <a href="/guardians_database/{{$guardian->id}}/edit_guardian">
                            <x-primary-button class="ml-3">
                                <i class="fa-solid fa-pencil"></i> Edit Guardian
                            </x-primary-button>
                        </a>

                        <!-- Delete Button -->
                        <form method="POST" action="/guardians_database/{{$guardian->id}}">
                            @csrf
                            @method('DELETE')
                            <x-danger-button onclick="return confirm('Are you sure you want to delete this record?')">
                                <i class="fa-solid fa-trash"></i> Delete Guardian
                            </x-danger-button> 
                        </form>

                        <!-- Print Button -->
                        {{-- <x-primary-button class="ml-3" onclick="window.print()">
                            <i class="fa-solid fa-download"></i> Print Guardian Details
                        </x-primary-button> --}}

                        <!-- Additional Executive Button (if needed) -->
                        @can('isExecutive')
                            <a href="{{ url('/dashboard/guardians/' . $guardian->id) }}">
                                <x-primary-button class="ml-3">
                                    <i class="fa-solid fa-computer"></i> Dashboard
                                </x-primary-button>
                            </a>
                        @endcan
                    </div>
                </div>
            @endcan

        </div>
    </div>
</x-app-layout>

