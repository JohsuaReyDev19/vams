
<div id="crud-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <!-- Modal content -->
        <div class="relative bg-white rounded-lg shadow-sm p-3">
            <!-- Modal header -->
            <div class="flex items-center justify-between p-4 md:p-5 border-b rounded-t dark:border-gray-600 border-gray-200">
                <h3 class="text-lg font-semibold">
                    Register New Vehicle
                </h3>
            </div>
            <!-- Modal body -->
            <form id="registrationForm" class="p-4 md:p-5">
                <div class="grid gap-4 mb-4 grid-cols-2">
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-500">Name</label>
                        <input type="text" name="owner_name" id="name" class="border rounded-lg p-2 w-full" placeholder="Enter Owner name" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-500">Purpose</label>
                        <select id="category" name="entry_type" class="border rounded-lg p-2 w-full">
                            <option selected></option>
                            <option >Student</option>
                            <option >Faculty</option>
                            <option >Ojt</option>
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="category" class="block mb-2 text-sm font-medium text-gray-500">Vehicle Type</label>
                        <select id="category" name="vehicle_type" class="border rounded-lg p-2 w-full">
                            <option selected></option>
                            <option >Car</option>
                            <option >Motorcycle</option>
                            <option >Truck</option>
                        </select>
                    </div>
                    <div class="col-span-2 sm:col-span-1">
                        <label for="price" class="block mb-2 text-sm font-medium text-gray-500">Plate/MV file</label>
                        <input type="text" name="plate_number" id="price" class="border rounded-lg p-2 w-full" placeholder="Enter plate number" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-500">RFID Number</label>
                        <input type="text" name="rfid_number" id="name" class="border rounded-lg p-2 w-full" placeholder="Enter Rfid number" required="">
                    </div>
                    <div class="col-span-2">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-500">Contact Number</label>
                        <input type="tel" name="contact_number" id="name" class="border rounded-lg p-2 w-full" placeholder="Enter Contact number" required="">
                    </div>
                </div>
                <div class="text-end">
                    <button data-modal-toggle="crud-modal" type="submit" class="bg-gray-300 text-black px-4 py-2 rounded">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">
                        Register Now
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>