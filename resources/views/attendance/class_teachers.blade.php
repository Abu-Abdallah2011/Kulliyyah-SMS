
<x-app-layout>

    <x-slot name="header">
       <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
           {{ __('Teachers') }}
       </h2>
   </x-slot>
   <div class="py-6">
 
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
       <div class="p-3 text-gray-900 dark:text-gray-100">

        @if (!is_null($class))
    <h1 class="font-bold text-center">
        NAMES OF TEACHERS IN {{ $class }}: {{$malams->count()}}
    </h1>
    <ol>
        @foreach ($malams as $teacher)
            <li> {{ $teacher->fullname }} -> {{ $teacher->set }}</li>
        @endforeach
    </ol>
@endif
      
       </div>
    </div>

   </div>
 
    </x-app-layout>