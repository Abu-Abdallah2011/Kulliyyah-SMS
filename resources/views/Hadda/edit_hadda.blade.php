      <x-app-layout>
        <x-slot name="header">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Curriculum Edit Form') }}
            </h2>
        </x-slot>
    
        <div class="py-12">
    
    
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                
                <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                    
                        <section>
    
                <form method="POST" action="/hadda_page/{{$hadda->id}}" enctype="multipart/form-data" data-hadda-page-count-form>
                @csrf
                @method('PUT')
            
                 <!-- Date -->
                 <div>
                    <x-input-label for="date" :value="__('Date')" />
                    <x-date-picker id="date" class="block mt-1 w-full" type="text" name="date" value="{{ $hadda->date }}" required autocomplete="date" />
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>
        
                <!--From Surah -->
                <div>
                    <x-input-label for="sura" :value="__('From Surah')" />
                    <select name="from_surah" id="from_surah" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                        <option>{{$hadda->sura}}</option>
                    @foreach ($sura as $sura)
                    <option value="{{ $sura->id }}">{{ $sura->sura }}</option>
                    @endforeach
                    </select>
                </div>
               
               <!-- From Verse -->
               <div>
                <x-input-label for="from" :value="__('From Page')" />
                <x-text-input id="from" class="block mt-1 w-full" type="text" name="from" value="{{ old('from', $hadda->from) }}" required autofocus autocomplete="from" data-hadda-from-page />
                <x-input-error :messages="$errors->get('from')" class="mt-2" />
            </div>

            <!--To Surah -->
                <div>
                    <x-input-label for="to_surah" :value="__('To Surah')" />
                    <select name="to_surah" id="to_surah" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text">
                        <option>{{$hadda->to_surah}}</option>
                    @foreach ($surah as $sura)
                    <option value="{{ $sura->id }}">{{ $sura->sura }}</option>
                    @endforeach
                    </select>
                </div>
        
               <!-- To Verse-->
               <div>
                <x-input-label for="to" :value="__('To Page')" />
                <x-text-input id="to" class="block mt-1 w-full" type="text" name="to" value="{{ old('to', $hadda->to) }}" required autofocus autocomplete="to" data-hadda-to-page />
                <x-input-error :messages="$errors->get('to')" class="mt-2" />
            </div>

            <!-- Page Count-->
               <div>
                <x-input-label for="score" :value="__('Page Count')" />
                <x-text-input id="score" class="block mt-1 w-full bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-200" type="text" value="" readonly autocomplete="off" data-hadda-page-count />
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400" data-hadda-page-count-message>
                    Enter the from and to pages to calculate the page count.
                </p>
                <div class="mt-2 hidden rounded-md border px-3 py-2 text-sm" data-hadda-page-alert></div>
                <x-input-error :messages="$errors->get('score')" class="mt-2" />
            </div>
        
               <!-- Grade -->
               <div>
                <x-input-label for="grade" :value="__('Select a Grade')" />
                <select id="grade" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="grade">
                    <option>{{$hadda->grade}}</option>
                    <option>ممتاز</option>
                    <option>جيدجدا</option>
                    <option>جيد</option>
                    <option>مقبول</option>
                </select>
        
            {{-- Comment --}}
<div>
    <x-input-label for="comment" :value="__('Comment')" />
    <textarea id="comment" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="comment">
        {{ $hadda->comment }}
    </textarea>
    <x-input-error :messages="$errors->get('comment')" class="mt-2" />
</div>
                
              
                <br/>
                <div>
                    <x-primary-button class="ml-3" data-hadda-submit>
                        {{ __('Update') }}
                    </x-primary-button>
                </div>
        </form>

    </section>
