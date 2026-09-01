 <x-app-layout>
    
   <x-slot name="header">
    <div class="flex items-center justify-between">
        <!-- Page Title -->
        <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Hadda') }}
        </h2>

        <!-- Download PDF Button -->
        <a href="{{ route('hadda_class.pdf', ['class' => $teacher->class]) }}">
            <x-primary-button class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white shadow-md transition duration-200">
                <i class="fa-solid fa-file-pdf text-white"></i>
                <span class="hidden sm:inline">Download PDF</span>
            </x-primary-button>
        </a>
    </div>
</x-slot>

   
   <x-success-status class="mb-4" :status="session('message')" />

   <div class="py-6">
 
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
       <div class="p-3 text-gray-900 dark:text-gray-100">

        {{-- @foreach ($teachers as $teacher) --}}

        <div class="mb-5 overflow-hidden rounded-2xl border border-emerald-200 bg-gradient-to-r from-emerald-50 via-white to-cyan-50 shadow-sm">
            <div class="flex flex-col gap-4 p-5 md:flex-row md:items-center md:justify-between">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-emerald-700">Current Class Hadda Rank</p>
                    <h3 class="mt-1 text-2xl font-black text-gray-900">{{ $teacher->class }}</h3>
                    <p class="text-sm text-gray-600">{{ $rankingTerm ?? 'Current Term' }}{{ !empty($rankingSession) ? ' - '.$rankingSession : '' }}</p>
                </div>

                @if($classRank)
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                        <div class="rounded-xl bg-white/80 p-3 text-center shadow-sm">
                            <p class="text-xs font-semibold uppercase text-gray-500">Rank</p>
                            <p class="text-2xl font-black text-emerald-700">#{{ $classRank['rank'] }}</p>
                        </div>
                        <div class="rounded-xl bg-white/80 p-3 text-center shadow-sm">
                            <p class="text-xs font-semibold uppercase text-gray-500">Avg Points</p>
                            <p class="text-2xl font-black text-gray-900">{{ number_format((float) $classRank['average_points_per_student'], 1) }}</p>
                        </div>
                        <div class="rounded-xl bg-white/80 p-3 text-center shadow-sm">
                            <p class="text-xs font-semibold uppercase text-gray-500">Total Pages</p>
                            <p class="text-2xl font-black text-gray-900">{{ $classRank['confirmed_pages'] }}</p>
                        </div>
                        <div class="rounded-xl bg-white/80 p-3 text-center shadow-sm">
                            <p class="text-xs font-semibold uppercase text-gray-500">Students</p>
                            <p class="text-2xl font-black text-gray-900">{{ $classRank['student_count'] }}</p>
                        </div>
                    </div>
                @else
                    <div class="rounded-xl bg-white/80 p-4 text-sm font-semibold text-gray-500 shadow-sm">
                        No class ranking yet for this term.
                    </div>
                @endif
            </div>

            @if($classRank)
                <div class="border-t border-emerald-100 px-5 py-3 text-sm text-gray-600">
                    @if($classRank['gap_to_above'] !== null)
                        {{ $classRank['gap_to_above'] }} avg point(s) behind {{ $classRank['above_name'] ?? 'the class above' }}.
                    @else
                        Leading the class leaderboard.
                    @endif

                    @if($classRank['gap_to_below'] !== null)
                        {{ $classRank['gap_to_below'] }} avg point(s) ahead of {{ $classRank['below_name'] ?? 'the class below' }}.
                    @endif
                </div>
            @endif
        </div>

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
