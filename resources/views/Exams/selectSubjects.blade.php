<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Subjects') }}

        </h2>
    </x-slot>

    <div class="py-6">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ url('/subjectsCreate') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="text-center font-bold">

                            @foreach($teacher->students as $student)
                            <input type="hidden" name="student_ids[]" value="{{$student->id}}">
                            @endforeach

                        <x-input-label for="subjects" :value="__('Select Subjects')" />
                    @foreach($subjects as $subject)

                            <div>
                       <input type="checkbox" name="subjects[]" value="{{ $subject->subject }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1" /> {{ $subject->subject }}
                    </div>

                    @endforeach

                <br/>
                <div>
                    <x-primary-button class="ml-3">
                        {{ __('Save') }}
                    </x-primary-button>
                </div>
            </div>
                    </form>


                </div>
            </div>
    </div>


</x-app-layout>