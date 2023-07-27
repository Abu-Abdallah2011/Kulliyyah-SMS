<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Class Edit Form') }}
        </h2>
    </x-slot>

    <div class="py-12">


        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                
                    <section>

                        <form method="POST" action="/class/{{$class->id}}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                    
                            <!-- Class -->
                            <div>
                                <x-input-label for="class" :value="__('Class')" />
                                <x-text-input id="class" class="block mt-1 w-full" type="text" name="class" value="{{$class->class}}" autofocus autocomplete="class" />
                                <x-input-error :messages="$errors->get('class')" class="mt-2" />
                            </div>
                           
                            <br/>
                        <div>
                            <x-primary-button class="ml-3">
                                {{ __('Update') }}
                            </x-primary-button>
                        </div>
                        </form>
                    </section>
            </div>
        </div>
    </div>
</x-app-layout>
