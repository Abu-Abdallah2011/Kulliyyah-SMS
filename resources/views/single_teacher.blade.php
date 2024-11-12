{{-- <x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                  
            
            <div class="bg-gray-50 border border-gray-200 rounded p-6">
                <div class="flex">
                    <div class="">
                        <div class="grid grid-flow-col col-md-6 text-right">
                            <img class="w-48 mr-6 md:block" src="{{ Storage::disk('s3')->url($teacher->photo) }}" alt="" />
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
    
</x-app-layout> --}}


<x-app-layout>
    <x-slot name="header">
    </x-slot>

    <div class="py-8 bg-gradient-to-r from-blue-50 via-teal-50 to-indigo-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Profile Container -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">

                <!-- Profile Header Section (Image and Basic Info) -->
                <div class="flex items-center space-x-8 p-6 bg-indigo-100">
                    <!-- Photo Section -->
                    <div class="flex-shrink-0">
                        <img class="w-32 h-32 rounded-full border-4 border-indigo-300 shadow-xl" src="{{ Storage::disk('s3')->url($teacher->photo) }}" alt="Teacher Photo" />
                    </div>

                    <!-- Info Section -->
                    <div class="space-y-4">
                        <h1 class="text-3xl font-extrabold text-gray-900">{{$teacher->fullname}}</h1>
                        <p class="text-lg text-gray-600">ID: {{$teacher->id}}</p>

                        @if($teacher->user)
                            @can('isAdmin')
                                <p class="text-sm text-indigo-600">TEACHER'S USER ID: <a href="/users_database/{{$teacher->user->id}}/edit_user" class="underline hover:text-indigo-800">{{$teacher->user->id}}</a></p>
                            @endcan
                        @endif
                    </div>
                </div>

                <!-- Personal Information Section -->
                <div class="bg-gray-50 p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Personal Information</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <p class="text-md text-gray-700">Gender: <span class="text-indigo-600">{{$teacher->gender}}</span></p>
                        <p class="text-md text-gray-700">Class: <span class="text-indigo-600">{{$teacher->class}}</span></p>
                        <p class="text-md text-gray-700">Set: <span class="text-indigo-600">{{$teacher->set}}</span></p>
                        <p class="text-md text-gray-700">Date of Birth: <span class="text-indigo-600">{{$teacher->dob}}</span></p>
                        <p class="text-md text-gray-700">Marital Status: <span class="text-indigo-600">{{$teacher->marital_status}}</span></p>
                        <p class="text-md text-gray-700">Status: <span class="text-indigo-600">{{$teacher->status}}</span></p>
                        <p class="text-md text-gray-700">Rank: <span class="text-indigo-600">{{$teacher->rank}}</span></p>
                        <p class="text-md text-gray-700">Promotion Year: <span class="text-indigo-600">{{$teacher->promotion_yr}}</span></p>
                    </div>
                </div>

                <!-- Contact & Bank Information Section -->
                <div class="bg-white p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Contact & Banking Information</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <p class="text-md text-gray-700">Phone: <span class="text-indigo-600">{{$teacher->contact_no}}</span></p>
                        @if($teacher->user)
                            <p class="text-md text-gray-700">Email: <span class="text-indigo-600">{{$teacher->user->email}}</span></p>
                        @endif
                        <p class="text-md text-gray-700">Bank Branch: <span class="text-indigo-600">{{$teacher->bank_branch}}</span></p>
                        <p class="text-md text-gray-700">Account Name: <span class="text-indigo-600">{{$teacher->acct_name}}</span></p>
                        <p class="text-md text-gray-700">Account Number: <span class="text-indigo-600">{{$teacher->acct_no}}</span></p>
                        <p class="text-md text-gray-700">Monthly Allowance: <span class="text-indigo-600">{{$teacher->allowance}}</span></p>
                    </div>
                </div>

                <!-- Hometown & Next of Kin Information Section -->
                <div class="bg-gray-50 p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Hometown & Next of Kin</h2>
                    <div class="grid grid-cols-2 gap-6">
                        <p class="text-md text-gray-700">Hometown: <span class="text-indigo-600">{{$teacher->hometown}}</span></p>
                        <p class="text-md text-gray-700">Next of Kin: <span class="text-indigo-600">{{$teacher->nok}}</span></p>
                        <p class="text-md text-gray-700">Relationship: <span class="text-indigo-600">{{$teacher->relationship}}</span></p>
                        <p class="text-md text-gray-700">Next of Kin Phone: <span class="text-indigo-600">{{$teacher->contact}}</span></p>
                    </div>
                </div>

                <!-- Address Section -->
                <div class="bg-white p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Address</h2>
                    <div class="flex items-center space-x-2">
                        <i class="fa-solid fa-location-dot text-indigo-600"></i>
                        <p class="text-md text-gray-700">{{$teacher->address}}</p>
                    </div>
                </div>

                <!-- Action Buttons Section -->
                <div class="bg-gray-50 p-6 flex justify-end space-x-4">
                    @can('isExecutive')
                        <a href="/teachers_database/{{$teacher->id}}/edit_teacher">
                            <x-primary-button class="bg-indigo-600 text-white hover:bg-indigo-700 transition duration-200">
                                <i class="fa-solid fa-pencil"></i> Edit
                            </x-primary-button>
                        </a>
                    @endcan

                    @can('isAdmin')
                        <form method="POST" action="/teachers_database/{{$teacher->id}}">
                            @csrf
                            @method('DELETE')
                            <x-danger-button onclick="return confirm('Are you sure you want to delete this record?')" class="bg-red-600 text-white hover:bg-red-700 transition duration-200">
                                <i class="fa-solid fa-trash"></i> Delete
                            </x-danger-button>
                        </form>

                        <x-primary-button class="bg-green-600 text-white hover:bg-green-700 transition duration-200" onclick="window.print()">
                            <i class="fa-solid fa-download"></i> Print
                        </x-primary-button>
                    @endcan
                </div>

            </div>
        </div>
    </div>
</x-app-layout>
