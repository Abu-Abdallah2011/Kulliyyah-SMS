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

                 <!-- Teacher Excuses for Absentism or Lateness section -->
                 <div class="bg-gray-300 p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Excuses (if any)</h2>
                
                    <!-- Button to Add a New Excuse Record -->
                    @can('isExecutive')
                    <a href="{{ url('excuses/create/' . $teacher->id) }}">
                        <x-primary-button class="mb-4 bg-blue-500 hover:bg-blue-600">
                            <i class="fa-solid fa-plus"></i> Add Excuse Record
                        </x-primary-button>
                    </a>
                    @endcan
                
                    @if ($teacher->excuses->isNotEmpty())
                        @foreach ($teacher->excuses as $teacherExcuse)
                            <div class="bg-white p-4 mb-4 border rounded shadow-sm">
                                <h3 class="font-bold">{{ $teacherExcuse->title }}</h3>
                                <p>{{ $teacherExcuse->description }}</p>
                                <p><strong>Date Submitted:</strong> {{ $teacherExcuse->start_date }}</p>
                                <p><strong>Termination Date(if any):</strong> {{ ucfirst($teacherExcuse->end_date) }}</p>
                                
                                @if ($teacherExcuse->supporting_documents)
                                    <a href="{{ asset('storage/' . $teacherExcuse->supporting_documents) }}" target="_blank" class="text-blue-500">View Supporting Documents</a>
                                @endif
                                
                                <!-- Button to Edit & delete this Disciplinary Record -->
                                @can('isExecutive')
                                <div class="flex space-x-4">
                                <a href="{{ url('excuses/edit/' . $teacherExcuse->id) }}" class="inline-block mt-4">
                                    <x-primary-button class="bg-yellow-500 hover:bg-yellow-600">
                                        <i class="fa-solid fa-pencil"></i> Edit Record
                                    </x-primary-button>
                                </a>
                                <form method="POST" action="/excuses/delete/{{$teacherExcuse->id}}">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button onclick="return confirm('Are you sure you want to delete this record?')" class="bg-red-600 text-white hover:bg-red-700 transition duration-200 inline-block mt-4">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </x-danger-button>
                                </form>
                                </div>
                                @endcan
                            </div>
                        @endforeach
                    @else
                        <div>No Excuse has been recorded for this teacher.</div>
                    @endif
                </div>

                <!-- Disciplinary Actions against the teacher section -->
                <div class="bg-red-300 p-6">
                    <h2 class="text-2xl font-semibold text-gray-800 mb-4">Disciplinary Actions (if any)</h2>
                
                    <!-- Button to Add a New Disciplinary Record -->
                    @can('isExecutive')
                    <a href="{{ url('disciplinary/create/' . $teacher->id) }}">
                        <x-primary-button class="mb-4 bg-blue-500 hover:bg-blue-600">
                            <i class="fa-solid fa-plus"></i> Add Disciplinary Record
                        </x-primary-button>
                    </a>
                    @endcan
                
                    @if ($teacher->disciplinaryActions->isNotEmpty())
                        @foreach ($teacher->disciplinaryActions as $action)
                            <div class="bg-white p-4 mb-4 border rounded shadow-sm">
                                <h3 class="font-bold">{{ $action->title }}</h3>
                                <p>{{ $action->description }}</p>
                                <p><strong>Action Taken:</strong> {{ $action->action_taken }}</p>
                                <p><strong>Status:</strong> {{ ucfirst($action->status) }}</p>
                                
                                @if ($action->school_issued_document)
                                    <a href="{{ asset('storage/' . $action->school_issued_document) }}" target="_blank" class="text-blue-500">View School-Issued Document</a>
                                @endif
                                
                                @if ($action->offender_issued_document)
                                    <a href="{{ asset('storage/' . $action->offender_issued_document) }}" target="_blank" class="text-blue-500 ml-4">View Offender-Issued Document</a>
                                @endif
                                
                                <!-- Button to Edit & delete this Disciplinary Record -->
                                @can('isExecutive')
                                <div class="flex space-x-4">
                                <a href="{{ url('disciplinary/edit/' . $action->id) }}" class="inline-block mt-4">
                                    <x-primary-button class="bg-yellow-500 hover:bg-yellow-600">
                                        <i class="fa-solid fa-pencil"></i> Edit Record
                                    </x-primary-button>
                                </a>
                                <form method="POST" action="/disciplinary/delete/{{$action->id}}">
                                    @csrf
                                    @method('DELETE')
                                    <x-danger-button onclick="return confirm('Are you sure you want to delete this record?')" class="bg-red-600 text-white hover:bg-red-700 transition duration-200 inline-block mt-4">
                                        <i class="fa-solid fa-trash"></i> Delete
                                    </x-danger-button>
                                </form>
                                </div>
                                @endcan
                            </div>
                        @endforeach
                    @else
                        <div>No Disciplinary Action has been recorded against this teacher.</div>
                    @endif
                </div>

                              <!-- Attendance Summary Section -->
                <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 mt-6 rounded-xl shadow-inner">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
                    Attendance Summary
                </h2>

                @if($attendanceRecords->isNotEmpty())
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($attendanceRecords as $session => $terms)
                            @foreach($terms as $term => $summary)
                                @php
                                    $total = $summary['total'] ?: 1; // avoid division by zero
                                    $percentages = [
                                        'presents' => round(($summary['presents'] / $total) * 100, 1),
                                        'absents'  => round(($summary['absents'] / $total) * 100, 1),
                                        'lates'    => round(($summary['lates'] / $total) * 100, 1),
                                        'excuses'  => round(($summary['excuses'] / $total) * 100, 1),
                                    ];
                                @endphp

                                <div class="bg-white p-5 border rounded-2xl shadow-md hover:shadow-lg transition duration-300 ease-in-out">
                                    <div class="flex justify-between items-center mb-3">
                                        <h3 class="text-lg font-semibold text-indigo-700">{{ ucfirst($term) }}</h3>
                                        <span class="text-sm bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full font-medium">{{ $session }}</span>
                                    </div>

                                    <div class="space-y-2">
                                        {{-- Present --}}
                                        <div>
                                            <div class="flex justify-between text-sm font-medium">
                                                <span class="text-green-500">Present</span>
                                                <span class="text-green-500">{{ $percentages['presents'] }}% ({{ $summary['presents'] }})</span>
                                            </div>
                                            <div class="w-full bg-gray-100 rounded-full h-2">
                                                <div class="bg-green-500 h-2 rounded-full" style="width: {{ $percentages['presents'] }}%"></div>
                                            </div>
                                        </div>

                                        {{-- Absent --}}
                                        <div>
                                            <div class="flex justify-between text-sm font-medium">
                                                <span class="text-red-500">Absent</span>
                                                <span class="text-red-500">{{ $percentages['absents'] }}% ({{ $summary['absents'] }})</span>
                                            </div>
                                            <div class="w-full bg-gray-100 rounded-full h-2">
                                                <div class="bg-red-500 h-2 rounded-full" style="width: {{ $percentages['absents'] }}%"></div>
                                            </div>
                                        </div>

                                        {{-- Late --}}
                                        <div>
                                            <div class="flex justify-between text-sm font-medium">
                                                <span class="text-yellow-500">Late</span>
                                                <span class="text-yellow-500">{{ $percentages['lates'] }}% ({{ $summary['lates'] }})</span>
                                            </div>
                                            <div class="w-full bg-gray-100 rounded-full h-2">
                                                <div class="bg-yellow-400 h-2 rounded-full" style="width: {{ $percentages['lates'] }}%"></div>
                                            </div>
                                        </div>

                                        {{-- Excused --}}
                                        <div>
                                            <div class="flex justify-between text-sm font-medium">
                                                <span class="text-purple-500">Excused</span>
                                                <span class="text-purple-500">{{ $percentages['excuses'] }}% ({{ $summary['excuses'] }})</span>
                                            </div>
                                            <div class="w-full bg-gray-100 rounded-full h-2">
                                                <div class="bg-purple-500 h-2 rounded-full" style="width: {{ $percentages['excuses'] }}%"></div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            @endforeach
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-600">No attendance record available for this teacher yet.</p>
                @endif
            </div>

            @can('isAdmin')
            <div class="mt-6 text-right">
    <a href="{{ route('teacher.attendance.pdf', $teacher->id) }}" 
       class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
        <i class="fa-solid fa-file-pdf mr-2"></i> Download PDF Summary
    </a>
</div>
@endcan



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
