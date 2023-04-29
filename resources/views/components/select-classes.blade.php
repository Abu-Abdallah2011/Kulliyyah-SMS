@props(['disabled' => false])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}>
    <option></option>
    <option>HADDA FOUR MALE</option>
    <option>HADDA FOUR FEMALE</option>
    <option>HADDA THREE MALE</option>
    <option>HADDA THREE FEMALE</option>
    <option>HADDA TWO MALE</option>
    <option>HADDA TWO FEMALE</option>
    <option>HADDA ONE MALE</option>
    <option>HADDA ONE FEMALE</option>
    <option>TARTEEL MALE</option>
    <option>TARTEEL FEMALE</option>
    <option>PRE-HADDA THREE MALE</option>
    <option>PRE-HADDA THREE FEMALE</option>
    <option>PRE-HADDA TWO MALE</option>
    <option>PRE-HADDA TWO FEMALE</option>
    <option>PRE-HADDA ONE</option>
    {{-- <option>PRE-HADDA ONE MALE</option>
    <option>PRE-HADDA ONE FEMALE</option> --}}
    <option>NONE FOR NOW</option>
</select>