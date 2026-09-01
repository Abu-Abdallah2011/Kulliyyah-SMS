<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $card->card_number }}</title>
    <style>
        @page {
            margin: 0;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #111827;
            margin: 0;
            padding: 3px;
            font-size: 6.7px;
            background: #ffffff;
        }
        .card {
            border: 1.3px solid #111827;
            border-radius: 6px;
            padding: 4px;
            box-sizing: border-box;
        }
        .header-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .header-table td {
            border: none;
            padding: 0;
            vertical-align: middle;
        }
        .logo {
            width: 23px;
            height: 23px;
            object-fit: contain;
        }
        .school-name {
            text-align: center;
            font-size: 8.4px;
            font-weight: 700;
            text-transform: uppercase;
            line-height: 1.1;
        }
        .school-info {
            text-align: center;
            font-size: 5px;
            color: #4b5563;
            line-height: 1.05;
            margin-top: 0;
        }
        .title {
            text-align: center;
            margin: 2px 0;
            padding: 2px 0;
            border-top: 1px solid #d1d5db;
            border-bottom: 1px solid #d1d5db;
            font-size: 9.2px;
            font-weight: 800;
            letter-spacing: .5px;
        }
        .status-row {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 2px;
        }
        .status-row td {
            border: none;
            padding: 0;
            font-size: 6.2px;
        }
        @php
            $statusLabel = $pdfStatus['label'] ?? strtoupper($card->status);
            $statusColor = $pdfStatus['color'] ?? '#6b7280';
        @endphp
        .status-badge {
            display: inline-block;
            padding: 1px 4px;
            border-radius: 9px;
            font-size: 5.8px;
            font-weight: 700;
            color: #ffffff;
            background: {{ $statusColor }};
            text-transform: uppercase;
        }
        .details {
            width: 100%;
            border-collapse: collapse;
        }
        .details td {
            border: 1px solid #d1d5db;
            padding: 1.8px 2px;
            vertical-align: top;
            line-height: 1.08;
        }
        .label {
            width: 31%;
            font-weight: 700;
            color: #111827;
            background: #f3f4f6;
        }
        .main-row {
            font-size: 7.1px;
            font-weight: 700;
        }
        .content-grid {
            width: 100%;
            border-collapse: collapse;
        }
        .content-grid td {
            border: none;
            padding: 0;
            vertical-align: top;
        }
        .qr-box {
            width: 58px;
            text-align: center;
            border: 1px solid #d1d5db;
            padding: 2px;
            border-radius: 4px;
            margin-left: 4px;
        }
        .qr-box img {
            width: 40px;
            height: 40px;
        }
        .qr-label {
            font-size: 5.2px;
            font-weight: 700;
            margin-bottom: 1px;
        }
        .comment-box {
            margin-top: 1.5px;
            padding: 2px;
            border: 1px solid #e5e7eb;
            background: #f9fafb;
            font-size: 5.7px;
            line-height: 1.08;
            max-height: 16px;
            overflow: hidden;
        }
        .signature-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 2px;
        }
        .signature-table td {
            border: none;
            padding: 0;
            vertical-align: bottom;
            font-size: 5.3px;
            color: #4b5563;
        }
        .signature-line {
            border-top: 1px solid #111827;
            text-align: center;
            padding-top: 1.5px;
            width: 88px;
            float: right;
            color: #111827;
            font-size: 5.6px;
        }
    </style>
</head>
<body>
    <div class="card">
        <table class="header-table">
            <tr>
                <td style="width: 26px;"><img src="images/logo.jpg" class="logo" alt="Logo"></td>
                <td>
                    <div class="school-name">Kulliyyatu Masjidil Qur'an</div>
                    <div class="school-info">Makama New Extension, Federal Low-Cost, Bauchi, Bauchi State</div>
                    <div class="school-info">08032116211, 08135966693, 08037916173</div>
                </td>
                <td style="width: 26px; text-align: right;"><img src="images/logo.jpg" class="logo" alt="Logo"></td>
            </tr>
        </table>

        <div class="title">{{ $card->type === 'late' ? 'LATE EXCUSE CARD' : 'OUT EXCUSE CARD' }}</div>

        <table class="status-row">
            <tr>
                <td><strong>No:</strong> {{ $card->card_number }}</td>
                <td style="text-align: right;"><span class="status-badge">{{ strtoupper($statusLabel) }}</span></td>
            </tr>
        </table>

        <table class="content-grid">
            <tr>
                <td>
                    <table class="details">
                        <tr>
                            <td class="label">Student</td>
                            <td class="main-row">{{ $card->student->fullname ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Class</td>
                            <td>{{ $card->student->class ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Days</td>
                            <td>{{ implode(', ', $card->valid_days ?? []) }}</td>
                        </tr>
                        <tr>
                            <td class="label">Time</td>
                            <td>
                                @if($card->type === 'late')
                                    Latest: {{ \Carbon\Carbon::parse($card->latest_time)->format('h:i A') }}
                                @else
                                    {{ \Carbon\Carbon::parse($card->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($card->end_time)->format('h:i A') }}
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td class="label">Term/Session</td>
                            <td>{{ $card->term ?? 'N/A' }} / {{ $card->session ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Valid</td>
                            <td>{{ $card->valid_from?->format('d M Y') ?? 'N/A' }} - {{ $card->valid_until?->format('d M Y') ?? 'N/A' }}</td>
                        </tr>
                        <tr>
                            <td class="label">Approved By</td>
                            <td>{{ $card->approverDisplayName() }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 66px;">
                    <div class="qr-box">
                        <div class="qr-label">Scan to verify</div>
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=70x70&format=svg&data={{ urlencode($verificationUrl) }}" alt="QR Code">
                    </div>
                </td>
            </tr>
        </table>

        @if($card->status === 'revoked' && $card->revocation_reason)
            <div class="comment-box"><strong>Revocation:</strong> {{ $card->revocation_reason }}</div>
        @elseif($card->request?->review_note)
            <div class="comment-box"><strong>Note:</strong> {{ $card->request->review_note }}</div>
        @endif

        <table class="signature-table">
            <tr>
                <td>
                    <div>Generated: {{ now()->format('d M Y h:i A') }}</div>
                </td>
                <td style="width: 120px;">
                    <div class="signature-line">Authorized Signature / Stamp</div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
