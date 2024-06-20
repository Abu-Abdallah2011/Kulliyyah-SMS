<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('System Settings') }}
        </h2>
    </x-slot>
                
                    <section>
                                            
                    <div class="bg-black dark:bg-gray-800 overflow-hidden shadow-sm">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <div class="min-h-screen flex flex-col">

                                <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                                <!-- Card: Sets -->
                                <a href="sets">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-cogs fa-2x text-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Sets</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">Number of Sets</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card: Classes -->
                                <a href="classes_database">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-cogs fa-2x text-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Classes</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">No. of Classes</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card: Subjects -->
                                <a href="subjects">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-cogs fa-2x text-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Subjects</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">No. of Subjects</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card: Surahs -->
                                <a href="suras_database">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-cogs fa-2x text-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Surahs</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">No. of Suras</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card: Session/Term -->
                                <a href="sessions_database">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-cogs fa-2x text-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Sessions</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">No. of Sessions</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                    </div>
                                </main>
                            </div>
                        </div>
                    </div>
                    </section>
</x-app-layout>
