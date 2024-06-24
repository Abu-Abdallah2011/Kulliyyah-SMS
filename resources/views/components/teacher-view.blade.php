
                    {{-- the dashboard --}}
                    <div class="dark:bg-gray-800 overflow-hidden shadow-sm">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                    <body class="bg-gray-100 dark:bg-gray-900">
                        <div class="min-h-screen flex flex-col">
                            <!-- Session Status -->
                        <x-success-status class="mb-4" :status="session('message')" />

                        <h1 class="font-bold text-center text-gray-900">{{$class}}</h1>
                    
                            <!-- Main Content -->
                            <main class="flex-grow container mx-auto px-4 sm:px-6 lg:px-8 py-8">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                    <!-- Card: Number of Teachers -->
                                    <a href="class_teachers">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-chalkboard-teacher fa-2x text-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Teachers</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">{{$teachers->count()}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                    
                                    <!-- Card: Number of Students -->
                                    <a href="class_students">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-users fa-2x text-success"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Students</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">{{$teacher->students->count()}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                    
                                    <!-- Card Template For Profile -->
                                    <a href="{{ url('/teachers_database/' . $teacher->id) }}">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-user fa-2x text-warning"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Bio Data</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">Profile</p>
                                            </div>
                                        </div>
                                    </div>
                                    </a>

                                    <!-- Card: Number of Graduates -->
                                    <a href="graduates_database">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-user-graduate fa-2x text-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Graduates</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">{{$graduates->count()}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card: Hadda -->
                                <a href="{{ url('/studentsHadda/' . $teacher->id) }}">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-file-alt fa-2x text-danger"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Hadda</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">% Hadda</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <!-- Card: Attendance -->
                                <a href="attendance/show">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-chart-pie fa-2x text-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">Attendance</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">{{ number_format($percentageclasspresent, 2) }}%</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                    
                                </div>

                                <hr class="my-4 border-gray-300 dark:border-gray-500">
                                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

                                <!-- Card: Number Of Teachers -->
                                    <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6 w-full">
                                        <div class="flex items-center w-full">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-chalkboard-teacher fa-2x text-secondary"></i>
                                            </div>
                                            <div class="ml-4 w-full">
                                                <h3 class="text-2xl font-bold text-gray-500 dark:text-gray-900">Total Teachers: {{$totalteachers}}</h3>
                                                <p class="text-lg font-semibold text-gray-500 dark:text-gray-900">Male: {{$totalmaleteachers}} Female: {{$totalfemaleteachers}}</p>
                                            </div>
                                        </div>
                                    </div>


                                    <!-- Card: Number Of Students -->
                                    <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-users fa-2x text-secondary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-2xl font-bold text-gray-500 dark:text-gray-900">Total Students: {{$totalstudents}}</h3>
                                                <p class="text-lg font-semibold text-gray-500 dark:text-gray-900">Male: {{$totalmalestudents}} Female: {{$totalfemalestudents}}</p>
                                            </div>
                                        </div>
                                    </div>
                                
                                </div>
                                </div>

                                {{-- FOR EXECUTIVES ONLY --}}
                                @can('isExecutive')

                                <!-- Card: School Fees -->
                                <a href="fees_database/show">
                                    <div class="bg-white dark:bg-gray-800 shadow rounded-lg p-6">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0">
                                                <i class="fas fa-money-check-alt fa-2x text-primary"></i>
                                            </div>
                                            <div class="ml-4">
                                                <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-900">School Fees</h3>
                                                <p class="text-2xl font-bold text-gray-900 dark:text-gray-900">Paid: {{ number_format($percentagepaid, 2) }}%</p>
                                            </div>
                                        </div>
                                    </div>
                                </a>

                                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                                    <div class="flex items-center">
                                        <div style="width: 75%;">
                                            <canvas id="FeesChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var ctx = document.getElementById('FeesChart').getContext('2d');
                                
                                        var labels = ['%Paid', '%Free', '%Uncleared', '%Part'];
                                        var data = [
                                            {{ $percentagepaid }},
                                            {{ $percentagefree }},
                                            {{ $percentagenotpaid }},
                                            {{ $percentagepart }}
                                        ];
                                
                                        var myChart = new Chart(ctx, {
                                            type: 'bar', // Change this to 'line', 'pie', etc. as needed
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    label: 'Fee Status Percentages',
                                                    data: data,
                                                    backgroundColor: [
                                                        'rgba(0, 255, 0, 0.5)',
                                                        'rgba(153, 0, 255, 0.5)',
                                                        'rgba(255, 0, 0, 0.5)',
                                                        'rgba(255, 255, 0, 0.5)'
                                                    ],
                                                    borderColor: [
                                                        'rgba(75, 192, 192, 1)',
                                                        'rgba(153, 102, 255, 1)',
                                                        'rgba(255, 99, 132, 1)',
                                                        'rgba(200, 255, 0, 1)'
                                                    ],
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    y: {
                                                        beginAtZero: true
                                                    }
                                                }
                                            }
                                        });
                                    });
                                </script>

                                @endcan
                                {{-- END OF FOR EXECUTIVES ONLY --}}

                                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                                    <div class="flex items-center">
                                        <div style="width: 75%;">
                                            <canvas id="classAttendanceChart"></canvas>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var ctx = document.getElementById('classAttendanceChart').getContext('2d');

                                        var labels = ['%Present', '%Excused', '%Absent', '%Late'];
                                        var data = [
                                            {{ $percentageclasspresent }},
                                            {{ $percentageclassexcused }},
                                            {{ $percentageclassabsent }},
                                            {{ $percentageclasslate }}
                                        ];

                                        var myChart = new Chart(ctx, {
                                            type: 'doughnut', // Change this to 'line', 'pie', etc. as needed
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    label: 'Students Attendance Percentages',
                                                    data: data,
                                                    backgroundColor: [
                                                        'rgba(0, 255, 0, 0.5)',
                                                        'rgba(153, 0, 255, 0.5)',
                                                        'rgba(255, 0, 0, 0.5)',
                                                        'rgba(255, 255, 0, 0.5)'
                                                    ],
                                                    borderColor: [
                                                        'rgba(75, 192, 192, 1)',
                                                        'rgba(153, 102, 255, 1)',
                                                        'rgba(255, 99, 132, 1)',
                                                        'rgba(200, 255, 0, 1)'
                                                    ],
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    y: {
                                                        beginAtZero: true
                                                    }
                                                }
                                            }
                                        });
                                    });
                                </script>
                                
                                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                                    <div class="flex items-center">
                                        <div style="width: 75%;">
                                            <canvas id="AttendanceChart"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var ctx = document.getElementById('AttendanceChart').getContext('2d');
                                
                                        var labels = ['%Present', '%Excused', '%Absent', '%Late'];
                                        var data = [
                                            {{ $presentpercentage }},
                                            {{ $excusedpercentage }},
                                            {{ $absentpercentage }},
                                            {{ $latepercentage }}
                                        ];
                                
                                        var myChart = new Chart(ctx, {
                                            type: 'pie', // Change this to 'line', 'pie', etc. as needed
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    label: 'Students Attendance Percentages',
                                                    data: data,
                                                    backgroundColor: [
                                                        'rgba(0, 255, 0, 0.5)',
                                                        'rgba(153, 0, 255, 0.5)',
                                                        'rgba(255, 0, 0, 0.5)',
                                                        'rgba(255, 255, 0, 0.5)'
                                                    ],
                                                    borderColor: [
                                                        'rgba(75, 192, 192, 1)',
                                                        'rgba(153, 102, 255, 1)',
                                                        'rgba(255, 99, 132, 1)',
                                                        'rgba(200, 255, 0, 1)'
                                                    ],
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    y: {
                                                        beginAtZero: true
                                                    }
                                                }
                                            }
                                        });
                                    });
                                </script>

                            <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                                <div class="flex items-center">
                                    <div style="width: 75%;">
                                        <canvas id="teachersAttendance"></canvas>
                                    </div>
                                </div>
                            </div>

                            <script>
                                document.addEventListener('DOMContentLoaded', function () {
                                    var ctx = document.getElementById('teachersAttendance').getContext('2d');

                                    var labels = ['%Present', '%Absent', '%Excused', '%Late', '%E. Late', '%Early Close', '%Came Late And Closed Early'];
                                    var data = [
                                        {{ $presentpercentageforteachers }},
                                        {{ $absentpercentageforteachers }},
                                        {{ $excusedpercentageforteachers }},
                                        {{ $latepercentageforteachers }},
                                        {{ $percentageexcusedlate }},
                                        {{ $percentageclosedearly }},
                                        {{ $percentagelatecominandearlyclose }},
                                    ];

                                    var myChart = new Chart(ctx, {
                                        type: 'line', // Change this to 'line', 'pie', etc. as needed
                                        data: {
                                            labels: labels,
                                            datasets: [{
                                                label: 'Teachers Attendance Percentages',
                                                data: data,
                                                backgroundColor: [
                                                    'rgba(0, 255, 0, 0.5)',
                                                    'rgba(255, 0, 0, 0.5)',
                                                    'rgba(153, 0, 255, 0.5)',
                                                    'rgba(255, 255, 0, 0.5)',
                                                    'rgba(255, 135, 0, 0.5)',
                                                    'rgba(255, 87, 255, 0.5)',
                                                    'rgba(0, 0, 0, 0.5)',
                                                ],
                                                borderColor: [
                                                    'rgba(75, 192, 192, 1)',
                                                    'rgba(255, 99, 132, 1)',
                                                    'rgba(153, 102, 255, 1)',
                                                    'rgba(200, 255, 0, 1)',
                                                    'rgba(255, 135, 0, 1)',
                                                    'rgba(255, 87, 255, 1)',
                                                    'rgba(0, 0, 0, 1)',
                                                ],
                                                borderWidth: 1,
                                               // fill: true // This makes the area under the line filled
                                            }]
                                        },
                                        options: {
                                            scales: {
                                                y: {
                                                    beginAtZero: true
                                                }
                                            }
                                        }
                                    });
                                });
                            </script>

                                <div class="bg-gray-300 dark:bg-gray-800 shadow rounded-lg p-6">
                                    <div class="flex items-center">
                                        <div style="width: 75%;">
                                            <canvas id="teachersAttendance"></canvas>
                                        </div>
                                    </div>
                                </div>
                                
                                <script>
                                    document.addEventListener('DOMContentLoaded', function () {
                                        var ctx = document.getElementById('teachersAttendance').getContext('2d');
                                
                                        var labels = ['%Present', '%Excused', '%Absent', '%Late', '%E. Late', '%Early Close', '%Came Late And Closed Early'];
                                        var data = [
                                            {{ $presentpercentageforteachers }},
                                            {{ $absentpercentageforteachers }},
                                            {{ $excusedpercentageforteachers }},
                                            {{ $latepercentageforteachers }},
                                            {{ $percentageexcusedlate }},
                                            {{ $percentageclosedearly }},
                                            {{ $percentagelatecominandearlyclose }},
                                        ];
                                
                                        var myChart = new Chart(ctx, {
                                            type: 'line', // Change this to 'line', 'pie', etc. as needed
                                            data: {
                                                labels: labels,
                                                datasets: [{
                                                    label: 'Teachers Attendance Percentages',
                                                    data: data,
                                                    backgroundColor: [
                                                        'rgba(0, 255, 0, 0.5)',
                                                        'rgba(153, 0, 255, 0.5)',
                                                        'rgba(255, 0, 0, 0.5)',
                                                        'rgba(255, 255, 0, 0.5)',
                                                        'rgba(255, 135, 0, 0.5)',
                                                        'rgba(255, 87, 255, 0.5)',
                                                        'rgba(0, 0, 0, 0.5)',
                                                    ],
                                                    borderColor: [
                                                        'rgba(75, 192, 192, 1)',
                                                        'rgba(153, 102, 255, 1)',
                                                        'rgba(255, 99, 132, 1)',
                                                        'rgba(200, 255, 0, 1)',
                                                        'rgba(255, 135, 0, 1)',
                                                        'rgba(255, 87, 255, 1)',
                                                        'rgba(0, 0, 0, 1)',
                                                    ],
                                                    borderWidth: 1
                                                }]
                                            },
                                            options: {
                                                scales: {
                                                    y: {
                                                        beginAtZero: true
                                                    }
                                                }
                                            }
                                        });
                                    });
                                </script>


                                    </div>
                                </div>
                            </main>
                        </div>
                    </body>
                    </div>
                    </div>

                