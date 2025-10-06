<div class="flex flex-wrap items-end gap-3">
  <p class="font-medium text-gray-700 w-full md:w-auto">Set the date of Semester</p>

  <!-- Start Date -->
  <div class="relative">
    <input type="date" id="startDateInput"
      class="peer p-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-sm text-sm w-40">
    <label for="startDateInput"
      class="absolute left-2 -top-2.5 bg-white px-1 text-xs text-gray-600 transition-all 
             peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 
             peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-500">
      From
    </label>
  </div>

  <!-- End Date -->
  <div class="relative">
    <input type="date" id="endDateInput"
      class="peer p-2 border rounded-lg focus:ring-2 focus:ring-blue-400 focus:border-transparent shadow-sm text-sm w-40">
    <label for="endDateInput"
      class="absolute left-2 -top-2.5 bg-white px-1 text-xs text-gray-600 transition-all 
             peer-placeholder-shown:top-2 peer-placeholder-shown:text-gray-400 
             peer-placeholder-shown:text-sm peer-focus:-top-2.5 peer-focus:text-xs peer-focus:text-blue-500">
      To
    </label>
  </div>

  <!-- Buttons -->
  <div class="flex gap-2 mt-2 md:mt-0">
    <button id="StartSemesterBtn"
      class="px-4 py-2 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg shadow-sm text-sm transition duration-150 ease-in-out">
      Start Semester
    </button>
    <button id="EndSemesterBtn"
      class="hidden px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-medium rounded-lg shadow-sm text-sm transition duration-150 ease-in-out">
      End Semester
    </button>
  </div>
</div>
