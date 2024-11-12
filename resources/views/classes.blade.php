{{-- <x-app-layout>

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

</x-app-layout> --}}

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
                
                    <!-- Filters Section (Optional) -->
                    {{-- <div class="flex justify-between mb-4">
                        <div class="flex space-x-4">
                            <button class="bg-blue-500 text-white py-2 px-4 rounded-md hover:bg-blue-700 transition duration-200">Filter by Set</button>
                            <button class="bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-700 transition duration-200">Sort by Name</button>
                        </div>
                        <div>
                            <input type="search" class="px-4 py-2 border border-gray-300 rounded-md" placeholder="Search teachers...">
                        </div>
                    </div> --}}

                    <!-- Teacher Cards Section -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                        @foreach ($allteachers as $class)
                            <div class="bg-gray-50 rounded-lg shadow-lg p-6 mb-6">
                                <h3 class="text-xl font-semibold text-gray-800 text-center mb-4">
                                    <span class="text-blue-600">NAMES OF TEACHERS IN</span> {{ strtoupper($class) }}:
                                </h3>

                                @foreach ($malams->where('class', $class) as $teacher)
                                    <div class="bg-white rounded-lg shadow-md p-4 hover:shadow-xl transition-shadow duration-300">
                                        <div class="flex items-center space-x-4">
                                            <!-- Teacher Profile Pic (Optional) -->
                                            <div class="w-12 h-12 bg-gray-300 rounded-full flex items-center justify-center">
                                                <i class="fa-solid fa-user text-2xl text-white"></i> <!-- Placeholder for Profile Pic -->
                                            </div>
                                            <div class="flex-1">
                                                <h4 class="font-semibold text-lg text-gray-700">
                                                    @can('isAdmin')
                                                        <a href="{{ url('/teachers_database/' . $teacher->id) }}" class="text-blue-600 hover:underline">
                                                    @endcan
                                                        {{ $teacher->fullname }}
                                                    @can('isAdmin')
                                                        </a>
                                                    @endcan
                                                </h4>
                                                <p class="text-gray-600">Set: {{ $teacher->set }}</p>
                                            </div>
                                        </div>

                                        <div class="text-center mt-4">
                                            <a href="{{ url('/dashboard/classes/' . $teacher->id) }}">
                                                <x-primary-button class="bg-blue-500 hover:bg-blue-700 text-white py-2 px-4 rounded-md">
                                                    <i class="fa-solid fa-computer mr-2"></i> View Dashboard
                                                </x-primary-button>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    </div>

                @else
                    <div class="text-center text-gray-600 font-semibold">
                        <p>No teachers available for the selected classes.</p>
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>
