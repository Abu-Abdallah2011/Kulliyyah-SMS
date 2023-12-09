   
<div class="bg-black dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
<div class="p-6 text-gray-900 dark:text-gray-100">
   
   <h1 class="font-bold text-center text-white">{{$class}}</h1>


        <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4">


            <div class="bg-blue-500 border border-gray-200 rounded p-6">
                <div class="flex">
                <a href="{{ url('/curriculum_scale/' . $teacher->id) }}">
                {{-- <img class="w-40 mr-6 md:block" src="/images/icon.png" alt=""> --}}
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
                    <a href="{{ url('/dashboard/class_teachers/' . $teacher->id) }}">
                    {{-- <img class="w-40 mr-6 md:block" src="/images/blue_book.png" alt="Icon"> --}}
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
                <a href="{{ url('/dashboard/class_students/' . $teacher->id) }}">
                    {{-- <img class="w-40 mr-6 md:block" src="/images/book_icon.png" alt="Icon"> --}}
                    <div class="font-bold">
                        <h3 class="text-2xl">
                            Students: {{$teacher->students->count()}}
                        </h3>
                    </a>
                </div>
            </div>
        </div>
                    
                        @can('isAdmin')
                        <div class="bg-pink-500 border border-gray-200 rounded p-6">
                            <div class="flex">
                                <a href="{{ url('/teachers_database/' . $teacher->id) }}">
                                    {{-- <img class="w-40 mr-6 md:block" src="/images/prfl.png" alt="Icon"> --}}
                                    <div class="font-bold">
                                        <h3 class="text-2xl">
                                            Profile
                                        </h3>
                                    </a>
                                </div>
                            </div>
                        </div>
                        @endcan
                    
                        <div class="bg-orange-500 border border-gray-200 rounded p-6">
                            <div class="flex">
                                <a href="{{ url('/dashboard/studentsHadda/' . $teacher->id) }}">
                                    {{-- <img class="w-40 mr-6 md:block" src="/images/blue_book.png" alt="Icon"> --}}
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
                                <a href="{{ url('/dashboard/attendance/' . $teacher->id) }}">
                                    {{-- <img class="w-40 mr-6 md:block" src="/images/book_icon.png" alt="Icon"> --}}
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
                                <a href="{{ url('/dashboard/exams/' . $teacher->id) }}">
                                    {{-- <img class="w-40 mr-6 md:block" src="/images/book_icon.png" alt="Icon"> --}}
                                    <div class="font-bold">
                                        <h3 class="text-2xl">
                                            Exams
                                        </h3>
                                    </a>
                                </div>
                            </div>
                        </div>
    
            </div>
            
    </div>
    
    
    
                        
    
                    