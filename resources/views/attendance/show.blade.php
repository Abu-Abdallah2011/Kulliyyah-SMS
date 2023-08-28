<x-app-layout>

   <x-slot name="header">
      <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
          {{ __('Attendance Record') }}

          <a href="{{ url('/attendance') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded absolute top-15 right-9">
            <i class="fa-solid fa-pen"></i>
        </a>
      </h2>
  </x-slot>
  <div class="py-6">

@if (!$teachers->isEmpty())
   @foreach ($teachers as $teacher)
   <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
      <div class="p-3 text-gray-900 dark:text-gray-100">
      <h1 class="font-bold text-center">{{ $teacher->class }}: {{ $teacher->students->count() }}</h1>
      
      <table class="border-collapse w-full">
         <thead>
            <tr>
               <th class="p-3 font-bold uppercase text-gray-600 border border-gray-300 hidden lg:table-cell">ID</th>
               <th class="p-3 font-bold uppercase text-gray-600 border border-gray-300 hidden lg:table-cell">NAME</th>
               {{-- @foreach($attendance as $record)
               <td class="p-3 uppercase text-gray-600 border border-gray-300 hidden lg:table-cell">{{$record->date}}</td>
               @endforeach --}}

               @php
                  $datesDisplayed = []; 
               @endphp

               @foreach($attendance as $record)
    @php
        $dayOfWeek = Carbon\Carbon::parse($record->date)->dayOfWeek;
    @endphp

@if (!in_array($record->date, $datesDisplayed))

    <td class="p-3 text-gray-600 border border-gray-300 lg:table-cell" style="writing-mode: vertical-rl; transform: rotate(180deg);" 
        {{-- @if ($dayOfWeek == 0 || $dayOfWeek == 6)
            colspan="4"
        @endif> --}}>
        {{$record->date}}
    </td>

    @php
            $datesDisplayed[] = $record->date;
        @endphp
    @endif
@endforeach

            </tr>
         </thead>
         <tbody>
            @foreach ($teacher->students as $student)
               <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
                  <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static hidden font-bold">{{ $student->id }}</td>
                  <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static font-bold">{{ $student->fullname }}</td>
                  @foreach($attendance as $record)
                  @if($record->student_id === $student->id)
                  {{-- <td class="px-6 py-4 whitespace-nowrap"> --}}
                     <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static font-bold">
                     @if($record->status === 'present')
                     {!! '<i class="fas fa-check text-green-500"></i>' !!}

                        @elseif($record->status === 'absent')
                        {!! '<i class="fas fa-times text-red-500"></i>' !!}

                        @elseif($record->status === 'late')
                        {!! '<i class="fas fa-clock text-yellow-500"></i>' !!}

                        @elseif($record->status === 'excused')
                        {!! '<i class="fas fa-pencil text-purple-500"></i>' !!}
                     
                     @endif
                     
                 @endif
                  </td>
                  @endforeach
               </tr>
            @endforeach
         </tbody>
      </table>
      </div>
   </div>
   @endforeach
@else
   <p>No teachers found.</p>
@endif
  </div>

  <div class="mt-6 p-4">
   {{$attendance->Links()}}
</div>

   </x-app-layout>