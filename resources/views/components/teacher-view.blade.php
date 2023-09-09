<!-- Session Status -->
<x-success-status class="mb-4" :status="session('message')" />

<h1 class="font-bold text-center">{{$class}}</h1>
<div class="flex flex-wrap justify-center">

        <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
            <a href="curriculum_scale">
            <img src="/images/icon.png" alt="" class="">
            <span class="text-base">Curriculum Scale</span>
        </a>
        </div>
        
            <div class="h-30 w-25 bg-rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                <a href="class_teachers">
                <img src="/images/blue_book.png" alt="Icon" class="">
                <span class="text-base">Teachers: {{$teachers->count()}}</span>
                </a>
            </div>
            

                <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                    <a href="class_students">
                    <img src="/images/book_icon.png" alt="Icon" class="">
                    <span class="text">Students: {{$teacher->students->count()}} </span>
                </a>
                </div>

                    <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="{{ url('/teachers_database/' . $teacher->id) }}">
                        <img src="/images/icon.png" alt="Icon" class="">
                        <span class="text-base">Profile</span>
                    </a>
                    </div>
                
                    <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="studentsHadda">
                        <img src="/images/blue_book.png" alt="Icon" class="">
                        <span class="text-base">Hadda</span>
                    </a>
                    </div>

                    <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="attendance/show">
                        <img src="/images/book_icon.png" alt="Icon" class="">
                        <span class="text-base ">Attendance</span>
                    </a>
                    </div>

                    @can('isExecutive')

                        <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="sets">
                        <img src="/images/book_icon.png" alt="Icon" class="">
                        <span class="text-base ">Sets</span>
                    </a>
                    </div>

                    <div class="h-30 w-25 bg-rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="classes_database">
                        <img src="/images/blue_book.png" alt="Icon" class="">
                        <span class="text-base">Classes</span>
                        </a>
                    </div>

                    {{-- <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="subjects">
                        <img src="/images/book_icon.png" alt="Icon" class="">
                        <span class="text-base ">Subjects</span>
                    </a>
                    </div> --}}

                    <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="suras_database">
                        <img src="/images/icon.png" alt="Icon" class="">
                        <span class="text-base">Surahs</span>
                    </a>
                    </div>

                    <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="graduates_database">
                        <img src="/images/book_icon.png" alt="Icon" class="">
                        <span class="text-base ">Graduates: {{$graduates->count()}}</span>
                    </a>
                    </div>

                    <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="{{ url('sessions/' . $session->id . '/editform') }}">
                        <img src="/images/book_icon.png" alt="Icon" class="">
                        <span class="text-base ">Session/Term</span>
                    </a>
                    </div>
                        
                    @endcan

        </div>
        
</div>


                    

                