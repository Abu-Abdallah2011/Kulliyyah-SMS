<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Classes') }}
        </h2>
    </x-slot>

    <div class="py-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">


@if (!is_null($allteachers))



<table class="border-collapse w-full">
    <thead>
    <tr>
        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">NAME</th>
        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">SET</th>
        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">DASHBOARD</th>
    </tr>
    
    </thead>
    <tbody>
        @foreach ($allteachers as $class)
        <tr
        ><td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static font-bold" colspan="3"><h1 class="font-bold">
            NAMES OF TEACHERS IN {{ $class }}:
        </h1></td>
    </tr>
        @foreach ($malams->where('class', $class) as $teacher)
    <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
        <td class="w-full lg:w-auto p-3 text-gray-800  border border-b block lg:table-cell relative lg:static">
            @can('isAdmin')<a href="{{ url('/teachers_database/' . $teacher->id) }}">@endcan
                {{ $teacher->fullname }}
            @can('isAdmin')</a>@endcan
        </td>
        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $teacher->set }}</td>
        <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static font-bold">
            <a href="{{ url('/dashboard/classes/' . $teacher->id) }}">
            <x-primary-button class="ml-3">
                    <i class="fa-solid fa-computer">{{ __('') }}</i>
            </x-primary-button>
        </a>
        </td>
    </tr>
    @endforeach
    @endforeach
</tbody>
</table>

@endif
                </div>
            </div>
    </div>

</x-app-layout>