<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Add Disciplinary Action') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">

                    <form method="POST" action="{{ url('disciplinary/store/' . $teacher->id) }}" enctype="multipart/form-data">
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

                        <!-- Action Taken -->
                        <div class="mb-4">
                            <label for="action_taken" class="block text-sm font-medium text-gray-700">Action Taken</label>
                            <input type="text" id="action_taken" name="action_taken" class="mt-1 block w-full" value="{{ old('action_taken') }}" required>
                        </div>

                        <!-- Status -->
                        <div class="mb-4">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select type="text" id="status" name="status" class="mt-1 block w-full" value="{{ old('status') }}">
                                <option value="pending">Pending</option>
                                <option value="resolved">Resolved</option>
                            </select>
                        </div>

                        <!-- School Issued Document Upload -->
                        <div class="mb-4">
                            <label for="school_issued_document" class="block text-sm font-medium text-gray-700">School Issued Document (optional)</label>
                            <input type="file" id="school_issued_document" name="school_issued_document" class="mt-1 block w-full">
                        </div>

                        <!-- Offender Issued Document Upload -->
                        <div class="mb-4">
                            <label for="offender_issued_document" class="block text-sm font-medium text-gray-700">Offender Issued Document (optional)</label>
                            <input type="file" id="offender_issued_document" name="offender_issued_document" class="mt-1 block w-full">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button type="submit">
                                {{ __('Save Disciplinary Action') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
