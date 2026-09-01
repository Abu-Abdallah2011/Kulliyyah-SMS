<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Generate Allowance Voucher') }}
        </h2>
    </x-slot>

    <div class="py-8 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <x-success-status class="mb-4" :status="session('message')" />

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 border border-gray-100">
                <form method="POST" action="{{ route('allowance_vouchers.store') }}">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div>
                            <x-input-label for="voucher_month" :value="__('Voucher Month')" />
                            <x-text-input id="voucher_month" class="block mt-1 w-full" type="text" name="voucher_month" value="{{ old('voucher_month', $month) }}" required />
                        </div>

                        <div>
                            <x-input-label for="voucher_year" :value="__('Voucher Year')" />
                            <x-text-input id="voucher_year" class="block mt-1 w-full" type="number" name="voucher_year" value="{{ old('voucher_year', $year) }}" required />
                        </div>

                        <div>
                            <x-input-label for="notes" :value="__('Notes (Optional)')" />
                            <x-text-input id="notes" class="block mt-1 w-full" type="text" name="notes" value="{{ old('notes') }}" />
                        </div>
                    </div>

                    <div class="mb-6 flex justify-end">
                        <button
                            type="button"
                            class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700"
                            onclick="window.location='{{ route('allowance_vouchers.create') }}?voucher_month=' + encodeURIComponent(document.getElementById('voucher_month').value) + '&voucher_year=' + encodeURIComponent(document.getElementById('voucher_year').value)"
                        >
                            Load Attendance for Selected Month
                        </button>
                    </div>

                    <h3 class="font-bold text-lg mb-3">Teachers In School (60% Attendance and Above Included)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Include</th>
                                    <th class="px-4 py-2 text-left">Teacher</th>
                                    <th class="px-4 py-2 text-left">Monthly Attendance</th>
                                    <th class="px-4 py-2 text-left">Account Name</th>
                                    <th class="px-4 py-2 text-left">Account No</th>
                                    <th class="px-4 py-2 text-left">Bank</th>
                                    <th class="px-4 py-2 text-left">Deduction</th>
                                    <th class="px-4 py-2 text-left">Bonus</th>
                                    <th class="px-4 py-2 text-left">Allowance</th>
                                    <th class="px-4 py-2 text-left">Reason (Optional)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inSchoolTeachers as $teacher)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">
                                            <input type="checkbox" name="include[{{ $teacher->id }}]" value="1" @checked(session()->hasOldInput('include') ? old('include.'.$teacher->id) : $teacher->voucher_attendance_eligible)>
                                        </td>
                                        <td class="px-4 py-2">{{ $teacher->fullname }}</td>
                                        <td class="px-4 py-2">
                                            <div class="{{ $teacher->voucher_attendance_eligible ? 'text-green-700' : 'text-red-600' }} font-semibold">
                                                {{ number_format((float) $teacher->voucher_attendance_percentage, 1) }}%
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Present: {{ $teacher->voucher_attendance_present }},
                                                Late: {{ $teacher->voucher_attendance_late }},
                                                Total: {{ $teacher->voucher_attendance_total }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-2">{{ $teacher->acct_name }}</td>
                                        <td class="px-4 py-2">{{ $teacher->acct_no }}</td>
                                        <td class="px-4 py-2">{{ $teacher->bank_branch }}</td>
                                        <td class="px-4 py-2"><input class="w-full border rounded" type="number" min="0" step="0.01" name="deduction[{{ $teacher->id }}]" value="{{ old('deduction.'.$teacher->id, 0) }}"></td>
                                        <td class="px-4 py-2"><input class="w-full border rounded" type="number" min="0" step="0.01" name="bonus[{{ $teacher->id }}]" value="{{ old('bonus.'.$teacher->id, 0) }}"></td>
                                        <td class="px-4 py-2">&#8358;{{ number_format((float) $teacher->allowance, 2) }}</td>
                                        <td class="px-4 py-2">
                                            <input class="w-full border rounded" type="text" name="reason[{{ $teacher->id }}]" value="{{ old('reason.'.$teacher->id) }}" placeholder="{{ $teacher->voucher_attendance_eligible ? 'Optional' : 'Required if included' }}">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <h3 class="font-bold text-lg mt-8 mb-3">Add Extra Teacher (Wrong Status Correction)</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-4 py-2 text-left">Include</th>
                                    <th class="px-4 py-2 text-left">Teacher</th>
                                    <th class="px-4 py-2 text-left">Status</th>
                                    <th class="px-4 py-2 text-left">Monthly Attendance</th>
                                    <th class="px-4 py-2 text-left">Account Name</th>
                                    <th class="px-4 py-2 text-left">Account No</th>
                                    <th class="px-4 py-2 text-left">Bank</th>
                                    <th class="px-4 py-2 text-left">Deduction</th>
                                    <th class="px-4 py-2 text-left">Bonus</th>
                                    <th class="px-4 py-2 text-left">Allowance</th>
                                    <th class="px-4 py-2 text-left">Reason</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($otherTeachers as $teacher)
                                    <tr class="border-b">
                                        <td class="px-4 py-2">
                                            <input type="checkbox" name="include[{{ $teacher->id }}]" value="1" {{ old('include.'.$teacher->id) ? 'checked' : '' }}>
                                        </td>
                                        <td class="px-4 py-2">{{ $teacher->fullname }}</td>
                                        <td class="px-4 py-2">{{ $teacher->status }}</td>
                                        <td class="px-4 py-2">
                                            <div class="{{ $teacher->voucher_attendance_eligible ? 'text-green-700' : 'text-red-600' }} font-semibold">
                                                {{ number_format((float) $teacher->voucher_attendance_percentage, 1) }}%
                                            </div>
                                            <div class="text-xs text-gray-500">
                                                Present: {{ $teacher->voucher_attendance_present }},
                                                Late: {{ $teacher->voucher_attendance_late }},
                                                Total: {{ $teacher->voucher_attendance_total }}
                                            </div>
                                        </td>
                                        <td class="px-4 py-2">{{ $teacher->acct_name }}</td>
                                        <td class="px-4 py-2">{{ $teacher->acct_no }}</td>
                                        <td class="px-4 py-2">{{ $teacher->bank_branch }}</td>
                                        <td class="px-4 py-2"><input class="w-full border rounded" type="number" min="0" step="0.01" name="deduction[{{ $teacher->id }}]" value="{{ old('deduction.'.$teacher->id, 0) }}"></td>
                                        <td class="px-4 py-2"><input class="w-full border rounded" type="number" min="0" step="0.01" name="bonus[{{ $teacher->id }}]" value="{{ old('bonus.'.$teacher->id, 0) }}"></td>
                                        <td class="px-4 py-2">&#8358;{{ number_format((float) $teacher->allowance, 2) }}</td>
                                        <td class="px-4 py-2">
                                            <input class="w-full border rounded" type="text" name="reason[{{ $teacher->id }}]" value="{{ old('reason.'.$teacher->id) }}" placeholder="Why included">
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <p class="text-sm text-gray-600">All amounts are in Nigerian Naira (&#8358;). Teachers below 60% attendance can still be included manually with a reason.</p>
                        <x-primary-button>
                            {{ __('Create Voucher Draft') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
