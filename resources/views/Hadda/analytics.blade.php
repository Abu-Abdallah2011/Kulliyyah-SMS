<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Hadda Performance Analytics') }}
            </h2>
            <a href="{{ route('hadda.leaderboard') }}" class="text-sm font-bold text-indigo-600 hover:underline">
                View Leaderboard
            </a>
        </div>
    </x-slot>

    @php
        $summary = $analytics['summary'];
        $weeklyProgress = $analytics['weeklyProgress'];
        $studentRows = $analytics['studentRows'];
        $classRows = $analytics['classRows'];
        $maxWeeklyPoints = max(1, (float) $weeklyProgress->max('points'));
        $visibleStudents = $studentRows->take(20);
        $hiddenStudents = $studentRows->slice(20)->values();
        $trendClasses = [
            'Improving' => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            'Stable' => 'bg-blue-100 text-blue-800 border-blue-200',
            'Declining' => 'bg-amber-100 text-amber-800 border-amber-200',
            'Started' => 'bg-slate-100 text-slate-800 border-slate-200',
            'No Record' => 'bg-red-100 text-red-800 border-red-200',
        ];
    @endphp

    <div class="py-4 md:py-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="max-w-full overflow-hidden p-3 text-gray-900 dark:text-gray-100 md:p-6">
                <form method="GET" action="{{ route('hadda.analytics') }}" class="mb-6 grid gap-3 md:grid-cols-5 md:items-end">
                    <div>
                        <x-input-label for="session" :value="__('Session')" />
                        <select id="session" name="session" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach($sessions->pluck('session')->unique()->filter() as $session)
                                <option value="{{ $session }}" @selected($session === $selectedSession)>{{ $session }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="term" :value="__('Term')" />
                        <select id="term" name="term" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            @foreach($sessions->pluck('term')->unique()->filter() as $term)
                                <option value="{{ $term }}" @selected($term === $selectedTerm)>{{ $term }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="class" :value="__('Class')" />
                        <select id="class" name="class" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">School Wide</option>
                            @foreach($classes as $class)
                                <option value="{{ $class }}" @selected($class === $selectedClass)>{{ $class }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="student_id" :value="__('Student')" />
                        <select id="student_id" name="student_id" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">All Students</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" @selected((int) $selectedStudentId === (int) $student->id)>{{ $student->fullname }}</option>
                            @endforeach
                        </select>
                    </div>

                    <x-primary-button class="h-10 justify-center">
                        {{ __('View Analytics') }}
                    </x-primary-button>
                </form>

                <div class="mb-5 max-w-full rounded-xl border border-indigo-100 bg-gradient-to-r from-indigo-50 via-white to-emerald-50 p-4">
                    <p class="text-sm font-semibold text-gray-700">
                        Showing {{ $selectedStudent ? $selectedStudent->fullname : ($selectedClass ?: 'School Wide') }}
                        for {{ $selectedTerm }} {{ $selectedSession }}.
                    </p>
                    <p class="mt-1 text-xs text-gray-500">
                        These figures are calculated live from Hadda records. Pages are raw coverage; points apply the Tarteel rule where 2 Tarteel pages equal 1 Hadda point.
                    </p>
                </div>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-4">
                        <p class="text-xs font-bold uppercase text-emerald-700">Pages</p>
                        <p class="mt-2 text-3xl font-black text-emerald-900">{{ number_format($summary['total_pages']) }}</p>
                        <p class="text-xs text-emerald-700">{{ number_format((float) $summary['total_points'], 1) }} points</p>
                    </div>

                    <div class="rounded-xl border border-blue-200 bg-blue-50 p-4">
                        <p class="text-xs font-bold uppercase text-blue-700">Students</p>
                        <p class="mt-2 text-3xl font-black text-blue-900">{{ $summary['student_count'] }}</p>
                        <p class="text-xs text-blue-700">{{ $summary['recorded_student_count'] }} with records, {{ $summary['no_record_count'] }} without</p>
                    </div>

                    <div class="rounded-xl border border-amber-200 bg-amber-50 p-4">
                        <p class="text-xs font-bold uppercase text-amber-700">Avg Points</p>
                        <p class="mt-2 text-3xl font-black text-amber-900">{{ number_format((float) $summary['average_points_per_student'], 1) }}</p>
                        <p class="text-xs text-amber-700">per eligible student</p>
                    </div>

                    <div class="rounded-xl border {{ $trendClasses[$summary['trend']] ?? 'border-slate-200 bg-slate-50 text-slate-800' }} p-4">
                        <p class="text-xs font-bold uppercase">Trend</p>
                        <p class="mt-2 break-words text-3xl font-black">{{ $summary['trend'] }}</p>
                        <p class="text-xs">Last record: {{ $summary['last_record_date'] ? $summary['last_record_date']->format('M d, Y') : 'N/A' }}</p>
                    </div>
                </div>

                <div class="mt-6 max-w-full rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                    <div class="mb-4 flex flex-col gap-1 sm:flex-row sm:items-center sm:justify-between">
                        <h3 class="text-lg font-bold text-gray-800">Weekly Progress</h3>
                        <span class="text-xs font-semibold text-gray-500">{{ $analytics['period']['term_starts'] ?? 'Term start unknown' }} - {{ $analytics['period']['term_ends'] ?? 'Term end unknown' }}</span>
                    </div>

                    <div class="space-y-3">
                        @forelse($weeklyProgress as $week)
                            @php $width = max(2, ((float) $week['points'] / $maxWeeklyPoints) * 100); @endphp
                            <div>
                                <div class="mb-1 flex flex-col gap-1 text-xs font-semibold text-gray-600 sm:flex-row sm:items-center sm:justify-between">
                                    <span>{{ $week['range'] }}</span>
                                    <span>{{ number_format((float) $week['points'], 1) }} pts / {{ $week['pages'] }} pages</span>
                                </div>
                                <div class="h-4 overflow-hidden rounded-full bg-gray-100">
                                    <div class="h-full rounded-full bg-gradient-to-r from-emerald-500 to-indigo-500" style="width: {{ $width }}%"></div>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No weekly data yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-4 {{ $hasFullAnalyticsAccess ? 'lg:grid-cols-2' : 'lg:grid-cols-1' }}">
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <h3 class="mb-3 text-lg font-bold text-gray-800">Top Students</h3>
                        <div class="space-y-2">
                            @forelse($analytics['topStudents'] as $row)
                                <div class="flex items-center justify-between gap-3 rounded-lg bg-emerald-50 p-3">
                                    <div class="min-w-0">
                                        <p class="break-words font-bold text-gray-800">#{{ $row['rank'] }} {{ $row['fullname'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $row['class'] }} · {{ $row['valid_records'] }} valid records</p>
                                    </div>
                                    <p class="shrink-0 font-black text-emerald-700">{{ number_format((float) $row['leaderboard_points'], 1) }} pts</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No student records yet.</p>
                            @endforelse
                        </div>
                    </div>

                    @if($hasFullAnalyticsAccess)
                    <div class="rounded-xl border border-gray-200 bg-white p-4 shadow-sm">
                        <h3 class="mb-3 text-lg font-bold text-gray-800">Needs Attention</h3>
                        <div class="space-y-2">
                            @forelse($analytics['bottomStudents'] as $row)
                                <div class="flex items-center justify-between gap-3 rounded-lg bg-amber-50 p-3">
                                    <div class="min-w-0">
                                        <p class="break-words font-bold text-gray-800">#{{ $row['rank'] }} {{ $row['fullname'] }}</p>
                                        <p class="text-xs text-gray-500">{{ $row['class'] }} · {{ $row['record_count'] }} records</p>
                                    </div>
                                    <p class="shrink-0 font-black text-amber-700">{{ number_format((float) $row['leaderboard_points'], 1) }} pts</p>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No students to review yet.</p>
                            @endforelse
                        </div>
                    </div>
                    @endif
                </div>

                @if(!$selectedStudent)
                    <div class="mt-6 max-w-full overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                        <div class="flex flex-col gap-1 p-4 sm:flex-row sm:items-center sm:justify-between">
                            <h3 class="text-lg font-bold text-gray-800">{{ $selectedClass ? $selectedClass.' Students' : 'Student Analytics' }}</h3>
                            <p class="text-xs text-gray-500">Showing {{ $visibleStudents->count() }} of {{ $studentRows->count() }}</p>
                        </div>
                        <div class="space-y-3 p-4 pt-0 md:hidden">
                            @forelse($visibleStudents as $row)
                                @php
                                    $canOpenStudentAnalytics = $hasFullAnalyticsAccess || ($teacherClass && $row['class'] === $teacherClass);
                                @endphp
                                <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-black uppercase text-indigo-600">#{{ $row['rank'] }}</p>
                                            @if($canOpenStudentAnalytics)
                                                <a href="{{ route('hadda.analytics', array_merge(request()->except('student_id'), ['student_id' => $row['student_id'], 'class' => $row['class']])) }}" class="break-words font-bold text-gray-900 hover:underline">
                                                    {{ $row['fullname'] }}
                                                </a>
                                            @else
                                                <p class="break-words font-bold text-gray-900">{{ $row['fullname'] }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500">{{ $row['class'] }}</p>
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
                                            <p class="font-black text-gray-900">{{ $row['record_count'] }}</p>
                                            <p class="text-gray-500">Records</p>
                                        </div>
                                        <div class="rounded-lg bg-white p-2">
                                            <p class="font-black {{ $row['uncounted_records'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $row['uncounted_records'] }}</p>
                                            <p class="text-gray-500">Uncounted</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-lg border border-gray-200 p-4 text-center text-sm text-gray-500">No analytics data yet.</p>
                            @endforelse
                        </div>

                        <div class="hidden overflow-x-auto md:block">
                            <table class="w-full border-collapse text-sm">
                                <thead>
                                    <tr>
                                        <th class="border bg-gray-100 p-3 text-center">Rank</th>
                                        <th class="border bg-gray-100 p-3 text-left">Student</th>
                                        <th class="border bg-gray-100 p-3 text-left">Class</th>
                                        <th class="border bg-gray-100 p-3 text-center">Pages</th>
                                        <th class="border bg-gray-100 p-3 text-center">Points</th>
                                        <th class="border bg-gray-100 p-3 text-center">Records</th>
                                        <th class="border bg-gray-100 p-3 text-center">Uncounted</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($visibleStudents as $row)
                                        @php
                                            $canOpenStudentAnalytics = $hasFullAnalyticsAccess || ($teacherClass && $row['class'] === $teacherClass);
                                        @endphp
                                        <tr>
                                            <td class="border p-3 text-center font-bold">#{{ $row['rank'] }}</td>
                                            <td class="border p-3 font-bold">
                                                @if($canOpenStudentAnalytics)
                                                    <a href="{{ route('hadda.analytics', array_merge(request()->except('student_id'), ['student_id' => $row['student_id'], 'class' => $row['class']])) }}" class="text-indigo-600 hover:underline">
                                                        {{ $row['fullname'] }}
                                                    </a>
                                                @else
                                                    <span>{{ $row['fullname'] }}</span>
                                                @endif
                                            </td>
                                            <td class="border p-3">{{ $row['class'] }}</td>
                                            <td class="border p-3 text-center font-bold">{{ $row['confirmed_pages'] }}</td>
                                            <td class="border p-3 text-center font-bold">{{ number_format((float) $row['leaderboard_points'], 1) }}</td>
                                            <td class="border p-3 text-center">{{ $row['record_count'] }}</td>
                                            <td class="border p-3 text-center">{{ $row['uncounted_records'] }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="border p-4 text-center text-gray-500">No analytics data yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($hiddenStudents->isNotEmpty())
                            <details class="p-4">
                                <summary class="cursor-pointer font-bold text-gray-800">Show remaining {{ $hiddenStudents->count() }} students</summary>
                                <div class="mt-4 space-y-3 md:hidden">
                                    @foreach($hiddenStudents as $row)
                                        <div class="rounded-xl border border-gray-200 bg-gray-50 p-3">
                                            <div class="flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <p class="text-xs font-black uppercase text-indigo-600">#{{ $row['rank'] }}</p>
                                                    <p class="break-words font-bold text-gray-900">{{ $row['fullname'] }}</p>
                                                    <p class="text-xs text-gray-500">{{ $row['class'] }}</p>
                                                </div>
                                                <p class="shrink-0 font-black text-emerald-700">{{ number_format((float) $row['leaderboard_points'], 1) }} pts</p>
                                            </div>
                                            <div class="mt-3 grid grid-cols-3 gap-2 text-center text-xs">
                                                <div class="rounded-lg bg-white p-2">
                                                    <p class="font-black text-gray-900">{{ $row['confirmed_pages'] }}</p>
                                                    <p class="text-gray-500">Pages</p>
                                                </div>
                                                <div class="rounded-lg bg-white p-2">
                                                    <p class="font-black text-gray-900">{{ $row['record_count'] }}</p>
                                                    <p class="text-gray-500">Records</p>
                                                </div>
                                                <div class="rounded-lg bg-white p-2">
                                                    <p class="font-black {{ $row['uncounted_records'] > 0 ? 'text-red-600' : 'text-gray-900' }}">{{ $row['uncounted_records'] }}</p>
                                                    <p class="text-gray-500">Uncounted</p>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-4 hidden overflow-x-auto md:block">
                                    <table class="w-full border-collapse text-sm">
                                        <tbody>
                                            @foreach($hiddenStudents as $row)
                                                <tr>
                                                    <td class="border p-3 text-center font-bold">#{{ $row['rank'] }}</td>
                                                    <td class="border p-3 font-bold">{{ $row['fullname'] }}</td>
                                                    <td class="border p-3">{{ $row['class'] }}</td>
                                                    <td class="border p-3 text-center font-bold">{{ $row['confirmed_pages'] }}</td>
                                                    <td class="border p-3 text-center font-bold">{{ number_format((float) $row['leaderboard_points'], 1) }}</td>
                                                    <td class="border p-3 text-center">{{ $row['record_count'] }}</td>
                                                    <td class="border p-3 text-center">{{ $row['uncounted_records'] }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </details>
                        @endif
                    </div>
                @endif

                @if(!$selectedClass && !$selectedStudent)
                    <div class="mt-6 max-w-full overflow-hidden rounded-xl border border-emerald-200 bg-white shadow-sm">
                        <div class="p-4">
                            <h3 class="text-lg font-bold text-gray-800">Class Analytics</h3>
                        </div>
                        <div class="space-y-3 p-4 pt-0 md:hidden">
                            @forelse($classRows as $row)
                                @php
                                    $canOpenClassAnalytics = $hasFullAnalyticsAccess || ($teacherClass && $row['class'] === $teacherClass);
                                @endphp
                                <div class="rounded-xl border border-emerald-100 bg-emerald-50 p-3">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-xs font-black uppercase text-emerald-700">#{{ $row['rank'] }}</p>
                                            @if($canOpenClassAnalytics)
                                                <a href="{{ route('hadda.analytics', array_merge(request()->query(), ['class' => $row['class'], 'student_id' => null])) }}" class="break-words font-bold text-gray-900 hover:underline">
                                                    {{ $row['class'] }}
                                                </a>
                                            @else
                                                <p class="break-words font-bold text-gray-900">{{ $row['class'] }}</p>
                                            @endif
                                            <p class="text-xs text-gray-500">{{ $row['student_count'] }} students · {{ $row['recorded_student_count'] }} recorded</p>
                                        </div>
                                        <div class="shrink-0 rounded-lg bg-white px-3 py-2 text-right shadow-sm">
                                            <p class="text-xs text-gray-500">Avg</p>
                                            <p class="font-black text-emerald-700">{{ number_format((float) $row['average_points_per_student'], 1) }}</p>
                                        </div>
                                    </div>
                                    <div class="mt-3 grid grid-cols-2 gap-2 text-center text-xs">
                                        <div class="rounded-lg bg-white p-2">
                                            <p class="font-black text-gray-900">{{ $row['confirmed_pages'] }}</p>
                                            <p class="text-gray-500">Pages</p>
                                        </div>
                                        <div class="rounded-lg bg-white p-2">
                                            <p class="font-black text-gray-900">{{ number_format((float) $row['leaderboard_points'], 1) }}</p>
                                            <p class="text-gray-500">Points</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p class="rounded-lg border border-emerald-100 p-4 text-center text-sm text-gray-500">No class analytics yet.</p>
                            @endforelse
                        </div>

                        <div class="hidden overflow-x-auto md:block">
                            <table class="w-full border-collapse text-sm">
                                <thead>
                                    <tr>
                                        <th class="border bg-emerald-100 p-3 text-center">Rank</th>
                                        <th class="border bg-emerald-100 p-3 text-left">Class</th>
                                        <th class="border bg-emerald-100 p-3 text-center">Students</th>
                                        <th class="border bg-emerald-100 p-3 text-center">Recorded</th>
                                        <th class="border bg-emerald-100 p-3 text-center">Pages</th>
                                        <th class="border bg-emerald-100 p-3 text-center">Points</th>
                                        <th class="border bg-emerald-100 p-3 text-center">Avg Points</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($classRows as $row)
                                        @php
                                            $canOpenClassAnalytics = $hasFullAnalyticsAccess || ($teacherClass && $row['class'] === $teacherClass);
                                        @endphp
                                        <tr>
                                            <td class="border p-3 text-center font-bold">#{{ $row['rank'] }}</td>
                                            <td class="border p-3 font-bold">
                                                @if($canOpenClassAnalytics)
                                                    <a href="{{ route('hadda.analytics', array_merge(request()->query(), ['class' => $row['class'], 'student_id' => null])) }}" class="text-indigo-600 hover:underline">
                                                        {{ $row['class'] }}
                                                    </a>
                                                @else
                                                    <span>{{ $row['class'] }}</span>
                                                @endif
                                            </td>
                                            <td class="border p-3 text-center">{{ $row['student_count'] }}</td>
                                            <td class="border p-3 text-center">{{ $row['recorded_student_count'] }}</td>
                                            <td class="border p-3 text-center font-bold">{{ $row['confirmed_pages'] }}</td>
                                            <td class="border p-3 text-center font-bold">{{ number_format((float) $row['leaderboard_points'], 1) }}</td>
                                            <td class="border p-3 text-center font-bold">{{ number_format((float) $row['average_points_per_student'], 1) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7" class="border p-4 text-center text-gray-500">No class analytics yet.</td>
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
