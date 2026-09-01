<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Student Excuse Requests</h2>
    </x-slot>

    <div class="py-6 max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <x-success-status class="mb-4" :status="session('message')" />

        <form method="GET" action="{{ route('student_excuses.admin.index') }}" class="mb-5 rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
            <div class="grid gap-3 md:grid-cols-12 md:items-end">
                <div class="md:col-span-5">
                    <x-input-label for="search" :value="__('Search Requests')" />
                    <input
                        id="search"
                        name="search"
                        type="text"
                        value="{{ $search ?? '' }}"
                        placeholder="Student, class, guardian, phone, email, card no..."
                        class="mt-1 block h-10 w-full rounded-md border-gray-300 text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                    >
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="status" :value="__('Status')" />
                    <select id="status" name="status" class="mt-1 block h-10 w-full rounded-md border-gray-300 bg-white text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Statuses</option>
                        <option value="pending" @selected(($status ?? '') === 'pending')>Pending</option>
                        <option value="approved" @selected(($status ?? '') === 'approved')>Approved</option>
                        <option value="rejected" @selected(($status ?? '') === 'rejected')>Rejected</option>
                        <option value="revoked" @selected(($status ?? '') === 'revoked')>Revoked</option>
                    </select>
                </div>

                <div class="md:col-span-2">
                    <x-input-label for="type" :value="__('Type')" />
                    <select id="type" name="type" class="mt-1 block h-10 w-full rounded-md border-gray-300 bg-white text-gray-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">All Types</option>
                        <option value="late" @selected(($type ?? '') === 'late')>Late Excuse</option>
                        <option value="out" @selected(($type ?? '') === 'out')>Out Excuse</option>
                    </select>
                </div>

                <div class="flex gap-2 md:col-span-3">
                    <button type="submit" class="inline-flex h-10 flex-1 items-center justify-center rounded-md bg-slate-800 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-slate-900">
                        Search
                    </button>
                    <a href="{{ route('student_excuses.admin.index') }}" class="inline-flex h-10 items-center justify-center rounded-md border border-slate-300 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-slate-700 transition hover:bg-slate-50">
                        Clear
                    </a>
                </div>
            </div>

            <div class="mt-3 text-sm text-slate-500">
                Showing {{ $requests->firstItem() ?? 0 }} - {{ $requests->lastItem() ?? 0 }} of {{ $requests->total() }} excuse request(s).
            </div>
        </form>

        <div class="hidden md:block bg-white shadow rounded-lg overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead class="bg-gray-100 text-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left">Student</th>
                        <th class="px-4 py-3 text-left">Guardian</th>
                        <th class="px-4 py-3 text-left">Type</th>
                        <th class="px-4 py-3 text-left">Days</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-left">Submitted</th>
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
                            <td class="px-4 py-3">{{ $request->student->fullname ?? 'N/A' }}<br><span class="text-xs text-gray-500">{{ $request->student->class ?? '' }}</span></td>
                            <td class="px-4 py-3">{{ $request->guardian->fullname ?? 'N/A' }}</td>
                            <td class="px-4 py-3">{{ $request->type === 'late' ? 'Late Excuse' : 'Out Excuse' }}</td>
                            <td class="px-4 py-3">{{ implode(', ', $request->requested_days ?? []) }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $statusClass }}">
                                    {{ $status }}
                                </span>
                            </td>
                            <td class="px-4 py-3">{{ $request->created_at?->format('d M Y') }}</td>
                            <td class="px-4 py-3">
                                @if($request->card)
                                    <a href="{{ route('student_excuses.admin.show', $request->card->id) }}" class="text-blue-700 font-semibold">View Card</a>
                                    <span class="text-gray-300 mx-1">|</span>
                                    <a href="{{ route('student_excuses.admin.edit', $request->id) }}" class="text-green-700 font-semibold">Edit</a>
                                @else
                                    <a href="{{ route('student_excuses.admin.review', $request->id) }}" class="text-blue-700 font-semibold">Review</a>
                                    <span class="text-gray-300 mx-1">|</span>
                                    <a href="{{ route('student_excuses.admin.edit', $request->id) }}" class="text-green-700 font-semibold">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="px-4 py-6 text-center text-gray-500">No student excuse requests found.</td></tr>
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
                @endphp

                <div class="bg-white shadow rounded-xl border border-gray-100 p-4">
                    <div class="flex justify-between items-start gap-3">
                        <div>
                            <h3 class="font-bold text-gray-900 leading-snug">{{ $request->student->fullname ?? 'N/A' }}</h3>
                            <p class="text-sm text-gray-500">{{ $request->student->class ?? 'N/A' }}</p>
                        </div>
                        <span class="shrink-0 inline-flex items-center px-3 py-1 rounded-full text-xs font-bold uppercase border {{ $statusClass }}">
                            {{ $status }}
                        </span>
                    </div>

                    <div class="mt-4 grid grid-cols-1 gap-2 text-sm text-gray-700">
                        <p><span class="font-semibold text-gray-900">Guardian:</span> {{ $request->guardian->fullname ?? 'N/A' }}</p>
                        <p><span class="font-semibold text-gray-900">Type:</span> {{ $request->type === 'late' ? 'Late Excuse' : 'Out Excuse' }}</p>
                        <p><span class="font-semibold text-gray-900">Days:</span> {{ implode(', ', $request->requested_days ?? []) }}</p>
                        <p><span class="font-semibold text-gray-900">Submitted:</span> {{ $request->created_at?->format('d M Y') }}</p>
                    </div>

                    <div class="mt-4 pt-3 border-t">
                        @if($request->card)
                            <a href="{{ route('student_excuses.admin.show', $request->card->id) }}" class="inline-flex justify-center w-full bg-blue-700 text-white font-semibold px-4 py-2 rounded-lg hover:bg-blue-800">View Card</a>
                            <a href="{{ route('student_excuses.admin.edit', $request->id) }}" class="mt-2 inline-flex justify-center w-full bg-green-700 text-white font-semibold px-4 py-2 rounded-lg hover:bg-green-800">Edit</a>
                        @else
                            <a href="{{ route('student_excuses.admin.review', $request->id) }}" class="inline-flex justify-center w-full bg-green-700 text-white font-semibold px-4 py-2 rounded-lg hover:bg-green-800">Review</a>
                            <a href="{{ route('student_excuses.admin.edit', $request->id) }}" class="mt-2 inline-flex justify-center w-full border border-green-700 text-green-700 font-semibold px-4 py-2 rounded-lg hover:bg-green-50">Edit</a>
                        @endif
                    </div>
                </div>
            @empty
                <div class="bg-white shadow rounded-xl p-6 text-center text-gray-500">No student excuse requests found.</div>
            @endforelse
        </div>

        <div class="mt-4">{{ $requests->links() }}</div>
    </div>
</x-app-layout>
