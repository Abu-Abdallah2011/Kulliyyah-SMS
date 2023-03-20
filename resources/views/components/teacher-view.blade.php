 @foreach ($teachers as $teacher)
                    
                    <h1 class="font-bold text-center">{{ $teacher->class }}: {{ $teacher->students->count() }}</h1>
                    
                    <table class="border-collapse w-full">
                        <thead>
                        <tr>
                            <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">ID</th>
                            <th class="p-3 font-bold uppercase bg-gray-200 text-gray-600 border border-gray-300 hidden lg:table-cell">NAME</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($teacher->students as $student)
                       
                        <tr  class="bg-white lg:hover:bg-gray-100 flex lg:table-row flex-row lg:flex-row flex-wrap mb-10 lg:mb-0">
                            <td class="w-full lg:w-auto p-3 text-gray-800 text-center border border-b block lg:table-cell relative lg:static">{{ $student->id }}</td>
                            <td class="w-full lg:w-auto p-3 text-gray-800 border border-b block lg:table-cell relative lg:static">{{ $student->fullname }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                @endforeach