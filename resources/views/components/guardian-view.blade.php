@foreach ($guardians as $guardian)
<div class="mx-4 mb-5">
    <div class="bg-white border border-gray-200 shadow-sm rounded-xl overflow-hidden">
        <div class="p-5 sm:p-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <p class="text-sm uppercase tracking-wide text-gray-500 font-semibold">Guardian Dashboard</p>
                <h2 class="text-2xl font-bold text-gray-900">
                    <a href="{{ url('/guardians_database/' . $guardian->id) }}">{{$guardian->fullname}}</a>
                </h2>
                <p class="text-sm text-gray-600 mt-1">Manage your children's school records and excuse card requests.</p>
            </div>

            <a href="{{ route('student_excuses.guardian.index') }}" class="group inline-flex items-center justify-center gap-3 bg-gray-900 text-white px-5 py-3 rounded-lg shadow hover:bg-blue-800 transition">
                <span class="inline-flex h-9 w-9 items-center justify-center rounded-full bg-white/15">
                    <i class="fas fa-id-card"></i>
                </span>
                <span class="text-left">
                    <span class="block font-bold leading-tight">Student Excuses</span>
                    <span class="block text-xs text-white/80 leading-tight">Request and view cards</span>
                </span>
            </a>
        </div>
    </div>
</div>
<div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">
    
    @foreach ($guardian->students as $student)

    <div class="bg-gray-50 border border-gray-200 rounded p-6">
        <div class="flex">
            @if ($student->photo)
            @php($photoUrl = \App\Support\PhotoUrl::resolve($student->photo))
            @if($photoUrl)
            <img class="hidden w-48 mr-6 md:block" src="{{ $photoUrl }}" alt="" />
            @endif
            @endif
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
