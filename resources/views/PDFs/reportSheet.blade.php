
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Student Report Sheet</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
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
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 0.5rem;
            text-align: left;
        }
        th {
            background-color: #f8f8f8;
        }
        .table-striped tbody tr:nth-of-type(odd) {
            background-color: #f9f9f9;
        }
        .table-hover tbody tr:hover {
            background-color: #f1f1f1;
        }
    </style>
</head>
<body>

    <div class="text-center">
            <x-letterhead />
        <h2 class="text-3xl mt-8 mb-4"><u>STUDENT REPORT SHEET</u></h2>
    </div>

    <div>
        <table>
            <tr>
                <td><strong>NAME:</strong> {{ $dalibi->fullname }}</td>
                <td><strong>CLASS:</strong> {{ $dalibi->class }}</td>
            </tr>
            <tr>
                <td><strong>TERM:</strong> {{ $sessions->term }}</td>
                <td><strong>SESSION:</strong> {{ $sessions->session }}</td>
            </tr>
        </table>
    </div>

    <table class="table table-striped table-hover">
        <thead>
            <tr>
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
        <table>
            <tr>
                <td><strong>TOTAL:</strong> 
                    @foreach($exam->unique('student_id') as $examRecord)
                        {{ $grandTotal[$examRecord->student_id] }}
                    @endforeach
                </td>
                @foreach($exam->unique('student_id') as $examRecord)
                <td><strong>AVERAGE:</strong> {{ number_format($averageTotal[$examRecord->student_id], 2) }}</td>
                <td><strong>CUMULATIVE AVERAGE:</strong> {{ number_format($cummulativeAverageTotal[$examRecord->student_id], 2) }}</td>
                @endforeach
            </tr>
            <tr>
                <td><strong>ATTENDANCE:</strong> {{ number_format($dalibi->attendancePercentage) }}%</td>
                <td><strong>POSITION:</strong> {{ $studentPosition }}</td>
                <td><strong>OUT OF:</strong> {{ $class }}</td>
            </tr>
            <tr>
                <td colspan="4"><strong>RESUMPTION DATE FOR NEXT TERM: </strong>{{ $sessions->next_term_starts }}</td>
            </tr>
        </table>
        <strong>CLASS TEACHERS' REMARK:</strong>
        @foreach($exam as $examRecord)
                        {{ $examRecord->comment }}
                    @endforeach
                    <hr>
        <br/><br/><br/><br/><br/><br/><br/><br/><br/><br/>
        <hr style="margin-bottom: 0" />
        <h5 class="text-center" style="margin-top: 0">
            Principals' Signature
        </h5>
    </div>

</body>
</html>