<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Review Excuse Request</h2>
    </x-slot>

    <div class="py-6 max-w-5xl mx-auto sm:px-6 lg:px-8">
        @if ($errors->any())
            <div class="mb-4 bg-red-100 text-red-700 p-4 rounded">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <div class="bg-white shadow rounded-lg p-6 mb-5">
            <h3 class="font-bold text-lg mb-4">Request Details</h3>
            <div class="grid sm:grid-cols-2 gap-4 text-sm">
                <p><strong>Student:</strong> {{ $excuseRequest->student->fullname ?? 'N/A' }}</p>
                <p><strong>Class:</strong> {{ $excuseRequest->student->class ?? 'N/A' }}</p>
                <p><strong>Guardian:</strong> {{ $excuseRequest->guardian->fullname ?? 'N/A' }}</p>
                <p><strong>Guardian Phone:</strong> {{ $excuseRequest->guardian->phone ?? 'N/A' }}</p>
                <p><strong>Guardian Email:</strong> {{ $excuseRequest->guardian->user->email ?? 'N/A' }}</p>
                <p><strong>Type:</strong> {{ $excuseRequest->type === 'late' ? 'Late Excuse' : 'Out Excuse' }}</p>
                <p><strong>Term:</strong> {{ $excuseRequest->term ?? 'N/A' }}</p>
                <p><strong>Session:</strong> {{ $excuseRequest->session ?? 'N/A' }}</p>
            </div>
            <div class="mt-4">
                <strong>Reason:</strong>
                <p class="mt-1 text-gray-700 whitespace-pre-line">{{ $excuseRequest->reason }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-2 gap-5">
            <form method="POST" action="{{ route('student_excuses.admin.approve', $excuseRequest->id) }}" class="bg-white shadow rounded-lg p-6 space-y-4">
                @csrf
                <h3 class="font-bold text-lg">Approve And Generate Card</h3>

                @if($excuseRequest->type === 'late')
                    <div>
                        <label class="block font-semibold mb-2">Approved Days</label>
                        <div class="grid grid-cols-2 gap-2">
                            @foreach($days as $day)
                                <label class="flex items-center gap-2 bg-gray-50 border rounded px-3 py-2">
                                    <input type="checkbox" name="valid_days[]" value="{{ $day }}" @checked(in_array($day, old('valid_days', $excuseRequest->requested_days ?? [])))>
                                    <span>{{ $day }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <label class="block font-semibold mb-1">Latest Arrival Time</label>
                        <input type="time" name="latest_time" value="{{ old('latest_time', $excuseRequest->requested_latest_time ? substr($excuseRequest->requested_latest_time, 0, 5) : '') }}" class="w-full border-gray-300 rounded h-10" required>
                    </div>
                @else
                    <div class="bg-amber-50 border border-amber-200 rounded p-4 text-sm text-amber-800">
                        Out excuse cards are fixed for Saturdays and Sundays. You may adjust the time if management approves a different window.
                    </div>
                    <div class="grid sm:grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-1">Start Time</label>
                            <input type="time" name="start_time" value="{{ old('start_time', '13:00') }}" class="w-full border-gray-300 rounded h-10" required>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">End Time</label>
                            <input type="time" name="end_time" value="{{ old('end_time', '14:00') }}" class="w-full border-gray-300 rounded h-10" required>
                        </div>
                    </div>
                @endif

                <div>
                    <label class="block font-semibold mb-1">Approval Note</label>
                    <textarea name="review_note" rows="4" class="w-full border-gray-300 rounded">{{ old('review_note') }}</textarea>
                </div>

                <button type="submit" class="bg-green-700 text-white px-5 py-2 rounded hover:bg-green-800">Approve And Generate</button>
            </form>

            <form method="POST" action="{{ route('student_excuses.admin.reject', $excuseRequest->id) }}" class="bg-white shadow rounded-lg p-6 space-y-4">
                @csrf
                <h3 class="font-bold text-lg text-red-700">Reject Request</h3>
                <div>
                    <label class="block font-semibold mb-1">Rejection Reason</label>
                    <textarea name="review_note" rows="6" class="w-full border-gray-300 rounded" required>{{ old('review_note') }}</textarea>
                </div>
                <button type="submit" class="bg-red-700 text-white px-5 py-2 rounded hover:bg-red-800">Reject Request</button>
            </form>
        </div>
    </div>
</x-app-layout>
