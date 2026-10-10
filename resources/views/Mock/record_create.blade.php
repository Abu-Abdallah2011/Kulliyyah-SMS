<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ __('Add Mock Record') }}</h2>
    </x-slot>

    <div class="py-8 bg-gray-50">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-4 rounded-xl border border-indigo-100 bg-white p-4 shadow-sm">
                <p class="text-xs font-black uppercase tracking-wide text-indigo-700">{{ $cycle->typeLabel() }}</p>
                <h3 class="text-xl font-black text-gray-900">{{ $cycle->student?->fullname }}</h3>
                <p class="text-sm text-gray-600">{{ $cycle->student?->class }} - Assigned to {{ $cycle->teacher?->fullname }}</p>
            </div>

            <div class="bg-white shadow-sm sm:rounded-lg p-6">
                <form method="POST" action="{{ route('mock_records.store', $cycle->id) }}" class="space-y-4" data-mock-page-count-form>
                    @csrf

                    <div>
                        <x-input-label for="date" :value="__('Date')" />
                        <x-date-picker id="date" name="date" class="block mt-1 w-full" value="{{ old('date') }}" required />
                        <x-input-error :messages="$errors->get('date')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="from_surah" :value="__('From Surah')" />
                        <select id="from_surah" name="from_surah" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($sura as $item)
                                <option value="{{ $item->id }}" @selected(old('from_surah') == $item->id)>{{ $item->sura }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="from" :value="__('From Page')" />
                        <x-text-input id="from" name="from" class="block mt-1 w-full" type="text" value="{{ old('from') }}" required data-mock-from-page />
                        <x-input-error :messages="$errors->get('from')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="to_surah" :value="__('To Surah')" />
                        <select id="to_surah" name="to_surah" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            @foreach($surah as $item)
                                <option value="{{ $item->id }}" @selected(old('to_surah') == $item->id)>{{ $item->sura }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <x-input-label for="to" :value="__('To Page')" />
                        <x-text-input id="to" name="to" class="block mt-1 w-full" type="text" value="{{ old('to') }}" required data-mock-to-page />
                        <x-input-error :messages="$errors->get('to')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="page_count" :value="__('Page Count')" />
                        <x-text-input id="page_count" class="block mt-1 w-full bg-gray-100 text-gray-700" type="text" readonly data-mock-page-count />
                        <p class="mt-1 text-sm text-gray-500" data-mock-page-count-message>Enter the from and to pages to calculate page count.</p>
                    </div>

                    <div>
                        <x-input-label for="grade" :value="__('Grade')" />
                        <select id="grade" name="grade" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
                            <option value="">Select grade</option>
                            <option @selected(old('grade') === 'ممتاز')>ممتاز</option>
                            <option @selected(old('grade') === 'جيدجدا')>جيدجدا</option>
                            <option @selected(old('grade') === 'جيد')>جيد</option>
                            <option @selected(old('grade') === 'مقبول')>مقبول</option>
                        </select>
                    </div>

                    <div>
                        <x-input-label for="comment" :value="__('Comment')" />
                        <textarea id="comment" name="comment" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" rows="4">{{ old('comment') }}</textarea>
                    </div>

                    <div class="flex items-center gap-3">
                        <x-primary-button data-mock-submit>Save Record</x-primary-button>
                        <a href="{{ route('mock_cycles.show', $cycle->id) }}" class="text-sm font-bold text-gray-600 hover:underline">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const form = document.querySelector('[data-mock-page-count-form]');
            const fromPage = form?.querySelector('[data-mock-from-page]');
            const toPage = form?.querySelector('[data-mock-to-page]');
            const pageCount = form?.querySelector('[data-mock-page-count]');
            const message = form?.querySelector('[data-mock-page-count-message]');
            const submitButton = form?.querySelector('[data-mock-submit]');

            const normalizeNumber = (value) => {
                const normalized = value.trim().replace(/,/g, '').replace(/[\u0660-\u0669\u06F0-\u06F9]/g, (digit) => {
                    const code = digit.charCodeAt(0);
                    return String(code >= 0x06F0 ? code - 0x06F0 : code - 0x0660);
                });

                return /^\d+$/.test(normalized) ? Number.parseInt(normalized, 10) : null;
            };

            const setCanSubmit = (canSubmit) => {
                if (!submitButton) {
                    return;
                }

                submitButton.disabled = !canSubmit;
                submitButton.classList.toggle('opacity-50', !canSubmit);
                submitButton.classList.toggle('cursor-not-allowed', !canSubmit);
            };

            const updatePageCount = () => {
                const from = normalizeNumber(fromPage?.value || '');
                const to = normalizeNumber(toPage?.value || '');

                if (!from || !to || from < 1 || to < 1 || from > 604 || to > 604 || from > to) {
                    pageCount.value = '';
                    message.textContent = 'Enter a valid Quran page range between 1 and 604.';
                    setCanSubmit(false);
                    return;
                }

                const pages = (to - from) + 1;
                pageCount.value = pages;
                message.textContent = `${pages} ${pages === 1 ? 'page' : 'pages'} will be counted from this mock record.`;
                setCanSubmit(true);
            };

            fromPage?.addEventListener('input', updatePageCount);
            toPage?.addEventListener('input', updatePageCount);
            updatePageCount();
        });
    </script>
</x-app-layout>
