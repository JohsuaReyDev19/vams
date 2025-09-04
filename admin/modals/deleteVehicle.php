<div id="ownerList" class="grid grid-cols-1 lg:grid-cols-3 gap-2 h-96 overflow-y-scroll">
    <!-- Example Owner Card (Dynamic Data will be populated here via AJAX) -->
    <div class="bg-white p-4 rounded-lg shadow-md flex flex-col justify-between">
        <h3 class="text-lg font-semibold">John Doe</h3>
        <p class="text-gray-500">Vehicle: Car</p>
        <p class="text-gray-500">RFID: 123456789</p>
        <div class="mt-4 flex gap-2">
            <button class="edit-btn bg-yellow-500 text-white px-4 py-2 rounded" 
                data-id="1" data-name="John Doe" data-vehicle="Car" data-rfid="123456789">Edit</button>
            <button class="delete-btn bg-red-500 text-white px-4 py-2 rounded" 
                data-id="1">Delete</button>
        </div>
    </div>
</div>
