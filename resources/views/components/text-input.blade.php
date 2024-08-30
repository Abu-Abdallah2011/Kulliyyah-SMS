@props(['disabled' => false])

<input {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}>


<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get elements
        const statusSelect = document.getElementById('status');
        const graduateFields = document.getElementById('graduateFields');

        // Function to toggle visibility based on selected status
        function toggleGraduateFields() {
            if (statusSelect.value === 'GRADUATE') {
                graduateFields.style.display = 'block';
            } else {
                graduateFields.style.display = 'none';
            }
        }

        // Attach event listener for when the status changes
        statusSelect.addEventListener('change', toggleGraduateFields);

        // Initial check to show or hide fields based on current value
        toggleGraduateFields();
    });
</script>
