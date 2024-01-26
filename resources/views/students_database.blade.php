<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Students Database') }}
            @can('isAdmin')
            <a href="students_registration_form">
                <x-primary-button class="absolute top-15 right-9 bg-green-500">
                    <i class="fa-solid fa-user-plus"></i>
                </x-primary-button> 
                </a>
                @endcan
        </h2>
    </x-slot>
            <!-- Session Status -->
        <x-success-status class="mb-4" :status="session('message')" />
        <x-search />
        
        <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
            @unless (count($students) == 0)
                
            @foreach ($students as $student)

            @if ($student->status == 'IN SCHOOL')
            <div class="bg-green-300 border border-gray-200 rounded p-6">
                @elseif ($student->status == 'GRADUATE')
                <div class="bg-blue-300 border border-gray-200 rounded p-6">
                    @else
                    <div class="bg-red-300 border border-gray-200 rounded p-6">
                        @endif
                <div class="flex">
                    @can('isAdmin')
                    <img class="hidden w-48 mr-6 md:block" src="{{asset('storage/' . $student->photo) }}" alt="" />
                    @endcan

                    <div class="font-bold">
                        <h3 class="text-2xl">
                            {{$student->id}}
                        </h3>
                    
                    <div class="font-bold">
                        <h3 class="text-2xl">
                            <a href="{{ url('/students_database/' . $student->id) }}">{{$student->fullname}}</a>
                        </h3>
                        <div class="text-xl mb-4">
                            {{$student->class}}
                            @can('isAdmin')
                            <div class="text-xl mb-4">
                            Born On: {{$student->dob}}
                                @endcan
                                {{-- <div class="text-xl mb-4">
                                Gender: {{$student->gender}} --}}
                                    <div class="text-xl mb-4">
                                    Status: {{$student->status}}
                                    @can('isAdmin')
                            <div class="text-lg mt-4">
                                <i class="fa-solid fa-location-dot"></i>
                                {{$student->address}}
                                @endcan
                                <div class="text-sm mt-4">
                                    Added by: {{ $student->created_by }} at: {{ $student->created_at }}
                                     <br/>
                                    Edited by: {{ $student->edited_by }} at: {{ $student->updated_at }}
                                </div>
                                @can('isAdmin')
                            </div>
                        </div>
                        @endcan
                    </div>
                            {{-- </div> --}}
                </div>

            </div>
                    </div>
                </div>
            </div>

            @endforeach
            @endunless
                </div>
                

                <div class="mt-6 p-4">
                    {{$students->Links()}}
                </div>
            {{-- </div>
            
        </div> --}}
    
</x-app-layout>
