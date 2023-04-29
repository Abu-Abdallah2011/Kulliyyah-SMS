<!-- Include Bootstrap CSS -->
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/css/bootstrap.min.css" /> --}}

<!-- Include Bootstrap Datepicker CSS -->
{{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css" /> --}}

<!-- Include jQuery library -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script> --}}

<!-- Include Bootstrap JS -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.0/js/bootstrap.min.js"></script> --}}

<!-- Include Bootstrap Datepicker JS -->
{{-- <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script> --}}

<!-- Initialize the datepicker -->
<script>
    $(function() {
        $('#datepicker').datepicker({
            format: 'DD, MM dd, yyyy',
            autoclose: true
        });
    });
</script>

<!-- Input field -->
@props(['disabled' => false])

<input id="datepicker" name="date" {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}>

{{-- <div class="form-group">
    <label for="datepicker" style="font-weight: bold;">Select a date:</label> --}}
    {{-- <div class="input-group date">
        <input type="text" class="form-control" id="datepicker" name="date" style="max-width: 250px;">
    </div>
</div> --}}
