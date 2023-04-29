<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Curriculum Scale') }}
            {{-- <a href="curriculum_form"> --}}
                @can('isAssistant')
                <x-primary-button class="absolute top-15 right-9 bg-green-500" data-bs-toggle="modal" data-bs-target="#UpdateQuran" data-bs-whatever="Quran">
                    <i class="fa-solid fa-plus"> </i>
                </x-primary-button> 
                @endcan
                {{-- </a> --}}
        </h2>
    </x-slot>
          
    {{-- <button type="button" class="btn editScore btn-primary" data-bs-toggle="modal" data-bs-target="#UpdateQuran" data-bs-whatever="Quran" subjectID ="2" ><i class="bi bi-pencil-square">kjn</i></button>  --}}
    <x-modal-window />
    <x-search-curriculum />
    <x-success-status class="mb-4" :status="session('message')" />
    <div class="py-6">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-100">

                {{-- <table class="border-collapse w-full">
                    @php
                        $prevSession = '';
                        $prevTerm = '';
                    @endphp
                    
                            <tr>
                                @foreach($sets as $set)
                        @if ($set->session !== $prevSession)
                                <td class="w-full font-bold lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">SESSION:</td>
                                <td class="w-full font-bold lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">
                                    @foreach($sessions as $session)
                                    @can('isExecutive')
                                    <a href="/sessions/{{$session->id}}/editform">
                                        @endcan
                                    {{ $set->session }} 
                                    @can('isExecutive')
                                </a>
                                @endcan
                                @endforeach
                                </td>
                            
                            @php $prevSession = $set->session; @endphp
                        @endif
                        @if ($set->term !== $prevTerm)
                            
                                <td class="w-full font-bold lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">TERM:</td>
                                <td class="w-full font-bold lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $set->term }}</td>
                            
                            @php $prevTerm = $set->term; @endphp
                        @endif
                        @endforeach
                            <td class="w-full font-bold lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">ENTRIES:</td>
                            <td class="w-full font-bold lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $sets->count() }}</td>
                        </tr>
                    
                </table> --}}
                

                <table class="border-collapse w-full">
                    <thead>
                    <tr>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">DATE</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">CLASS</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">SET</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">SURAH</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">FROM</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TO</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TIMES</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">BITA</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">GRADE</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">HADDA</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">TEACHER</th>
                        <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">COMMENT</th>
                        {{-- <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">DELETE</th> --}}
                    </tr>
                    
                    </thead>
                    <tbody>
                        @foreach ($sets as $curriculum)
                        
                    <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">
                            @can('isAssistant')
                            <a href="/curriculum_scale/{{$curriculum->id}}/edit_curriculum">
                                @endcan
                                {{ $curriculum->date }}
                            @can('isAssistant')</a>@endcan
                        </td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->class }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->set }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->sura }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->from }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->to }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->times }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->bita }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->grade }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->hadda }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $curriculum->teacher }}</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800  border border-b block lg:table-cell relative lg:static">{{ $curriculum->comment }}</td>
                        {{-- <td class="w-full lg:w-auto p-3 text-gray-800  border border-b block lg:table-cell relative lg:static">
                            <form method="POST" action="/curriculum_scale/{{$curriculum->id}}">
                                @csrf
                                @method('DELETE')
                                <x-primary-button class="ml-3 bg-red-500">
                                <i class="fa-solid fa-trash"> 
                                     {{ __('') }}
                                     </i>
                            </x-primary-button> 
                    </form> 
                        </td> --}}
                    </tr>
                    {{-- <x-edit-curriculum-modal :curriculum="$curriculum"/> --}}
                    @endforeach
                </tbody>
                </table>
                


            </div>
        </div>
    </div>

    <div class="mt-6 p-4">
        {{$sets->Links()}}
    </div> 
    
</x-app-layout>
