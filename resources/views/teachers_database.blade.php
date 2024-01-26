<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Teachers Database') }}
            <a href="teachers_reg_form">
            <x-primary-button class="absolute top-15 right-9 bg-green-500">
                <i class="fa-solid fa-user-plus"></i>
            </x-primary-button> 
            </a>
        </h2>
    </x-slot>
            <!-- Session Status -->
        <x-success-status class="mb-4" :status="session('message')" />
        <x-search-teachers />
        
        <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
            @unless (count($teachers) == 0)
                
            @foreach ($teachers as $teacher)
            @if ($teacher->status == "IN SCHOOL")
            <div class="bg-green-300 border border-gray-200 rounded p-6">
                {{-- @endif --}}
                @else
                <div class="bg-red-300 border border-gray-200 rounded p-6">
                    @endif
                <div class="flex">
                    <img class="hidden w-48 mr-6 md:block" src="{{ asset('storage/' . $teacher->photo) }}" alt="" />
                    
                    <div class="font-bold">
                        <h3 class="text-2xl">
                            {{$teacher->id}}
                        </h3>

                    <div class="font-bold">
                        <h3 class="text-2xl">
                            <a href="{{ url('/teachers_database/' . $teacher->id) }}">{{$teacher->fullname}}</a>
                        </h3>
                        <div class="text-xl mb-4">
                            {{$teacher->class}}
                            <div class="text-xl mb-4">
                            Born On: {{$teacher->dob}}
                                {{-- <div class="text-xl mb-4">
                                Gender: {{$teacher->gender}} --}}
                                    <div class="text-xl mb-4">
                                    Status: {{$teacher->status}}
                            <div class="text-lg mt-4">
                                <i class="fa-solid fa-location-dot"></i>
                                {{$teacher->address}}
                                <div class="text-sm mt-4">
                                    Added by: {{ $teacher->created_by }} at: {{ $teacher->created_at }}
                                     <br/>
                                    Edited by: {{ $teacher->edited_by }} at: {{ $teacher->updated_at }}
                                </div>
                            </div>
                        </div>
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
                    {{$teachers->Links()}}
                </div>
            {{-- </div>
            
        </div> --}}
    
</x-app-layout>
