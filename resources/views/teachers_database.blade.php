
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

    <!-- Teachers Grid -->
    <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
        @unless (count($teachers) == 0)
            @foreach ($teachers as $teacher)
                <div class="bg-white shadow-xl rounded-lg border border-gray-200 overflow-hidden">
                    <div class="flex p-6">
                        <!-- Teacher Image -->
                        <div class="flex-shrink-0 mr-6">
                            @php($photoUrl = \App\Support\PhotoUrl::resolve($teacher->photo))
                    @if($photoUrl)
                    <img class="hidden w-48 mr-6 md:block" src="{{ $photoUrl }}" alt="" />
                    @endif
                        </div>

                        <!-- Teacher Info -->
                        <div class="flex-1">
                            <div class="space-y-2">
                                <h3 class="text-2xl font-bold text-gray-800">
                                    <a href="{{ url('/teachers_database/' . $teacher->id) }}" class="hover:text-green-600">
                                        {{$teacher->fullname}}
                                    </a>
                                </h3>
                                <p class="text-lg text-gray-600">ID: {{$teacher->id}}</p>

                                <div class="text-md text-gray-700">
                                    <p class="mb-1">Class: {{$teacher->class}}</p>
                                    <p class="mb-1">Born On: {{$teacher->dob}}</p>
                                    <p class="mb-1">Status: <span class="font-semibold {{$teacher->status == 'IN SCHOOL' ? 'text-green-600' : 'text-red-600'}}">{{$teacher->status}}</span></p>
                                </div>
                                
                                <div class="mt-4 text-lg text-gray-600 flex items-center">
                                    <i class="fa-solid fa-location-dot mr-2 text-indigo-600"></i> 
                                    {{$teacher->address}}
                                </div>

                                <div class="mt-4 text-sm text-gray-500">
                                    <p>Added by: <span class="font-medium">{{ $teacher->created_by ?? 'N/A' }}</span> at {{ $teacher->created_at ? $teacher->created_at->format('M d, Y H:i') : 'N/A' }}</p>
                                    @if(!empty($teacher->edited_by))
                                    <p>Edited by: <span class="font-medium">{{ $teacher->edited_by }}</span> at {{ $teacher->updated_at ? $teacher->updated_at->format('M d, Y H:i') : 'N/A' }}</p>
                                    @else
                                    <p>Edited by: <span class="font-medium">Not edited yet</span></p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <p class="text-center text-gray-600 col-span-2">No teachers found.</p>
        @endunless
    </div>

    <!-- Pagination -->
    <div class="mt-6 p-4">
        {{$teachers->links()}}
    </div>
</x-app-layout>

