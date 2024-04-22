<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Previous Sessions Exams Record') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <h3 class="text-center font-bold">CLICK A BUTTON TO SEE THE TERMS' EXAMS DETAILS FOR YOUR CLASS</h3>
                  <br>
                                @foreach($exam->unique('term') as $PreviousTerm)
                                <div>
                                <a href="{{ url('/previousExams/' . $PreviousTerm->term . '/' . str_replace('/', '_', $PreviousTerm->session)) }}">
                               <x-primary-button> {{$PreviousTerm->term}} {{$PreviousTerm->session}} Academic Session</x-primary-button>
                            </a>
                            </div>
                            @endforeach                   
            </div>
        </div>
    </div>
</x-app-layout>
