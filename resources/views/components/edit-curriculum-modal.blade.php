<!--===========================================================================================
                              CODES FOR CURRICULUM SCALE RECORD MODAL WINDOW
      ==========================================================================================-->

      <div class="modal fade" id="UpdateCurriculum" data-bs-backdrop="static" tabindex="-1" aria-labelledby="UpdateCurriculumLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="UpdateCurriculum">Curriculum Scale Edit Form {{$curriculum->id}}</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
    
            <form method="POST" action="{{ url('curriculum_scale') }}" enctype="multipart/form-data">
                @csrf
            <div class="modal-body">
                
                {{-- Select Date --}}
                <div>
                    <x-input-label class="font-bold" for="date" :value="__('Select a Date')" />
                    <x-date-picker id="date" class="block mt-1 w-full" type="text" name="date" value="{{ $curriculum->date }}" required/>
                    <x-input-error :messages="$errors->get('date')" class="mt-2" />
                </div>
                {{-- Surah --}}
                <div>
                    <x-input-label class="font-bold" for="sura" :value="__('Select a Surah')" />
                    <x-sura-select id="sura" class="block mt-1 w-full" type="text" name="sura" value="{{ $curriculum->sura }}"/>
                    <x-input-error :messages="$errors->get('sura')" class="mt-2" />
                </div>
                {{-- From --}}
                <div>
                    <x-input-label class="font-bold" for="from" :value="__('From')" />
                    <x-text-input id="from" class="block mt-1 w-full" type="text" name="from" value="{{ $curriculum->from }}"/>
                    <x-input-error :messages="$errors->get('from')" class="mt-2" />
                </div>
                {{-- To --}}
                <div>
                    <x-input-label class="font-bold" for="to" :value="__('To')" />
                    <x-text-input id="to" class="block mt-1 w-full" type="text" name="to" value="{{ $curriculum->to }}"/>
                    <x-input-error :messages="$errors->get('to')" class="mt-2" />
                </div>
                 {{-- Times --}}
                 <div>
                    <x-input-label class="font-bold" for="times" :value="__('Times')" />
                    <x-text-input id="times" class="block mt-1 w-full" type="text" name="times" value="{{ $curriculum->times }}"/>
                    <x-input-error :messages="$errors->get('times')" class="mt-2" />
                </div>
                {{-- Bita --}}
                <div>
                    <x-input-label class="font-bold" for="bita" :value="__('Izu nawa aka Bita Yau?')" />
                    <x-text-input id="bita" class="block mt-1 w-full" type="text" name="bita" value="{{ $curriculum->bita }}"/>
                    <x-input-error :messages="$errors->get('bita')" class="mt-2" />
                </div>
                {{-- Grade --}}
                <div>
                    <x-input-label class="font-bold" for="grade" :value="__('Select a Grade')" />
                    <select id="sura" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="grade">
                      <option>{{ $curriculum->grade }}</option>
                      <option>ممتاز</option>
                      <option>جيدجدا</option>
                      <option>جيد</option>
                      <option>لابأس</option>
                    </select>
                    <x-input-error :messages="$errors->get('grade')" class="mt-2" />
                </div>
                {{-- An Karbi Hadda? --}}
                <div>
                    <x-input-label class="font-bold" for="hadda" :value="__('An Karbi Hadda Yau?')" />
                    <select id="hadda" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="hadda" required>
                    <option>{{ $curriculum->hadda }}</option>
                    <option>Yes</option>
                    <option>No</option>
                    </select>
                    <x-input-error :messages="$errors->get('hadda')" class="mt-2" />
                </div>
                {{-- Comment --}}
                <div>
                    <x-input-label class="font-bold" for="comment" :value="__('Comment')" />
                    <textarea id="comment" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" type="text" name="comment">
                      {{ $curriculum->comment }}
                    </textarea>
                    <x-input-error :messages="$errors->get('comment')" class="mt-2" />
                </div>
              
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
              <button type="submit" class="btn btn-primary" name="save_curriculum">Save</button>
          </div>
        </form>
          </div>
        </div>
      </div>
    <!--======================================================================================
                          END OF CURRICULUM SCALE RECORD MODAL WINDOW CODES
      ========================================================================================-->
      