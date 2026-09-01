<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Request Excuse Card</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('student_excuses.store') }}" class="bg-white shadow rounded-lg p-6 space-y-5">
            @csrf

            <div>
                <label class="block font-semibold mb-1">Student</label>
                <select name="student_id" class="w-full border-gray-300 rounded h-10" required>
                    <option value="">Select student</option>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id') == $student->id)>{{ $student->fullname }} - {{ $student->class }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Excuse Type</label>
                <select name="type" id="excuse_type" class="w-full border-gray-300 rounded h-10" required>
                    <option value="late" @selected(old('type') === 'late')>Late Excuse</option>
                    <option value="out" @selected(old('type') === 'out')>Out Excuse</option>
                </select>
                <p class="text-sm text-gray-500 mt-1">Out excuse automatically uses Saturdays and Sundays, 01:00 PM to 02:00 PM.</p>
            </div>

            <div id="late_fields">
                <label class="block font-semibold mb-2">Late Excuse Days</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach($days as $day)
                        <label class="flex items-center gap-2 bg-gray-50 border rounded px-3 py-2">
                            <input type="checkbox" name="requested_days[]" value="{{ $day }}" @checked(in_array($day, old('requested_days', [])))>
                            <span>{{ $day }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="block font-semibold mb-1">Latest Arrival Time</label>
                    <input type="time" name="requested_latest_time" value="{{ old('requested_latest_time') }}" class="w-full border-gray-300 rounded h-10">
                </div>
            </div>

            <div class="bg-amber-50 border border-amber-200 rounded p-4 text-sm text-amber-800">
                Out Excuse default: Saturday and Sunday, 01:00 PM - 02:00 PM. Admin can still review before approval.
            </div>

            <div>
                <label class="block font-semibold mb-1">Reason</label>
                <textarea name="reason" rows="5" class="w-full border-gray-300 rounded" required>{{ old('reason') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('student_excuses.guardian.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded hover:bg-blue-800">Submit Request</button>
            </div>
        </form>
    </div>

    <script>
        function toggleExcuseFields() {
            const type = document.getElementById('excuse_type').value;
            document.getElementById('late_fields').style.display = type === 'late' ? 'block' : 'none';
        }

        document.getElementById('excuse_type').addEventListener('change', toggleExcuseFields);
        toggleExcuseFields();
    </script>
</x-app-layout>
