<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Attendance Edit Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="table-responsive">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="/attendance/{{$date}}" method="POST">
                        @csrf
                        @method('PUT')

                        <label for="date">Date: </label>
                        <x-date-picker class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{ $date }}" placeholder="Input Date..."/>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Name</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Status</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zango</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($teachers as $teacher)
                                @foreach ($teacher->students as $student)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $student->fullname }}</div>
                                        </td>

                                        @php
                             $statusesForDate = $attendance->where('date', $date)
                                                         ->where('student_id', $student->id)
                                                         ->pluck('status')
                                                         ->toArray();
                            $TimeForDate = $attendance->where('date', $date)
                                                         ->where('student_id', $student->id)
                                                         ->pluck('time')
                                                         ->toArray();
                                            @endphp

                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-block relative w-64">
                                                @foreach ($statusesForDate as $index => $status)
                                                <select name="attendance[{{ $student->id }}][{{ $TimeForDate[$index] }}]" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                                    <option>{{ucfirst($status)}}</option>
                                                    <option value="present">Present</option>
                                                    <option value="late">Late</option>
                                                    <option value="excused">Excused</option>
                                                    <option value="absent">Absent</option>
                                                </select>
                                                <input type="hidden" name="student_ids[]" value="{{$student->id}}">
                                                @endforeach
                                            </div>
                                        </td>
                                        <td>
                                            <div class="inline-block relative w-64">
                                            @foreach($TimeForDate as $time)
                                            <select name="time[]" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                                <option value="">{{ $time }}</option>
                                                <option value="Safiya">Safiya</option>
                                                <option value="Bayan Break">Bayan Break</option>
                                                <option value="Da Rana">Da Rana</option>
                                                <option value="Yamma">Yamma</option>
                                            </select>
                                            @endforeach
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
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
                    <form method="POST" action="/attendance/{{$date}}">
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
