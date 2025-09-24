import serial
import binascii
import time
import threading
import os

TAG_FILE = "rfid_tag.txt"
TAG_TIMEOUT = 2  # time in seconds before auto-clearing tag
last_written_time = 0
last_epc = None
cleared = True

def write_tag(tag):
    global last_written_time, cleared, last_epc
    with open(TAG_FILE, "w") as f:
        f.write(tag)
    last_written_time = time.time()
    last_epc = tag
    cleared = False
    print(f"[INFO] Tag written: {tag}")

def auto_clear_tag():
    global cleared, last_epc
    while True:
        time.sleep(1)
        if os.path.exists(TAG_FILE):
            if time.time() - last_written_time > TAG_TIMEOUT and not cleared:
                with open(TAG_FILE, "w") as f:
                    f.write("")
                print("[INFO] Tag auto-cleared.")
                cleared = True
                last_epc = None  # reset last_epc when cleared

def extract_epc(packet):
    hex_str = binascii.hexlify(packet).upper().decode()
    if 'E280' in hex_str:
        start = hex_str.find('E280')
        return hex_str[start:start+24]
    return None

# Start auto-clear thread
threading.Thread(target=auto_clear_tag, daemon=True).start()

# Initialize Serial
try:
    ser = serial.Serial('COM3', 57600, timeout=1)
    print("[INFO] Listening on COM3...")
except serial.SerialException as e:
    print(f"[ERROR] Could not open COM port: {e}")
    exit()

# Main loop
while True:
    try:
        if ser.in_waiting:
            raw = ser.read(24)
            epc = extract_epc(raw)
            if epc and (cleared or epc != last_epc):
                print(f"[INFO] RFID Tag EPC: {epc}")
                write_tag(epc)
    except Exception as e:
        print(f"[ERROR] Reading failed: {e}")
