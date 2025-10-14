<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $teacher->fullname }} - Attendance Summary</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            margin: 40px;
            font-size: 13px;
        }
        h1, h2, h3 {
            text-align: center;
            margin: 0;
        }
        h1 {
            color: #1e3a8a;
            font-size: 22px;
            margin-bottom: 4px;
        }
        h3 {
            color: #555;
            font-weight: normal;
            font-size: 14px;
        }
        .info-section {
            margin: 20px 0;
            border: 1px solid #ddd;
            padding: 12px;
            background-color: #f8fafc;
            border-radius: 6px;
        }
        .info-section p {
            margin: 4px 0;
            font-size: 13px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
            font-size: 13px;
        }
        th, td {
            border: 1px solid #ccc;
            padding: 8px 6px;
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
        .summary {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #555;
        }
        .rate-good { color: #16a34a; font-weight: bold; }
        .rate-fair { color: #ca8a04; font-weight: bold; }
        .rate-poor { color: #dc2626; font-weight: bold; }
    </style>
</head>
<body>

    <div style="position: relative; width: 100%; margin-bottom: 10px; font-family: 'DejaVu Sans', sans-serif;">
    <table style="width: 100%; border-collapse: collapse; border: none;">
        <tr>
            <!-- Left Logo -->
            <td style="width: 100px; text-align: center; vertical-align: middle; border: none;">
                <img src="images/logo.jpg" style="height: 60px; width: auto;" alt="Left Logo" />
            </td>
            
            <!-- Center Content -->
            <td style="text-align: center; vertical-align: middle; padding: 0 10px; border: none;">
                <h4 style="margin: 2px 0; font-weight: bold; font-size: 22px; color: #333;">
                    KULLIYYATU MASJIDIL QUR'AN
                </h4>
                <p style="margin: 2px 0; font-size: 10px; color: #555;">
                    Makama New Extension, Federal Low-Cost, Bauchi, Bauchi State.
                </p>
                <p style="margin: 2px 0; font-size: 10px; color: #555;">
                    <strong>EMAIL:</strong> kulliyyah2009@gmail.com
                </p>
                    <p style="margin: 2px 0; font-size: 10px; color: #555;">
                    <strong>GSM:</strong> 08032116211, 08135966693, 08037916173
                </p>
                {{-- <p style="margin: 2px 0; font-size: 10px; color: #555;">
                    <strong>Motto:</strong> 
                    <span style="font-family: 'DejaVu Sans', sans-serif; direction: rtl;">التصفية والتربية</span>
                </p> --}}
            </td>
            
            <!-- Right Logo -->
            <td style="width: 100px; text-align: center; vertical-align: middle; border: none;">
                <img src="images/logo.jpg" style="height: 60px; width: auto;" alt="Right Logo" />
            </td>
        </tr>
    </table>
    <br>

    <h1>Teacher Attendance Summary</h1>
    <h3>{{ $teacher->fullname }}</h3>

    <div class="info-section">
        <p><strong>Teacher ID:</strong> {{ $teacher->id }}</p>
        <p><strong>Class:</strong> {{ $teacher->class ?? 'Not Assigned' }}</p>
        <p><strong>Date Generated:</strong> {{ now()->format('d M, Y h:i A') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>Session</th>
                <th>Term</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Late</th>
                <th>Excused</th>
                <th>Total</th>
                <th>Present %</th>
                <th>Absent %</th>
                <th>Late %</th>
                <th>Excused %</th>
            </tr>
        </thead>
        <tbody>
            @foreach($attendanceRecords as $session => $terms)
                @foreach($terms as $term => $summary)
                    @php
                        $total = max($summary['total'], 1);
                        $presentRate = round(($summary['presents'] / $total) * 100, 1);
                        $absentRate  = round(($summary['absents'] / $total) * 100, 1);
                        $lateRate    = round(($summary['lates'] / $total) * 100, 1);
                        $excuseRate  = round(($summary['excuses'] / $total) * 100, 1);

                        // $rateClass = $presentRate >= 90 ? 'rate-good' : ($presentRate >= 75 ? 'rate-fair' : 'rate-poor');
                    @endphp
                    <tr>
                        <td>{{ $session }}</td>
                        <td>{{ ucfirst($term) }}</td>
                        <td>{{ $summary['presents'] }}</td>
                        <td>{{ $summary['absents'] }}</td>
                        <td>{{ $summary['lates'] }}</td>
                        <td>{{ $summary['excuses'] }}</td>
                        <td>{{ $summary['total'] }}</td>
                        <td>{{ $presentRate }}%</td>
                        <td>{{ $absentRate }}%</td>
                        <td>{{ $lateRate }}%</td>
                        <td>{{ $excuseRate }}%</td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <p>Powered by <strong>kulliyyah.couplecert.com</strong></p>
        <p>© {{ date('Y') }} All Rights Reserved</p>
    </div>

</body>
</html>
