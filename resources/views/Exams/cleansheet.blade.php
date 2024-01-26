<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Exams') }}

            @can('isAssistant')
                <a href="/selectSubjects">
                <x-primary-button class="absolute top-15 right-9 bg-green-500">
                    <i class="fa-solid fa-plus"> </i>
                </x-primary-button>
            </a> 
                @endcan
        </h2>
    </x-slot>

    <x-success-status class="mb-4" :status="session('message')" />

    <div class="py-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <div class="text-center">
                        <h1 class="text-3xl font-bold mt-8 mb-4">STUDENTS RESULTS</h1>
                    </div>
                    
                    <table class="table table-responsive">
                  
                      <tr>
                          <td>Session: {{ $sessions->session }}</td>
                  
                          <td>Term: {{ $sessions->term }}</td>
                  
                          <td>Class: {{ $class }}</td>
                            
                      </tr>
                      <tr>
                  
                          <td>Class Teachers' Name: {{ $teacher->fullname }}</td>
                      </tr>
                      </table>
                    
                    <table class="border-collapse w-full table-responsive">
                        <thead>
                  
                            <tr>
                                <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">ID</th>
                                <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">NAME</th>
                               
                                @php
                                    $subjectsDisplayed = [];
                                @endphp

                                @foreach($teacher->students as $student)
                                @foreach($student->exams as $subject)
                                {{-- @php
                                $matchingSubjects = $subjects->where('session', $sessions->session)
                                  ->where('term', $sessions->term)
                                  ->get();
                                  @endphp
                                @foreach ($matchingSubjects as $subject) --}}
                                @if (!in_array($subject->subject_id, $subjectsDisplayed))
                                <th colspan="2" class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">
                                    <a href="{{ url('/exams/' . $subject->subject_id . '/examsEdit')}}">{{$subject->subject_id}}</a>
                                </th>
                                @php
                             $subjectsDisplayed[] = $subject->subject_id;
                         @endphp
                         @endif
                         @endforeach
                         {{-- @endforeach --}}
                                @endforeach
                                <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">TOTAL</th>
                                <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">AVR.</i></th>
                                <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">ATT.</i></th>
                                <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">POS.</th>
                                <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell"><a href="/exams/commentEditView">COMMENT</a></th>
                            </tr>
                        
                        </thead>

                        <tbody>
                            @foreach($orderedStudents as $student)
                      
                                <tr class="bg-white lg:hover:bg-gray-100 lg:table-row mb-10 lg:mb-0">
                                <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">{{ $student->id }}</td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static"><a href="{{ url ('/reportSheet/' . $student->id)}}">{{ $student->fullname }}</a></td>

                                    @php
                                    $columnsDisplayed = [];
                                @endphp

                                    @foreach($student->exams as $subject)
                                    {{-- @php
                                $matchingSubjects = $subjects->where('session', $sessions->session)
                                  ->where('term', $sessions->term)
                                  ->get();
                                  @endphp
                                @foreach ($matchingSubjects as $subject) --}}

                                @if($subject)
                                    @if (!in_array($subject->subject_id, $columnsDisplayed))
                                    <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">{{ $totalCa[$student->id][$subject->subject_id] }}</td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">{{ $subject->exams }}</td>
                                    @php
                             $columnsDisplayed[] = $subject->subject_id;
                         @endphp
                         @endif
                         @endif
                         @endforeach
                                    {{-- @endforeach --}}
                                    <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">{{ $totalScores[$student->id] }}</td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">{{ number_format($averageTotal[$student->id], 2) }}</td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">
                                        {{ number_format($student->attendancePercentage) }}%
                                </td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">{{ $student->position}}</td>
                                    <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">
                                        @foreach($student->exams as $subject)
                                        @php
                                $matchingSubjects = $subject->where('session', $sessions->session)
                                  ->where('term', $sessions->term)
                                  ->first();
                                  @endphp
                                        {{ $subject->comment }}
                                        @endforeach
                                </td>
                                    
                                  </tr>

                                  @endforeach
                      
                                
                      
                            </tbody>

                </div>
            </div>
    </div>

</x-app-layout>