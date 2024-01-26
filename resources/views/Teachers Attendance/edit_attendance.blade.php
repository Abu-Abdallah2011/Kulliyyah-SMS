<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Teachers Attendance Edit Form') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="table-responsive">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="/teachersAttendance/{{$date}}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="flex">
                        <label for="date">Date: </label>
                        @if (Auth::user()->can('isExecutive'))
                        <x-date-picker class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{ $date }}" placeholder="Input Date..."/>
                        @endif

                        @if (!Auth::user()->can('isExecutive'))
                        <x-date-picker class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{ $date }}" placeholder="Input Date..." disabled />
                        @endif

                    </div>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Teacher Name</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time In</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time Out</th>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Zango</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($teachers as $teacher)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $teacher->fullname }}</div>
                                        </td>

                                        @php
                                        $statusesForDate = $attendance->where('date', $date)->where('teacher_id', $teacher->id)->pluck('status')->toArray();
                                       $TimeForDate = $attendance->where('date', $date)->where('teacher_id', $teacher->id)->pluck('time')->toArray();
                                        $TimeInForDate = $attendance->where('date', $date)->where('teacher_id', $teacher->id)->pluck('time_in')->toArray();
                                        $TimeOutForDate = $attendance->where('date', $date)->where('teacher_id', $teacher->id)->pluck('time_out')->toArray();
                                        @endphp


                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-block relative w-64">
                                                @foreach ($statusesForDate as $index => $status)

                                                @if (Auth::user()->can('isExecutive'))
                                                <select name="attendance[{{ $teacher->id }}][{{ $TimeForDate[$index] }}]" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                                    @endif

                                                    @if (!Auth::user()->can('isExecutive'))
                                                    <select name="" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" disabled>
                                                        @endif

                                                    <option>{{ucfirst($status)}}</option>
                                                    <option value="absent">Absent</option>
                                                    <option value="present">Present</option>
                                                    <option value="late">Late</option>
                                                    <option value="late with an excuse">Late With An Excuse</option>
                                                    <option value="excused">Excused</option>
                                                    <option value="closed early">Closed Early</option>
                                                    <option value="came late and closed early">Came Late And Closed Early</option>
                                                </select>
                                                <input type="hidden" name="teacher_ids[]" value="{{$teacher->id}}">
                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-block relative w-64">
                                                @foreach($TimeInForDate as $index => $TimeIn)
                                                @if (Auth::user()->can('isExecutive'))
                                                <x-time-picker name="time_in[{{ $teacher->id }}][{{ $TimeForDate[$index] }}]" class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{ $TimeIn }}" />
                                                @endif

                                                @if (!Auth::user()->can('isExecutive'))
                                                <x-time-picker name="" class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{ $TimeIn }}" disabled />
                                                @endif

                                                @endforeach
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="inline-block relative w-64">
                                                @foreach($TimeOutForDate as $index => $TimeOut)
                                                @if (Auth::user()->can('isExecutive'))
                                                <x-time-picker name="time_out[{{ $teacher->id }}][{{ $TimeForDate[$index] }}]" class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{ $TimeOut }}" />
                                                @endif

                                                @if (!Auth::user()->can('isExecutive'))
                                                <x-time-picker name="" class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{ $TimeOut }}" disabled />
                                                @endif

                                                @endforeach
                                            </div>
                                        </td>
                                            {{-- <input type="hidden" name="teacher_ids[]" class="block appearance-none w-half bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" value="{{$teacher->id}}"> --}}
                                            <td>
                                                <div class="inline-block relative w-64">
                                                @foreach($TimeForDate as $time)
                                                @if (Auth::user()->can('isExecutive'))
                                                <select name="time[]" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                                                    @endif

                                                @if (!Auth::user()->can('isExecutive'))
                                                <select name="time[]" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500" disabled>
                                                    @endif

                                                    <option value="">{{ $time }}</option>
                                                    <option>Morning</option>
                                                    <option>Evening</option>
                                                </select>
                                                @endforeach
                                                </div>
                                            </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @can('isExecutive')
                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                                Submit
                            </button>
                        </div>
                        @endcan
                    </form>
                    @can('isExecutive')
                    <div class="flex items-center justify-end mt-4">
                    <form method="POST" action="/teachersAttendance/{{$date}}">
                        @csrf
                        @method('DELETE')
                        <x-danger-button onclick="return confirm('Are you sure you want to delete this record?')">
                        <i class="fa-solid fa-trash"> 
                             {{ __('Delete') }}
                             </i>
                    </x-danger-button> 
            </form>
                    </div>
                    @endcan
                </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
