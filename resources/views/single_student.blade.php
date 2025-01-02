<x-app-layout>
    <x-slot name="header">
        <!-- Header content if needed -->
    </x-slot>

    <div class="py-8 bg-gray-100">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Main Content Container -->
            <div class="bg-white border border-gray-200 rounded-lg shadow-lg p-8">
                <!-- Student Info Section -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <!-- Photo Section -->
                    @can('isAdGuardian')
                    <div class="flex justify-center">
                        @if ($student->photo)
                        <img class="rounded-full w-48 h-48 object-cover" src="{{ Storage::disk('s3')->url($student->photo) }}" alt="Student Photo" />
                        @endif
                    </div>
                    @endcan

                    <!-- Student Details Section -->
                    <div class="space-y-6">
                        <div class="text-xl font-semibold text-gray-700">
                            <p><strong>ID:</strong> {{$student->id}}</p>
                            <p><strong>Name:</strong> {{$student->fullname}}</p>
                            <p><strong>Gender:</strong> {{$student->gender}}</p>
                            <p><strong>Class:</strong> {{$student->class}}</p>
                            <p><strong>Set:</strong> {{$student->set}}</p>
                            @can('isAdGuardian')
                            <p><strong>Date of Birth:</strong> {{$student->dob}}</p>
                            @endcan
                            <p><strong>Date of Admission:</strong> {{$student->doa}}</p>
                            <p><strong>Registration Fee:</strong> {{$student->reg_fee}}</p>
                            <p><strong>Status:</strong> {{$student->status}}</p>
                            
                            @if ($student->status === 'GRADUATE')
                            <div class="space-y-4 mt-4">
                                <p><strong>Graduation Type:</strong> {{$student->grad_type}}</p>
                                <p><strong>Mock Fee:</strong> {{$student->mock_fee}}</p>
                                <p><strong>Graduation Date:</strong> {{$student->grad_date}}</p>
                                <p><strong>Graduation Year:</strong> {{$student->grad_yr}}</p>
                            </div>
                            @endif

                            @if($student->guardian)
                            <div class="space-y-2 mt-4">
                                <p><strong>Guardian ID:</strong> @can('isAdmin')<a href="{{ url('/guardians_database/' . $student->guardian->id) }}">@endcan{{ $student->guardian->id }}@can('isAdmin')</a>@endcan</p>
                                <p><strong>Name of Guardian:</strong> {{$student->guardian->fullname}}</p>
                            </div>
                            @else
                            <p>No Guardian Assigned</p>
                            @endif

                            <p><strong>Relationship:</strong> {{$student->relationship}}</p>

                            @can('isAdGuardian')
                            <div class="mt-4 text-lg">
                                <i class="fa-solid fa-location-dot"></i> {{$student->address}}
                            </div>
                            @endcan
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons Section -->
            <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-lg p-6">
                <div class="flex flex-wrap gap-4">
                    @can('isAdGuardian')
                    <a href="{{ url('/attendance/guardian_view/' . $student->id) }}">
                        <x-primary-button class="bg-blue-600 hover:bg-blue-700 text-white">
                            <i class="fa-solid fa-clock"></i> View Attendance
                        </x-primary-button>
                    </a>
                    @endcan
                    @can('isAssistant')
                    <a href="/students_database/{{$student->id}}/edit_student">
                        <x-primary-button class="bg-green-600 hover:bg-green-700 text-white">
                            <i class="fa-solid fa-pencil"></i> Edit Student
                        </x-primary-button>
                    </a>
                    @endcan

                    @can('isAdmin')
                    <form method="POST" action="/students_database/{{$student->id}}" onsubmit="return confirm('Are you sure you want to delete this record?')">
                        @csrf
                        @method('DELETE')
                        <x-danger-button class="bg-red-600 hover:bg-red-700 text-white">
                            <i class="fa-solid fa-trash"></i> Delete Student
                        </x-danger-button>
                    </form>

                    <x-primary-button class="ml-4 bg-gray-600 hover:bg-gray-700 text-white" onclick="window.print()">
                        <i class="fa-solid fa-download"></i> Print Details
                    </x-primary-button>
                    @endcan

                    <a href="{{ url('/curriculum_scale/guardianview/' . $student->id) }}">
                        <x-primary-button class="bg-yellow-600 hover:bg-yellow-700 text-white">
                            <i class="fa-solid fa-book"></i> View Curriculum
                        </x-primary-button>
                    </a>

                    <a href="{{ url('/hadda_page/' . $student->id) }}">
                        <x-primary-button class="bg-teal-600 hover:bg-teal-700 text-white">
                            <i class="fa-solid fa-book-open"></i> Hadda Page
                        </x-primary-button>
                    </a>

                    @can('isAdGuardian')
                    <a href="{{ url('/fees_record/' . $student->id . '/PreviousSessions') }}">
                        <x-primary-button class="bg-indigo-600 hover:bg-indigo-700 text-white">
                            <i class="fa-solid fa-calculator"></i> Fee Records
                        </x-primary-button>
                    </a>

                    <a href="{{ url('/ExamsRecords/' . $student->id . '/PreviousTerms') }}">
                        <x-primary-button class="bg-purple-600 hover:bg-purple-700 text-white">
                            <i class="fa-solid fa-list"></i> Exam Records
                        </x-primary-button>
                    </a>
                    @endcan
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
