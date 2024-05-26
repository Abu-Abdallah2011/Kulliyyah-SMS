<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Student Report Sheet') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">


    <div class="text-center">
        <h1 class="text-3xl font-bold mt-8 mb-4">KULLIYYATU MASJIDIL QUR'AN</h1>

        Makama New Extension, Federal Low-Cost, Bauchi.<br/>
        
        <h2 class="text-3xl font-bold mt-8 mb-4"><u>Student Report Sheet</u></h2>
        </div>

    <div>
        <table class="table">
        
        <tr>
            <td>Name: {{ $dalibi->fullname }}</td>
            <td>Class: {{ $dalibi->class }}</td>
        </tr>
        <tr>
            <td>Term: {{ $sessions->term }}</td>
            <td>Session: {{ $sessions->session }}</td>
        </tr>
        
        </table>
    </div>

    <table class="table table-striped table-bordered table-hover table-responsive">
        <thead>

            <tr>
                {{-- <th>S/N</th> --}}
                <th>SUBJECTS</th>
                <th>MARKS OBTAINABLE</th>
                <th>C.A</th>
                <th>EXAMS</th>
                <th>TOTAL</th>
           
            </tr>
        
        </thead>
        <tbody>
            @foreach($exam as $examRecord)
            <tr>
                {{-- <td></td> --}}
                <td>{{ $examRecord->subject_id }}</td>
                <td>
                    @foreach($subjects->where('subject', $examRecord->subject_id) as $subject)
                {{ $subject->category }}
                @endforeach
            </td>
            
                <td>{{ $totalCa[$examRecord->subject_id] }}</td>
                <td>{{ $totalExam[$examRecord->subject_id] }}</td>
                <td>{{ $totalScores[$examRecord->subject_id] }}</td>
            </tr> 
            @endforeach

        </tbody>
    </table>
    <div>
        <table class="table">
        <tr>
            <td>Total: <strong>
                {{-- @foreach($exam as $examRecord)
                {{ $grandTotal[$examRecord->student_id] }}
                @endforeach --}}
                @foreach($exam->unique('student_id') as $examRecord)
    {{ $grandTotal[$examRecord->student_id] }}
@endforeach

            </strong></td>
            @foreach($exam->unique('student_id') as $examRecord)
            <td>Average: <strong>{{ number_format($averageTotal[$examRecord->student_id], 2) }}</strong></td>
            @endforeach
            <td>Attendance: <strong>{{ number_format($dalibi->attendancePercentage) }}%</strong></td>
            <td>Position: <strong>{{ $student->position }}</strong></td>
            <td>Out Of: <strong>{{ $class }}</strong></td>
            
        </tr>
        <tr>
            <td colspan="2">Class Teachers' Remark: <strong>
                @foreach($exam as $examRecord)
                {{ $examRecord->comment }}
                @endforeach
            </strong></td>
            <td colspan="2">Resumption Date For Next Term:</td>
            <td colspan="2"></td>
        </tr>
        </table><br/><br/>
        <hr/>
        <h5 class="text-center">
            Principals' Signature
        </h5>

            </div>
        </div>
    </div>

</x-app-layout>