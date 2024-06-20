<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <link rel="icon" href="images/favicon.ico">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
        {{-- Added css codes --}}
        <script src="//unpkg.com/alpinejs" defer></script>
        <script src="https://cdn.tailwindcss.com"></script>
        <script>
          tailwind.config = {
            theme: {
              extend: {
                colors: {
                  clifford: '#da373d',
                }
              }
            }
            
          }
        </script>

        <link rel="icon" href="{{ asset('images/logo.jpg') }}">
        <title>{{ config('APP_NAME', 'KMQ') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

         {{-- Include Bootstrap CSS --}}
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css">
          {{-- Include Bootstrap Datepicker CSS --}}
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
         {{-- Include Bootstrap Icons CSS --}}
         <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">

         <!-- Include Bootstrap CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" />

<!-- Include Bootstrap Datepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" />

<!-- Include Bootstrap Timepicker CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/css/bootstrap-timepicker.min.css" />

<!-- Include jQuery library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

<!-- Include Bootstrap JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script>

<!-- Include Bootstrap Datepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

<!-- Include Bootstrap Timepicker JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-timepicker/0.5.2/js/bootstrap-timepicker.min.js"></script>

{{-- CODES FOR STATISTICAL CHARTS --}}

{{-- <canvas id="myChart" width="400" height="400"></canvas> --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels"></script>

{{-- END OF CODES FOR STATISTICAL CHARTS --}}

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    {{-- <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100 dark:bg-gray-900"> --}}
          <body class="font-sans antialiased" x-data="{ sidebarOpen: false }">
            @include('layouts.navigation')
                  <!-- Page Heading -->
      @if (isset($header))
      <header class="bg-white dark:bg-gray-800 shadow">
          <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
              {{ $header }}
          </div>
      </header>
  @endif
            <div class="flex min-h-screen bg-gray-100 dark:bg-gray-900">

<!-- Sidebar -->
<div :class="{'block': sidebarOpen, 'hidden': !sidebarOpen}" class="fixed inset-0 z-50 flex bg-gray-900 bg-opacity-75 md:hidden sidebar overflow-y-auto">
  <div class="relative flex-1 flex flex-col max-w-xs w-full bg-gray-800 dark:bg-gray-800">
      <div class="absolute top-0 right-0 -mr-12 pt-2">
          <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:bg-gray-600">
              <i class="fas fa-times text-white"></i>
          </button>
      </div>
      {{-- <div class="flex items-center justify-center h-16 bg-gray-900 text-white">
          <span class="text-xl font-semibold pt-3 pb-3">{{ config('APP_NAME', 'KULLIYYAH') }}</span>
      </div> --}}
      <nav class="mt-4">
          <a href="{{ route('dashboard') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
              <i class="fas fa-tachometer-alt w-5 h-5"></i>
              <span class="ml-3">Dashboard</span>
          </a>
          @can('isAdmin')
          <a href="{{ url('/teachers_database') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-chalkboard-teacher w-5 h-5"></i>
            <span class="ml-3">Teachers</span>
        </a>
        <a href="{{ url('/users_database') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
          <i class="fas fa-user-friends w-5 h-5"></i>
          <span class="ml-3">Users</span>
      </a>
          @endcan
          @can('isExecutive')
          <a href="{{ url('/students_database') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-users w-5 h-5"></i>
            <span class="ml-3">Students</span>
        </a>
          <a href="{{ url('/guardians_database') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-user-shield w-5 h-5"></i>
            <span class="ml-3">Guardians</span>
        </a>
          <a href="{{ url('classes') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-building w-5 h-5"></i>
            <span class="ml-3">Classes</span>
        </a>
        <a href="settings" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
          <i class="fas fa-cogs w-5 h-5"></i>
          <span class="ml-3">Settings</span>
      </a>
          @endcan
          @can('isAssistant')
          <a href="curriculum_scale" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-balance-scale w-5 h-5"></i>
            <span class="ml-3">Curriculum Scale</span>
        </a>
          <a href="teachersAttendance/show" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-user-check w-5 h-5"></i>
            <span class="ml-3">Teachers Attendance</span>
        </a>
          <a href="exams/show" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-pencil-alt w-5 h-5"></i>
            <span class="ml-3">Exams</span>
        </a>  
          @endcan
          <a href="{{ route('profile.edit') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-user w-5 h-5"></i>
            <span class="ml-3">Profile</span>
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <hr class="my-4 border-gray-300 dark:border-gray-500">
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); this.closest('form').submit();" 
               class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                <i class="fas fa-sign-out-alt w-5 h-5"></i>
                <span class="ml-3">Log Out</span>
            </a>
          </form>
      </nav>
  </div>
</div>
<div class="hidden md:flex md:flex-shrink-0">
  <div class="flex flex-col w-64 bg-gray-800 dark:bg-gray-800 shadow-lg">
      {{-- <div class="flex items-center justify-center h-16 bg-gray-900 text-white">
          <span class="text-xl font-semibold">{{ config('APP_NAME', 'KULLIYYAH') }}</span>
      </div> --}}
      <nav class="mt-4">
          <a href="{{ route('dashboard') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
              <i class="fas fa-tachometer-alt w-5 h-5"></i>
              <span class="ml-3">Dashboard</span>
          </a>
          @can('isAdmin')
          <a href="{{ url('/teachers_database') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-chalkboard-teacher w-5 h-5"></i>
            <span class="ml-3">Teachers</span>
        </a>
        <a href="{{ url('/users_database') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
          <i class="fas fa-user-friends w-5 h-5"></i>
          <span class="ml-3">Users</span>
      </a>
          @endcan
          @can('isExecutive')
          <a href="{{ url('/students_database') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-users w-5 h-5"></i>
            <span class="ml-3">Students</span>
        </a>
          <a href="{{ url('/guardians_database') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-user-shield w-5 h-5"></i>
            <span class="ml-3">Guardians</span>
        </a>
          <a href="{{ url('classes') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-building w-5 h-5"></i>
            <span class="ml-3">Classes</span>
        </a>
        <a href="settings" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
          <i class="fas fa-cogs w-5 h-5"></i>
          <span class="ml-3">Settings</span>
      </a>
          @endcan
          @can('isAssistant')
          <a href="curriculum_scale" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-balance-scale w-5 h-5"></i>
            <span class="ml-3">Curriculum Scale</span>
        </a>
          <a href="teachersAttendance/show" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-user-check w-5 h-5"></i>
            <span class="ml-3">Teachers Attendance</span>
        </a>
          <a href="exams/show" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-pencil-alt w-5 h-5"></i>
            <span class="ml-3">Exams</span>
        </a>  
          @endcan
          <a href="{{ route('profile.edit') }}" class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
            <i class="fas fa-user w-5 h-5"></i>
            <span class="ml-3">Profile</span>
          </a>
          <form method="POST" action="{{ route('logout') }}">
            @csrf
            <hr class="my-4 border-gray-300 dark:border-gray-500">
            <a href="{{ route('logout') }}" 
               onclick="event.preventDefault(); this.closest('form').submit();" 
               class="flex items-center p-4 text-gray-300 dark:text-gray-300 hover:bg-gray-200 dark:hover:bg-gray-700">
                <i class="fas fa-sign-out-alt w-5 h-5"></i>
                <span class="ml-3">Log Out</span>
            </a>
          </form>
      </nav>
  </div>
</div>
<div class="flex flex-col flex-1">
  <header class="bg-gray-800 text-white p-4 md:hidden">
      <button @click="sidebarOpen = true" class="focus:outline-none">
          <i class="fas fa-bars"></i>
      </button>
  </header>
  <div class="flex-1">
      {{-- @include('layouts.navigation') --}}

      {{-- <!-- Page Heading -->
      @if (isset($header))
          <header class="bg-white dark:bg-gray-800 shadow">
              <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                  {{ $header }}
              </div>
          </header>
      @endif --}}

      <!-- Page Content -->
      <main>
          {{ $slot }}
      </main>
  </div>
</div>
</div>
    </body>
</html>