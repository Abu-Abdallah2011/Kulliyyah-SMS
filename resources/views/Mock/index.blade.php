<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Mock Cycles') }}</h2>
            @if($hasManagementAccess)
                <a href="{{ route('mock_cycles.create') }}">
                    <x-primary-button class="bg-green-600 hover:bg-green-700">Assign Student</x-primary-button>
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-success-status class="mb-4" :status="session('message')" />

            <div class="mb-4 rounded-xl border border-indigo-100 bg-white p-4 shadow-sm">
                <form method="GET" action="{{ route('mock_cycles.index') }}" class="flex flex-wrap items-end gap-3">
                    <div class="w-full sm:w-48">
                        <x-input-label for="status" :value="__('Status')" />
                        <select id="status" name="status" class="block mt-1 w-full h-10 border-gray-300 rounded-md shadow-sm">
                            <option value="">All Statuses</option>
                            @foreach($statusOptions as $value => $label)
                                <option value="{{ $value }}" @selected(request('status') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="w-full sm:w-48">
                        <x-input-label for="cycle_type" :value="__('Cycle Type')" />
                        <select id="cycle_type" name="cycle_type" class="block mt-1 w-full h-10 border-gray-300 rounded-md shadow-sm">
                            <option value="">All Types</option>
                            @foreach($typeOptions as $value => $label)
                                <option value="{{ $value }}" @selected(request('cycle_type') === $value)>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <x-primary-button class="h-10">Filter</x-primary-button>
                </form>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg overflow-hidden">
                <div class="p-4 md:p-6">
                    <div class="space-y-3 md:hidden">
                        @forelse($cycles as $cycle)
                            <a href="{{ route('mock_cycles.show', $cycle->id) }}" class="block rounded-xl border border-gray-200 bg-gray-50 p-4">
                                <div class="flex items-start justify-between gap-3">
                                    <div>
                                        <p class="font-bold text-gray-900">{{ $cycle->student?->fullname ?? 'Unknown Student' }}</p>
                                        <p class="text-sm text-gray-500">{{ $cycle->student?->class ?? 'N/A' }} - {{ $cycle->teacher?->fullname ?? 'No teacher' }}</p>
                                    </div>
                                    <span class="rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold text-indigo-700">{{ $cycle->statusLabel() }}</span>
                                </div>
                                <p class="mt-3 text-sm text-gray-600">{{ $cycle->typeLabel() }} - {{ $cycle->session ?? 'N/A' }} - {{ $cycle->term ?? 'N/A' }}</p>
                            </a>
                        @empty
                            <p class="rounded-lg border p-4 text-center text-gray-500">No mock cycles yet.</p>
                        @endforelse
                    </div>

                    <div class="hidden overflow-x-auto md:block">
                        <table class="min-w-full divide-y divide-gray-200 text-sm">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Student</th>
                                    <th class="px-4 py-2 text-left">Class</th>
                                    <th class="px-4 py-2 text-left">Teacher</th>
                                    <th class="px-4 py-2 text-left">Type</th>
                                    <th class="px-4 py-2 text-left">Period</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($cycles as $cycle)
                                    <tr class="border-b hover:bg-gray-50">
                                        <td class="px-4 py-2 font-bold">{{ $cycle->student?->fullname ?? 'Unknown Student' }}</td>
                                        <td class="px-4 py-2">{{ $cycle->student?->class ?? 'N/A' }}</td>
                                        <td class="px-4 py-2">{{ $cycle->teacher?->fullname ?? 'No teacher' }}</td>
                                        <td class="px-4 py-2">{{ $cycle->typeLabel() }}</td>
                                        <td class="px-4 py-2">{{ $cycle->term ?? 'N/A' }} {{ $cycle->session ?? '' }}</td>
                                        <td class="px-4 py-2 font-bold">{{ $cycle->statusLabel() }}</td>
                                        <td class="px-4 py-2"><a class="text-indigo-600 font-bold" href="{{ route('mock_cycles.show', $cycle->id) }}">View</a></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="7" class="px-4 py-4 text-center text-gray-500">No mock cycles yet.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">{{ $cycles->links() }}</div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
