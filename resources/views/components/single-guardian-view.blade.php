
<div class="text-center font-bold">
    <a href="{{ url('/guardians_database/' . $guardian->id) }}">{{$guardian->fullname}}</a>
</div>
<div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
    
    @foreach ($guardian->students as $student)

    <div class="bg-gray-50 border border-gray-200 rounded p-6">
        <div class="flex">
           @can('isAdGuardian') <img class="hidden w-48 mr-6 md:block" src="{{ asset('storage/' . $student->photo) }}" alt="" />@endcan
            <div class="font-bold">
                <h3 class="text-2xl">
                    {{$student->id}}
                </h3>
            <div class="font-bold">
                <h3 class="text-2xl">
                    <a href="{{ url('/students_database/' . $student->id) }}"> {{$student->fullname}}</a>
                </h3>
                <div class="text-xl mb-4">
                    {{$student->class}}
                    @can('isAdGuardian') 
                     <div class="text-xl mb-4">
                    Born On: {{$student->dob}}
                    @endcan
                        <div class="text-xl mb-4">
                        Gender: {{$student->gender}}
                            <div class="text-xl mb-4">
                            Status: {{$student->status}}
                            @can('isAdGuardian')
                    <div class="text-lg mt-4">
                        <i class="fa-solid fa-location-dot"></i>
                        {{$student->address}}
                        @endcan
                    </div>
                </div>
            </div>
                    </div>
        </div>

    </div>
            </div>
        </div>
    </div>
    @endforeach
    
        </div>

        