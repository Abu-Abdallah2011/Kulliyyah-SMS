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
                        <div class="col-md-6 text-right">
                            <img class="w-48 mr-6 md:block" src="{{ asset('storage/' . $teacher->photo) }}" alt="" />
                        </div>
                            <div>
                                <h3 class="text-2xl">
                                   ID: {{$teacher->id}}
                                </h3>
                       
                        <div>
                        <h3 class="text-2xl">
                           NAME: {{$teacher->fullname}}
                        </h3>
                    
                        <div class="text-xl mb-4">
                            <h3 class="text-2xl">
                           GENDER: {{$teacher->gender}}
                            </h3>
                            
                        <div class="text-xl mb-4">
                            <h3 class="text-2xl">
                           CLASS: {{$teacher->class}}
                            </h3>
                            <div class="text-xl mb-4">
                                <h3 class="text-2xl">
                            DATE OF BIRTH: {{$teacher->dob}}
                                </h3>
                                <div class="text-xl mb-4">
                                    <h3 class="text-2xl">
                                MARITAL STATUS: {{$teacher->marital_status}}
                                    </h3>
                                <div class="text-xl mb-4">
                                    <h3 class="text-2xl">
                                DATE OF FIRST APPOINTMENT: {{$teacher->dofa}}
                                    </h3>
                                    <div class="text-xl mb-4">
                                        <h3 class="text-2xl">
                                    STATUS: {{$teacher->status}}
                                        </h3>
                                    <h3 class="text-2xl">
                                        RANK: {{$teacher->rank}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        YEAR OF PROMOTION: {{$teacher->promotion_yr}}
                                    </h3>
                                    <h3 class="text-2xl">
                                        PHONE NUMBER: {{$teacher->contact_no}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        EMAIL: {{$user->email}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        BANK BRANCH: {{$teacher->bank_branch}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        ACCOUNT NAME: {{$teacher->acct_name}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        ACCOUNT NUMBER: {{$teacher->acct_no}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        MONTHLY ALLOWANCE: {{$teacher->allowance}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        HOMETOWN: {{$teacher->hometown}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        NEXT OF KIN: {{$teacher->nok}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        RELATIONSHIP: {{$teacher->relationship}}
                                            </h3>
                                    <h3 class="text-2xl">
                                        NEXT OF KIN PHONE NUMBER: {{$teacher->contact}}
                                            </h3>
                            <div class="text-lg mt-4">
                                <i class="fa-solid fa-location-dot"></i>
                                {{$teacher->address}}
                                
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
        <div class="mt-4 p-2 flex space-x-6"><a href="/teachers_database/{{$teacher->id}}/edit_teacher"><x-primary-button class="ml-3">
            <i class="fa-solid fa-pencil">  {{ __('Edit') }} </i>
        </x-primary-button></a>
    
        {{-- <form method="POST" action="/teachers_database/{{$teacher->id}}">
            @csrf
            @method('DELETE')
            <x-primary-button class="ml-3 bg-red-500">
            <i class="fa-solid fa-trash"> 
                 {{ __('Delete') }}
                 </i>
        </x-primary-button> 
</form> --}}

<x-primary-button class="ml-3" onclick="window.print()">
    <i class="fa-solid fa-download">  {{ __('Download') }} </i>
</x-primary-button>
    
    </div>


        </div>
        @endcan
    </div>
    
</x-app-layout>
