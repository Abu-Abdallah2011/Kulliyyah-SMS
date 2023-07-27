    <h1 class="font-bold text-center">{{$class}}</h1>
    <div class="flex flex-wrap justify-center">
            <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                <a href="{{ url('/curriculum_scale/guardianview/' . $teacher->id) }}">
                <img src="/images/icon.png" alt="" class="">
                <span class="text-base">Curriculum Scale</span>
            </a>
            </div>
    
                <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                    <a href="{{ url('/dashboard/class_teachers/' . $teacher->id) }}">
                    <img src="/images/blue_book.png" alt="Icon" class="">
                    <span class="text-base">Teachers: {{$teachers->count()}}</span>
                    </a>
                </div>
    
                    <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                        <a href="{{ url('/dashboard/class_students/' . $teacher->id) }}">
                        <img src="/images/book_icon.png" alt="Icon" class="">
                        <span class="text">Students: {{$teacher->students->count()}}</span>
                    </a>
                    </div>
                    
    
                        @can('isAdmin')
                        <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                            <a href="{{ url('/teachers_database/' . $teacher->id) }}">
                            <img src="/images/icon.png" alt="Icon" class="">
                            <span class="text-base">Profile</span>
                        </a>
                        </div>
                        @endcan
                    
                        <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                            <a href="{{ url('/dashboard/studentsHadda/' . $teacher->id) }}">
                            <img src="/images/blue_book.png" alt="Icon" class="">
                            <span class="text-base">Hadda</span>
                        </a>
                        </div>
    
                        {{-- <div class="h-30 w-25 bg- rounded-lg overflow-hidden border border-grey-500 flex flex-col items-center m-4">
                            <a href="{{ url('/dashboard/attendance/' . $teacher->id) }}">
                            <img src="/images/book_icon.png" alt="Icon" class="">
                            <span class="text-base">Attendance</span>
                        </a>
                        </div> --}}
    
            </div>
            
    </div>
    
    
                        
    
                    