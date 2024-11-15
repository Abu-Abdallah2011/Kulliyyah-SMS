<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Excuse') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ url('/excuses/store/' . $teacher->id) }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Teacher id -->
                        <div class="mb-4 hidden">
                            <label for="teacher_id" class="block text-sm font-medium text-gray-700">Teacher Id</label>
                            <input type="text" id="teacher_id" name="teacher_id" class="mt-1 block w-full" value="{{ $teacher->id }}">
                        </div>

                        <!-- Student Id -->
                        <div class="mb-4">
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student Id</label>
                            <input type="text" id="student_id" name="student_id" class="mt-1 block w-full" value="{{ old('student_id') }}">
                        </div>

                        <!-- Title -->
                        <div class="mb-4">
                            <label for="title" class="block text-sm font-medium text-gray-700">Title</label>
                            <input type="text" id="title" name="title" class="mt-1 block w-full" value="{{ old('title') }}" required>
                        </div>

                        <!-- Description -->
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea id="description" name="description" class="mt-1 block w-full" rows="4" required>{{ old('description') }}</textarea>
                        </div>

                        <!-- Start Date -->
                        <div class="mb-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <x-date-picker type="text" id="start_date" name="start_date" class="mt-1 block w-full" value="{{ old('start_date') }}" />
                        </div>

                        <!-- End Date -->
                        <div class="mb-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <x-date-picker type="text" id="end_date" name="end_date" class="mt-1 block w-full" value="{{ old('end_date') }}" />
                        </div>

                        <!-- Supporting Document Upload -->
                        <div class="mb-4">
                            <label for="supporting_document" class="block text-sm font-medium text-gray-700">Excuse Supporting Document (optional)</label>
                            <input type="file" id="supporting_document" name="supporting_document" class="mt-1 block w-full">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button type="submit">
                                {{ __('Save Excuse') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
