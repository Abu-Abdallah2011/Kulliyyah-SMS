<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Exam Edit Form For ') . $selectedSubject->subject_id }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="table-responsive">
                <div class="p-6 bg-white border-b border-gray-200">
                    @foreach($exam as $subject)
                    <form action="/exams/{{$subject->subject_id}}" method="POST">
                        @endforeach
                        @csrf
                        @method('PUT')

                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">1st C.A</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">2nd C.A</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">3rd C.A</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach ($teacher->students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->fullname }}</div>
                                            <input type="hidden" name="student_ids[]" value="{{$student->id}}" />
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-block relative w-64">
                                                @foreach($studentExams[$student->id] as $subject)
                                                <input type="hidden" name="subjects[]" value="{{$subject->subject_id}}" />
                                                <input type="hidden" name="term" value="{{$subject->term}}" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                                                <input type="hidden" name="session" value="{{$subject->session}}" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                                                @endforeach
                                                <input name="scores[{{ $student->id }}][{{ $subject->subject_id }}][1st_ca]" value="{{ $subject->first_ca }}" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-block relative w-64">
                                                
                                                <input name="scores[{{ $student->id }}][{{ $subject->subject_id }}][2nd_ca]" value="{{ $subject->second_ca }}" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                                                
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-block relative w-64">
                                                <input name="scores[{{ $student->id }}][{{ $subject->subject_id }}][3rd_ca]" value="{{ $subject->third_ca }}" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-block relative w-64">
                                                <input name="scores[{{ $student->id }}][{{ $subject->subject_id }}][exams]" value="{{ $subject['exams'] }}" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" />
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Update
                            </button>
                        </div>
                    </form>
                    <div class="flex items-center justify-end mt-4">

                    <form method="POST" action="/exams/{{$subject->subject_id}}">
                        @csrf
                        @method('DELETE')
                        <x-danger-button onclick="return confirm('Are you sure you want to delete this record?')">
                        <i class="fa-solid fa-trash"> 
                             {{ __('Delete') }}
                             </i>
                    </x-danger-button> 
            </form>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