</div>
</div>
</div>
</x-app-layout>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('[data-hadda-page-count-form]');
    const fromPage = form?.querySelector('[data-hadda-from-page]');
    const toPage = form?.querySelector('[data-hadda-to-page]');
    const pageCount = form?.querySelector('[data-hadda-page-count]');
    const pageCountMessage = form?.querySelector('[data-hadda-page-count-message]');
    const pageAlert = form?.querySelector('[data-hadda-page-alert]');
    const submitButton = form?.querySelector('[data-hadda-submit]');
    const existingPageRanges = @json($existingPageRanges ?? []);

    const normalizeNumber = (value) => {
        const normalized = value.trim().replace(/,/g, '').replace(/[\u0660-\u0669\u06F0-\u06F9]/g, (digit) => {
            const code = digit.charCodeAt(0);
            return String(code >= 0x06F0 ? code - 0x06F0 : code - 0x0660);
        });

        return /^\d+$/.test(normalized) ? Number.parseInt(normalized, 10) : null;
    };

    const setAlert = (message, type = 'info') => {
        if (!pageAlert) {
            return;
        }

        pageAlert.textContent = message || '';
        pageAlert.classList.toggle('hidden', !message);
        pageAlert.classList.toggle('border-red-200', type === 'error');
        pageAlert.classList.toggle('bg-red-50', type === 'error');
        pageAlert.classList.toggle('text-red-700', type === 'error');
        pageAlert.classList.toggle('border-amber-200', type === 'warning');
        pageAlert.classList.toggle('bg-amber-50', type === 'warning');
        pageAlert.classList.toggle('text-amber-800', type === 'warning');
    };

    const setCanSubmit = (canSubmit) => {
        if (submitButton) {
            submitButton.disabled = !canSubmit;
            submitButton.classList.toggle('opacity-50', !canSubmit);
            submitButton.classList.toggle('cursor-not-allowed', !canSubmit);
        }
    };

    const findOverlap = (from, to) => existingPageRanges.find((range) => {
        return from <= Number(range.to) && to >= Number(range.from);
    });

    const findSkippedPages = (to) => {
        const latestRange = existingPageRanges
            .slice()
            .sort((a, b) => {
                const dateDifference = Number(b.record_date_timestamp || 0) - Number(a.record_date_timestamp || 0);
                return dateDifference !== 0 ? dateDifference : Number(b.id || 0) - Number(a.id || 0);
            })[0];

        if (!latestRange || to >= Number(latestRange.from) - 1) {
            return null;
        }

        return {
            from: to + 1,
            to: Number(latestRange.from) - 1,
        };
    };

    const updatePageCount = () => {
        if (!fromPage || !toPage || !pageCount || !pageCountMessage) {
            return;
        }

        const from = normalizeNumber(fromPage.value);
        const to = normalizeNumber(toPage.value);

        if (!from || !to || from < 1 || to < 1 || from > 604 || to > 604) {
            pageCount.value = '';
            pageCountMessage.textContent = 'Enter valid Quran page numbers between 1 and 604.';
            setAlert('Page numbers must be between 1 and 604.', 'error');
            setCanSubmit(false);
            return;
        }

        if (from > to) {
            pageCount.value = '';
            pageCountMessage.textContent = 'Enter the lower page first and the higher page last.';
            setAlert('This page range is in reverse order. Please correct it before saving.', 'error');
            setCanSubmit(false);
            return;
        }

        const overlap = findOverlap(from, to);
        if (overlap) {
            pageCount.value = '';
            pageCountMessage.textContent = 'This range overlaps with a previous Hadda record.';
            setAlert(`This overlaps with an existing record: pages ${overlap.from}-${overlap.to}${overlap.date ? ' on '+overlap.date : ''}. Please correct it before saving.`, 'error');
            setCanSubmit(false);
            return;
        }

        const pages = (to - from) + 1;
        pageCount.value = pages;
        pageCountMessage.textContent = `${pages} ${pages === 1 ? 'page' : 'pages'} will be counted from this Hadda record.`;
        setCanSubmit(true);

        const skippedPages = findSkippedPages(to);
        if (skippedPages) {
            const skippedText = skippedPages.from === skippedPages.to
                ? `page ${skippedPages.from}`
                : `pages ${skippedPages.from}-${skippedPages.to}`;
            setAlert(`Warning: this appears to skip ${skippedText}. You may still save if this is correct.`, 'warning');
            return;
        }

        setAlert('');
    };

    fromPage?.addEventListener('input', updatePageCount);
    toPage?.addEventListener('input', updatePageCount);
    updatePageCount();
});
</script>
