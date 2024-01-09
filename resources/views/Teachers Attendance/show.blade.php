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
              @foreach ($teachers as $teacher)
                  <tr class="bg-white lg:hover:bg-gray-100 lg:table-row mb-10 lg:mb-0">
                      <td class="w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">{{ $teacher->id }}</td>
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
 @else
    <p>No teachers found.</p>
 @endif
   </div>
 
   <div class="mt-6 p-4">
    {{$attendance->Links()}}
 </div>
 
    </x-app-layout>