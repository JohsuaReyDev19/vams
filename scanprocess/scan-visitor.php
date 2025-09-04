<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>RFID Vehicle Access System</title>
    <style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: url('../../480156258_1036463918529354_3266044399125512979_n.jpg') no-repeat center center fixed;
        background-size: cover;
        position: relative;
        min-height: 100vh;
        color: hsl(40, 50%, 20%);
    }

    body::before {
        content: '';
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(229, 231, 235, 0.86); /* Tailwind: bg-gray-200 with bg-opacity-80 */
        z-index: -1;
    }

    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 1rem;
    }

    .header {
        text-align: center;
        margin-bottom: 2rem;
    }

    .header h1 {
        font-size: 2.5rem;
        font-weight: bold;
        background: linear-gradient(135deg, hsl(40, 80%, 50%), hsl(35, 100%, 45%));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        margin-bottom: 0.5rem;
    }

    .header p {
        font-size: 1.1rem;
        color: hsl(40, 20%, 40%);
    }

    .grid {
        display: grid;
        grid-template-columns: 1fr 1fr 1fr;
        gap: 1.5rem;
    }

    @media (max-width: 1024px) {
        .grid {
            grid-template-columns: 1fr;
        }
    }

    .card {
        background: hsl(40, 50%, 98%);
        border: 1px solid hsl(40, 40%, 85%);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: 0 4px 20px rgba(180, 83, 9, 0.05);
    }

    .scanner-card {
        text-align: center;
    }

    .scanner-icon {
        width: 4rem;
        height: 4rem;
        background: linear-gradient(135deg, hsl(40, 80%, 55%), hsl(35, 100%, 60%));
        border-radius: 50%;
        margin: 0 auto 1rem auto;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        color: white;
    }

    .scanner-input {
        width: 100%;
        padding: 0.75rem;
        border: 1px solid hsl(40, 40%, 75%);
        border-radius: 8px;
        background: hsl(40, 60%, 98%);
        color: hsl(40, 20%, 20%);
        font-size: 1rem;
        margin-bottom: 1rem;
    }

    .scanner-input:focus {
        outline: none;
        border-color: hsl(40, 80%, 60%);
        box-shadow: 0 0 0 2px hsla(40, 80%, 60%, 0.2);
    }

    .btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        width: 100%;
    }

    .btn-primary {
        background: linear-gradient(135deg, hsl(40, 80%, 55%), hsl(35, 100%, 60%));
        color: white;
    }

    .btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 5px 15px hsla(40, 80%, 60%, 0.3);
    }

    .btn-secondary {
        background: hsl(40, 30%, 90%);
        color: hsl(40, 20%, 30%);
        border: 1px solid hsl(40, 40%, 75%);
    }

    .btn-secondary:hover {
        background: hsl(40, 30%, 85%);
    }

    .btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .loading {
        display: inline-block;
        width: 1rem;
        height: 1rem;
        border: 2px solid transparent;
        border-top: 2px solid currentColor;
        border-radius: 50%;
        animation: spin 1s linear infinite;
        margin-right: 0.5rem;
    }

    @keyframes spin {
        to { transform: rotate(360deg); }
    }

    .alert {
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .alert-success {
        background: hsl(140, 60%, 95%);
        border: 1px solid hsl(140, 60%, 75%);
        color: hsl(140, 40%, 30%);
    }

    .alert-error {
        background: hsl(0, 80%, 95%);
        border: 1px solid hsl(0, 80%, 75%);
        color: hsl(0, 60%, 35%);
    }

    .alert-warning {
        background: hsl(40, 90%, 95%);
        border: 1px solid hsl(40, 90%, 75%);
        color: hsl(40, 70%, 30%);
    }

    .vehicle-info h3,
    .access-log h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: hsl(40, 80%, 45%);
    }

    .vehicle-detail {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px solid hsl(40, 40%, 85%);
    }

    .vehicle-detail:last-child {
        border-bottom: none;
    }

    .vehicle-detail strong {
        color: hsl(40, 20%, 45%);
    }

    .log-entry {
        padding: 0.75rem;
        border: 1px solid hsl(40, 40%, 80%);
        border-radius: 8px;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
    }

    .log-entry.allowed {
        border-color: hsl(140, 60%, 50%);
        background: hsl(140, 60%, 97%);
    }

    .log-entry.denied {
        border-color: hsl(0, 60%, 50%);
        background: hsl(0, 60%, 97%);
    }

    .log-entry.pending {
        border-color: hsl(40, 80%, 50%);
        background: hsl(40, 80%, 97%);
    }

    .log-time {
        color: hsl(40, 20%, 50%);
    }

    .placeholder {
        text-align: center;
        padding: 2rem;
        color: hsl(40, 20%, 50%);
    }

    .placeholder-icon {
        width: 4rem;
        height: 4rem;
        margin: 0 auto 1rem auto;
        background: hsl(40, 30%, 90%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .hidden {
        display: none;
    }

    .mode-btn {
        padding: 0.5rem 1rem;
        border: none;
        border-radius: 6px;
        font-size: 0.875rem;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        background: transparent;
        color: hsl(40, 30%, 40%);
    }

    .mode-btn.active {
        background: hsl(40, 80%, 55%);
        color: white;
    }

    .mode-btn:hover:not(.active) {
        background: hsl(40, 30%, 85%);
        color: hsl(40, 20%, 30%);
    }
</style>

</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>Vehicle Access Monitoring</h1>
            <p>Secure visitor entry monitoring</p>
        </div>

        <div class="grid">
            <!-- Left Column - Scanner -->
            <div class="card scanner-card">
                <!-- Mode Toggle -->
               <div style="display: flex; margin-bottom: 1rem; border-radius: 8px; background: hsl(210, 60%, 96%); padding: 4px; border: 1px solid hsl(210, 40%, 85%);">
                    <button id="rfidModeBtn" class="mode-btn active hidden" style="flex: 1;">
                        🏷️ RFID Scan
                    </button>
                    <button id="visitorModeBtn" class="mode-btn" style="flex: 1;">
                        👤 Visitor Entry
                    </button>
                </div>
                <!-- RFID Mode -->
                <div id="rfidMode">
                    <div class="scanner-icon">🏷️</div>
                    <h3 style="margin-bottom: 1rem; color: hsl(200, 100%, 60%);">RFID Scanner</h3>
                    <input 
                        type="text" 
                        id="rfidInput" 
                        class="scanner-input" 
                        placeholder="Enter RFID tag (e.g., TAG001, TAG002, TAG003)"
                        maxlength="20"
                    >
                    <button id="scanBtn" class="btn btn-primary">
                        <span id="scanBtnText">Scan RFID Tag</span>
                    </button>
                </div>

                <!-- Visitor Mode -->
                <div id="visitorMode" class="">
                    <div class="scanner-icon">👤</div>
                    <h3 style="margin-bottom: 1rem; color: hsl(200, 100%, 60%);">Visitor Entry</h3>
                    <form id="visitorForm" style="display: flex; flex-direction: column; gap: 0.75rem;">
                        <input type="text" id="visitorLicense" class="scanner-input" placeholder="License Plate" required maxlength="10">
                        <input type="text" id="visitorName" class="scanner-input" placeholder="Owner Name" required maxlength="50">
                        <input type="text" id="visitorPhone" class="scanner-input" placeholder="Phone Number" required maxlength="15">
                        <select id="visitorVehicleType" class="scanner-input" required>
                            <option value="">Select Vehicle Type</option>
                            <option value="Sedan">Sedan</option>
                            <option value="SUV">SUV</option>
                            <option value="Truck">Truck</option>
                            <option value="Van">Van</option>
                            <option value="Motorcycle">Motorcycle</option>
                        </select>
                        <input type="text" id="visitorColor" class="scanner-input" placeholder="Vehicle Color" required maxlength="20">
                        <textarea id="visitorPurpose" class="scanner-input" placeholder="Purpose of visit" rows="2" maxlength="100"></textarea>
                        <button type="submit" class="btn btn-primary">
                            Register Visitor
                        </button>
                    </form>
                </div>

                <button id="clearBtn" class="btn btn-secondary hidden" style="margin-top: 0.5rem;">
                    🔄 Clear & Scan Next
                </button>
            </div>

            <!-- Middle Column - Vehicle Information -->
            <div class="card">
                <div id="alertContainer"></div>
                
                <div id="vehicleInfo">
                    <div class="vehicle-info">
                        <h3>Vehicle Information</h3>
                        <div id="vehicleDetails">
                            <div class="vehicle-detail">
                                <strong>License Plate:</strong>
                                <span>UHD-9726</span>
                            </div>
                            <div class="vehicle-detail">
                                <strong>Owner:</strong>
                                <span>Joshua Rey Burce</span>
                            </div>
                            <div class="vehicle-detail">
                                <strong>Vehicle Type:</strong>
                                <span>Van</span>
                            </div>
                            <div class="vehicle-detail">
                                <strong>Color:</strong>
                                <span>blue</span>
                            </div>
                            <div class="vehicle-detail">
                                <strong>Phone:</strong>
                                <span>09667248923</span>
                            </div>
                            <div class="vehicle-detail">
                                <strong>Purpose of Visit:</strong>
                                <span>Not specified</span>
                            </div>
                            <br>
                            <br>
                            <div class="vehicle-detail">
                                <strong>Record this to Access logs?</strong>
                                <span>action</span>
                            </div>
                            <div class="vehicle-detail mt-3">
                                <strong></strong>
                                <span>
                                    <button style="
                                    background-color: #c23e0aff;
                                    color: white;
                                    padding: 0.4rem 1.2rem;
                                    border: none;
                                    border-radius: 6px;
                                    cursor: pointer;
                                    transition: background 0.3s ease;
                                    " 
                                    onmouseover="this.style.backgroundColor='#dc400cff'"
                                    onmouseout="this.style.backgroundColor='#c23b0aff'">
                                        Cancel
                                    </button>
                                    <button style="
                                    background-color: hsla(99, 94%, 40%, 1.00);
                                    color: white;
                                    padding: 0.4rem 1.2rem;
                                    border: none;
                                    border-radius: 6px;
                                    cursor: pointer;
                                    transition: background 0.3s ease;
                                    " 
                                    onmouseover="this.style.backgroundColor='#44c20aff'"
                                    onmouseout="this.style.backgroundColor='#3aa608ff'">
                                        Entry
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="placeholder" class="placeholder hidden">
                    <div class="placeholder-icon">🛡️</div>
                    <h3 style="color: hsl(220, 15%, 50%);"></h3>
                    <p>manually to view vehicle information</p>
                </div>
            </div>

            <!-- Right Column - Access Log -->
            <div class="card">
                <div class="access-log">
                    <h3>Access Log</h3>
                    <div id="accessLogEntries">
                        <div class="log-entry">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                                <strong>ABC-1234</strong>
                                <span class="log-time">2:20:53 PM</span>
                            </div>
                            <div style="color: hsl(220, 15%, 65%); font-size: 0.8rem;">
                                Johsua Burce • Motor • entry
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- <script>
        // Mock vehicle data
        const mockVehicles = {
            'TAG001': {
                id: '1',
                licensePlate: 'ABC-1234',
                ownerName: 'John Smith',
                vehicleType: 'Sedan',
                color: 'Blue',
                make: 'Toyota',
                model: 'Camry',
                year: '2020',
                rfidTag: 'TAG001',
                accessLevel: 'allowed',
                phoneNumber: '+1-555-0123',
                email: 'john.smith@email.com'
            },
            'TAG002': {
                id: '2',
                licensePlate: 'XYZ-5678',
                ownerName: 'Sarah Johnson',
                vehicleType: 'SUV',
                color: 'White',
                make: 'Honda',
                model: 'CR-V',
                year: '2021',
                rfidTag: 'TAG002',
                accessLevel: 'allowed',
                phoneNumber: '+1-555-0456',
                email: 'sarah.johnson@email.com'
            },
            'TAG003': {
                id: '3',
                licensePlate: 'DEF-9012',
                ownerName: 'Mike Wilson',
                vehicleType: 'Truck',
                color: 'Red',
                make: 'Ford',
                model: 'F-150',
                year: '2019',
                rfidTag: 'TAG003',
                accessLevel: 'denied',
                phoneNumber: '+1-555-0789',
                email: 'mike.wilson@email.com'
            }
        };

        // Access log storage
        let accessEntries = [
            {
                id: '1',
                vehicleId: '1',
                rfidTag: 'TAG001',
                timestamp: new Date(Date.now() - 300000),
                status: 'allowed',
                gateName: 'Main Gate',
                notes: 'Regular entry'
            },
            {
                id: '2',
                vehicleId: '2',
                rfidTag: 'TAG002',
                timestamp: new Date(Date.now() - 600000),
                status: 'allowed',
                gateName: 'North Gate',
                notes: 'Visitor entry'
            },
            {
                id: '3',
                vehicleId: '3',
                rfidTag: 'TAG003',
                timestamp: new Date(Date.now() - 900000),
                status: 'denied',
                gateName: 'Main Gate',
                notes: 'Access revoked'
            }
        ];

        // DOM elements
        const rfidInput = document.getElementById('rfidInput');
        const scanBtn = document.getElementById('scanBtn');
        const clearBtn = document.getElementById('clearBtn');
        const scanBtnText = document.getElementById('scanBtnText');
        const alertContainer = document.getElementById('alertContainer');
        const vehicleInfo = document.getElementById('vehicleInfo');
        const placeholder = document.getElementById('placeholder');
        const vehicleDetails = document.getElementById('vehicleDetails');
        const accessLogEntries = document.getElementById('accessLogEntries');
        
        // Mode switching elements
        const rfidModeBtn = document.getElementById('rfidModeBtn');
        const visitorModeBtn = document.getElementById('visitorModeBtn');
        const rfidMode = document.getElementById('rfidMode');
        const visitorMode = document.getElementById('visitorMode');
        const visitorForm = document.getElementById('visitorForm');

        // Current mode tracking
        let currentMode = 'rfid';

        // Initialize
        renderAccessLog();

        // Event listeners
        scanBtn.addEventListener('click', handleScan);
        clearBtn.addEventListener('click', handleClear);
        rfidInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                handleScan();
            }
        });

        // Mode switching event listeners
        rfidModeBtn.addEventListener('click', () => switchMode('rfid'));
        visitorModeBtn.addEventListener('click', () => switchMode('visitor'));
        visitorForm.addEventListener('submit', handleVisitorSubmit);

        async function handleScan() {
            const rfidTag = rfidInput.value.trim().toUpperCase();
            
            if (!rfidTag) {
                showAlert('Please enter an RFID tag', 'error');
                return;
            }

            // Show loading state
            scanBtn.disabled = true;
            scanBtnText.innerHTML = '<span class="loading"></span>Scanning...';
            clearAlert();

            try {
                // Simulate API delay
                await new Promise(resolve => setTimeout(resolve, 1000));
                
                const vehicle = mockVehicles[rfidTag];
                
                if (vehicle) {
                    displayVehicle(vehicle);
                    addAccessEntry(vehicle);
                    showStatusAlert(vehicle.accessLevel);
                    clearBtn.classList.remove('hidden');
                } else {
                    showAlert(`RFID tag "${rfidTag}" not found in system`, 'error');
                    hideVehicle();
                }
            } catch (error) {
                showAlert('Failed to process RFID scan', 'error');
                hideVehicle();
            } finally {
                scanBtn.disabled = false;
                scanBtnText.textContent = 'Scan RFID Tag';
            }
        }

        function handleClear() {
            rfidInput.value = '';
            clearAlert();
            hideVehicle();
            clearBtn.classList.add('hidden');
            rfidInput.focus();
        }

        function displayVehicle(vehicle) {
            vehicleDetails.innerHTML = `
                <div class="vehicle-detail">
                    <strong>License Plate:</strong>
                    <span>${vehicle.licensePlate}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Owner:</strong>
                    <span>${vehicle.ownerName}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Vehicle:</strong>
                    <span>${vehicle.year} ${vehicle.make} ${vehicle.model}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Type:</strong>
                    <span>${vehicle.vehicleType}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Color:</strong>
                    <span>${vehicle.color}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>RFID Tag:</strong>
                    <span>${vehicle.rfidTag}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Phone:</strong>
                    <span>${vehicle.phoneNumber}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Email:</strong>
                    <span>${vehicle.email}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Access Level:</strong>
                    <span style="color: ${getAccessColor(vehicle.accessLevel)}; font-weight: bold; text-transform: uppercase;">
                        ${vehicle.accessLevel}
                    </span>
                </div>
            `;
            vehicleInfo.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }

        function hideVehicle() {
            vehicleInfo.classList.add('hidden');
            placeholder.classList.remove('hidden');
        }

        function showAlert(message, type) {
            const iconMap = {
                success: '✅',
                error: '❌',
                warning: '⚠️'
            };
            
            alertContainer.innerHTML = `
                <div class="alert alert-${type}">
                    <span>${iconMap[type]}</span>
                    <span>${message}</span>
                </div>
            `;
        }

        function showStatusAlert(accessLevel) {
            const messages = {
                allowed: 'Access granted. Vehicle is authorized to enter the Campus.',
                denied: 'Access denied. This vehicle is not authorized to enter.',
                pending: 'Access pending. Vehicle requires manual approval for entry.'
            };
            
            const types = {
                allowed: 'success',
                denied: 'error',
                pending: 'warning'
            };
            
            showAlert(messages[accessLevel], types[accessLevel]);
        }

        function clearAlert() {
            alertContainer.innerHTML = '';
        }

        function addAccessEntry(vehicle) {
            const newEntry = {
                id: Date.now().toString(),
                vehicleId: vehicle.id,
                rfidTag: vehicle.rfidTag,
                timestamp: new Date(),
                status: vehicle.accessLevel,
                gateName: 'Main Gate',
                notes: `${vehicle.accessLevel === 'allowed' ? 'Access granted' : 
                         vehicle.accessLevel === 'denied' ? 'Access denied' : 'Pending approval'}`
            };
            
            accessEntries.unshift(newEntry);
            accessEntries = accessEntries.slice(0, 10); // Keep last 10 entries
            renderAccessLog();
        }

        function renderAccessLog() {
            accessLogEntries.innerHTML = accessEntries.map(entry => {
                const vehicle = Object.values(mockVehicles).find(v => v.id === entry.vehicleId);
                return `
                    <div class="log-entry ${entry.status}">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.25rem;">
                            <strong>${vehicle ? vehicle.licensePlate : 'Unknown Vehicle'}</strong>
                            <span class="log-time">${entry.timestamp.toLocaleTimeString()}</span>
                        </div>
                        <div style="color: hsl(220, 15%, 65%); font-size: 0.8rem;">
                            ${entry.gateName} • ${entry.rfidTag} • ${entry.notes}
                        </div>
                    </div>
                `;
            }).join('');
        }

        function getAccessColor(accessLevel) {
            const colors = {
                allowed: 'hsl(120, 60%, 70%)',
                denied: 'hsl(0, 60%, 70%)',
                pending: 'hsl(45, 60%, 70%)'
            };
            return colors[accessLevel] || 'hsl(220, 15%, 65%)';
        }

        // Mode switching function
        function switchMode(mode) {
            currentMode = mode;
            
            if (mode === 'rfid') {
                rfidModeBtn.classList.add('active');
                visitorModeBtn.classList.remove('active');
                rfidMode.classList.remove('hidden');
                visitorMode.classList.add('hidden');
                clearAlert();
                hideVehicle();
                clearBtn.classList.add('hidden');
                rfidInput.focus();
            } else if (mode === 'visitor') {
                visitorModeBtn.classList.add('active');
                rfidModeBtn.classList.remove('active');
                visitorMode.classList.remove('hidden');
                rfidMode.classList.add('hidden');
                clearAlert();
                hideVehicle();
                clearBtn.classList.add('hidden');
                document.getElementById('visitorLicense').focus();
            }
        }

        // Visitor form submission handler
        async function handleVisitorSubmit(e) {
            e.preventDefault();
            
            const formData = {
                licensePlate: document.getElementById('visitorLicense').value.trim().toUpperCase(),
                ownerName: document.getElementById('visitorName').value.trim(),
                phoneNumber: document.getElementById('visitorPhone').value.trim(),
                vehicleType: document.getElementById('visitorVehicleType').value,
                color: document.getElementById('visitorColor').value.trim(),
                purpose: document.getElementById('visitorPurpose').value.trim()
            };

            // Show loading state
            const submitBtn = e.target.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="loading"></span>Registering...';
            clearAlert();

            try {
                // Simulate API delay
                await new Promise(resolve => setTimeout(resolve, 1500));
                
                // Create visitor vehicle object
                const visitorVehicle = {
                    id: 'V' + Date.now(),
                    licensePlate: formData.licensePlate,
                    ownerName: formData.ownerName,
                    vehicleType: formData.vehicleType,
                    color: formData.color,
                    make: 'N/A',
                    model: 'N/A',
                    year: 'N/A',
                    rfidTag: 'VISITOR-' + formData.licensePlate,
                    accessLevel: 'pending',
                    phoneNumber: formData.phoneNumber,
                    email: 'N/A',
                    purpose: formData.purpose,
                    isVisitor: true
                };

                displayVisitorVehicle(visitorVehicle);
                addVisitorAccessEntry(visitorVehicle);
                showStatusAlert('pending');
                clearBtn.classList.remove('hidden');
                
                // Clear form
                visitorForm.reset();
                
            } catch (error) {
                showAlert('Failed to register visitor', 'error');
            } finally {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        }

        // Display visitor vehicle information
        function displayVisitorVehicle(vehicle) {
            vehicleDetails.innerHTML = `
                <div class="vehicle-detail">
                    <strong>License Plate:</strong>
                    <span>${vehicle.licensePlate}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Owner:</strong>
                    <span>${vehicle.ownerName}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Vehicle Type:</strong>
                    <span>${vehicle.vehicleType}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Color:</strong>
                    <span>${vehicle.color}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Phone:</strong>
                    <span>${vehicle.phoneNumber}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Purpose of Visit:</strong>
                    <span>${vehicle.purpose || 'Not specified'}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Visitor ID:</strong>
                    <span>${vehicle.rfidTag}</span>
                </div>
                <div class="vehicle-detail">
                    <strong>Status:</strong>
                    <span style="color: ${getAccessColor(vehicle.accessLevel)}; font-weight: bold; text-transform: uppercase;">
                        ${vehicle.accessLevel === 'pending' ? 'PENDING APPROVAL' : vehicle.accessLevel}
                    </span>
                </div>
            `;
            vehicleInfo.classList.remove('hidden');
            placeholder.classList.add('hidden');
        }

        // Add visitor access entry
        function addVisitorAccessEntry(vehicle) {
            const newEntry = {
                id: Date.now().toString(),
                vehicleId: vehicle.id,
                rfidTag: vehicle.rfidTag,
                timestamp: new Date(),
                status: 'pending',
                gateName: 'Main Gate',
                notes: 'Visitor registration - pending approval'
            };
            
            accessEntries.unshift(newEntry);
            accessEntries = accessEntries.slice(0, 10);
            renderAccessLog();
        }

        // Auto-focus input on page load
        rfidInput.focus();
    </script> -->
</body>
</html>