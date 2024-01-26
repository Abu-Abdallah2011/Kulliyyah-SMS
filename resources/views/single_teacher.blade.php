<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                  
            
            <div class="bg-gray-50 border border-gray-200 rounded p-6">
                <div class="flex">
                    <div class="">
                        <div class="grid grid-flow-col col-md-6 text-right">
                            <img class="w-48 mr-6 md:block" src="{{ asset('storage/' . $teacher->photo) }}" alt="" />
                        </div>
                            <div>
                                <h5 class="text-base">
                                   ID: {{$teacher->id}}
                                </h5>

                                @if($teacher->user)
                                @can('isAdmin') TEACHERS' USER ID:<a href="/users_database/{{$teacher->user->id}}/edit_user"> {{$teacher->user->id}}</a>@endcan
                                @endif
                       
                        <div>
                        <h5 class="text-base">
                           NAME: {{$teacher->fullname}}
                        </h5>
                    
                        <div class="text-xl mb-4">
                            <h5 class="text-base">
                           GENDER: {{$teacher->gender}}
                            </h5>
                            
                        <div class="text-xl mb-4">
                            <h5 class="text-base">
                           CLASS: {{$teacher->class}}
                            </h5>
                            <div class="text-xl mb-4">
                                <h5 class="text-base">
                               SET: {{$teacher->set}}
                                </h5>
                            <div class="text-xl mb-4">
                                <h5 class="text-base">
                            DATE OF BIRTH: {{$teacher->dob}}
                                </h5>
                                <div class="text-xl mb-4">
                                    <h5 class="text-base">
                                MARITAL STATUS: {{$teacher->marital_status}}
                                    </h5>
                                <div class="text-xl mb-4">
                                    <h5 class="text-base">
                                DATE OF FIRST APPOINTMENT: {{$teacher->dofa}}
                                    </h5>
                                    <div class="text-xl mb-4">
                                        <h5 class="text-base">
                                    STATUS: {{$teacher->status}}
                                        </h5>
                                    <h5 class="text-base">
                                        RANK: {{$teacher->rank}}
                                            </h5>
                                    <h5 class="text-base">
                                        YEAR OF PROMOTION: {{$teacher->promotion_yr}}
                                    </h5>
                                    <h5 class="text-base">
                                        PHONE NUMBER: {{$teacher->contact_no}}
                                            </h5>
                                    @if($teacher->user)
                                    <h5 class="text-base">
                                        EMAIL: {{$user->email}}
                                            </h5>
                                    @endif
                                    <h5 class="text-base">
                                        BANK BRANCH: {{$teacher->bank_branch}}
                                            </h5>
                                    <h5 class="text-base">
                                        ACCOUNT NAME: {{$teacher->acct_name}}
                                            </h5>
                                    <h5 class="text-base">
                                        ACCOUNT NUMBER: {{$teacher->acct_no}}
                                            </h5>
                                    <h5 class="text-base">
                                        MONTHLY ALLOWANCE: {{$teacher->allowance}}
                                            </h5>
                                    <h5 class="text-base">
                                        HOMETOWN: {{$teacher->hometown}}
                                            </h5>
                                    <h5 class="text-base">
                                        NEXT OF KIN: {{$teacher->nok}}
                                            </h5>
                                    <h5 class="text-base">
                                        RELATIONSHIP: {{$teacher->relationship}}
                                            </h5>
                                    <h5 class="text-base">
                                        NEXT OF KIN PHONE NUMBER: {{$teacher->contact}}
                                            </h5>
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

        @can('isExecutive')
        <div class="bg-gray-50 border border-gray-200 rounded">
        <div class="mt-4 p-2 flex space-x-6"><a href="/teachers_database/{{$teacher->id}}/edit_teacher"><x-primary-button class="ml-3">
            <i class="fa-solid fa-pencil">  {{ __('') }} </i>
        </x-primary-button></a>
        @endcan

        @can('isAdmin')
        <form method="POST" action="/teachers_database/{{$teacher->id}}">
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
    
    </div>


        </div>
        @endcan
    </div>
    
</x-app-layout>
