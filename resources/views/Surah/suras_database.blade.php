<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Surah Database
            
                <a href="surasForm">
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
               <div class="text-center font-bold"> Surahs </div>
                <table class="border-collapse w-full">
                    <thead>
                    <tr>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">ID</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">CLASS</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">DELETE</th>
                    </tr>
                    
                    </thead>
                    <tbody>

                        @foreach($suras as $sura)
                        
                    <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $sura->id }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">
                            <a href="/sura/{{$sura->id}}/suraEdit">
                            {{ $sura->sura }}
                            </a>
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800  border border-b block lg:table-cell relative lg:static">
                            <form method="POST" action="/sura/{{$sura->id}}">
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
        {{$suras->Links()}}
    </div> 
    
</x-app-layout>
