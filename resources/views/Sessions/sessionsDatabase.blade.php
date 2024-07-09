<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Sessions Database
            
                <a href="sessionsForm">
                <x-primary-button class="absolute top-15 right-9 bg-green-500">
                    <i class="fa-solid fa-plus"> </i>
                </x-primary-button>
            </a> 
                
        </h2>
    </x-slot>
        
          {{-- <x-search-hadda :student="$student" /> --}}
       <x-success-status class="mb-4" :status="session('message')" />
    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">
               <div class="text-center font-bold"> Sessions </div>
                <table class="border-collapse w-full">
                    <thead>
                    <tr>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">ID</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">SESSION</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TERM</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TERM STARTS</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TERM ENDS</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">NEXT TERM STARTS</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">DELETE</th>
                    </tr>
                    
                    </thead>
                    <tbody>

                        @foreach($sessions as $session)
                        
                    <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $session->id }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">
                            <a href="/sessions/{{$session->id}}/editform">
                            {{ $session->session }}
                            </a>
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $session->term }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $session->term_starts }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $session->term_ends }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $session->next_term_starts }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800  border border-b block lg:table-cell relative lg:static">
                            <form method="POST" action="/session/{{$session->id}}">
                                @csrf
                                @method('DELETE')
                                <x-danger-button onclick="return confirm('Are you sure you want to delete this record?')">
                                <i class="fa-solid fa-trash"> 
                                     {{ __('') }}
                                     </i>
                            </x-danger-button> 
                    </form> 
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                </table>
                


            </div>
        </div>
    </div>

    <div class="mt-6 p-4">
        {{$sessions->Links()}}
    </div> 
    
</x-app-layout>
