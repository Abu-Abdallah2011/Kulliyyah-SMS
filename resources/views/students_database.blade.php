{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Students Database') }}
            @can('isAdmin')
            <a href="students_registration_form">
                <x-primary-button class="absolute top-15 right-9 bg-green-500">
                    <i class="fa-solid fa-user-plus"></i>
                </x-primary-button> 
                </a>
                @endcan
        </h2>
    </x-slot> --}}
            <!-- Session Status -->
        {{-- <x-success-status class="mb-4" :status="session('message')" />
        <x-search />
        
        <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
            @unless (count($students) == 0)
                
            @foreach ($students as $student)

            @if ($student->status == 'IN SCHOOL')
            <div class="bg-green-300 border border-gray-200 rounded p-6">
                @elseif ($student->status == 'GRADUATE')
                <div class="bg-blue-300 border border-gray-200 rounded p-6">
                    @else
                    <div class="bg-red-300 border border-gray-200 rounded p-6">
                        @endif
                <div class="flex">
                    @can('isAdmin')
                    @if ($student->photo)
                    <img class="hidden w-48 mr-6 md:block" src="{{ Storage::disk('s3')->url($student->photo) }}" alt="" />
                    @endif
                    @endcan

                    <div class="font-bold">
                        <h3 class="text-2xl">
                            {{$student->id}}
                        </h3>
                    
                    <div class="font-bold">
                        <h3 class="text-2xl">
                            <a href="{{ url('/students_database/' . $student->id) }}">{{$student->fullname}}</a>
                        </h3>
                        <div class="text-xl mb-4">
                            {{$student->class}}
                            @can('isAdmin')
                            <div class="text-xl mb-4">
                            Born On: {{$student->dob}}
                                @endcan --}}
                                {{-- <div class="text-xl mb-4">
                                Gender: {{$student->gender}} --}}
                                    {{-- <div class="text-xl mb-4">
                                    Status: {{$student->status}}
                                    @can('isAdmin')
                            <div class="text-lg mt-4">
                                <i class="fa-solid fa-location-dot"></i>
                                {{$student->address}}
                                @endcan
                                <div class="text-sm mt-4">
                                    Added by: {{ $student->created_by }} at: {{ $student->created_at }}
                                     <br/>
                                    Edited by: {{ $student->edited_by }} at: {{ $student->updated_at }}
                                </div>
                                @can('isAdmin')
                            </div>
                        </div>
                        @endcan
                    </div> --}}
                            {{-- </div> --}}
                {{-- </div>

            </div>
                    </div>
                </div>
            </div>

            @endforeach
            @endunless
                </div>
                

                <div class="mt-6 p-4">
                    {{$students->Links()}}
                </div> --}}
            {{-- </div>
            
        </div> --}}
    
{{-- </x-app-layout> --}}

<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Students Database') }}
            @can('isAdmin')
                <a href="students_registration_form">
                    <x-primary-button class="absolute top-15 right-9 bg-green-500">
                        <i class="fa-solid fa-user-plus"></i>
                    </x-primary-button> 
                </a>
            @endcan
        </h2>
    </x-slot>

    <!-- Session Status -->
    <x-success-status class="mb-4" :status="session('message')" />
    <x-search />

    <!-- Students Grid -->
    <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
        @unless (count($students) == 0)
            @foreach ($students as $student)
                <div class="bg-white shadow-lg rounded-lg border border-gray-200 overflow-hidden">
                    <div class="flex p-6">
                        <!-- Student Image -->
                        @can('isAdmin')
                            @if ($student->photo)
                                <div class="flex-shrink-0 mr-6">
                                    <img class="w-48 h-48 rounded-lg object-cover" src="{{ Storage::disk('s3')->url($student->photo) }}" alt="Student Photo" />
                                </div>
                            @endif
                        @endcan

                        <!-- Student Info -->
                        <div class="flex-1">
                            <div class="space-y-2">
                                <h3 class="text-2xl font-bold text-gray-800">
                                    <a href="{{ url('/students_database/' . $student->id) }}" class="hover:text-blue-600">
                                        {{$student->fullname}}
                                    </a>
                                </h3>
                                <p class="text-lg text-gray-600">ID: {{$student->id}}</p>

                                <div class="text-md text-gray-700">
                                    <p class="mb-1">Class: {{$student->class}}</p>
                                    @can('isAdmin')
                                        <p class="mb-1">Born On: {{$student->dob}}</p>
                                    @endcan

                                    <p class="mb-1">Status: 
                                        <span class="font-semibold 
                                            {{$student->status == 'IN SCHOOL' ? 'text-green-600' : ''}} 
                                            {{$student->status == 'GRADUATE' ? 'text-blue-600' : ''}} 
                                            {{$student->status == 'NOT IN SCHOOL' ? 'text-red-600' : ''}}">
                                            {{$student->status}}
                                        </span>
                                    </p>
                                </div>

                                <div class="mt-4 text-lg text-gray-600 flex items-center">
                                    <i class="fa-solid fa-location-dot mr-2 text-indigo-600"></i> 
                                    {{$student->address}}
                                </div>

                                @can('isAdmin')
                                    <div class="text-sm mt-4 text-gray-500">
                                        <p>Added by: <span class="font-medium">{{$student->created_by}}</span> at {{ $student->created_at->format('M d, Y H:i') }}</p>
                                        <p>Edited by: <span class="font-medium">{{$student->edited_by}}</span> at {{ $student->updated_at->format('M d, Y H:i') }}</p>
                                    </div>
                                @endcan
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center text-gray-600 col-span-2">No students found.</p>
        @endunless
    </div>

    <!-- Pagination -->
    <div class="mt-6 p-4">
        {{$students->links()}}
    </div>

</x-app-layout>

