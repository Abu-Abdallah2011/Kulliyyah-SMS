<x-app-layout>

    <x-slot name="header">
       <h2 class="font-semibold lg-text-xl md-text-lg text-gray-800 dark:text-gray-200 leading-tight">
           {{ __('Teachers Attendance Record') }}
 
         @can('isExecutive')
           <a href="{{ url('/teachersAttendance') }}" class="lg-text-xl md-text-lg bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded absolute top-15 right-9">
             <i class="fa-solid fa-pen"></i>
         </a>
         @endcan
       </h2>
   </x-slot>
   <x-success-status class="mb-4" :status="session('message')" />
 
   <div class="py-6">
    {{-- @if((Auth::user()->can('isExecutive'))) --}}
 @if (!$teachers->isEmpty())
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
       <div class="p-3 text-gray-900 dark:text-gray-100">
       <h1 class="font-bold text-center">NUMBER OF TEACHERS: {{ $teachers->count() }}</h1>

       @can('isExecutive')
       <div class="mt-4 mb-6 bg-gray-50 border border-gray-200 rounded-lg p-4">
           <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
               <div>
                   <h2 class="font-bold text-gray-900">Teachers Signing Book</h2>
                   <p class="text-sm text-gray-600">Download separate term signing books for male and female teachers. Fridays are skipped automatically.</p>
               </div>
               <div class="flex flex-col sm:flex-row gap-3">
                   <a href="{{ route('teachersAttendance.signingBook', 'male') }}" class="inline-flex justify-center bg-blue-700 hover:bg-blue-800 text-white font-bold py-2 px-4 rounded">
                       Download Male Book
                   </a>
                   <a href="{{ route('teachersAttendance.signingBook', 'female') }}" class="inline-flex justify-center bg-pink-700 hover:bg-pink-800 text-white font-bold py-2 px-4 rounded">
                       Download Female Book
                   </a>
               </div>
           </div>
       </div>
       @endcan

       <form method="GET" action="{{ url('/teachersAttendance/show') }}" class="mt-4 mb-6 flex flex-wrap items-end gap-3">
           <div class="w-full sm:w-48">
               <x-input-label for="month" :value="__('Month')" />
               <select id="month" name="month" class="block mt-1 w-full h-10 px-3 py-2 border-gray-300 bg-white text-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                   @foreach($months as $monthOption)
                       <option value="{{ $monthOption }}" @selected($monthOption === $month)>{{ $monthOption }}</option>
                   @endforeach
               </select>
           </div>

           <div class="w-full sm:w-32">
               <x-input-label for="year" :value="__('Year')" />
               <x-text-input id="year" class="block mt-1 w-full h-10 px-3 py-2" type="number" name="year" value="{{ $year }}" required />
           </div>

           <div class="w-full sm:w-auto">
               <x-primary-button class="h-10">
                   {{ __('View Month') }}
               </x-primary-button>
           </div>
       </form>

       <div class="mb-6 overflow-x-auto">
       <h2 class="font-bold mb-2">Voucher Attendance Eligibility for {{ $month }} {{ $year }}</h2>
       <table class="border-collapse w-full">
          <thead>
             <tr>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-left">Teacher</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Present</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Late</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Absent</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Excused</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Total</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Eligible %</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Voucher Status</th>
             </tr>
          </thead>
          <tbody>
              @foreach ($teachers as $teacher)
                  @php
                      $summary = $attendanceSummaries[$teacher->id] ?? \App\Support\TeacherAttendanceSummary::empty();
                  @endphp
                  <tr class="bg-white lg:hover:bg-gray-100">
                      <td class="p-2 md:p-3 text-gray-800 border border-b font-bold">{{ $teacher->fullname }}</td>
                      <td class="p-2 md:p-3 text-gray-800 border border-b text-center">{{ $summary['present'] }}</td>
                      <td class="p-2 md:p-3 text-gray-800 border border-b text-center">{{ $summary['late'] }}</td>
                      <td class="p-2 md:p-3 text-gray-800 border border-b text-center">{{ $summary['absent'] }}</td>
                      <td class="p-2 md:p-3 text-gray-800 border border-b text-center">{{ $summary['excused'] }}</td>
                      <td class="p-2 md:p-3 text-gray-800 border border-b text-center">{{ $summary['total'] }}</td>
                      <td class="p-2 md:p-3 border border-b text-center font-bold {{ $summary['eligible'] ? 'text-green-700' : 'text-red-600' }}">
                          {{ number_format((float) $summary['percentage'], 1) }}%
                      </td>
                      <td class="p-2 md:p-3 border border-b text-center font-bold {{ $summary['eligible'] ? 'text-green-700' : 'text-red-600' }}">
                          {{ $summary['eligible'] ? 'Eligible' : 'Below '.$eligibilityPercentage.'%' }}
                      </td>
                  </tr>
              @endforeach
          </tbody>
       </table>
       <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
           Voucher eligibility is calculated as (Present + Late) divided by Total attendance records for the selected month.
       </p>
       </div>

       @if($finalizedVoucher)
       <div class="mb-6 overflow-x-auto">
       <h2 class="font-bold mb-2">Finalized Voucher Snapshot: {{ $finalizedVoucher->voucher_no }}</h2>
       <table class="border-collapse w-full">
          <thead>
             <tr>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-left">Teacher</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Snapshot Present</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Snapshot Late</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Snapshot Total</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Snapshot %</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-center">Voucher Decision</th>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 text-left">Reason</th>
             </tr>
          </thead>
          <tbody>
              @foreach ($teachers as $teacher)
                  @php
                      $snapshot = $voucherSnapshotItems->get($teacher->id);
                  @endphp
                  <tr class="bg-white lg:hover:bg-gray-100">
                      <td class="p-2 md:p-3 text-gray-800 border border-b font-bold">{{ $teacher->fullname }}</td>
                      @if($snapshot)
                          <td class="p-2 md:p-3 text-gray-800 border border-b text-center">{{ $snapshot->attendance_present ?? 0 }}</td>
                          <td class="p-2 md:p-3 text-gray-800 border border-b text-center">{{ $snapshot->attendance_late ?? 0 }}</td>
                          <td class="p-2 md:p-3 text-gray-800 border border-b text-center">{{ $snapshot->attendance_total ?? 0 }}</td>
                          <td class="p-2 md:p-3 border border-b text-center font-bold {{ $snapshot->attendance_eligible ? 'text-green-700' : 'text-red-600' }}">
                              {{ number_format((float) ($snapshot->attendance_percentage ?? 0), 1) }}%
                          </td>
                          <td class="p-2 md:p-3 border border-b text-center font-bold {{ $snapshot->included ? 'text-green-700' : 'text-red-600' }}">
                              {{ $snapshot->included ? 'Included' : 'Not included' }}
                          </td>
                          <td class="p-2 md:p-3 text-gray-800 border border-b">{{ $snapshot->reason ?: 'N/A' }}</td>
                      @else
                          <td class="p-2 md:p-3 text-gray-500 border border-b text-center" colspan="4">No saved snapshot row</td>
                          <td class="p-2 md:p-3 border border-b text-center font-bold text-red-600">Not included</td>
                          <td class="p-2 md:p-3 text-gray-500 border border-b">Not included in finalized voucher</td>
                      @endif
                  </tr>
              @endforeach
          </tbody>
       </table>
       <p class="mt-2 text-sm text-gray-600 dark:text-gray-300">
           This section shows the saved voucher snapshot from finalization. The live attendance summary above may change if attendance records are edited later.
       </p>
       </div>
       @endif
         
       <div class="table-responsive">
       <h2 class="font-bold mb-2">Daily Attendance Records for {{ $month }} {{ $year }}</h2>
       <table class="border-collapse w-full">
          <thead>
             <tr>
                <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 lg:table-cell">S/N</th>
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
              @foreach ($teachers as $teacher)
                  <tr class="bg-white lg:hover:bg-gray-100 lg:table-row mb-10 lg:mb-0">
                      <td class="w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">{{ $loop->iteration }}</td>
                      <td class="w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">{{ $teacher->fullname }}</td>
                      @foreach($datesDisplayed as $date)
                          @php
                              $statusesForDate = $attendance->where('date', $date)
                                                          ->where('teacher_id', $teacher->id)
                                                          ->pluck('status')
                                                          ->toArray();
                          @endphp
                          <td class="text-center w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">                           
                               <a href="/teachersAttendance/{{$date}}/edit_attendance">
                               @foreach ($statusesForDate as $status)
                               {!! $statusIcons[strtolower($status)] ?? e($status) !!}
                                  @endforeach
                               </a>
                          </td>
                      @endforeach
                  </tr>
              @endforeach
          </tbody>
      </table>
       </div>
 
       </div>
    </div>
 @else
    <p>No teachers found.</p>
 @endif
   </div>
 
    </x-app-layout>
