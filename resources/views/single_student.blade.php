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
                            <img class="w-48 mr-6 md:block" src="{{ asset('storage/' . $student->photo) }}" alt="" />
                        
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
                            DATE OF BIRTH: {{$student->dob}}
                                </h3>
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
                                <h3 class="text-2xl">
                                    GUARDIAN ID: {{$student->guardian_id}}
                                        </h3>
                                                        
                            <div class="text-lg mt-4">
                                <i class="fa-solid fa-location-dot"></i>
                                {{$student->address}}
                                
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
                
        </div>

        @can('isAdmin')
        <div class="bg-gray-50 border border-gray-200 rounded">
        <div class="mt-4 p-2 flex space-x-6"><a href="/students_database/{{$student->id}}/edit_student"><x-primary-button class="ml-3">
            <i class="fa-solid fa-pencil">  {{ __('Edit') }} </i>
        </x-primary-button></a>
    
        {{-- <form method="POST" action="/students_database/{{$student->id}}">
            @csrf
            @method('DELETE')
            <x-danger-button class="ml-3">
            <i class="fa-solid fa-trash"> 
                 {{ __('Delete') }}
                 </i>
        </x-danger-button> 
</form> --}}

<x-primary-button class="ml-3" onclick="window.print()">
    <i class="fa-solid fa-download">  {{ __('Download') }} </i>
</x-primary-button>
    
    </div>


        </div>
        @endcan

    </div>
    
</x-app-layout>
