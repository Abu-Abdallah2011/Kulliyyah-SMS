
<!-- Button to Navigate to Attendance form -->
{{-- <a href="{{ url('/attendance') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
  Take Attendance
</a> --}}


  
@if (!is_null($allteachers))
    @foreach ($allteachers as $class)
        <h1 class="font-bold">
            NAMES OF TEACHERS IN THE CLASS: {{ $class }}
        </h1>
        <ol>
            @foreach ($malams->where('class', $class) as $teacher)
                <li> {{ $teacher->fullname }} -> {{ $teacher->rank }}</li>
            @endforeach
        </ol>
    @endforeach
@endif




 @foreach ($teachers as $teacher)
                    
 <a href="{{ url('/teachers_database/' . $teacher->id) }}"> <h1 class="font-bold text-center">Students: {{ $teacher->students->count() }}</h1></a>
                    
                    <table class="border-collapse w-full">
                        <thead>
                        <tr>
                            <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">ID</th>
                            <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">NAME</th>
                            <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">STATUS</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($teacher->students as $student)
                       
                        <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
                            <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static font-bold">{{ $student->id }}</td>
                            <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static font-bold">{{ $student->fullname }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                @endforeach

                