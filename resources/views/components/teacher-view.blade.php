
<div class="bg-black dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
    <div class="p-6 text-gray-900 dark:text-gray-100">


<!-- Session Status -->
<x-success-status class="mb-4" :status="session('message')" />

<h1 class="font-bold text-center text-white">{{$class}}</h1>

<div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">

<div class="bg-blue-500 border border-gray-200 rounded p-6">
    <div class="flex">
            <a href="curriculum_scale">
            <div class="font-bold">
                <h3 class="text-2xl">
                    Curriculum Scale
                </h3>
        </a>
        </div>
</div>
</div>

            <div class="bg-purple-500 border border-gray-200 rounded p-6">
                <div class="flex">
                    <a href="class_teachers">
                        <div class="font-bold">
                            <h3 class="text-2xl">
                                Teachers: {{$teachers->count()}}
                            </h3>
                        </a>
                    </div>
                </div>
            </div>

                <div class="bg-green-500 border border-gray-200 rounded p-6">
                    <div class="flex">
                        <a href="class_students">
                            <div class="font-bold">
                                <h3 class="text-2xl">
                                    Students: {{$teacher->students->count()}}
                                </h3>
                            </a>
                        </div>
                    </div>
                </div>

                    <div class="bg-pink-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="{{ url('/teachers_database/' . $teacher->id) }}">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Profile
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-red-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="{{ url('/studentsHadda/' . $teacher->id) }}">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Hadda
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="attendance/show">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Attendance
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="exams/show">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Exams
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="teachersAttendance/show">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                       Teachers Attendance
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    @can('isExecutive')

                    <div class="bg-indigo-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="sets">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Sets
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-violet-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="classes_database">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Classes
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="subjects">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Subjects
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-orange-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="suras_database">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Surahs
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="graduates_database">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Graduates: {{$graduates->count()}}
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-500 border border-gray-200 rounded p-6">
                        <div class="flex">
                            <a href="{{ url('sessions/' . $session->id . '/editform') }}">
                                <div class="font-bold">
                                    <h3 class="text-2xl">
                                        Session/Term
                                    </h3>
                                </a>
                            </div>
                        </div>
                    </div>
                        
                    @endcan

        </div>
        
</div>


                    

                