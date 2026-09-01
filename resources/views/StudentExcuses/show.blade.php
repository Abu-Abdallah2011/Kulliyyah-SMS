<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Excuse Card</h2>
    </x-slot>

    <div class="py-6 max-w-4xl mx-auto sm:px-6 lg:px-8">
        <x-success-status class="mb-4" :status="session('message')" />

        <div class="bg-white shadow rounded-lg p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start gap-4 mb-6">
                <div>
                    <h3 class="text-xl font-bold">{{ $card->card_number }}</h3>
                    <p class="text-gray-600">{{ $card->type === 'late' ? 'Late Excuse Card' : 'Out Excuse Card' }}</p>
                </div>
                <span class="px-4 py-2 rounded font-bold {{ $verification['valid'] ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $verification['label'] }}</span>
            </div>

            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                <p><strong>Student:</strong> {{ $card->student->fullname ?? 'N/A' }}</p>
                <p><strong>Class:</strong> {{ $card->student->class ?? 'N/A' }}</p>
                <p><strong>Guardian:</strong> {{ $card->guardian->fullname ?? 'N/A' }}</p>
                <p><strong>Term/Session:</strong> {{ $card->term ?? 'N/A' }} / {{ $card->session ?? 'N/A' }}</p>
                <p><strong>Valid Days:</strong> {{ implode(', ', $card->valid_days ?? []) }}</p>
                <p><strong>Status:</strong> {{ strtoupper($card->status) }}</p>
                <p><strong>Valid From:</strong> {{ $card->valid_from?->format('d M Y') ?? 'N/A' }}</p>
                <p><strong>Valid Until:</strong> {{ $card->valid_until?->format('d M Y') ?? 'N/A' }}</p>
                @if($card->type === 'late')
                    <p><strong>Latest Arrival:</strong> {{ \Carbon\Carbon::parse($card->latest_time)->format('h:i A') }}</p>
                @else
                    <p><strong>Out Time:</strong> {{ \Carbon\Carbon::parse($card->start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($card->end_time)->format('h:i A') }}</p>
                @endif
                <p><strong>Approved By:</strong> {{ $card->approverDisplayName() }}</p>
            </div>

            @if($card->request?->review_note)
                <div class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-4">
                    <h4 class="font-bold text-blue-900 mb-1">Approval Comment</h4>
                    <p class="text-blue-900 whitespace-pre-line">{{ $card->request->review_note }}</p>
                </div>
            @endif

            @if($card->revocation_reason)
                <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                    <h4 class="font-bold text-red-900 mb-1">Revocation Comment</h4>
                    <p class="text-red-900 whitespace-pre-line">{{ $card->revocation_reason }}</p>
                </div>
            @endif

            <div class="mt-6 flex flex-wrap gap-3">
                @if($card->status === 'active' || auth()->user()->can('isExecutive'))
                    <a href="{{ route('student_excuse_cards.download', $card->id) }}" class="bg-blue-700 text-white px-4 py-2 rounded hover:bg-blue-800">Download PDF</a>
                @else
                    <span class="bg-gray-200 text-gray-700 px-4 py-2 rounded">Download unavailable because this card is not active.</span>
                @endif
                @can('isExecutive')
                    <a href="{{ route('student_excuses.admin.edit', $card->request->id) }}" class="bg-green-700 text-white px-4 py-2 rounded hover:bg-green-800">Edit Excuse</a>
                @endcan
                <a href="{{ route('student_excuse_cards.verify', $card->qr_token) }}" target="_blank" class="border px-4 py-2 rounded hover:bg-gray-100">Open Verification</a>
            </div>

            @can('isExecutive')
                @if($card->status !== 'revoked')
                    <form method="POST" action="{{ route('student_excuse_cards.revoke', $card->id) }}" class="mt-6 border-t pt-5">
                        @csrf
                        <label class="block font-semibold mb-1 text-red-700">Revocation Reason</label>
                        <textarea name="revocation_reason" rows="3" class="w-full border-gray-300 rounded" required></textarea>
                        <button type="submit" class="mt-3 bg-red-700 text-white px-4 py-2 rounded hover:bg-red-800">Revoke Card</button>
                    </form>
                @endif
            @endcan
        </div>
    </div>
</x-app-layout>
