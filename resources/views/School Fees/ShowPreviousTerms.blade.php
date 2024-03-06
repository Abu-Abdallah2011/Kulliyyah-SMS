<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Previous Sessions Record') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                <table class="border-collapse w-full table-responsive">
                    <thead>
              
                        <tr>
                            <th class="text-center font-bold uppercase bg-gray-200  p-2 md:p-3 lg:p-4 text-gray-600 border border-gray-300 lg:table-cell">Term</th>
                    
                    </thead>

                    <tbody>
                  
                            <tr class="bg-white lg:hover:bg-gray-100 lg:table-row mb-10 lg:mb-0">
                                @foreach($paymentStatus as $PreviousSession)
                                @if($PreviousSession->status === 'PAID' || 'FREE')
                            <td class="w-full lg:w-auto p-3 bg-green-500 text-white border border-b lg:table-cell relative lg:static">{{$PreviousSession->term}} {{$PreviousSession->session}} Academic Session</td>
                            @else
                            <td class="w-full lg:w-auto p-3 bg-red-500 text-gray-800 border border-b lg:table-cell relative lg:static">{{$PreviousSession->term}} {{$PreviousSession->session}} Academic Session</td>
                            @endif
                            @endforeach                   
                        </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
