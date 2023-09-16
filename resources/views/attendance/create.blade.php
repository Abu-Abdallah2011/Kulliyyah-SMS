<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="table-responsive">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ url('/attendance') }}" method="POST">
                        @csrf
                        <div class="flex">
                        <label for="date">Date: </label>
                        <x-date-picker class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" placeholder="Input Date..."/>
                        <div class="relative w-64 flex ml-4">
                        <label for="zango">Zango: </label>
                        <select name="time" class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                            <option>Safiya</option>
                            <option>Bayan Break</option>
                            <option>Da Rana</option>
                            <option>Yamma</option>
                        </select>
                        </div>
                    </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Status</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($teachers as $teacher)
                                @foreach ($teacher->students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->fullname }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-block relative w-64">
                                                <select name="attendance[]" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                                    <option value="present">Present</option>
                                                    <option value="late">Late</option>
                                                    <option value="excused">Excused</option>
                                                    <option value="absent">Absent</option>
                                                </select>
                                            </div>
                                        </td>
                                            <input type="hidden" name="student_ids[]" class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{$student->id}}">
                                    </tr>
                                @endforeach
                                @endforeach
                            </tbody>
                        </table>
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Submit
                            </button>
                        </div>
                    </form>
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
