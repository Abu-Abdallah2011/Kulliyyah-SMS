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
                     @if ($record = $student->attendance()->where('date', $date)->first())
                     @switch($record->status)
                         @case('present')
                             {!! '<i class="fas fa-check text-green-500"></i>' !!}
                             @break
                         @case('absent')
                             {!! '<i class="fas fa-times text-red-500"></i>' !!}
                             @break
                         @case('late')
                             {!! '<i class="fas fa-clock text-orange-500"></i>' !!}
                             @break
                         @case('excused')
                             {!! '<i class="fas fa-question-circle text-purple-500"></i>' !!}
                             @break
                     @endswitch
                 @endif
                  </td>
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

   </x-app-layout>