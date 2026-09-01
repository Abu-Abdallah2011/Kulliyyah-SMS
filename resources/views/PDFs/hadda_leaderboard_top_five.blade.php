<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hadda Top Five</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #1f2937;
            margin: 36px;
            font-size: 12px;
        }

        h1, h2, h3, p {
            margin: 0;
        }

        .letterhead {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
        }

        .letterhead td {
            border: none;
            text-align: center;
        }

        .title {
            background: #ecfdf5;
            border: 1px solid #a7f3d0;
            border-radius: 12px;
            padding: 14px;
            text-align: center;
            margin-bottom: 16px;
        }

        .title h2 {
            color: #065f46;
            font-size: 22px;
            margin-bottom: 4px;
        }

        .podium {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 16px;
        }

        .podium-card {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 12px;
            text-align: center;
            background: #f9fafb;
        }

        .rank {
            color: #047857;
            font-size: 20px;
            font-weight: bold;
        }

        .summary {
            width: 100%;
            border-collapse: separate;
            border-spacing: 8px;
            margin-bottom: 16px;
        }

        .summary td {
            border: 1px solid #d1fae5;
            border-radius: 10px;
            background: #f0fdf4;
            padding: 10px;
            text-align: center;
        }

        .summary .label {
            color: #6b7280;
            display: block;
            font-size: 10px;
            text-transform: uppercase;
        }

        .summary .value {
            color: #065f46;
            display: block;
            font-size: 18px;
            font-weight: bold;
            margin-top: 3px;
        }

        table.ranking {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
        }

        table.ranking th,
        table.ranking td {
            border: 1px solid #d1d5db;
            padding: 8px;
            text-align: center;
        }

        table.ranking th {
            background: #d1fae5;
            color: #064e3b;
            text-transform: uppercase;
            font-size: 11px;
        }

        .footer {
            margin-top: 24px;
            text-align: center;
            font-size: 10px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    <table class="letterhead">
        <tr>
            <td style="width: 90px;"><img src="images/logo.jpg" style="height: 58px;" alt="School Logo"></td>
            <td>
                <h1 style="font-size: 22px;">KULLIYYATU MASJIDIL QUR'AN</h1>
                <p>Makama New Extension, Federal Low-Cost, Bauchi, Bauchi State</p>
                <p><strong>Email:</strong> kulliyyah2009@gmail.com | <strong>GSM:</strong> 08032116211, 08135966693</p>
            </td>
            <td style="width: 90px;"><img src="images/logo.jpg" style="height: 58px;" alt="School Logo"></td>
        </tr>
    </table>

    <div class="title">
        <h2>{{ $reportTitle ?? ($selectedClass ? $selectedClass.' Hadda Top Five' : 'School Hadda Top Five') }}</h2>
        <p>{{ $periodLabel }}</p>
        <p>Generated: {{ now()->format('d M, Y h:i A') }}</p>
    </div>

    @if($selectedClass && isset($classRankSummary) && $classRankSummary)
        <table class="summary">
            <tr>
                <td>
                    <span class="label">Class Rank</span>
                    <span class="value">#{{ $classRankSummary['rank'] }} of {{ $totalClassCount ?? 'N/A' }}</span>
                </td>
                <td>
                    <span class="label">Total Students</span>
                    <span class="value">{{ $classRankSummary['student_count'] }}</span>
                </td>
                <td>
                    <span class="label">Students With Records</span>
                    <span class="value">{{ $classRankSummary['recorded_student_count'] }}</span>
                </td>
                <td>
                    <span class="label">Avg Points</span>
                    <span class="value">{{ number_format((float) $classRankSummary['average_points_per_student'], 1) }}</span>
                </td>
            </tr>
        </table>
    @endif

    <table class="podium">
        <tr>
            @foreach($topStudents->take(3) as $studentRow)
                <td class="podium-card">
                    <div class="rank">#{{ $studentRow['rank'] }}</div>
                    <h3>{{ $studentRow['student']?->fullname ?? 'Unknown Student' }}</h3>
                    <p>{{ $studentRow['class'] }}</p>
                    <p><strong>{{ number_format((float) $studentRow['leaderboard_points'], 1) }}</strong> points</p>
                    <p>{{ $studentRow['confirmed_pages'] }} pages</p>
                </td>
            @endforeach
        </tr>
    </table>

    <table class="ranking">
        <thead>
            <tr>
                <th>Rank</th>
                <th>Student</th>
                <th>Class</th>
                <th>Pages</th>
                <th>Points</th>
                <th>Records</th>
                <th>Last Record</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topStudents as $row)
                <tr>
                    <td><strong>#{{ $row['rank'] }}</strong></td>
                    <td style="text-align: left;">{{ $row['student']?->fullname ?? 'Unknown Student' }}</td>
                    <td>{{ $row['class'] }}</td>
                    <td><strong>{{ $row['confirmed_pages'] }}</strong></td>
                    <td><strong>{{ number_format((float) $row['leaderboard_points'], 1) }}</strong></td>
                    <td>{{ $row['valid_records'] }}</td>
                    <td>{{ $row['last_record_date'] ? $row['last_record_date']->format('M d, Y') : 'N/A' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="7">No eligible Hadda records found for this selection.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($selectedClass && isset($excludedStudents) && $excludedStudents->isNotEmpty())
        <h3 style="margin-top: 18px; color: #92400e;">Students Not Counted In Average</h3>
        <table class="ranking">
            <thead>
                <tr>
                    <th>Student</th>
                    <th>Class</th>
                    <th>School Status</th>
                    <th>Hadda Status</th>
                    <th>Tarteel Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($excludedStudents as $student)
                    <tr>
                        <td style="text-align: left;">{{ $student->fullname }}</td>
                        <td>{{ $student->class }}</td>
                        <td>{{ $student->status }}</td>
                        <td>{{ $student->haddaStatusLabel() }}</td>
                        <td>{{ $student->tarteelStatusLabel() }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    @if(!$selectedClass)
        <h3 style="margin-top: 18px; color: #065f46;">All Valid Class Rankings</h3>
        <table class="ranking">
            <thead>
                <tr>
                    <th>Rank</th>
                    <th>Class</th>
                    <th>Students</th>
                    <th>Students With Records</th>
                    <th>Pages</th>
                    <th>Points</th>
                    <th>Avg Points</th>
                </tr>
            </thead>
            <tbody>
                @foreach($classRankings as $row)
                    <tr>
                        <td><strong>#{{ $row['rank'] }}</strong></td>
                        <td>{{ $row['class'] }}</td>
                        <td>{{ $row['student_count'] }}</td>
                        <td>{{ $row['recorded_student_count'] }}</td>
                        <td><strong>{{ $row['confirmed_pages'] }}</strong></td>
                        <td><strong>{{ number_format((float) $row['leaderboard_points'], 1) }}</strong></td>
                        <td>{{ number_format((float) $row['average_points_per_student'], 1) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <div class="footer">
        <p>Powered by kulliyyah.couplecert.com</p>
        <p>&copy; {{ date('Y') }} All Rights Reserved</p>
    </div>
</body>
</html>
