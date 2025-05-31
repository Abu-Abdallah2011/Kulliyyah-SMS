  <div class="py-6">

    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-3 text-gray-900 dark:text-gray-100">


            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <!-- Term Attendance Card -->
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-indigo-600 mb-4">📘 Term Attendance</h2>
        <div class="space-y-2">
            <div class="flex justify-between text-gray-700">
                <span>Total Days:</span>
                <span class="font-semibold">{{ $totalattendancerecordsforterm }}</span>
            </div>
            <div class="flex justify-between text-green-600">
                <span>Present:</span>
                <span class="font-semibold">{{ $presentattendancerecordsforterm }}</span>
            </div>
            <div class="flex justify-between text-red-600">
                <span>Absent:</span>
                <span class="font-semibold">{{ $absentattendancerecordsforterm }}</span>
            </div>
            <div class="flex justify-between text-yellow-600">
                <span>Late:</span>
                <span class="font-semibold">{{ $lateattendancerecordsforterm }}</span>
            </div>
            <div class="flex justify-between text-blue-500">
                <span>Excused:</span>
                <span class="font-semibold">{{ $excusedattendancerecordsforterm }}</span>
            </div>
            <div class="flex justify-between text-purple-600">
                <span>% Attendance:</span>
                <span class="font-semibold">{{ number_format($percentageAttendanceForTerm) }}%</span>
            </div>
        </div>
    </div>

    <!-- Session Attendance Card -->
    <div class="bg-white shadow-lg rounded-2xl p-6 border border-gray-200">
        <h2 class="text-lg font-semibold text-indigo-600 mb-4">📗 Session Attendance</h2>
        <div class="space-y-2">
            <div class="flex justify-between text-gray-700">
                <span>Total Days:</span>
                <span class="font-semibold">{{ $totalattendancerecordsforSession }}</span>
            </div>
            <div class="flex justify-between text-green-600">
                <span>Present:</span>
                <span class="font-semibold">{{ $presentattendancerecordsforSession }}</span>
            </div>
            <div class="flex justify-between text-red-600">
                <span>Absent:</span>
                <span class="font-semibold">{{ $absentattendancerecordsforSession }}</span>
            </div>
            <div class="flex justify-between text-yellow-600">
                <span>Late:</span>
                <span class="font-semibold">{{ $lateattendancerecordsforSession }}</span>
            </div>
            <div class="flex justify-between text-blue-500">
                <span>Excused:</span>
                <span class="font-semibold">{{ $excusedattendancerecordsforSession }}</span>
            </div>
            <div class="flex justify-between text-purple-600">
                <span>% Attendance:</span>
                <span class="font-semibold">{{ number_format($percentageAttendanceForSession) }}%</span>
            </div>
        </div>
    </div>
</div>
<br> <br>
        
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
        </div>
    </div>
      </div>