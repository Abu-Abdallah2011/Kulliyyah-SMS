<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Student Excuse Requests</h2>
    </x-slot>

    <div class="py-6 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-success-status class="mb-4" :status="session('message')" />

        <div class="mb-4 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
            <h3 class="text-lg font-bold text-gray-800">My Requests</h3>
            <a href="{{ route('student_excuses.create') }}" class="inline-flex justify-center bg-blue-700 text-white px-4 py-2 rounded shadow hover:bg-blue-800">New Request</a>
        </div>

        <div class="hidden md:block bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Student</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Days</th>
                        <th class="px-4 py-3 text-left">Time</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $request)
                        @php
                            $status = $request->card?->status === 'revoked' ? 'revoked' : $request->status;
                            $statusClass = match ($status) {
                                'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                                'approved' => 'bg-green-100 text-green-800 border-green-200',
                                'rejected' => 'bg-red-100 text-red-800 border-red-200',
                                'revoked' => 'bg-gray-200 text-gray-800 border-gray-300',
                                default => 'bg-blue-100 text-blue-800 border-blue-200',
                            };
                        @endphp
                        <tr class="border-t">
                            <td class="px-4 py-3">{{ $request->student->fullname ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $request->type === 'late' ? 'Late Excuse' : 'Out Excuse' }}</td>
                            <td class="px-4 py-3">{{ implode(', ', $request->requested_days ?? []) }}</td>
                            <td class="px-4 py-3">
                                @if($request->type === 'late')
                                    Latest: {{ \Carbon\Carbon::parse($request->requested_latest_time)->format('h:i A') }}
                                @else
                                    {{ \Carbon\Carbon::parse($request->requested_start_time)->format('h:i A') }} - {{ \Carbon\Carbon::parse($request->requested_end_time)->format('h:i A') }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if($request->card)
                                    <a href="{{ route('student_excuse_cards.guardian.show', $request->card->id) }}" class="text-blue-700 font-semibold">View Card</a>
                                @elseif($request->status === 'pending')
                                    <a href="{{ route('student_excuses.guardian.edit', $request->id) }}" class="text-blue-700 font-semibold">Edit</a>
                                @else
                                    <span class="text-gray-500">No card yet</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="px-4 py-6 text-center text-gray-500">No excuse requests yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="md:hidden space-y-4">
            @forelse($requests as $request)
                @php
                    $status = $request->card?->status === 'revoked' ? 'revoked' : $request->status;
                    $statusClass = match ($status) {
                        'pending' => 'bg-yellow-100 text-yellow-800 border-yellow-200',
                        'approved' => 'bg-green-100 text-green-800 border-green-200',
                        'rejected' => 'bg-red-100 text-red-800 border-red-200',
                        'revoked' => 'bg-gray-200 text-gray-800 border-gray-300',
                        default => 'bg-blue-100 text-blue-800 border-blue-200',
                    };
                    $timeText = $request->type === 'late'
                        ? 'Latest: ' . \Carbon\Carbon::parse($request->requested_latest_time)->format('h:i A')
                        : \Carbon\Carbon::parse($request->requested_start_time)->format('h:i A') . ' - ' . \Carbon\Carbon::parse($request->requested_end_time)->format('h:i A');
                @endphp

                <div class="bg-white shadow rounded-xl border border-gray-100 p-4">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <h3 class="font-bold text-gray-900 leading-snug">{{ $request->student->fullname ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-500">{{ $request->type === 'late' ? 'Late Excuse' : 'Out Excuse' }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-2 text-sm text-gray-700">
                        <p><span class="font-semibold text-gray-900">Days:</span> {{ implode(', ', $request->requested_days ?? []) }}</p>
                        <p><span class="font-semibold text-gray-900">Time:</span> {{ $timeText }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-t">
                        @if($request->card)
                            <a href="{{ route('student_excuse_cards.guardian.show', $request->card->id) }}" class="inline-flex justify-center w-full bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-800">View Card</a>
                        @elseif($request->status === 'pending')
                            <a href="{{ route('student_excuses.guardian.edit', $request->id) }}" class="inline-flex justify-center w-full bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-800">Edit Request</a>
                        @else
                            <span class="inline-flex justify-center w-full bg-gray-100 text-gray-500 font-semibold px-4 py-2 rounded-lg">No card yet</span>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white shadow rounded-xl p-6 text-center text-gray-500">No excuse requests yet.</div>
            @endforelse
        </div>

        <div class="mt-4">{{ $requests->links() }}</div>
    </div>
</x-app-layout>
