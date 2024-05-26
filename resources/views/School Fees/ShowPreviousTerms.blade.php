<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Previous Sessions Record') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">

                                @foreach($paymentStatus as $PreviousSession)

                                @php
                                $unPaidUrl = url('/fees_record/' . $studentId . '/' . $PreviousSession->term . '/' . str_replace('/', '_', $PreviousSession->session) . '/edit_fees');
                                $url = url('/reciept/' . $studentId . '/' . $PreviousSession->term . '/' . str_replace('/', '_', $PreviousSession->session))  
                                @endphp

                                @if($PreviousSession->status === 'PAID')
                                <div>
                                    <a href="{{ $url }}">
                            <x-primary-button class="bg-green-500">{{$PreviousSession->term}} {{$PreviousSession->session}} Academic Session</x-primary-button>
                                    </a>
                                </div>
                                @elseif($PreviousSession->status === 'FREE')
                                <div>
                                    <a href="{{ $url }}">
                            <x-primary-button class="bg-purple-500">{{$PreviousSession->term}} {{$PreviousSession->session}} Academic Session</x-primary-button>
                                    </a>
                                </div>
                            @elseif($PreviousSession->status === 'PART')
                            <div>
                                <a href="{{ $url }}">
                            <x-primary-button class="bg-yellow-500">{{$PreviousSession->term}} {{$PreviousSession->session}} Academic Session</x-primary-button>
                                </a>
                            </div>
                            @else
                            <div>
                               @can('isExecutive') <a href="{{ $unPaidUrl }}"> @endcan
                            <x-danger-button>{{$PreviousSession->term}} {{$PreviousSession->session}} Academic Session</x-danger-button>
                            @can('isExecutive') </a> @endcan
                        </div>
                            @endif
                        </a>
                            @endforeach                   
            </div>
        </div>
    </div>
</x-app-layout>
