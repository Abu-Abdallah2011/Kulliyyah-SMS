<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Assign Mock Student') }}</h2>
    </x-slot>

    <div class="py-8 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <x-success-status class="mb-4" :status="session('message')" />

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('mock_cycles.store') }}" class="space-y-4">
                    @csrf

                    <div>
                        <x-input-label for="student_id" :value="__('Student')" />
                        <select id="student_id" name="student_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Select student</option>
                            @foreach($students as $student)
                                <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->fullname }} - {{ $student->class }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('student_id')" class="mt-2" />
                        <p class="mt-1 text-sm text-gray-500">Only students whose Hadda status or Tarteel status is Mock Exam appear here.</p>
                    </div>

                    <div>
                        <x-input-label for="teacher_id" :value="__('Supervising Teacher')" />
                        <select id="teacher_id" name="teacher_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="">Select teacher</option>
                            @foreach($teachers as $teacher)
                                <option value="{{ $teacher->id }}" @selected(old('teacher_id') == $teacher->id)>{{ $teacher->fullname }} - {{ $teacher->class }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('teacher_id')" class="mt-2" />
                    </div>

                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <x-input-label for="cycle_type" :value="__('Cycle Type')" />
                            <select id="cycle_type" name="cycle_type" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                                @foreach($typeOptions as $value => $label)
                                    <option value="{{ $value }}" @selected(old('cycle_type', 'mock') === $value)>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <x-input-label for="start_date" :value="__('Start Date')" />
                            <x-date-picker id="start_date" name="start_date" class="block mt-1 w-full" value="{{ old('start_date') }}" required />
                            <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="target_end_date" :value="__('Target End Date')" />
                            <x-date-picker id="target_end_date" name="target_end_date" class="block mt-1 w-full" value="{{ old('target_end_date') }}" />
                            <x-input-error :messages="$errors->get('target_end_date')" class="mt-2" />
                        </div>
                    </div>

                    <div>
                        <x-input-label for="notes" :value="__('Notes')" />
                        <textarea id="notes" name="notes" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="4">{{ old('notes') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button>Assign Mock Cycle</x-primary-button>
                        <a href="{{ route('mock_cycles.index') }}" class="text-sm font-bold text-gray-600 hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
