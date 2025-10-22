<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $student->fullname }} - Attendance Summary</title>
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
        .student-info {
            margin-top: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .student-info p {
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
        .session-summary {
            margin-top: 25px;
            border-top: 2px solid #444;
            padding-top: 10px;
        }
        .footer {
            margin-top: 30px;
            font-size: 12px;
            text-align: center;
            color: #555;
        }
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
    <br><br>
    <h2>Student Attendance Summary</h2>
    <h3>{{ $student->fullname }}</h3>

    <div class="student-info">
        <p><strong>Student ID:</strong> {{ $student->id }}</p>
        <p><strong>Class:</strong> {{ $student->class ?? 'N/A' }}</p>
        <p><strong>Date Generated:</strong> {{ now()->format('d M, Y h:i A') }}</p>
    </div>

    @foreach($attendanceRecords as $session => $terms)
    <h3 style="margin-top: 15px;"><strong>{{ $session }} Academic Session</strong></h3>
    <table style="padding-top: 0; margin-top: 0;">
        <thead>
            <tr>
                <th>Term</th>
                <th>Present</th>
                <th>Absent</th>
                <th>Late</th>
                <th>Excused</th>
                <th>Total</th>
                <th>% Present</th>
                <th>% Absent</th>
                <th>% Late</th>
                <th>% Excused</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sessionTotals = ['presents' => 0, 'absents' => 0, 'lates' => 0, 'excuses' => 0, 'total' => 0];
            @endphp

            @foreach($terms as $term => $summary)
                @php
                    $total = $summary['total'] ?: 1;
                    $presentRate = round(($summary['presents'] / $total) * 100, 1);
                    $absentRate = round(($summary['absents'] / $total) * 100, 1);
                    $lateRate = round(($summary['lates'] / $total) * 100, 1);
                    $excuseRate = round(($summary['excuses'] / $total) * 100, 1);

                    // Add to session totals
                    foreach ($sessionTotals as $key => $val) {
                        if (isset($summary[$key])) {
                            $sessionTotals[$key] += $summary[$key];
                        }
                    }
                @endphp
                <tr>
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

            {{-- SESSION TOTAL ROW --}}
            @php
                $grandTotal = $sessionTotals['total'] ?: 1;
                $sessionRates = [
                    'presents' => round(($sessionTotals['presents'] / $grandTotal) * 100, 1),
                    'absents' => round(($sessionTotals['absents'] / $grandTotal) * 100, 1),
                    'lates' => round(($sessionTotals['lates'] / $grandTotal) * 100, 1),
                    'excuses' => round(($sessionTotals['excuses'] / $grandTotal) * 100, 1),
                ];
            @endphp

            <tr style="background-color: #e0e7ff; font-weight: bold;">
                <td>Session Total</td>
                <td>{{ $sessionTotals['presents'] }}</td>
                <td>{{ $sessionTotals['absents'] }}</td>
                <td>{{ $sessionTotals['lates'] }}</td>
                <td>{{ $sessionTotals['excuses'] }}</td>
                <td>{{ $sessionTotals['total'] }}</td>
                <td>{{ $sessionRates['presents'] }}%</td>
                <td>{{ $sessionRates['absents'] }}%</td>
                <td>{{ $sessionRates['lates'] }}%</td>
                <td>{{ $sessionRates['excuses'] }}%</td>
            </tr>
        </tbody>
    </table>
@endforeach
<p style="font-size: 11px; color: #666; margin-top: 5px;">
    <em><strong>Note:</strong> Session percentages are weighted by total school days, not averaged across terms.</em>
</p>


    <div class="footer">
        <p>Powered by <strong>kulliyyah.couplecert.com</strong></p>
        <p>© {{ date('Y') }} All Rights Reserved</p>
    </div>
</body>
</html>
