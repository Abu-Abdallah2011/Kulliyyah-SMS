<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Admin Edit Excuse</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('student_excuses.admin.update', $excuseRequest->id) }}" class="bg-white shadow rounded-lg p-6 space-y-5">
            @csrf
            @method('PUT')

            <div class="grid sm:grid-cols-2 gap-4 text-sm bg-gray-50 border rounded p-4">
                <p><strong>Current Student:</strong> {{ $excuseRequest->student->fullname ?? 'N/A' }}</p>
                <p><strong>Current Class:</strong> {{ $excuseRequest->student->class ?? 'N/A' }}</p>
                <p><strong>Guardian:</strong> {{ $excuseRequest->guardian->fullname ?? 'N/A' }}</p>
                <p><strong>Guardian Phone:</strong> {{ $excuseRequest->guardian->phone ?? 'N/A' }}</p>
                <p><strong>Guardian Email:</strong> {{ $excuseRequest->guardian->user->email ?? 'N/A' }}</p>
                <p><strong>Card No:</strong> {{ $excuseRequest->card->card_number ?? 'No card generated yet' }}</p>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Student</label>
                    <select name="student_id" class="w-full border-gray-300 rounded h-10" required>
                        @foreach($students as $student)
                            <option value="{{ $student->id }}" @selected(old('student_id', $excuseRequest->student_id) == $student->id)>{{ $student->fullname }} - {{ $student->class }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold mb-1">Status</label>
                    <select name="status" id="status" class="w-full border-gray-300 rounded h-10" required>
                        @foreach(['pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', 'revoked' => 'Revoked'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $excuseRequest->status) === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Changing to pending lets the guardian edit the request again.</p>
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-1">Excuse Type</label>
                <select name="type" id="excuse_type" class="w-full border-gray-300 rounded h-10" required>
                    <option value="late" @selected(old('type', $excuseRequest->type) === 'late')>Late Excuse</option>
                    <option value="out" @selected(old('type', $excuseRequest->type) === 'out')>Out Excuse</option>
                </select>
            </div>

            <div class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Valid From</label>
                    <input type="date" name="valid_from" value="{{ old('valid_from', $excuseRequest->card?->valid_from?->format('Y-m-d')) }}" class="w-full border-gray-300 rounded h-10">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to use the term start date.</p>
                </div>
                <div>
                    <label class="block font-semibold mb-1">Valid Until</label>
                    <input type="date" name="valid_until" value="{{ old('valid_until', $excuseRequest->card?->valid_until?->format('Y-m-d')) }}" class="w-full border-gray-300 rounded h-10">
                    <p class="text-xs text-gray-500 mt-1">Leave empty to use the term end date.</p>
                </div>
            </div>

            <div id="late_fields">
                <label class="block font-semibold mb-2">Approved Late Excuse Days</label>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                    @foreach($days as $day)
                        <label class="flex items-center gap-2 bg-gray-50 border rounded px-3 py-2">
                            <input type="checkbox" name="requested_days[]" value="{{ $day }}" @checked(in_array($day, old('requested_days', $excuseRequest->card->valid_days ?? $excuseRequest->requested_days ?? [])))>
                            <span>{{ $day }}</span>
                        </label>
                    @endforeach
                </div>

                <div class="mt-4">
                    <label class="block font-semibold mb-1">Latest Arrival Time</label>
                    <input type="time" name="requested_latest_time" value="{{ old('requested_latest_time', $excuseRequest->card?->latest_time ? substr($excuseRequest->card->latest_time, 0, 5) : ($excuseRequest->requested_latest_time ? substr($excuseRequest->requested_latest_time, 0, 5) : '')) }}" class="w-full border-gray-300 rounded h-10">
                </div>
            </div>

            <div id="out_fields" class="grid sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold mb-1">Out Start Time</label>
                    <input type="time" name="requested_start_time" value="{{ old('requested_start_time', $excuseRequest->card?->start_time ? substr($excuseRequest->card->start_time, 0, 5) : ($excuseRequest->requested_start_time ? substr($excuseRequest->requested_start_time, 0, 5) : '13:00')) }}" class="w-full border-gray-300 rounded h-10">
                </div>
                <div>
                    <label class="block font-semibold mb-1">Out End Time</label>
                    <input type="time" name="requested_end_time" value="{{ old('requested_end_time', $excuseRequest->card?->end_time ? substr($excuseRequest->card->end_time, 0, 5) : ($excuseRequest->requested_end_time ? substr($excuseRequest->requested_end_time, 0, 5) : '14:00')) }}" class="w-full border-gray-300 rounded h-10">
                </div>
            </div>

            <div>
                <label class="block font-semibold mb-1">Reason</label>
                <textarea name="reason" rows="4" class="w-full border-gray-300 rounded" required>{{ old('reason', $excuseRequest->reason) }}</textarea>
            </div>

            <div>
                <label class="block font-semibold mb-1">Approval / Review Comment</label>
                <textarea name="review_note" rows="4" class="w-full border-gray-300 rounded">{{ old('review_note', $excuseRequest->review_note) }}</textarea>
            </div>

            <div id="revocation_fields">
                <label class="block font-semibold mb-1 text-red-700">Revocation Comment</label>
                <textarea name="revocation_reason" rows="4" class="w-full border-gray-300 rounded">{{ old('revocation_reason', $excuseRequest->card->revocation_reason ?? '') }}</textarea>
            </div>

            <div class="flex justify-end gap-3">
                @if($excuseRequest->card)
                    <a href="{{ route('student_excuses.admin.show', $excuseRequest->card->id) }}" class="px-4 py-2 border rounded">Cancel</a>
                @else
                    <a href="{{ route('student_excuses.admin.index') }}" class="px-4 py-2 border rounded">Cancel</a>
                @endif
                <button type="submit" class="bg-blue-700 text-white px-5 py-2 rounded hover:bg-blue-800">Save Changes</button>
            </div>
        </form>
    </div>

    <script>
        function toggleFields() {
            const type = document.getElementById('excuse_type').value;
            const status = document.getElementById('status').value;
            document.getElementById('late_fields').style.display = type === 'late' ? 'block' : 'none';
            document.getElementById('out_fields').style.display = type === 'out' ? 'grid' : 'none';
            document.getElementById('revocation_fields').style.display = status === 'revoked' ? 'block' : 'none';
        }

        document.getElementById('excuse_type').addEventListener('change', toggleFields);
        document.getElementById('status').addEventListener('change', toggleFields);
        toggleFields();
    </script>
</x-app-layout>
