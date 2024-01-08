<script>

    $(document).ready(function() {
    $('.dynamic-timepicker').each(function() {
        $(this).timepicker({
            format: 'hh:mm TT',
            autoclose: true
        });
    });
});

</script>

<!-- Input field -->
@props(['disabled' => false])

<input id="timepicker" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'dynamic-timepicker border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}>
