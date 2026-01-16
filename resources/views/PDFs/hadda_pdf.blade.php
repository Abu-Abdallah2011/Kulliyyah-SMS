<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $student->fullname }} - Hadda Record</title>

    <style>

        body {
            font-family: 'Amiri', DejaVu Sans, sans-serif;
            color: #333;
            margin: 40px;
            font-size: 13px;
        }

        h1, h2, h3 {
            text-align: center;
            margin: 0;
        }

        h2 {
            font-size: 20px;
            margin-top: 10px;
            color: #1e3a8a;
        }

        h3 {
            font-size: 14px;
            font-weight: normal;
            color: #555;
        }

        .student-info {
            margin-top: 15px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .student-info p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            font-size: 13px;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 7px 6px;
            text-align: center;
        }

        th {
            background-color: #e0e7ff;
            font-weight: bold;
            color: #1e3a8a;
        }

        tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .footer {
            margin-top: 30px;
            font-size: 11px;
            text-align: center;
            color: #555;
        }
    </style>
</head>

<body>

{{-- ================= HEADER ================= --}}
<table style="width: 100%; border-collapse: collapse; border: none;">
    <tr>
        <td style="width: 100px; text-align: center; border: none;">
            <img src="images/logo.jpg" style="height: 60px;" alt="School Logo">
        </td>

        <td style="text-align: center; border: none;">
            <h4 style="margin: 2px 0; font-size: 22px;">
                KULLIYYATU MASJIDIL QUR'AN
            </h4>
            <p style="margin: 2px 0; font-size: 10px;">
                Makama New Extension, Federal Low-Cost, Bauchi, Bauchi State
            </p>
            <p style="margin: 2px 0; font-size: 10px;">
                <strong>Email:</strong> kulliyyah2009@gmail.com
            </p>
            <p style="margin: 2px 0; font-size: 10px;">
                <strong>GSM:</strong> 08032116211, 08135966693
            </p>
        </td>

        <td style="width: 100px; text-align: center; border: none;">
            <img src="images/logo.jpg" style="height: 60px;" alt="School Logo">
        </td>
    </tr>
</table>

<br><br>

<h2>Student Hadda Record</h2>
<h3>{{ $student->fullname }}</h3>

{{-- ================= STUDENT META ================= --}}
<div class="student-info">
    <p><strong>Student ID:</strong> {{ $student->id }}</p>
    <p><strong>Class:</strong> {{ $student->class }}</p>
    <p><strong>Term:</strong> {{ $term . ' ' . $session }} Academic session</p>
    <p><strong>Date Generated:</strong> {{ now()->format('d M, Y h:i A') }}</p>
</div>

{{-- ================= HADDA TABLE ================= --}}
<table>
    <thead>
        <tr>
            <th>Date</th>
            <th>From Surah</th>
            <th>From Verse</th>
            <th>To Surah</th>
            <th>To Verse</th>
            <th>Score</th>
            <th>Grade</th>
            <th>Teacher</th>
            <th>Comment</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($hadda as $item)
            <tr>
                <td>{{ $item->date }}</td>
                <td>{{ $item->sura_en }}</td>
                <td>{{ $item->from }}</td>
                <td>{{ $item->to_surah_en }}</td>
                <td>{{ $item->to }}</td>
                <td>{{ $item->score }}</td>
                <td>{{ $item->grade_en }}</td>
                <td>{{ $item->teacher }}</td>
                <td>{{ $item->comment }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- ================= FOOTER ================= --}}
<div class="footer">
    <p>Powered by <strong>kulliyyah.couplecert.com</strong></p>
    <p>© {{ date('Y') }} All Rights Reserved</p>
</div>

</body>
</html>
