@foreach ($guardians as $guardian)
    <div class="text-center font-bold">
    <a href="{{ url('/guardians_database/' . $guardian->id) }}">{{$guardian->fullname}}</a>
</div>
<div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
    
    @foreach ($guardian->students as $student)

    <div class="bg-gray-50 border border-gray-200 rounded p-6">
        <div class="flex">
            <img class="hidden w-48 mr-6 md:block" src="{{ asset('storage/' . $student->photo) }}" alt="" />
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
                    <div class="text-xl mb-4">
                    Born On: {{$student->dob}}
                        <div class="text-xl mb-4">
                        Gender: {{$student->gender}}
                            <div class="text-xl mb-4">
                            Status: {{$student->status}}
                    <div class="text-lg mt-4">
                        <i class="fa-solid fa-location-dot"></i>
                        {{$student->address}}
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

        @endforeach