<div id="editVehicleModal" class="hidden fixed inset-0 bg-gray-500 bg-opacity-50 flex justify-center items-center z-50 p-4">
    <div class="bg-white p-5 w-[400px] rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-3">Edit Vehicle details</h2>
        <form id="editVehicleForm">
            <input type="hidden" id="edit-vehicle-id">

            <div class="mb-3">
                <label class="text-gray-500">Entry Type</label>
                <input type="text" id="edit-entry-type" class="border rounded-lg p-2 w-full">
            </div>

            <div class="mb-3">
                <label class="text-gray-500">Owner Name</label>
                <input type="text" id="edit-owner" class="border rounded-lg p-2 w-full">
            </div>

            <div class="mb-3">
                <label class="text-gray-500">Plate Number</label>
                <input type="text" id="edit-plate-number" class="border rounded-lg p-2 w-full">
            </div>

            <div class="mb-3">
                <label class="text-gray-500">Vehicle Type</label>
                <input type="text" id="edit-vehicle-type" class="border rounded-lg p-2 w-full">
            </div>

            <div class="mb-3">
                <label class="text-gray-500">RFID No.</label>
                <input type="text" id="edit-barcode" class="border rounded-lg p-2 w-full">
            </div>

            <div class="mb-3">
                <label class="text-gray-500">Contact No.</label>
                <input type="tel" id="edit-contact" class="border rounded-lg p-2 w-full">
            </div>

            <div class="flex justify-end space-x-2 mt-4">
                <button type="button" onclick="closeModal()" class="bg-gray-300 px-4 py-2 rounded">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Update</button>
            </div>
        </form>
    </div>
</div>