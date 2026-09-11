<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Hadda Leaderboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-4 md:py-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="max-w-full overflow-hidden p-3 text-gray-900 dark:text-gray-100 md:p-6">
                <form method="GET" action="{{ route('hadda.leaderboard') }}" class="mb-6 flex flex-wrap items-end gap-3">
                    <div class="w-full sm:w-64">
                        <x-input-label for="session" :value="__('Session')" />
                        <select id="session" name="session" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach($sessions->pluck('session')->unique()->filter() as $session)
                                <option value="{{ $session }}" @selected($session === $selectedSession)>{{ $session }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full sm:w-48">
                        <x-input-label for="term" :value="__('Term')" />
                        <select id="term" name="term" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach($sessions->pluck('term')->unique()->filter() as $term)
                                <option value="{{ $term }}" @selected($term === $selectedTerm)>{{ $term }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full sm:w-56">
                        <x-input-label for="class" :value="__('Class')" />
                        <select id="class" name="class" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">School Wide</option>
                            @foreach($classes as $class)
                                <option value="{{ $class }}" @selected($class === $selectedClass)>{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="w-full sm:w-48">
                        <x-input-label for="period" :value="__('Ranking Period')" />
                        <select id="period" name="period" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="term" @selected($selectedPeriod === 'term')>Term</option>
                            <option value="weekly" @selected($selectedPeriod === 'weekly')>Weekly</option>
                        </select>
                    </div>

                    <div class="w-full sm:w-48">
                        <x-input-label for="week_start" :value="__('Week Start')" />
                        <input id="week_start" name="week_start" type="date" value="{{ $selectedWeekStart }}" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    </div>

                    <x-primary-button class="h-10">
                        {{ __('View Leaderboard') }}
                    </x-primary-button>

                    @if($showLeaderboardDownload && $leaderboardDownloadUrl)
                        <a href="{{ $leaderboardDownloadUrl }}" class="inline-flex h-10 items-center rounded-md bg-emerald-700 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-white transition hover:bg-emerald-800">
                            {{ $leaderboardDownloadLabel }}
                        </a>
                    @endif
                </form>

                <div class="mb-4 rounded-xl border border-indigo-100 bg-gradient-to-r from-indigo-50 via-white to-emerald-50 p-4 text-sm text-gray-600 dark:text-gray-300">
                    {{ $periodLabel }}. Ranking uses points from confirmed page coverage. For active Tarteel students, 2 pages count as 1 point. Ascending page ranges count; reverse, out-of-range, and unverified entries are not counted. Classes containing PRE are excluded.
                    @if(!$hasFullLeaderboardAccess && !$selectedClass)
                        Teachers can view the top 20 school-wide students here. Select your class to view and download the full class report.
                    @endif
                </div>

                @php
                    $summary = $summary ?? [];
                    $topClass = $summary['top_class'] ?? null;
                    $lowestClass = $summary['lowest_class'] ?? null;
                    $summaryScope = $selectedClass ? $selectedClass.' Summary' : 'School Wide Summary';
                @endphp

                <div class="mb-6 rounded-2xl border border-slate-200 bg-slate-50 p-4 shadow-sm">
                    <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                        <div>
                            <p class="text-xs font-black uppercase tracking-wide text-indigo-600">{{ $summaryScope }}</p>
                            <h3 class="text-xl font-black text-slate-900">Performance at a glance</h3>
                        </div>
                        <p class="text-sm text-slate-500">{{ $periodLabel }}</p>
                    </div>

                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-4">
                        <div class="rounded-xl border border-emerald-100 bg-white p-4">
                            <p class="text-xs font-bold uppercase text-emerald-700">Total Pages</p>
                            <p class="mt-2 text-3xl font-black text-emerald-900">{{ number_format($summary['total_pages'] ?? 0) }}</p>
                            <p class="text-xs text-emerald-700">{{ number_format((float) ($summary['total_points'] ?? 0), 1) }} points</p>
                        </div>

                        <div class="rounded-xl border border-blue-100 bg-white p-4">
                            <p class="text-xs font-bold uppercase text-blue-700">Students</p>
                            <p class="mt-2 text-3xl font-black text-blue-900">{{ number_format($summary['eligible_student_count'] ?? 0) }}</p>
                            <p class="text-xs text-blue-700">{{ number_format($summary['recorded_student_count'] ?? 0) }} with records, {{ number_format($summary['no_record_count'] ?? 0) }} without</p>
                        </div>

                        <div class="rounded-xl border border-amber-100 bg-white p-4">
                            <p class="text-xs font-bold uppercase text-amber-700">Average</p>
                            <p class="mt-2 text-3xl font-black text-amber-900">{{ number_format((float) ($summary['average_points_per_student'] ?? 0), 1) }}</p>
                            <p class="text-xs text-amber-700">{{ number_format((float) ($summary['average_pages_per_student'] ?? 0), 1) }} pages per eligible student</p>
                        </div>

                        <div class="rounded-xl border border-indigo-100 bg-white p-4">
                            <p class="text-xs font-bold uppercase text-indigo-700">Classes</p>
                            <p class="mt-2 text-3xl font-black text-indigo-900">{{ number_format($summary['class_count'] ?? 0) }}</p>
                            @if(!$selectedClass && $topClass)
                                <p class="text-xs text-indigo-700">Top: {{ $topClass['class'] }} at {{ number_format((float) $topClass['average_points_per_student'], 1) }} avg</p>
                            @elseif($selectedClass && $lowestClass)
                                <p class="text-xs text-indigo-700">{{ number_format((float) $lowestClass['average_points_per_student'], 1) }} class average points</p>
                            @else
                                <p class="text-xs text-indigo-700">No class average yet</p>
                            @endif
                        </div>
                    </div>
                </div>

                @php
                    $studentsWithoutRecords = $studentsWithoutRecords ?? collect();
                @endphp

                @if($studentsWithoutRecords->isNotEmpty())
                    <details class="mb-6 rounded-2xl border border-rose-200 bg-rose-50 p-4 shadow-sm">
                        <summary class="cursor-pointer list-none">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                                <div>
                                    <p class="text-xs font-black uppercase tracking-wide text-rose-700">Action Needed</p>
                                    <h3 class="text-lg font-black text-rose-950">
                                        View {{ number_format($studentsWithoutRecords->count()) }} students with no records
                                    </h3>
                                    <p class="text-sm text-rose-700">
                                        These eligible students have no Hadda entry for the selected {{ $selectedPeriod === 'weekly' ? 'week' : 'term' }}.
                                    </p>
                                </div>
                                <span class="inline-flex w-fit rounded-full bg-white px-3 py-1 text-xs font-black uppercase tracking-wide text-rose-700 shadow-sm">
                                    Open List
                                </span>
                            </div>
                        </summary>

                        <div class="mt-4 space-y-3 md:hidden">
                            @foreach($studentsWithoutRecords as $student)
                                <div class="rounded-xl border border-rose-100 bg-white p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="break-words font-bold text-gray-900">{{ $student->fullname }}</p>
                                            <p class="text-xs text-gray-500">{{ $student->class ?? 'No class' }}</p>
                                        </div>
                                        <span class="shrink-0 rounded-full bg-rose-100 px-2 py-1 text-xs font-bold text-rose-700">No record</span>
                                    </div>
                                    <div class="mt-3 grid grid-cols-2 gap-2 text-xs">
                                        <div class="rounded-lg bg-gray-50 p-2">
                                            <p class="font-bold text-gray-900">{{ $student->haddaStatusLabel() }}</p>
                                            <p class="text-gray-500">Hadda Status</p>
                                        </div>
                                        <div class="rounded-lg bg-gray-50 p-2">
                                            <p class="font-bold text-gray-900">{{ $student->tarteelStatusLabel() }}</p>
                                            <p class="text-gray-500">Tarteel Status</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-4 hidden overflow-x-auto md:block">
                            <table class="w-full border-collapse text-sm">
                                <thead>
                                    <tr>
                                        <th class="border border-rose-200 bg-rose-100 p-3 text-center font-bold uppercase text-rose-800">S/N</th>
                                        <th class="border border-rose-200 bg-rose-100 p-3 text-left font-bold uppercase text-rose-800">Student</th>
                                        <th class="border border-rose-200 bg-rose-100 p-3 text-left font-bold uppercase text-rose-800">Class</th>
                                        <th class="border border-rose-200 bg-rose-100 p-3 text-center font-bold uppercase text-rose-800">Hadda Status</th>
                                        <th class="border border-rose-200 bg-rose-100 p-3 text-center font-bold uppercase text-rose-800">Tarteel Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($studentsWithoutRecords as $index => $student)
                                        <tr class="bg-white lg:hover:bg-rose-50">
                                            <td class="border border-rose-100 p-3 text-center font-bold text-gray-800">{{ $index + 1 }}</td>
                                            <td class="border border-rose-100 p-3 font-bold text-gray-800">{{ $student->fullname }}</td>
                                            <td class="border border-rose-100 p-3 text-gray-800">{{ $student->class ?? 'N/A' }}</td>
                                            <td class="border border-rose-100 p-3 text-center text-gray-800">{{ $student->haddaStatusLabel() }}</td>
                                            <td class="border border-rose-100 p-3 text-center text-gray-800">{{ $student->tarteelStatusLabel() }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                    @php
                        $podiumStyles = [
                            ['wrap' => 'from-amber-100 via-white to-yellow-50 border-amber-300', 'label' => '1st', 'text' => 'text-amber-700'],
                            ['wrap' => 'from-slate-100 via-white to-gray-50 border-slate-300', 'label' => '2nd', 'text' => 'text-slate-700'],
                            ['wrap' => 'from-orange-100 via-white to-amber-50 border-orange-300', 'label' => '3rd', 'text' => 'text-orange-700'],
                        ];
                    @endphp

                    @forelse($topStudents as $index => $row)
                        @php $style = $podiumStyles[$index] ?? $podiumStyles[2]; @endphp
                        <div class="rounded-lg border bg-gradient-to-br {{ $style['wrap'] }} p-5 shadow-sm">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold uppercase {{ $style['text'] }}">{{ $style['label'] }} Place</span>
                                <span class="rounded-full bg-white px-3 py-1 text-xs font-bold {{ $style['text'] }}">#{{ $row['rank'] }}</span>
                            </div>
                            <h3 class="mt-4 break-words text-xl font-bold text-gray-900">{{ $row['student']?->fullname ?? 'Unknown Student' }}</h3>
                            <p class="text-sm font-semibold text-gray-600">{{ $row['class'] }}</p>
                            <div class="mt-4 flex items-end justify-between">
                                <div>
                                    <p class="text-xs uppercase text-gray-500">Points</p>
                                    <p class="text-3xl font-black {{ $style['text'] }}">{{ number_format((float) $row['leaderboard_points'], 1) }}</p>
                                    <p class="text-xs text-gray-500">{{ $row['confirmed_pages'] }} pages</p>
                                </div>
                                <div class="text-right text-sm text-gray-600">
                                    <p>Valid: <strong>{{ $row['valid_records'] }}</strong></p>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="md:col-span-3 rounded-lg border border-gray-200 bg-gray-50 p-5 text-center text-gray-500">
                            No top students yet for this selection.
                        </div>
                    @endforelse
                </div>

                @php
                    $schoolWide = !$selectedClass;
                    $visibleLeaderboard = ($schoolWide && $leaderboard->count() > 20) ? $leaderboard->take(20) : $leaderboard;
                    $hiddenLeaderboard = ($schoolWide && $canExpandFullSchoolLeaderboard && $leaderboard->count() > 20) ? $leaderboard->slice(20)->values() : collect();
                @endphp

                <div class="max-w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <div class="mb-2 flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                        <h3 class="px-4 pt-4 text-lg font-bold text-gray-800 sm:px-0 sm:pt-0">
                            {{ $selectedClass ? $selectedClass.' Leaderboard' : 'School Leaderboard' }}
                        </h3>
                        @if($hiddenLeaderboard->isNotEmpty())
                            <p class="text-sm text-gray-500">
                                Showing top {{ $visibleLeaderboard->count() }} of {{ $leaderboard->count() }} students. Expand below for the full list.
                            </p>
                        @elseif(!$hasFullLeaderboardAccess && $schoolWide && $leaderboard->count() > $visibleLeaderboard->count())
                            <p class="text-sm text-gray-500">
                                Showing top {{ $visibleLeaderboard->count() }} school-wide students.
                            </p>
                        @endif
                    </div>
                    <div class="space-y-3 p-4 md:hidden">
                        @forelse($visibleLeaderboard as $index => $row)
                            @php
                                $canOpenStudent = $hasFullLeaderboardAccess || ($teacherClass && $row['class'] === $teacherClass);
                            @endphp
                            <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="min-w-0">
                                        <p class="text-xs font-black uppercase text-indigo-600">#{{ $row['rank'] }}</p>
                                        @if($row['student'])
                                            @if($canOpenStudent)
                                                <a href="{{ route('hadda_page.show', $row['student']->id) }}" class="break-words font-bold text-gray-900 hover:underline">
                                                    {{ $row['student']->fullname }}
                                                </a>
                                            @else
                                                <p class="break-words font-bold text-gray-900">{{ $row['student']->fullname }}</p>
                                            @endif
                                        @else
                                            <p class="font-bold text-gray-900">Unknown Student</p>
                                        @endif
                                        <p class="text-xs text-gray-500">{{ $row['class'] }} · Last: {{ $row['last_record_date'] ? $row['last_record_date']->format('M d, Y') : 'N/A' }}</p>
                                    </div>
                                    <div class="shrink-0 rounded-lg bg-white px-3 py-2 text-right shadow-sm">
                                        <p class="text-xs text-gray-500">Points</p>
                                        <p class="font-black text-emerald-700">{{ number_format((float) $row['leaderboard_points'], 1) }}</p>
                                    </div>
                                </div>
                                <div class="mt-3 grid grid-cols-3 gap-2 text-center text-xs">
                                    <div class="rounded-lg bg-white p-2">
                                        <p class="font-black text-gray-900">{{ $row['confirmed_pages'] }}</p>
                                        <p class="text-gray-500">Pages</p>
                                    </div>
                                    <div class="rounded-lg bg-white p-2">
                                        <p class="font-black text-gray-900">{{ $row['valid_records'] }}</p>
                                        <p class="text-gray-500">Records</p>
                                    </div>
                                    <div class="rounded-lg bg-white p-2">
                                        <p class="font-black {{ $row['uncounted_records'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $row['uncounted_records'] }}</p>
                                        <p class="text-gray-500">Uncounted</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="rounded-lg border border-gray-200 p-4 text-center text-sm text-gray-500">
                                No eligible Hadda records found for this term and session.
                            </p>
                        @endforelse
                    </div>

                    <div class="hidden overflow-x-auto md:block">
                        <table class="border-collapse w-full text-sm">
                            <thead>
                                <tr>
                                    <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Rank</th>
                                    <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-left">Student</th>
                                    <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-left">Class</th>
                                    <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Pages</th>
                                    <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Points</th>
                                    <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Records</th>
                                    <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Uncounted</th>
                                    <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Last Record</th>
                                </tr>
                            </thead>
                            <tbody>
                            @forelse($visibleLeaderboard as $index => $row)
                                @php
                                    $canOpenStudent = $hasFullLeaderboardAccess || ($teacherClass && $row['class'] === $teacherClass);
                                @endphp
                                <tr class="bg-white lg:hover:bg-gray-100">
                                    <td class="p-3 text-gray-800 border border-b text-center font-bold">{{ $row['rank'] }}</td>
                                    <td class="p-3 text-gray-800 border border-b font-bold">
                                        @if($row['student'])
                                            @if($canOpenStudent)
                                                <a href="{{ route('hadda_page.show', $row['student']->id) }}" class="text-indigo-600 hover:underline">
                                                    {{ $row['student']->fullname }}
                                                </a>
                                            @else
                                                <span class="text-gray-800">{{ $row['student']->fullname }}</span>
                                            @endif
                                        @else
                                            Unknown Student
                                        @endif
                                    </td>
                                    <td class="p-3 text-gray-800 border border-b">{{ $row['class'] }}</td>
                                    <td class="p-3 text-gray-800 border border-b text-center font-bold">{{ $row['confirmed_pages'] }}</td>
                                    <td class="p-3 text-gray-800 border border-b text-center font-bold">{{ number_format((float) $row['leaderboard_points'], 1) }}</td>
                                    <td class="p-3 text-gray-800 border border-b text-center">{{ $row['valid_records'] }}</td>
                                    <td class="p-3 text-gray-800 border border-b text-center">
                                        <span class="{{ $row['uncounted_records'] > 0 ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                            {{ $row['uncounted_records'] }}
                                        </span>
                                        <div class="text-xs text-gray-500">
                                            Verse: {{ $row['verse_based_records'] }}, Unverified: {{ $row['unverified_records'] }}
                                        </div>
                                    </td>
                                    <td class="p-3 text-gray-800 border border-b text-center">
                                        {{ $row['last_record_date'] ? $row['last_record_date']->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="p-4 text-center text-gray-500">
                                        No eligible Hadda records found for this term and session.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($hiddenLeaderboard->isNotEmpty())
                    <details class="mt-4 rounded-lg border border-gray-200 bg-gray-50 p-4">
                        <summary class="cursor-pointer font-bold text-gray-800">
                            Show remaining {{ $hiddenLeaderboard->count() }} students
                        </summary>
                        <div class="mt-4 space-y-3 md:hidden">
                            @foreach($hiddenLeaderboard as $hiddenIndex => $row)
                                @php
                                    $canOpenStudent = $hasFullLeaderboardAccess || ($teacherClass && $row['class'] === $teacherClass);
                                @endphp
                                <div class="rounded-xl border border-gray-200 bg-white p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-black uppercase text-indigo-600">#{{ $row['rank'] }}</p>
                                            @if($row['student'])
                                                @if($canOpenStudent)
                                                    <a href="{{ route('hadda_page.show', $row['student']->id) }}" class="break-words font-bold text-gray-900 hover:underline">
                                                        {{ $row['student']->fullname }}
                                                    </a>
                                                @else
                                                    <p class="break-words font-bold text-gray-900">{{ $row['student']->fullname }}</p>
                                                @endif
                                            @else
                                                <p class="font-bold text-gray-900">Unknown Student</p>
                                            @endif
                                            <p class="text-xs text-gray-500">{{ $row['class'] }} · Last: {{ $row['last_record_date'] ? $row['last_record_date']->format('M d, Y') : 'N/A' }}</p>
                                        </div>
                                        <p class="shrink-0 font-black text-emerald-700">{{ number_format((float) $row['leaderboard_points'], 1) }} pts</p>
                                    </div>
                                    <div class="mt-3 grid grid-cols-3 gap-2 text-center text-xs">
                                        <div class="rounded-lg bg-gray-50 p-2">
                                            <p class="font-black text-gray-900">{{ $row['confirmed_pages'] }}</p>
                                            <p class="text-gray-500">Pages</p>
                                        </div>
                                        <div class="rounded-lg bg-gray-50 p-2">
                                            <p class="font-black text-gray-900">{{ $row['valid_records'] }}</p>
                                            <p class="text-gray-500">Records</p>
                                        </div>
                                        <div class="rounded-lg bg-gray-50 p-2">
                                            <p class="font-black {{ $row['uncounted_records'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $row['uncounted_records'] }}</p>
                                            <p class="text-gray-500">Uncounted</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-4 hidden overflow-x-auto md:block">
                            <table class="border-collapse w-full text-sm">
                                <thead>
                                    <tr>
                                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Rank</th>
                                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-left">Student</th>
                                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-left">Class</th>
                                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Pages</th>
                                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Points</th>
                                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Records</th>
                                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Uncounted</th>
                                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 text-center">Last Record</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($hiddenLeaderboard as $hiddenIndex => $row)
                                        @php
                                            $canOpenStudent = $hasFullLeaderboardAccess || ($teacherClass && $row['class'] === $teacherClass);
                                        @endphp
                                        <tr class="bg-white lg:hover:bg-gray-100">
                                            <td class="p-3 text-gray-800 border border-b text-center font-bold">{{ $row['rank'] }}</td>
                                            <td class="p-3 text-gray-800 border border-b font-bold">
                                                @if($row['student'])
                                                    @if($canOpenStudent)
                                                        <a href="{{ route('hadda_page.show', $row['student']->id) }}" class="text-indigo-600 hover:underline">
                                                            {{ $row['student']->fullname }}
                                                        </a>
                                                    @else
                                                        <span class="text-gray-800">{{ $row['student']->fullname }}</span>
                                                    @endif
                                                @else
                                                    Unknown Student
                                                @endif
                                            </td>
                                            <td class="p-3 text-gray-800 border border-b">{{ $row['class'] }}</td>
                                            <td class="p-3 text-gray-800 border border-b text-center font-bold">{{ $row['confirmed_pages'] }}</td>
                                            <td class="p-3 text-gray-800 border border-b text-center font-bold">{{ number_format((float) $row['leaderboard_points'], 1) }}</td>
                                            <td class="p-3 text-gray-800 border border-b text-center">{{ $row['valid_records'] }}</td>
                                            <td class="p-3 text-gray-800 border border-b text-center">
                                                <span class="{{ $row['uncounted_records'] > 0 ? 'text-red-600 font-bold' : 'text-gray-700' }}">
                                                    {{ $row['uncounted_records'] }}
                                                </span>
                                                <div class="text-xs text-gray-500">
                                                    Verse: {{ $row['verse_based_records'] }}, Unverified: {{ $row['unverified_records'] }}
                                                </div>
                                            </td>
                                            <td class="p-3 text-gray-800 border border-b text-center">
                                                {{ $row['last_record_date'] ? $row['last_record_date']->format('M d, Y') : 'N/A' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </details>
                @endif

                @if(!$selectedClass)
                    <div class="mt-8 max-w-full overflow-hidden rounded-xl border border-emerald-200 bg-white shadow-sm">
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800">Class Rankings</h3>
                        </div>
                        <div class="space-y-3 p-4 pt-0 md:hidden">
                            @forelse($classRankings as $index => $row)
                                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-black uppercase text-emerald-700">#{{ $row['rank'] }}</p>
                                            <p class="break-words font-bold text-gray-900">{{ $row['class'] }}</p>
                                            <p class="text-xs text-gray-500">{{ $row['student_count'] }} students · {{ $row['recorded_student_count'] }} recorded</p>
                                        </div>
                                        <div class="shrink-0 rounded-lg bg-white px-3 py-2 text-right shadow-sm">
                                            <p class="text-xs text-gray-500">Avg</p>
                                            <p class="font-black text-emerald-700">{{ number_format((float) $row['average_points_per_student'], 1) }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 grid grid-cols-3 gap-2 text-center text-xs">
                                        <div class="rounded-lg bg-white p-2">
                                            <p class="font-black text-gray-900">{{ $row['confirmed_pages'] }}</p>
                                            <p class="text-gray-500">Pages</p>
                                        </div>
                                        <div class="rounded-lg bg-white p-2">
                                            <p class="font-black text-gray-900">{{ number_format((float) $row['leaderboard_points'], 1) }}</p>
                                            <p class="text-gray-500">Points</p>
                                        </div>
                                        <div class="rounded-lg bg-white p-2">
                                            <p class="font-black {{ $row['uncounted_records'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $row['uncounted_records'] }}</p>
                                            <p class="text-gray-500">Uncounted</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-lg border border-emerald-100 p-4 text-center text-sm text-gray-500">
                                    No class ranking can be shown yet.
                                </p>
                            @endforelse
                        </div>
                        <div class="hidden overflow-x-auto md:block">
                            <table class="border-collapse w-full text-sm">
                                <thead>
                                    <tr>
                                        <th class="p-3 font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 text-center">Rank</th>
                                        <th class="p-3 font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 text-left">Class</th>
                                        <th class="p-3 font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 text-center">Total Students</th>
                                        <th class="p-3 font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 text-center">Students With Records</th>
                                        <th class="p-3 font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 text-center">Pages</th>
                                        <th class="p-3 font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 text-center">Points</th>
                                        <th class="p-3 font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 text-center">Avg Points</th>
                                        <th class="p-3 font-bold uppercase bg-emerald-100 text-emerald-800 border border-emerald-200 text-center">Uncounted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($classRankings as $index => $row)
                                        <tr class="bg-white lg:hover:bg-emerald-50">
                                            <td class="p-3 text-gray-800 border border-b text-center font-bold">{{ $row['rank'] }}</td>
                                            <td class="p-3 text-gray-800 border border-b font-bold">{{ $row['class'] }}</td>
                                            <td class="p-3 text-gray-800 border border-b text-center">{{ $row['student_count'] }}</td>
                                            <td class="p-3 text-gray-800 border border-b text-center">{{ $row['recorded_student_count'] }}</td>
                                            <td class="p-3 text-emerald-700 border border-b text-center font-black">{{ $row['confirmed_pages'] }}</td>
                                            <td class="p-3 text-emerald-700 border border-b text-center font-black">{{ number_format((float) $row['leaderboard_points'], 1) }}</td>
                                            <td class="p-3 text-gray-800 border border-b text-center font-bold">{{ number_format((float) $row['average_points_per_student'], 1) }}</td>
                                            <td class="p-3 text-gray-800 border border-b text-center">{{ $row['uncounted_records'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="p-4 text-center text-gray-500">
                                                No class ranking can be shown yet.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
