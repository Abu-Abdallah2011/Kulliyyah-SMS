<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Clean Sheets PDF</title>
    <style>
        @page {
            size: landscape; /* Set page orientation to landscape */
            margin: 1cm;
        }
        body {
            width: 100%;
            height: 100%;
            font-family: 'Arial', sans-serif;
            color: #1a202c;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
        }
        .content {
            width: 100%;
            height: 100%;
        }
        /* body {
            font-family: 'Arial', sans-serif;
            color: #1a202c;
            background-color: #f7fafc;
            margin: 0;
            padding: 0;
            transform: rotate(-90deg) translateX(-100%);
            transform-origin: top left;
            width: 100vh;
            height: 100vw;
            overflow: hidden;
        } */
        /* .content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
        } */
        .py-6 {
            padding-top: 1.5rem;
            padding-bottom: 1.5rem;
        }
        .bg-white {
            background-color: #fff;
        }
        .dark-bg-gray-800 {
            background-color: #2d3748;
        }
        .overflow-hidden {
            overflow: hidden;
        }
        .shadow-sm {
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        }
        .sm-rounded-lg {
            border-radius: 0.5rem;
        }
        .p-6 {
            padding: 1.5rem;
        }
        .text-gray-900 {
            color: #1a202c;
        }
        .dark-text-gray-100 {
            color: #f7fafc;
        }
        .text-center {
            text-align: center;
        }
        .text-3xl {
            font-size: 1.875rem;
            line-height: 2.25rem;
            font-weight: bold;
        }
        .font-bold {
            font-weight: bold;
        }
        .mt-8 {
            margin-top: 2rem;
        }
        .mb-4 {
            margin-bottom: 1rem;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        .table-responsive {
            width: 100%;
            overflow-x: auto;
        }
        .table thead th {
            background-color: #edf2f7;
            color: #718096;
            padding: 0.5rem 1rem;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        .table tbody td {
            padding: 0.5rem 1rem;
            text-align: left;
            border: 1px solid #e2e8f0;
        }
        .table tbody tr:nth-child(odd) {
            background-color: #f7fafc;
        }
        .bg-gray-200 {
            background-color: #edf2f7;
        }
        .text-gray-600 {
            color: #718096;
        }
        .border {
            border: 1px solid #e2e8f0;
        }
        .border-collapse {
            border-collapse: collapse;
        }
        .w-full {
            width: 100%;
        }
        .p-3 {
            padding: 0.75rem;
        }
        .p-2 {
            padding: 0.5rem;
        }
        .md\:p-3 {
            padding: 0.75rem;
        }
        .lg\:p-4 {
            padding: 1rem;
        }
        .lg\:table-cell {
            display: table-cell;
        }
        .relative {
            position: relative;
        }
        .lg\:hover\:bg-gray-100:hover {
            background-color: #f7fafc;
        }
        .lg\:mb-0 {
            margin-bottom: 0;
        }
        .text-gray-800 {
            color: #2d3748;
        }
    </style>
</head>
<body>
    <div class="content">

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
                            <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">POS</th>
                            <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">NAME</th>
                           
                            @php
                                $subjectsDisplayed = [];
                            @endphp

                            @foreach($teacher->students as $student)
                            @foreach($matchingSubjects as $subject)


                            @if (!in_array($subject->subject_id, $subjectsDisplayed))
                            <th colspan="2" class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">
                                {{$subject->subject_id}}
                            </th>
                            @php
                         $subjectsDisplayed[] = $subject->subject_id;
                     @endphp
                     @endif
                     @endforeach
                     
                            @endforeach
                            <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">TOTAL</th>
                            <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">AVR.</i></th>
                            <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">CUM</i></th>
                            <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">ATT.</i></th>
                        </tr>
                    
                    </thead>

                    <tbody>
                        @foreach($orderedStudents as $student)
                  
                            <tr class="bg-white lg:hover:bg-gray-100 lg:table-row mb-10 lg:mb-0">
                            <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">{{ $student->position }}</td>
                                <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">{{ $student->fullname }}</td>

                                @php
                                $columnsDisplayed = [];
                            @endphp

                                @foreach($matchingSubjects as $subjects)

                                @php
                                    $subjective = $subjects
                                                        ->where('session', $sessions->session)
                                                        ->where('term', $sessions->term)
                                                        ->where('student_id', $student->id)
                                                        ->where('subject_id', $subjects->subject_id)
                                                        ->get();
                                    @endphp

                                @if (!in_array($subjects->subject_id, $columnsDisplayed))
                                <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">{{ $totalCa[$student->id][$subjects->subject_id] }}</td>
                                <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">
                                    @foreach($subjective as $exam)
                                    {{ $exam->exams }}</td>
                                    @endforeach
                                @php
                         $columnsDisplayed[] = $subjects->subject_id;
                     @endphp
                     @endif
                     @endforeach
                                
                                <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">
                                    @if ($matchingSubjects !== null)
                                    {{ $totalScores[$student->id] }}
                                    @endif
                                </td>
                                <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">
                                    @if ($matchingSubjects !== null)
                                    {{ number_format($averageTotal[$student->id], 2) }}
                                    @endif
                                </td>
                                <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">
                                    @if ($cummulativematchingSubjects !== null)
                                    {{ number_format($cummulativeaverageTotal[$student->id], 2) }}
                                    @endif
                                </td>
                                <td class="w-full lg:w-auto p-3 text-gray-800 border border-b lg:table-cell relative lg:static">
                                    {{ number_format($student->attendancePercentage) }}%
                            </td>
                                
                              </tr>

                              @endforeach
                  
                            
                  
                        </tbody>

            </div>
        </div>
</div>
</div>
    
</body>
</html>