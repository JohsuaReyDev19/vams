<div class="max-w-6xl mx-auto space-y-8">
  <div class="bg-white shadow rounded-lg p-6 mb-4">
    <h2 class="text-xl font-semibold text-gray-900">Vehicle Registration</h2>
    <form id="addVehicle" method="POST" class="space-y-4">

      <input type="hidden" id="owner_id" name="owner_id">

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Search Name</label>
        <input type="text" id="searchName" name="searchName" autocomplete="off"
               class="w-full px-4 py-2 border rounded-md focus:outline-none focus:ring focus:ring-indigo-200" required>
        <div id="searchResult" class="bg-white border rounded-md mt-1 hidden fade-in"></div>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Plate/Mv file</label>
        <input type="text" name="plate" id="plate" class="w-full px-4 py-2 border rounded-md" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Purpose</label>
        <input type="text" name="purpose" id="purpose" class="w-full px-4 py-2 border rounded-md" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Vehicle Type</label>
        <input type="text" name="vehicle_type" id="vehicle_type" class="w-full px-4 py-2 border rounded-md" required>
      </div>

      <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Scan RFID</label>
        <input type="text" name="rfid_tag" id="rfid_tag" class="hidden w-full px-4 py-2 border rounded-md" required>
      </div>
      <div class="flex gap-1">
        <span id="scanRfid" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700 cursor-pointer">Scan RFID</span>
        <span id="clear" class="hidden bg-gray-400 text-white px-4 py-2 rounded hover:bg-gray-600">Clear</span>
      </div>
      <div id="success" class="hidden">
        <div role="alert" class="alert alert-success alert-soft">
          <span>Vehicle successfully added.</span>
        </div>
      </div>
      <button type="submit" class="w-full bg-gray-800 text-white px-4 py-2 rounded hover:bg-gray-700">Add Vehicle</button>
    </form>
  </div>
</div>