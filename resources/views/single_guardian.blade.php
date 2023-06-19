<x-app-layout>
    <x-slot name="header">
        {{-- <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2> --}}
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
                                @can('isAdmin') GUARDIANS' USER ID:<a href="/users_database/{{$guardian->user->id}}/edit_user"> {{$guardian->user->id}}</a>@endcan
                            </h3>
                            
                       <div>
                        <h3 class="text-2xl">
                           NAME: {{$guardian->fullname}}
                        </h3>

                        <div>
                            <h3 class="text-2xl">
                               USERNAME: {{$user->username}}
                            </h3>
                            
                        <div class="text-xl mb-4">
                            <h3 class="text-2xl">
                           PHONE NUMBER: {{$guardian->phone}}
                            </h3>
                            <div class="text-xl mb-4">
                                <h3 class="text-2xl">
                            EMAIL ADDRESS: {{$user->email}}
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
    
</x-app-layout>
