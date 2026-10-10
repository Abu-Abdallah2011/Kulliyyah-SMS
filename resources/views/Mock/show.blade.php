<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mock Cycle Details') }}</h2>
            @if($cycle->status === \App\Models\MockCycle::STATUS_ACTIVE)
                <a href="{{ route('mock_records.create', $cycle->id) }}">
                    <x-primary-button class="bg-green-600 hover:bg-green-700">Add Record</x-primary-button>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <x-success-status class="mb-4" :status="session('message')" />

            <div class="rounded-2xl border border-indigo-100 bg-gradient-to-r from-indigo-50 via-white to-emerald-50 p-5 shadow-sm">
                <div class="flex flex-col gap-4 md:flex-row md:items-start md:justify-between">
                    <div>
                        <p class="text-xs font-black uppercase tracking-wide text-indigo-700">{{ $cycle->typeLabel() }} Cycle</p>
                        <h3 class="mt-1 text-2xl font-black text-gray-900">{{ $cycle->student?->fullname ?? 'Unknown Student' }}</h3>
                        <p class="text-sm font-semibold text-gray-600">{{ $cycle->student?->class ?? 'N/A' }} - Assigned to {{ $cycle->teacher?->fullname ?? 'No teacher' }}</p>
                        <p class="mt-2 text-sm text-gray-500">{{ $cycle->term ?? 'N/A' }} {{ $cycle->session ?? '' }}</p>
                    </div>
                    <div class="rounded-xl bg-white p-4 text-right shadow-sm">
                        <p class="text-xs font-bold uppercase text-gray-500">Status</p>
                        <p class="text-2xl font-black text-indigo-700">{{ $cycle->statusLabel() }}</p>
                    </div>
                </div>

                <div class="mt-5 grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-xl bg-white/80 p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase text-gray-500">Total Pages</p>
                        <p class="text-3xl font-black text-emerald-700">{{ $totalPages }}</p>
                    </div>
                    <div class="rounded-xl bg-white/80 p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase text-gray-500">Records</p>
                        <p class="text-3xl font-black text-gray-900">{{ $records->count() }}</p>
                    </div>
                    <div class="rounded-xl bg-white/80 p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase text-gray-500">Valid Records</p>
                        <p class="text-3xl font-black text-gray-900">{{ $validRecords }}</p>
                    </div>
                    <div class="rounded-xl bg-white/80 p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase text-gray-500">Last Record</p>
                        <p class="text-lg font-black text-gray-900">{{ $lastRecord?->date?->format('d M Y') ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="mt-4 grid grid-cols-1 gap-3 text-sm text-gray-700 md:grid-cols-3">
                    <p><strong>Started:</strong> {{ $cycle->start_date?->format('d M Y') ?? 'N/A' }}</p>
                    <p><strong>Target End:</strong> {{ $cycle->target_end_date?->format('d M Y') ?? 'N/A' }}</p>
                    <p><strong>Completed:</strong> {{ $cycle->completed_at?->format('d M Y') ?? 'N/A' }}</p>
                </div>

                @if($cycle->notes)
                    <div class="mt-4 rounded-xl bg-white/80 p-4 text-sm text-gray-700 shadow-sm">
                        <strong>Notes:</strong> {{ $cycle->notes }}
                    </div>
                @endif

                @if($hasManagementAccess && $cycle->status === \App\Models\MockCycle::STATUS_ACTIVE)
                    <div class="mt-4 flex flex-wrap gap-2">
                        <form method="POST" action="{{ route('mock_cycles.complete', $cycle->id) }}" onsubmit="return confirm('Are you sure you want to mark this mock cycle as completed?')">
                            @csrf
                            <x-primary-button class="bg-emerald-700 hover:bg-emerald-800">Mark Completed</x-primary-button>
                        </form>
                        <form method="POST" action="{{ route('mock_cycles.fail', $cycle->id) }}" onsubmit="return confirm('Are you sure you want to mark this mock cycle as failed?')">
                            @csrf
                            <x-primary-button class="bg-amber-600 hover:bg-amber-700">Mark Failed</x-primary-button>
                        </form>
                        <form method="POST" action="{{ route('mock_cycles.cancel', $cycle->id) }}" onsubmit="return confirm('Are you sure you want to cancel this mock cycle?')">
                            @csrf
                            <x-primary-button class="bg-red-600 hover:bg-red-700">Cancel Cycle</x-primary-button>
                        </form>
                    </div>
                @endif
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-4 md:p-6">
                    <h3 class="mb-4 text-lg font-bold text-gray-800">Mock Records</h3>

                    <div class="space-y-3 md:hidden">
                        @forelse($records as $record)
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $record->date?->format('d M Y') ?? 'N/A' }}</p>
                                        <p class="text-sm text-gray-600">{{ $record->sura }} {{ $record->from }} - {{ $record->to_surah }} {{ $record->to }}</p>
                                    </div>
                                    <span class="rounded-lg bg-white px-3 py-2 text-sm font-black text-emerald-700">{{ $record->pageCount() }} pages</span>
                                </div>
                                <p class="mt-2 text-sm text-gray-500">{{ $record->grade ?? 'No grade' }} - {{ $record->recorded_by_name ?? 'N/A' }}</p>
                                @if($record->comment)
                                    <p class="mt-2 text-sm text-gray-700">{{ $record->comment }}</p>
                                @endif
                                @if($cycle->status === \App\Models\MockCycle::STATUS_ACTIVE)
                                    <div class="mt-3 flex flex-wrap items-center gap-3 text-sm font-bold">
                                        <a href="{{ route('mock_records.edit', $record->id) }}" class="text-indigo-600 hover:underline">Edit</a>
                                        <form method="POST" action="{{ route('mock_records.delete', $record->id) }}" onsubmit="return confirm('Are you sure you want to delete this mock record?')" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                        </form>
                                    </div>
                                @endif
                            </div>
                        @empty
                            <p class="rounded-lg border p-4 text-center text-gray-500">No mock records yet.</p>
                        @endforelse
                    </div>

                    <div class="hidden overflow-x-auto md:block">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Date</th>
                                    <th class="px-4 py-2 text-left">From</th>
                                    <th class="px-4 py-2 text-left">To</th>
                                    <th class="px-4 py-2 text-center">Pages</th>
                                    <th class="px-4 py-2 text-left">Grade</th>
                                    <th class="px-4 py-2 text-left">Recorded By</th>
                                    <th class="px-4 py-2 text-left">Comment</th>
                                    @if($cycle->status === \App\Models\MockCycle::STATUS_ACTIVE)
                                        <th class="px-4 py-2 text-left">Actions</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $record)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2">{{ $record->date?->format('d M Y') ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $record->sura }} {{ $record->from }}</td>
                                        <td class="px-4 py-2">{{ $record->to_surah }} {{ $record->to }}</td>
                                        <td class="px-4 py-2 text-center font-bold">{{ $record->pageCount() }}</td>
                                        <td class="px-4 py-2">{{ $record->grade ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $record->recorded_by_name ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $record->comment }}</td>
                                        @if($cycle->status === \App\Models\MockCycle::STATUS_ACTIVE)
                                            <td class="px-4 py-2">
                                                <div class="flex items-center gap-3 font-bold">
                                                    <a href="{{ route('mock_records.edit', $record->id) }}" class="text-indigo-600 hover:underline">Edit</a>
                                                    <form method="POST" action="{{ route('mock_records.delete', $record->id) }}" onsubmit="return confirm('Are you sure you want to delete this mock record?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:underline">Delete</button>
                                                    </form>
                                                </div>
                                            </td>
                                        @endif
                                    </tr>
                                @empty
                                    <tr><td colspan="{{ $cycle->status === \App\Models\MockCycle::STATUS_ACTIVE ? 8 : 7 }}" class="px-4 py-4 text-center text-gray-500">No mock records yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
