{{-- <x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Hadda Records
            
                @can('isAssistant')
                <a href="{{url('hadda_page/' . $student->id . '/HaddaForm')}}">
                <x-primary-button class="absolute top-15 right-9 bg-green-500">
                    <i class="fa-solid fa-plus"> </i>
                </x-primary-button>
            </a> 
                @endcan
                
        </h2>
    </x-slot>
        
          <x-search-hadda :student="$student" />
       <x-success-status class="mb-4" :status="session('message')" />
    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
               <div class="text-center font-bold"> {{ $student->fullname }}: {{$student->class}} </div>
                <table class="border-collapse w-full">
                    <thead>
                    <tr>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">DATE</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TERM</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">SESSION</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">FROM SURAH</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">FROM</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TO SURAH</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TO</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">SCORE</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">GRADE</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TEACHER</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">COMMENT</th>
                        @can('isAssistant')
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">DELETE</th>
                        @endcan
                    </tr>
                    
                    </thead>
                    <tbody>

                        @foreach($hadda as $item)
                        
                    <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">
                            @can('isAssistant')
                            <a href="/hadda_page/{{$item->id}}/edit_hadda">
                                @endcan
                                {{ $item->date }}
                           @can('isAssistant') 
                        </a>
                        @endcan
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $item->term }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $item->session }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $item->sura }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $item->from }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $item->to_surah }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $item->to }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $item->score }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $item->grade }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $item->teacher }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800  border border-b block lg:table-cell relative lg:static">{{ $item->comment }}</td>
                        @can('isAssistant')
                        <td class="w-full lg:w-auto p-3 text-gray-800  border border-b block lg:table-cell relative lg:static">
                            <form method="POST" action="/hadda_page/{{$item->id}}">
                                @csrf
                                @method('DELETE')
                                <x-danger-button onclick="return confirm('Are you sure you want to delete this record?')">
                                <i class="fa-solid fa-trash"> 
                                     {{ __('') }}
                                     </i>
                            </x-danger-button> 
                    </form> 
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
                </table>
                


            </div>
        </div>
    </div>

    <div class="mt-6 p-4">
        {{$hadda->Links()}}
    </div> 
    
</x-app-layout> --}}


<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Hadda Records
            </h2>

            @can('isAssistant')
            <a href="{{ url('hadda_page/' . $student->id . '/HaddaForm') }}">
                <x-primary-button class="bg-green-500 hover:bg-green-700 flex items-center gap-2">
                    <i class="fa-solid fa-plus"></i>
                    <span>Add Record</span>
                </x-primary-button>
            </a>
            @endcan
        </div>
    </x-slot>

    <div class="py-4 space-y-4">
        <x-search-hadda :student="$student" />
        <x-success-status class="mb-4" :status="session('message')" />

        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg overflow-hidden">
            <div class="p-4 md:p-6 text-gray-900 dark:text-gray-100">
                <div class="text-center font-bold text-lg mb-4">
                    {{ $student->fullname }} — {{ $student->class }}
                </div>

                <!-- Responsive table wrapper -->
                <div class="overflow-x-auto">
                    <table class="min-w-full border-collapse text-sm">
                        <thead class="bg-gray-200 dark:bg-gray-700 sticky top-0">
                            <tr>
                                @foreach ([
                                    'Date', 'Term', 'Session', 'From Surah', 'Verse', 
                                    'To Surah', 'Verse', 'Score', 'Grade', 'Teacher', 'Comment'
                                ] as $col)
                                    <th class="p-3 font-semibold uppercase text-gray-600 dark:text-gray-300 border border-gray-300 text-left hidden lg:table-cell">
                                        {{ $col }}
                                    </th>
                                @endforeach
                                @can('isAssistant')
                                    <th class="p-3 font-semibold uppercase text-gray-600 dark:text-gray-300 border border-gray-300 text-left hidden lg:table-cell">
                                        Delete
                                    </th>
                                @endcan
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($hadda as $item)
                                <tr class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition flex flex-col lg:table-row mb-6 lg:mb-0 border-b lg:border-0 rounded-lg lg:rounded-none shadow-sm lg:shadow-none">
                                    
                                    <!-- Date -->
                                    <td class="p-3 border-b lg:border border-gray-200 dark:border-gray-700">
                                        @can('isAssistant')
                                            <a href="{{ url('/hadda_page/' . $item->id . '/edit_hadda') }}" class="text-indigo-600 hover:underline">
                                                {{ $item->date }}
                                            </a>
                                        @else
                                            {{ $item->date }}
                                        @endcan
                                    </td>

                                    <!-- Term -->
                                    <td class="p-3 border-b lg:border">{{ $item->term }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->session }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->sura }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->from }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->to_surah }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->to }}</td>
                                    <td class="p-3 border-b lg:border font-bold">{{ $item->score }}</td>
                                    <td class="p-3 border-b lg:border text-green-600 dark:text-green-400 font-semibold">{{ $item->grade }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->teacher }}</td>
                                    <td class="p-3 border-b lg:border">{{ $item->comment }}</td>

                                    @can('isAssistant')
                                    <td class="p-3 border-b lg:border">
                                        <form method="POST" action="{{ url('/hadda_page/' . $item->id) }}">
                                            @csrf
                                            @method('DELETE')
                                            <x-danger-button 
                                                onclick="return confirm('Are you sure you want to delete this record?')"
                                                class="bg-red-600 hover:bg-red-700"
                                            >
                                                <i class="fa-solid fa-trash"></i>
                                            </x-danger-button>
                                        </form>
                                    </td>
                                    @endcan
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="12" class="text-center py-4 text-gray-500 dark:text-gray-400">
                                        No Hadda records found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $hadda->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Floating add button for mobile -->
    @can('isAssistant')
    <a href="{{ url('hadda_page/' . $student->id . '/HaddaForm') }}" 
       class="lg:hidden fixed bottom-6 right-6 bg-green-500 hover:bg-green-700 text-white rounded-full p-4 shadow-lg">
        <i class="fa-solid fa-plus"></i>
    </a>
    @endcan
</x-app-layout>
