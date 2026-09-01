<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Hadda Records
            </h2>

            @can('isAssistant')
            <div class="flex items-center gap-2">
    <a href="{{ url('hadda_page/' . $student->id . '/HaddaForm') }}"
       title="Add New Hadda Record">
        <x-primary-button class="bg-green-600 hover:bg-green-700 p-2">
            <i class="fa-solid fa-plus"></i>
        </x-primary-button>
    </a>

    <a href="{{ url('hadda_page/' . $student->id . '/pdf') }}"
       title="Download Hadda PDF">
        <x-primary-button class="bg-gray-200 hover:bg-gray-300 text-gray-800 p-2">
            <i class="fa-solid fa-file-pdf text-red-600"></i>
        </x-primary-button>
    </a>
</div>

            @endcan
        </div>
    </x-slot>

    <div class="py-4 space-y-4">
        <x-search-hadda :student="$student" />
        <x-success-status class="mb-4" :status="session('message')" />

        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
            <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                <div class="text-center font-bold text-lg mb-4">
                    {{ $student->fullname }} — {{ $student->class }}
                </div>

                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 p-4 text-emerald-900">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wide text-emerald-700">Current Term Total Pages</p>
                            <p class="text-sm text-emerald-800">
                                {{ $termPageSummary['term'] ?? 'Current Term' }}{{ !empty($termPageSummary['session']) ? ' - '.$termPageSummary['session'] : '' }}
                            </p>
                        </div>
                        <div class="text-left sm:text-right">
                            <p class="text-3xl font-black">{{ $termPageSummary['pages'] ?? 0 }}</p>
                            <p class="text-xs text-emerald-700">
                                {{ $termPageSummary['records'] ?? 0 }} records, {{ $termPageSummary['unverified'] ?? 0 }} unverified
                            </p>
                        </div>
                    </div>
                </div>

                @php
                    $classRank = $rankSummary['class'] ?? null;
                    $schoolRank = $rankSummary['school'] ?? null;
                @endphp

                <div class="mb-4 grid gap-3 md:grid-cols-2">
                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">Class Rank</p>
                        @if($classRank)
                            <div class="mt-2 flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-2xl font-black text-slate-900">#{{ $classRank['rank'] }}</p>
                                    <p class="text-xs text-slate-500">in {{ $student->class }}</p>
                                </div>
                                <p class="text-sm font-bold text-slate-700">{{ number_format((float) $classRank['leaderboard_points'], 1) }} points</p>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">
                                @if($classRank['gap_to_above'] !== null)
                                    {{ $classRank['gap_to_above'] }} point(s) behind the student above.
                                @else
                                    Leading this class.
                                @endif
                                @if($classRank['gap_to_below'] !== null)
                                    {{ $classRank['gap_to_below'] }} point(s) ahead of the student below.
                                @endif
                            </p>
                        @else
                            <p class="mt-2 text-sm text-slate-500">No rank yet for this term.</p>
                        @endif
                    </div>

                    <div class="rounded-lg border border-slate-200 bg-white p-4 shadow-sm">
                        <p class="text-xs font-bold uppercase tracking-wide text-slate-500">School Rank</p>
                        @if($schoolRank)
                            <div class="mt-2 flex items-end justify-between gap-3">
                                <div>
                                    <p class="text-2xl font-black text-slate-900">#{{ $schoolRank['rank'] }}</p>
                                    <p class="text-xs text-slate-500">school-wide</p>
                                </div>
                                <p class="text-sm font-bold text-slate-700">{{ number_format((float) $schoolRank['leaderboard_points'], 1) }} points</p>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">
                                @if($schoolRank['gap_to_above'] !== null)
                                    {{ $schoolRank['gap_to_above'] }} point(s) behind the student above.
                                @else
                                    Leading the school.
                                @endif
                                @if($schoolRank['gap_to_below'] !== null)
                                    {{ $schoolRank['gap_to_below'] }} point(s) ahead of the student below.
                                @endif
                            </p>
                        @else
                            <p class="mt-2 text-sm text-slate-500">No school rank yet for this term.</p>
                        @endif
                    </div>
                </div>

                <!-- Responsive table wrapper -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse text-sm">
                        <thead class="bg-gray-200 dark:bg-gray-700 sticky top-0">
                            <tr>
                                @foreach ([
                                    'Date', 'Class', 'Term', 'Session', 'From Surah', 'Verse', 
                                    'To Surah', 'Verse', 'Page(s)', 'Grade', 'Teacher', 'Comment'
                                ] as $col)
                                    <th class="p-3 font-semibold uppercase text-gray-600 dark:text-gray-300 border border-gray-300 text-left hidden lg:table-cell">
                                        {{ $col }}
                                    </th>
                                @endforeach
                                @can('isAssistant')
                                    <th class="p-3 font-semibold uppercase text-gray-600 dark:text-gray-300 border border-gray-300 text-left hidden lg:table-cell">
                                        Delete
                                    </th>
                                @endcan
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($hadda as $item)
                                @php
                                    $coverage = \App\Support\HaddaCoverageCalculator::analyze($item);
                                @endphp
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex flex-col lg:table-row mb-6 lg:mb-0 border-b lg:border-0 rounded-lg lg:rounded-none shadow-sm lg:shadow-none">
                                    
                                    <!-- Date -->
                                    <td class="p-3 border-b lg:border border-gray-200 dark:border-gray-700">
                                        @can('isAssistant')
                                            <a href="{{ url('/hadda_page/' . $item->id . '/edit_hadda') }}" class="text-indigo-600 hover:underline">
                                                {{ $item->date }}
                                            </a>
                                        @else
                                            {{ $item->date }}
                                        @endcan
                                    </td>

                                    <!-- Term -->
                                    <td class="p-3 border-b lg:border">{{ $item->class }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->term }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->session }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->sura }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->from }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->to_surah }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->to }}</td>
                                    <td class="p-3 border-b lg:border font-bold">
                                        @if ($coverage['status'] === 'page')
                                            {{ $coverage['pages'] }}
                                        @else
                                            Unverified
                                        @endif
                                    </td>
                                    <td class="p-3 border-b lg:border font-bold">{{ $item->grade }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->teacher }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->comment }}</td>

                                    @can('isAssistant')
                                    <td class="p-3 border-b lg:border">
                                        <form method="POST" action="{{ url('/hadda_page/' . $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button 
                                                onclick="return confirm('Are you sure you want to delete this record?')"
                                                class="bg-red-600 hover:bg-red-700"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                            </x-danger-button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        No Hadda records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $hadda->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Floating add button for mobile -->
    @can('isAssistant')
    <a href="{{ url('hadda_page/' . $student->id . '/HaddaForm') }}" 
       class="lg:hidden fixed bottom-6 right-6 bg-green-500 hover:bg-green-700 text-white rounded-full p-4 shadow-lg">
        <i class="fa-solid fa-plus"></i>
    </a>
    @endcan
</x-app-layout>
