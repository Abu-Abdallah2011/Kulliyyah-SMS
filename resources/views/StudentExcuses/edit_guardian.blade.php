<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Edit Excuse Request</h2>
    </x-slot>

    <div class="py-6 max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('student_excuses.guardian.update', $excuseRequest->id) }}" class="bg-white shadow rounded-lg p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="bg-yellow-50 border border-yellow-200 rounded p-4 text-sm text-yellow-800">
                You can edit this request because it is still pending. Once admin approves, rejects, or revokes it, only admin can reopen it for editing.
            </div>

            <div>
                <label class="block font-semibold mb-1">Student</label>
                <select name="student_id" class="w-full border-gray-300 rounded h-10" required>
                    @foreach($students as $student)
                        <option value="{{ $student->id }}" @selected(old('student_id', $excuseRequest->student_id) == $student->id)>{{ $student->fullname }} - {{ $student->class }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold mb-1">Excuse Type</label>
                <select name="type" id="excuse_type" class="w-full border-gray-300 rounded h-10" required>
                    <option value="late" @selected(old('type', $excuseRequest->type) === 'late')>Late Excuse</option>
                    <option value="out" @selected(old('type', $excuseRequest->type) === 'out')>Out Excuse</option>
                </select>
            </div>

            <div id="late_fields">
                <label class="block font-semibold mb-2">Late Excuse Days</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach($days as $day)
                        <label class="flex items-center gap-2 bg-gray-50 border rounded px-3 py-2">
                            <input type="checkbox" name="requested_days[]" value="{{ $day }}" @checked(in_array($day, old('requested_days', $excuseRequest->requested_days ?? [])))>
                            <span>{{ $day }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="block font-semibold mb-1">Latest Arrival Time</label>
                    <input type="time" name="requested_latest_time" value="{{ old('requested_latest_time', $excuseRequest->requested_latest_time ? substr($excuseRequest->requested_latest_time, 0, 5) : '') }}" class="w-full border-gray-300 rounded h-10">
                </div>
            </div>

            <div id="out_fields" class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Out Start Time</label>
                    <input type="time" name="requested_start_time" value="{{ old('requested_start_time', $excuseRequest->requested_start_time ? substr($excuseRequest->requested_start_time, 0, 5) : '13:00') }}" class="w-full border-gray-300 rounded h-10">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Out End Time</label>
                    <input type="time" name="requested_end_time" value="{{ old('requested_end_time', $excuseRequest->requested_end_time ? substr($excuseRequest->requested_end_time, 0, 5) : '14:00') }}" class="w-full border-gray-300 rounded h-10">
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-1">Reason</label>
                <textarea name="reason" rows="5" class="w-full border-gray-300 rounded" required>{{ old('reason', $excuseRequest->reason) }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                <a href="{{ route('student_excuses.guardian.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded hover:bg-blue-800">Update Request</button>
            </div>
        </form>
    </div>

    <script>
        function toggleExcuseFields() {
            const type = document.getElementById('excuse_type').value;
            document.getElementById('late_fields').style.display = type === 'late' ? 'block' : 'none';
            document.getElementById('out_fields').style.display = type === 'out' ? 'grid' : 'none';
        }

        document.getElementById('excuse_type').addEventListener('change', toggleExcuseFields);
        toggleExcuseFields();
    </script>
</x-app-layout>
