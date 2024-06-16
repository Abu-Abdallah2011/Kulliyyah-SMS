<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('School Fees Reciept') }}

        </h2>
    </x-slot>

    <div class="flex justify-center items-center min-h-screen bg-gray-100 dark:bg-gray-900">
        <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg w-full sm:w-1/2 p-6">
            <div class="text-center mb-6 dark:bg-gray-800">
                <x-letterhead />
            </div>
            
            <table class="border-collapse w-full table-auto dark:bg-gray-800">
                <tbody>
                    <tr>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">Date</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">{{ $studentFees->date }}</td>
                    </tr>
                    <tr>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">Name</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">{{ $student->fullname }}</td>
                    </tr>
                    <tr>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">Class</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">{{ $student->class }}</td>
                    </tr>
                    <tr>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">Amount</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">{{ $studentFees->amount }}</td>
                    </tr>
                    <tr>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">Balance</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">{{ $studentFees->balance }}</td>
                    </tr>
                    <tr>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">Being Paid For</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">{{ $studentFees->paid_for }}</td>
                    </tr>
                    <tr>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">For</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">{{ $studentFees->term }} {{ $studentFees->session }} Academic Session</td>
                    </tr>
                    @can('isExecutive');
                    <tr>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">Description</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">{{ $studentFees->description }}</td>
                    </tr>
                    @endcan
                    <tr>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">Entered By</td>
                        <td class="w-full lg:w-auto p-3 text-gray-800 dark:text-white border border-gray-300 dark:border-gray-600">{{ $studentFees->edited_by }}</td>
                    </tr>
                </tbody>
            </table>
    
            @can('isExecutive')
            <div class="mt-6 text-center">
                <a href="{{ url('/fees_record/' . $student->id . '/' . $studentFees->term . '/' . str_replace('/', '_', $studentFees->session) . '/edit_fees') }}">
                    <x-primary-button>Update</x-primary-button>
                </a>
            </div>
            @endcan
        </div>
    </div>
    

                {{-- </div>
            </div>
    </div> --}}

</x-app-layout>