<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Users Database') }}
            <a href="register">
                <x-primary-button class="absolute top-15 right-9 bg-green-500">
                    <i class="fa-solid fa-user-plus"></i>
                </x-primary-button>
            </a>
        </h2>
    </x-slot>

    <!-- Session Status -->
    <x-search-users />
    <x-success-status class="mb-4" :status="session('message')" />
    

    <div class="lg:grid lg:grid-cols-2 gap-4 space-y-4 md:space-y-0 mx-4"> 

        @unless (count($users) == 0)
        @foreach ($users as $user)    
        <div class="bg-blue-100 border border-gray-200 rounded p-6">

            <div class="flex">

            <div class="font-bold">
            <h3 class="text-2xl">
                <a href="/users_database/{{$user->id}}/edit_user">
                USER ID: {{$user->id}}
            </a>
            </h3>
            <div class="text-xl mb-4">
            USERNAME: {{$user->username}}
            <div class="text-xl mb-4">
            ROLE: {{$user->role}}
            <div class="text-xl mb-4">
            EMAIL: {{$user->email}}


            <div class="text-xl mb-4">
                <form method="POST" action="/users_database/{{$user->id}}">
                    @csrf
                    @method('DELETE')
                <x-danger-button  onclick="return confirm('Are you sure you want to delete this record?')">
                    <i class="fa-solid fa-trash"> 
                         {{ __('Delete') }}
                         </i>
                </x-danger-button>
                </form>
           

            </div>
                </div>

            </div>
        </div>
            </div>
    </div>
        </div>

    @endforeach 
    @endunless
    </div>

    <div class="mt-6 p-4">
        {{$users->Links()}}
    </div>

</x-app-layout>
