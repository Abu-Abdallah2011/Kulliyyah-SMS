<x-app-layout>
    <x-slot name="header">

    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                  
            
            <div class="bg-gray-50 border border-gray-200 rounded p-6">
                <div class="flex">
                    <div class="font-bold">
                        @can('isAdGuardian')
                        <div class="grid grid-flow-col col-md-6 text-right">
                            <img class="w-48 mr-6 md:block" src="{{ asset('storage/' . $student->photo) }}" alt="" />
                        </div>
                        @endcan
                            <div>
                                <h3 class="text-2xl">
                                   ID: {{$student->id}}
                                </h3>
                       
                            <div>
                        <h3 class="text-2xl">
                           NAME: {{$student->fullname}}
                        </h3>
                    
                        <div class="text-xl mb-4">
                            <h3 class="text-2xl">
                           GENDER: {{$student->gender}}
                            </h3>
                            
                        <div class="text-xl mb-4">
                            <h3 class="text-2xl">
                           CLASS: {{$student->class}}
                            </h3>
                            <div class="text-xl mb-4">
                                <h3 class="text-2xl">
                            SET: {{$student->set}}
                                </h3>
                                @can('isAdGuardian')
                            <div class="text-xl mb-4">
                                <h3 class="text-2xl">
                            DATE OF BIRTH: {{$student->dob}}
                                </h3>
                                @endcan
                                <div class="text-xl mb-4">
                                    <h3 class="text-2xl">
                                DATE OF ADMISSION: {{$student->doa}}
                                    </h3>
                                <div class="text-xl mb-4">
                                    <h3 class="text-2xl">
                                STATUS: {{$student->status}}
                                    </h3>
                                <h3 class="text-2xl">
                                    REGISTRATION FEE: {{$student->reg_fee}}
                                        </h3>
                                        @if($student->guardian)
                                <h3 class="text-2xl">
                                    GUARDIAN ID:@can('isAdmin') <a href="{{ url('/guardians_database/' . $student->guardian->id) }}">@endcan{{ $student->guardian->id }}@can('isAdmin')</a>@endcan
                                        </h3>
                                        <h3 class="text-2xl">
                                            NAME OF GUARDIAN: {{$student->guardian->fullname}}
                                                </h3>
                                                @else
                                    NO GUARDIAN ASSIGNED
                                    @endif
                                <h3 class="text-2xl">
                                    RELATIONSHIP: {{$student->relationship}}
                                        </h3>
                                        @can('isAdGuardian')
                            <div class="text-lg mt-4">
                                <i class="fa-solid fa-location-dot"></i>
                                {{$student->address}}
                                
                            </div>
                                </div> 
                                @endcan
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

        
        <div class="bg-gray-50 border border-gray-200 rounded">
        <div class="mt-4 p-2 flex space-x-6">
            @can('isAdmin')
            <a href="/students_database/{{$student->id}}/edit_student">
        <x-primary-button class="ml-3">
            <i class="fa-solid fa-pencil">  {{ __('Edit') }} </i>
        </x-primary-button>
    </a>
    @endcan
    @can('isAdmin')
        {{-- <form method="POST" action="/students_database/{{$student->id}}">
            @csrf
            @method('DELETE')
            <x-danger-button class="ml-3">
            <i class="fa-solid fa-trash"> 
                 {{ __('Delete') }}
                 </i>
        </x-danger-button> 
</form> --}}
@endcan
@can('isAdmin')
<x-primary-button class="ml-3" onclick="window.print()">
    <i class="fa-solid fa-download">  {{ __('Download') }} </i>
</x-primary-button>
    @endcan

    <a href="{{ url('/curriculum_scale/guardianview/' . $student->id) }}">
        <x-primary-button class="ml-3 bg-blue-500">
            <i class="fa-solid fa-book">  {{ __('') }} </i>
        </x-primary-button></a>
    

    <a href="{{ url('/hadda_page/' . $student->id) }}">
        <x-primary-button class="ml-3 bg-blue-500">
            <i class="fa-solid fa-book-open">  {{ __('') }} </i>
        </x-primary-button></a>
    </div>


        </div>
        
        

    </div>
    
</x-app-layout>
