
<!-- Button to Navigate to Attendance form -->
<a href="{{ url('/attendance') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
    Take Attendance
</a>

@if (!$teachers->isEmpty())
   @foreach ($teachers as $teacher)
      <h1 class="font-bold text-center">{{ $teacher->class }}: {{ $teacher->students->count() }}</h1>
      
      <table class="border-collapse w-full">
         <thead>
            <tr>
               <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">ID</th>
               <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">NAME</th>
               <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">STATUS</th>
            </tr>
         </thead>
         <tbody>
            @foreach ($teacher->students as $student)
               <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
                  <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static font-bold">{{ $student->id }}</td>
                  <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static font-bold">{{ $student->fullname }}</td>
                  <td class="px-6 py-4 whitespace-nowrap">
                     @if ($record = $student->attendanceRecords->where('date', $date)->first())
                        @switch($record->status)
                           @case('present')
                              <i class="fas fa-check text-green-500"></i>
                              @break
                           @case('absent')
                              <i class="fas fa-times text-red-500"></i>
                              @break
                           @case('late')
                              <i class="fas fa-clock text-orange-500"></i>
                              @break
                           @case('excused')
                              <i class="fas fa-question-circle text-purple-500"></i>
                              @break
                        @endswitch
                     @endif
                  </td>
               </tr>
            @endforeach
         </tbody>
      </table>
   @endforeach
@else
   <p>No teachers found.</p>
@endif
