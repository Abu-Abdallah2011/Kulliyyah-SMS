<x-app-layout>

   <x-slot name="header">
      <h2 class="font-semibold lg-text-xl md-text-lg text-gray-800 dark:text-gray-200 leading-tight">
          {{ __('Attendance Record') }}

        @can('isAssistant')
          <a href="{{ url('/attendance') }}">
            <x-primary-button class="absolute top-15 right-3 bg-green-500">
            <i class="fa-solid fa-pen"></i>
            </x-primary-button>
        </a>
        <a href="{{ url('/attendanceForPreviousTerms')}}">
            <x-primary-button class="absolute top-15 right-20 bg-yellow-500">
                <i class="fa-solid fa-backward"> </i>
            </x-primary-button>
        </a> 
        @endcan
      </h2>
  </x-slot>
  @if((Auth::user()->can('isAssistant') && Request::is('attendance/show')) || (Auth::user()->can('isAssistant') && Request::is('dashboard/attendance/*')))
  <x-success-status class="mb-4" :status="session('message')" />

  <div class="py-6">

@if (!$teachers->isEmpty())
   @foreach ($teachers as $teacher)
   <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-3 text-gray-900 dark:text-gray-100">
      <h1 class="font-bold text-center">{{ $teacher->class }}: {{ $teacher->students->count() }}</h1>
        
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
             @foreach ($teacher->students as $student)
                 <tr class="bg-white lg:hover:bg-gray-100 lg:table-row mb-10 lg:mb-0">
                     <td class="w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">{{ $student->id }}</td>
                     <td class="w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">{{ $student->fullname }}</td>
                     @foreach($datesDisplayed as $date)
                         @php
                             $statusesForDate = $attendance->where('date', $date)
                                                         ->where('student_id', $student->id)
                                                         ->pluck('status')
                                                         ->toArray();
                                                        //  echo "Student ID: $student->id, Date: $date, Statuses: " . implode(', ', $statusesForDate) . '<br>';
                         @endphp
                         <td class="text-center w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">                           
                             <a href="/attendance/{{$date}}/edit_attendance">
                              @foreach ($statusesForDate as $status)
                              {!! $statusIcons[strtolower($status)] !!}
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
   @endforeach
@else
   <p>No teachers found.</p>
@endif
  </div>
  @endif

  @if(Request::is('attendance/guardian_view/*'))
  <x-Guardian-attendance-view :attendance="$attendance" :statusIcons="$statusIcons" :student="$student" />
  @endif

  <div class="mt-6 p-4">
   {{$attendance->Links()}}
</div>

   </x-app-layout>