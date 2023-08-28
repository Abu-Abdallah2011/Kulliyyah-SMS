 <x-app-layout>

    <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           {{ __('Students') }}
       </h2>
   </x-slot>

   <x-success-status class="mb-4" :status="session('message')" />

   <div class="py-6">
 
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
       <div class="p-3 text-gray-900 dark:text-gray-100">

        @foreach ($teachers as $teacher)
                    
  <h1 class="font-bold text-center">Students: {{ $teacher->students->count() }}</h1>
                    
                    <table class="border-collapse w-full">
                        <thead>
                        <tr>
                            <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">SET</th>
                            <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">NAME</th>
                            <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">STATUS</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($teacher->students as $student)
                       
                        <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
                            <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static font-bold">{{ $student->set }}</td>
                            <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static font-bold"><a href="{{ url('/students_database/' . $student->id) }}">{{ $student->fullname }}</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                @endforeach
      
       </div>
    </div>

   </div>
 
    </x-app-layout>