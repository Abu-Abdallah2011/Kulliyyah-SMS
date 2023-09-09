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
                                                        //  echo "Student ID: $student->id, Date: $date, Statuses: " . implode(', ', $statusesForDate) . '<br>';
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