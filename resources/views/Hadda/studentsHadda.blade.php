 <x-app-layout>

    <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           {{ __('Hadda Conduit') }}
       </h2>
   </x-slot>
   
   <x-success-status class="mb-4" :status="session('message')" />

   <div class="py-6">
 
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
       <div class="p-3 text-gray-900 dark:text-gray-100">

        {{-- @foreach ($teachers as $teacher) --}}
                    
  <h1 class="font-bold text-center">Students: {{ $teacher->students->count() }}</h1>
                    <div class="table-responsive">
                    <table class="border-collapse w-full">
                        <thead>
                        <tr>
                            <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 lg:table-cell">SET</th>
                            <th class="p-2 md:p-3 font-bold uppercase text-gray-600 border border-gray-300 lg:table-cell">NAME</th>
                            @php
                            $datesDisplayed = [];
                        @endphp
                        @foreach($hadda as $record)
            
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
                       
                        <tr  class="bg-white lg:hover:bg-gray-100 lg:table-row mb-10 lg:mb-0">
                            <td class="w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">{{ $student->set }}</td>
                            <td class="w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold"><a href="{{ url('/hadda_page/' . $student->id) }}">{{ $student->fullname }}</a></td>
                            @foreach($datesDisplayed as $date)
                            @php
                            $statusForDate = $hadda->where('date', $date)
                                                        ->where('student_id', $student->id)
                                                        ->first();
                        @endphp

                            <td class="text-center w-full lg:w-auto p-2 md:p-3 lg:p-3 text-gray-800 border border-b lg:table-cell relative lg:static font-bold">                           
                              
                              @if($statusForDate)
                              <i class="fas fa-check text-green-500"></i>
                              @else
                              <i class="fas fa-times text-red-500"></i>
                              @endif
                                 
                         </td>
                         @endforeach
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
      
       </div>
    </div>

   </div>

   <div class="mt-6 p-4">
    {{$hadda->Links()}}
 </div>
 
    </x-app-layout>