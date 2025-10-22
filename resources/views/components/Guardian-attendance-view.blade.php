  <div class="py-6">

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-3 text-gray-900 dark:text-gray-100">

      <div class="table-responsive">
      <table class="border-collapse w-full">
         <thead>
            <tr>
               <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 lg:table-cell">ID</th>
               <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 lg:table-cell">NAME</th>
                 @php
                     $datesDisplayed = [];
                 @endphp
                 @foreach($attendance as $record)
     
                     @if (!in_array($record->date, $datesDisplayed))
                         <th class="text-center  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell" style="writing-mode: vertical-rl; transform: rotate(180deg);">{{ $record->date }}</th>
                         @php
                             $datesDisplayed[] = $record->date;
                         @endphp
                     @endif
                 @endforeach
             </tr>
         </thead>
         <tbody>
                 <tr class="bg-white lg:hover:bg-gray-100 lg:table-row mb-10 lg:mb-0">
                     <td class="w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">{{ $student->id }}</td>
                     <td class="w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">{{ $student->fullname }}</td>
                     @foreach($datesDisplayed as $date)
                         @php
                             $statusesForDate = $attendance->where('date', $date)
                                                         ->where('student_id', $student->id)
                                                         ->pluck('status')
                                                         ->toArray();
                         @endphp
                         <td class="text-center w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">                           
                             
                              @foreach ($statusesForDate as $status)
                              {!! $statusIcons[strtolower($status)] !!}
                                 @endforeach
                              
                         </td>
                     @endforeach
                 </tr>
         </tbody>
     </table>
      </div>

      <div class="bg-gradient-to-r from-green-50 to-green-100 p-6 mt-6 rounded-xl shadow-inner">
    <h2 class="text-2xl font-bold text-gray-800 mb-6 flex items-center">
        📊 Attendance Summary
    </h2>

    @if($attendanceRecords->isNotEmpty())
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($attendanceRecords as $session => $terms)
                @php
                    $sessionTotals = ['presents' => 0, 'absents' => 0, 'lates' => 0, 'excuses' => 0, 'total' => 0];
                @endphp

                @foreach($terms as $term => $summary)
                    @php
                        $total = $summary['total'] ?: 1;
                        $percentages = [
                            'presents' => round(($summary['presents'] / $total) * 100, 1),
                            'absents'  => round(($summary['absents'] / $total) * 100, 1),
                            'lates'    => round(($summary['lates'] / $total) * 100, 1),
                            'excuses'  => round(($summary['excuses'] / $total) * 100, 1),
                        ];

                        // accumulate for session summary
                        foreach ($sessionTotals as $key => $val) {
                            $sessionTotals[$key] += $summary[$key] ?? 0;
                        }
                    @endphp

                    <div class="bg-white p-5 border rounded-2xl shadow-md hover:shadow-lg transition duration-300 ease-in-out">
                        <div class="flex justify-between items-center mb-3">
                            <h3 class="text-lg font-semibold text-indigo-700">{{ ucfirst($term) }}</h3>
                            <span class="text-sm bg-indigo-50 text-indigo-600 px-3 py-1 rounded-full font-medium">{{ $session }}</span>
                        </div>

                        <div class="space-y-2">
                            @foreach(['presents' => 'Present', 'absents' => 'Absent', 'lates' => 'Late', 'excuses' => 'Excused'] as $key => $label)
                                <div>
                                    <div class="flex justify-between text-sm font-medium">
                                        <span class="
                                            {{ $key === 'presents' ? 'text-green-500' : '' }}
                                            {{ $key === 'absents' ? 'text-red-500' : '' }}
                                            {{ $key === 'lates' ? 'text-yellow-500' : '' }}
                                            {{ $key === 'excuses' ? 'text-purple-500' : '' }}
                                        ">{{ $label }}</span>
                                        <span class="
                                            {{ $key === 'presents' ? 'text-green-500' : '' }}
                                            {{ $key === 'absents' ? 'text-red-500' : '' }}
                                            {{ $key === 'lates' ? 'text-yellow-500' : '' }}
                                            {{ $key === 'excuses' ? 'text-purple-500' : '' }}
                                        ">
                                            {{ $percentages[$key] }}% ({{ $summary[$key] }})
                                        </span>
                                    </div>
                                    <div class="w-full bg-gray-100 rounded-full h-2">
                                        <div class="
                                            {{ $key === 'presents' ? 'bg-green-500' : '' }}
                                            {{ $key === 'absents' ? 'bg-red-500' : '' }}
                                            {{ $key === 'lates' ? 'bg-yellow-400' : '' }}
                                            {{ $key === 'excuses' ? 'bg-purple-500' : '' }}
                                            h-2 rounded-full
                                        " style="width: {{ $percentages[$key] }}%"></div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endforeach

                {{-- =============================================
     SESSION TOTAL SUMMARY CALCULATION
     =============================================
     Explanation:
     The session percentage for each category (Present, Absent, Late, Excused)
     is computed using the *weighted total*, not the average of term percentages.

     Formula used:
         Session % = (Sum of category days across all terms ÷ Total school days in session) × 100

     This ensures accuracy even if terms have different total school days.
     Example:
         (P1 + P2 + P3) / (T1 + T2 + T3) × 100
     ============================================= --}}

                {{-- ✅ SESSION AVERAGE CARD --}}
                @php
                    $sessionTotals['total'] = max($sessionTotals['total'], 1); // avoid divide by zero
                    $sessionPercentages = [
                        'presents' => round(($sessionTotals['presents'] / $sessionTotals['total']) * 100, 1),
                        'absents'  => round(($sessionTotals['absents'] / $sessionTotals['total']) * 100, 1),
                        'lates'    => round(($sessionTotals['lates'] / $sessionTotals['total']) * 100, 1),
                        'excuses'  => round(($sessionTotals['excuses'] / $sessionTotals['total']) * 100, 1),
                    ];
                @endphp

                <div class="col-span-full bg-indigo-50 border-l-4 border-indigo-500 p-6 rounded-xl shadow-inner">
                    <h3 class="text-xl font-bold text-indigo-700 mb-4">📘 {{ $session }} Overall Summary</h3>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($sessionPercentages as $key => $percentage)
    @php
        $count = $sessionTotals[$key] ?? 0; // total days for this category
    @endphp
    <div class="bg-white p-3 rounded-lg shadow text-center">
        <h4 class="text-sm font-semibold capitalize text-gray-700">
            {{ ucfirst($key) }}
        </h4>
        <p class="text-lg font-bold
            {{ $key === 'presents' ? 'text-green-600' : '' }}
            {{ $key === 'absents' ? 'text-red-600' : '' }}
            {{ $key === 'lates' ? 'text-yellow-600' : '' }}
            {{ $key === 'excuses' ? 'text-purple-600' : '' }}
        ">
            {{ $percentage }}% <span class="text-gray-500 text-sm font-normal">({{ $count }} days)</span>
        </p>
    </div>
@endforeach

                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-600">No attendance record available for this student yet.</p>
    @endif
</div>

{{-- PDF Download --}}
@can('isAdmin')
    <div class="mt-6 text-right">
        <a
         href="{{ route('student.attendance.pdf', $student->id) }}" 
           class="inline-flex items-center bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 transition">
            <i class="fa-solid fa-file-pdf mr-2"></i> Download PDF Summary
        </a>
    </div>
@endcan

        </div>
    </div>
      </div>