<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Excuse Card</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen py-8 px-4">
    <div class="max-w-2xl mx-auto bg-white shadow rounded-lg p-6">
        <div class="mb-6 border-b pb-5">
            <div class="flex items-center justify-between gap-4">
                <img src="{{ asset('images/logo.jpg') }}" alt="School Logo" class="w-16 h-16 object-contain">
                <div class="text-center flex-1">
                    <h1 class="text-2xl font-bold uppercase">Kulliyyatu Masjidil Qur'an</h1>
                    <p class="text-sm text-gray-600">Makama New Extension, Federal Low-Cost, Bauchi, Bauchi State.</p>
                    <p class="text-xs text-gray-500">EMAIL: kulliyyah2009@gmail.com</p>
                    <p class="text-xs text-gray-500">GSM: 08032116211, 08135966693, 08037916173</p>
                </div>
                <img src="{{ asset('images/logo.jpg') }}" alt="School Logo" class="w-16 h-16 object-contain hidden sm:block">
            </div>
            <p class="text-center text-gray-700 font-semibold mt-4">Excuse Card Verification</p>
        </div>

        <div class="text-center mb-6">
            <div class="inline-block px-6 py-3 rounded font-bold text-lg {{ $verification['valid'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                {{ $verification['label'] }}
            </div>
            <p class="mt-3 text-gray-700">{{ $verification['message'] }}</p>
        </div>

        <div class="grid sm:grid-cols-2 gap-4 text-sm">
            <p><strong>Card No:</strong> {{ $card->card_number }}</p>
            <p><strong>Type:</strong> {{ $card->type === 'late' ? 'Late Excuse' : 'Out Excuse' }}</p>
            <p><strong>Student:</strong> {{ $card->student->fullname ?? 'N/A' }}</p>
            <p><strong>Class:</strong> {{ $card->student->class ?? 'N/A' }}</p>
            <p><strong>Days:</strong> {{ implode(', ', $card->valid_days ?? []) }}</p>
            <p><strong>Term/Session:</strong> {{ $card->term ?? 'N/A' }} / {{ $card->session ?? 'N/A' }}</p>
            @if($card->type === 'late')
                <p><strong>Latest Arrival:</strong> {{ \Carbon\Carbon::parse($card->latest_time)->format('h:i A') }}</p>
            @else
                <p><strong>Out Time:</strong> {{ \Carbon\Carbon::parse($card->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($card->end_time)->format('h:i A') }}</p>
            @endif
            <p><strong>Card Status:</strong> {{ strtoupper($card->status) }}</p>
            <p><strong>Approved By:</strong> {{ $card->approverDisplayName() }}</p>
        </div>

        @if($card->status === 'revoked' && $card->revocation_reason)
            <div class="mt-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded">
                <strong>Revocation comment:</strong>
                <p class="mt-1 whitespace-pre-line">{{ $card->revocation_reason }}</p>
            </div>
        @elseif($card->request?->review_note)
            <div class="mt-6 bg-blue-50 border border-blue-200 text-blue-900 p-4 rounded">
                <strong>Approval comment:</strong>
                <p class="mt-1 whitespace-pre-line">{{ $card->request->review_note }}</p>
            </div>
        @endif

        @if($card->status === 'revoked' && !$card->revocation_reason)
            <div class="mt-6 bg-red-50 border border-red-200 text-red-800 p-4 rounded">
                <strong>Revocation reason:</strong> {{ $card->revocation_reason }}
            </div>
        @endif
    </div>
</body>
</html>
