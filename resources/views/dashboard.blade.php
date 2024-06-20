<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">

    {{-- SINGLE CLASS DASHBOARD VIEW FOR EXECUTIVE --}}
    @if(Auth::user()->can('isExecutive') && Request::is('dashboard/classes/*'))
    <x-single-teacher-view :teacher="$teacher" :class="$class" :teachers="$teachers" />
    @endif

    {{-- TEACHER DASHBOARD VIEW --}}
    @if(Auth::user()->can('isAssistant') && !Request::is('dashboard/*'))
    <x-teacher-view :teacher="$teacher" :class="$class" :teachers="$teachers" 
    :graduates="$graduates" :session="$session" :totalpaid="$totalpaid" :totalfree="$totalfree"
    :totalnotpaid="$totalnotpaid" :totalpart="$totalpart" :percentagepaid="$percentagepaid" 
    :percentagefree="$percentagefree" :percentagenotpaid="$percentagenotpaid" :percentagepart="$percentagepart"
    :presentpercentage="$presentpercentage" :absentpercentage="$absentpercentage" :excusedpercentage="$excusedpercentage"
    :latepercentage="$latepercentage" :presentpercentageforteachers="$presentpercentageforteachers"
    :absentpercentageforteachers="$absentpercentageforteachers" :excusedpercentageforteachers="$excusedpercentageforteachers"
    :latepercentageforteachers="$latepercentageforteachers" :percentageexcusedlate="$percentageexcusedlate"
    :percentageclosedearly="$percentageclosedearly" :percentagelatecominandearlyclose="$percentagelatecominandearlyclose"
    :totalteachers="$totalteachers" :totalmaleteachers="$totalmaleteachers" :totalfemaleteachers="$totalfemaleteachers"
    :totalstudents="$totalstudents" :totalmalestudents="$totalmalestudents" :totalfemalestudents="$totalfemalestudents"
    :percentageclasspresent="$percentageclasspresent" :percentageclasslate="$percentageclasslate" :percentageclassexcused="$percentageclassexcused"
    :percentageclassabsent="$percentageclassabsent" />
    @endif

        @can('isAssistant')
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    
            </div>
        </div>
        @endcan
    </div>

    {{-- GUARDIAN VIEW COMPONENT --}}
    @if(!Request::is('dashboard/*'))
    <x-guardian-view :guardians="$guardians" />
    @endif


        {{-- SINGLE GUARDIAN DASHBOARD VIEW FOR EXCO --}}
@if(Auth::user()->can('isExecutive') && Request::is('dashboard/guardians/*'))
    <x-single-guardian-view :guardian="$guardian" />
@endif

</div>              
               
</x-app-layout>
