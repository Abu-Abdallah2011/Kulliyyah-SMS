@props(['disabled' => false, 'oldValue' => ''])

<select {{ $disabled ? 'disabled' : '' }} {!! $attributes->get('old-value')->merge(['class' => 'border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm']) !!}>
    <option></option>
    <option {{ $oldValue == 'HADDA FOUR MALE' ? 'selected' : '' }}>HADDA FOUR MALE</option>
    <option {{ $oldValue == 'HADDA FOUR FEMALE' ? 'selected' : '' }}>HADDA FOUR FEMALE</option>
    <option {{ $oldValue == 'HADDA THREE MALE' ? 'selected' : '' }}>HADDA THREE MALE</option>
    <option {{ $oldValue == 'HADDA THREE FEMALE' ? 'selected' : '' }}>HADDA THREE FEMALE</option>
    <option {{ $oldValue == 'HADDA TWO MALE' ? 'selected' : '' }}>HADDA TWO MALE</option>
    <option {{ $oldValue == 'HADDA TWO FEMALE' ? 'selected' : '' }}>HADDA TWO FEMALE</option>
    <option {{ $oldValue == 'HADDA ONE MALE' ? 'selected' : '' }}>HADDA ONE MALE</option>
    <option {{ $oldValue == 'HADDA ONE FEMALE' ? 'selected' : '' }}>HADDA ONE FEMALE</option>
    <option {{ $oldValue == 'TARTEEL MALE' ? 'selected' : '' }}>TARTEEL MALE</option>
    <option {{ $oldValue == 'TARTEEL FEMALE' ? 'selected' : '' }}>TARTEEL FEMALE</option>
    <option {{ $oldValue == 'PRE-HADDA THREE MALE' ? 'selected' : '' }}>PRE-HADDA THREE MALE</option>
    <option {{ $oldValue == 'PRE-HADDA THREE FEMALE' ? 'selected' : '' }}>PRE-HADDA THREE FEMALE</option>
    <option {{ $oldValue == 'PRE-HADDA TWO MALE' ? 'selected' : '' }}>PRE-HADDA TWO MALE</option>
    <option {{ $oldValue == 'PRE-HADDA TWO FEMALE' ? 'selected' : '' }}>PRE-HADDA TWO FEMALE</option>
    <option {{ $oldValue == 'PRE-HADDA ONE' ? 'selected' : '' }}>PRE-HADDA ONE</option>
    <option {{ $oldValue == 'NONE FOR NOW' ? 'selected' : '' }}>NONE FOR NOW</option>
</select>
