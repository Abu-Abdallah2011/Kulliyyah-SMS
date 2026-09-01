<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $gender }} Teachers Signing Book</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            margin: 18px 22px;
            font-size: 11px;
        }
        .page {
            page-break-after: always;
        }
        .page:last-child {
            page-break-after: auto;
        }
        .title {
            text-align: center;
            margin: 4px 0 8px 0;
            padding: 6px 0;
            border-top: 1px solid #9ca3af;
            border-bottom: 1px solid #9ca3af;
            font-size: 16px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: .4px;
        }
        .meta {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 8px;
            font-size: 10px;
        }
        .meta td {
            border: 1px solid #d1d5db;
            padding: 5px 7px;
            width: 25%;
        }
        .meta strong {
            color: #111827;
        }
        .signing-table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }
        .signing-table th,
        .signing-table td {
            border: 1px solid #111827;
            padding: 6px 5px;
            vertical-align: middle;
        }
        .signing-table th {
            background: #f3f4f6;
            text-transform: uppercase;
            font-size: 9.5px;
            text-align: center;
        }
        .sn { width: 5%; text-align: center; }
        .teacher-name { width: 30%; font-weight: 700; }
        .time-col { width: 12%; height: 24px; }
        .sign-col { width: 20%; height: 24px; }
        .footer-note {
            margin-top: 8px;
            font-size: 9px;
            color: #4b5563;
            text-align: center;
        }
        .empty-row td {
            text-align: center;
            color: #6b7280;
            padding: 20px;
        }
    </style>
</head>
<body>
    @foreach($signingDays as $day)
        <div class="page">
            @include('components.letterhead')

            <div class="title">{{ $gender }} Teachers Signing Book</div>

            <table class="meta">
                <tr>
                    <td><strong>Date:</strong> {{ $day['date']->format('l, F d, Y') }}</td>
                    <td><strong>Period:</strong> {{ $day['period'] }}</td>
                    <td><strong>Term:</strong> {{ $session->term }}</td>
                    <td><strong>Session:</strong> {{ $session->session }}</td>
                </tr>
            </table>

            <table class="signing-table">
                <thead>
                    <tr>
                        <th class="sn">S/N</th>
                        <th class="teacher-name">Teacher Name</th>
                        <th class="time-col">Time In</th>
                        <th class="sign-col">Sign In</th>
                        <th class="time-col">Time Out</th>
                        <th class="sign-col">Sign Out</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachers as $teacher)
                        <tr>
                            <td class="sn">{{ $loop->iteration }}</td>
                            <td class="teacher-name">{{ $teacher->fullname }}</td>
                            <td class="time-col"></td>
                            <td class="sign-col"></td>
                            <td class="time-col"></td>
                            <td class="sign-col"></td>
                        </tr>
                    @empty
                        <tr class="empty-row">
                            <td colspan="6">No {{ strtolower($gender) }} teachers found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="footer-note">
                Fridays are excluded automatically. Saturdays and Sundays have separate Morning and Evening signing pages.
            </div>
        </div>
    @endforeach
</body>
</html>
