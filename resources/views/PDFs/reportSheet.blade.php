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
            /* font-family: 'DejaVu Sans', sans-serif; */
            margin: 15px;
            color: #000;
            font-size: 12px;
            line-height: 1.5;
        }
        .text-center {
            text-align: center;
        }
        .text-3xl {
            font-size: 1.5rem;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .section {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
        }
        .remarks-box {
            margin-top: 10px;
            padding: 5px;
            border: 1px dashed #000;
        }
        .handwriting {
            font-family: 'Dancing Script', 'Pacifico', 'Cursive', sans-serif;
            font-size: 14px;
            color: #444;
            font-style: italic;
        }
        .grades-table {
            border: 1px solid #000;
            width: 50%;
            margin: 0 auto;
        }
        .grades-table th,
        .grades-table td {
            text-align: center;
            padding: 5px;
            border: 1px solid #000;
        }
        .stamp-area {
            margin-top: 20px;
            height: 50px;
            border: 1px solid #000;
            text-align: center;
            line-height: 50px;
            font-style: italic;
        }
    </style>
</head>
<body>

    <!-- Header Section -->
    <div style="position: relative; min-height: 100vh;">
    <div class="text-center section">
        <x-letterhead />
        <h2 class="text-3xl"><u>Student Report Sheet</u></h2>
    </div>

    <!-- Student Information Section -->
    <div class="section">
        <table>
            <tr>
                <td><strong>Name:</strong> {{ $dalibi->fullname }}</td>
                <td><strong>Class:</strong> {{ $dalibi->class }}</td>
            </tr>
            <tr>
                <td><strong>Term:</strong> {{ $sessions->term }}</td>
                <td><strong>Session:</strong> {{ $sessions->session }}</td>
            </tr>
        </table>
    </div>

    <!-- Exam Results Table -->
        <div style="padding-bottom: 120px;">
            <div class="section">
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">S/N</th>
                    <th>Subjects</th>
                    <th class="text-center" style="width: 50px;">1st C.A(15)</th>
                    <th class="text-center" style="width: 50px;">2nd C.A(15)</th>
                    <th class="text-center" style="width: 50px;">Exams(70)</th>
                    <th class="text-center" style="width: 50px;">Total</th>
                    <th class="text-center" style="width: 50px;">Grade</th>
                    <th class="text-center" style="width: 70px;">Remark</th>
                </tr>
            </thead>
            <tbody>
                @foreach($exam as $index => $examRecord)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td> <!-- Incrementing S/N -->
                    <td>{{ $examRecord->subject_id }}</td>
                    <td class="text-center">{{ $first_cas[$examRecord->subject_id] }}</td>
                    <td class="text-center">{{ $second_cas[$examRecord->subject_id] }}</td>
                    <td class="text-center">{{ $totalExam[$examRecord->subject_id] }}</td>
                    <td class="text-center">{{ $totalScores[$examRecord->subject_id] }}</td>
                    <td class="text-center">{{ $grade[$examRecord->subject_id] }}</td>
                    <td class="text-center">{{ $remark[$examRecord->subject_id] }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
    </div>

    <!-- Summary Section -->
    <div class="section">
        <table>
            <tr>
                <td><strong>Grand Total:</strong> 
                    @foreach($exam->unique('student_id') as $examRecord)
                        {{ $grandTotal[$examRecord->student_id] }}
                    @endforeach
                </td>
                <td><strong>Average:</strong> 
                    @foreach($exam->unique('student_id') as $examRecord)
                        {{ number_format($averageTotal[$examRecord->student_id], 2) }}
                    @endforeach
                </td>
                <td><strong>Cumulative Average:</strong> 
                    @foreach($exam->unique('student_id') as $examRecord)
                        {{ number_format($cummulativeAverageTotal[$examRecord->student_id], 2) }}
                    @endforeach
                </td>
            </tr>
            <tr>
                <td><strong>Attendance:</strong> {{ number_format($dalibi->attendancePercentage) }}%</td>
                <td><strong>Position:</strong> {{ $studentPosition }}</td>
                <td><strong>Out Of:</strong> {{ $class }}</td>
            </tr>
            <tr>
                <td colspan="3"><strong>Resumption Date for Next Term:</strong> {{ $sessions->next_term_starts }}</td>
            </tr>
        </table>
    </div>

    <!-- Teacher's and Principal's Remarks Table -->
<div class="section">
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <tr>
            <!-- Teacher's Remarks -->
            <td style="border: 1px dashed #000; padding: 10px; vertical-align: top; width: 50%;">
                <strong>Teacher's Remark:</strong>
                <p class="handwriting">"{{ $TeachersRemark[$examRecord->student_id] }}"</p>
            </td>
            <!-- Principal's Comments -->
            <td style="border: 1px dashed #000; padding: 10px; vertical-align: top; width: 50%;">
                <strong>Principal's Comment:</strong>
                <p class="handwriting">"{{ $PrincipalsRemark[$examRecord->student_id] }}"</p>
            </td>
        </tr>
    </table>
</div>





    <div class="section">
        <table class="grades-table" style="border: 1px solid #000; margin: 0 auto;">
            <thead>
                <tr style="background-color: #f0f0f0;">
                    <th>0-39</th>
                    <th>40-49</th>
                    <th>50-59</th>
                    <th>60-69</th>
                    <th>70-100</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Fail</td>
                    <td>Pass</td>
                    <td>Good</td>
                    <td>V.Good</td>
                    <td>Excellent</td>
                </tr>
            </tbody>
        </table>
    </div>
        </div>
    
            <div style="position: absolute; bottom: 0; width: 100%; text-align: center;">
                <div style="display: inline-block; margin-top: 5px;">
            <img src="images/signature.jpg" style="height: 60px; width: auto;" alt="Digital Stamp" />
            <br />
            <hr>
 
            Signature/Stamp
        </div>
    </div>
    



</body>
</html>
