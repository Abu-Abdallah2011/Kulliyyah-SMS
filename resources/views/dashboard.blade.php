<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-6">

        

        @can('isAssistant')
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                
                    {{-- TEACHER DASHBOARD VIEW --}}
                    @if(!Request::is('dashboard/*'))
                    <x-teacher-view :teachers="$teachers" :class="$class" :malams="$malams"/>
                    @endif
                    {{-- SINGLE CLASS DASHBOARD VIEW FOR ADMIN --}}
                    @if(Auth::user()->can('isAdmin') && Request::is('dashboard/classes/*'))
                    <x-single-teacher-view :teacher="$teacher" :class="$class" :malams="$malams" />
                    @endif
                    
            </div>
        </div>
        @endcan
    </div>

    {{-- GUARDIAN VIEW COMPONENT --}}
    @if(!Request::is('dashboard/*'))
    <x-guardian-view :guardians="$guardians" />
    @endif


        {{-- SINGLE GUARDIAN DASHBOARD VIEW FOR ADMIN --}}
@if(Auth::user()->can('isAdmin') && Request::is('dashboard/guardians/*'))
    <x-single-guardian-view :guardian="$guardian" />
@endif

</div>
                    

                    {{-- <div class="max-w-7x mx-auto sm:px-6 lg:px-8">
                    <x-sura-select id="sura-select" class="block mt-1 w-25" type="text" name="sura-select" :value="old('sura-select')" required autofocus autocomplete="sura-select" /> --}}
                  
                

                    
               
</x-app-layout>
