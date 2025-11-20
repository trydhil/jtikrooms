// ===== DASHER ROOM MANAGEMENT SYSTEM =====
// File: public/js/script.js
// Description: Main JavaScript for Dasher Room Management
// Version: 2.0 - Integrated with Laravel Backend

console.log('🚀 Dasher System Loading...');

// ===== CONFIGURATION =====
const CONFIG = {
    API_TIMEOUT: 5000,
    AUTO_REFRESH: 30000,
    FALLBACK_MODE: true
};

// ===== ROOM DATA =====
const ROOMS = [
    // Ruangan Kelas
    { name: 'AE 101', type: 'kelas', floor: '1', display_name: 'Ruangan AE 101' },
    { name: 'AE 102', type: 'kelas', floor: '1', display_name: 'Ruangan AE 102' },
    { name: 'AE 103', type: 'kelas', floor: '1', display_name: 'Ruangan AE 103' },
    { name: 'AE 104', type: 'kelas', floor: '1', display_name: 'Ruangan AE 104' },
    { name: 'AE 105', type: 'kelas', floor: '1', display_name: 'Ruangan AE 105' },
    { name: 'AE 106', type: 'kelas', floor: '1', display_name: 'Ruangan AE 106' },
    { name: 'AE 107', type: 'kelas', floor: '1', display_name: 'Ruangan AE 107' },
    { name: 'AE 209', type: 'kelas', floor: '2', display_name: 'Ruangan AE 209' },
    
    // Laboratorium
    { name: 'Lab Animasi', type: 'lab', floor: '2', display_name: 'Laboratorium Animasi' },
    { name: 'IT Workshop', type: 'lab', floor: '2', display_name: 'IT Workshop' },
    { name: 'Lab Jaringan', type: 'lab', floor: '2', display_name: 'Laboratorium Jaringan' },
    { name: 'Lab Programing', type: 'lab', floor: '2', display_name: 'Laboratorium Programming' },
    { name: 'Lab Sistem Cerdas', type: 'lab', floor: '2', display_name: 'Laboratorium Sistem Cerdas' },
    { name: 'Lab Embeded', type: 'lab', floor: '2', display_name: 'Laboratorium Embedded' },
    
    // Ruangan Lainnya
    { name: 'Sekretariat HIMATIK', type: 'other', floor: '2', display_name: 'Sekretariat HIMATIK' },
    { name: 'Ruangan Admin', type: 'other', floor: '2', display_name: 'Ruangan Administrator' },
    { name: 'Perpustakaan', type: 'other', floor: '2', display_name: 'Perpustakaan JTIK' },
    { name: 'Ruangan Sekertaris Jurusan', type: 'other', floor: '2', display_name: 'Ruangan Sekretaris Jurusan' },
    { name: 'Ruangan Kepala Laboratorium', type: 'other', floor: '2', display_name: 'Ruangan Kepala Lab' },
    { name: 'Ruangan Dosen', type: 'other', floor: '2', display_name: 'Ruangan Dosen' },
    { name: 'Ruangan Ketua Prodi TEKOM', type: 'other', floor: '2', display_name: 'Ruangan Ketua Prodi TEKOM' },
    { name: 'Ruangan Ujian', type: 'other', floor: '2', display_name: 'Ruangan Ujian' },
    { name: 'Ruangan Ketua Prodi PTIK', type: 'other', floor: '2', display_name: 'Ruangan Ketua Prodi PTIK' },
];

// ===== STATE MANAGEMENT =====
let appState = {
    roomStatuses: {},
    availableRooms: [],
    currentFilter: 'kelas',
    isOnline: true,
    isLoading: false
};

// ===== CORE FUNCTIONS =====

/**
 * Get room status from server or fallback
 */
async function fetchRoomStatuses() {
    try {
        console.log('📡 Fetching room statuses...');
        const response = await fetch('/api/rooms/status');
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        
        const result = await response.json();
        
        if (result.success) {
            appState.roomStatuses = result.data;
            appState.isOnline = true;
            console.log('✅ Room statuses loaded:', Object.keys(appState.roomStatuses).length);
        } else {
            throw new Error('API returned error');
        }
    } catch (error) {
        console.warn('⚠️ Using fallback room statuses');
        appState.isOnline = false;
        // Set all rooms as available in fallback mode
        ROOMS.forEach(room => {
            appState.roomStatuses[room.name] = 'available';
        });
    }
}

/**
 * Get available rooms from server
 */
async function fetchAvailableRooms() {
    try {
        console.log('📡 Fetching available rooms...');
        const response = await fetch('/api/rooms/list');
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        
        const result = await response.json();
        
        if (result.success) {
            appState.availableRooms = result.data;
            console.log('✅ Available rooms loaded:', appState.availableRooms.length);
        }
    } catch (error) {
        console.warn('⚠️ No available rooms data');
        appState.availableRooms = [];
    }
}

/**
 * Get status display information
 */
function getStatusDisplay(status) {
    const statusMap = {
        'available': { 
            text: 'Tersedia', 
            class: 'status-available', 
            icon: 'fa-check-circle',
            color: 'success'
        },
        'occupied': { 
            text: 'Terpakai', 
            class: 'status-occupied', 
            icon: 'fa-users',
            color: 'warning'
        },
        'maintenance': { 
            text: 'Maintenance', 
            class: 'status-maintenance', 
            icon: 'fa-tools',
            color: 'info'
        }
    };
    
    return statusMap[status] || statusMap['available'];
}

/**
 * Get room status
 */
function getRoomStatus(roomName) {
    return appState.roomStatuses[roomName] || 'available';
}

/**
 * Get room icon path
 */
function getIconPath(type, name = '') {
    const icons = {
        kelas: '/img/icon/class.png',
        lab: '/img/icon/lab.png',
        other: '/img/icon/kantor.png'
    };
    
    // Custom icons for specific rooms
    const customIcons = {
        'Sekretariat HIMATIK': '/img/icon/sekret.png',
        'Ruangan Admin': '/img/icon/admin.png',
        'Perpustakaan': '/img/icon/perpus.png',
        'Ruangan Sekertaris Jurusan': '/img/icon/ketua.png',
        'Ruangan Kepala Laboratorium': '/img/icon/ketua.png',
        'Ruangan Dosen': '/img/icon/dosen.png',
        'Ruangan Ketua Prodi TEKOM': '/img/icon/ketua.png',
        'Ruangan Ujian': '/img/icon/class.png',
        'Ruangan Ketua Prodi PTIK': '/img/icon/ketua.png'
    };
    
    return customIcons[name] || icons[type] || icons.other;
}

/**
 * Get additional room info from database
 */
function getRoomInfo(roomName) {
    const dbRoom = appState.availableRooms.find(room => room.name === roomName);
    if (dbRoom) {
        return {
            display_name: dbRoom.display_name,
            description: dbRoom.description,
            capacity: dbRoom.capacity,
            facilities: dbRoom.facilities,
            location: dbRoom.location,
            existsInDB: true
        };
    }
    
    // Fallback to static data
    const staticRoom = ROOMS.find(room => room.name === roomName);
    return {
        display_name: staticRoom?.display_name || roomName,
        description: 'Ruangan dengan fasilitas lengkap',
        capacity: 40,
        location: 'Gedung JTIK',
        facilities: ['AC', 'Proyektor', 'WiFi'],
        existsInDB: false
    };
}

// ===== ROOM RENDERING =====

/**
 * Show loading state
 */
function showLoading() {
    const roomList = document.querySelector('.room-list');
    if (roomList) {
        roomList.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted">Memuat data ruangan...</p>
            </div>
        `;
    }
}

/**
 * Render rooms based on filter
 */
/**
 * Render rooms based on filter dengan informasi detail
 */
async function renderRooms(filter = 'kelas') {
    console.log(`🎨 Rendering ${filter} rooms...`);
    
    const roomList = document.querySelector('.room-list');
    if (!roomList) return;
    
    appState.isLoading = true;
    appState.currentFilter = filter;
    
    showLoading();
    
    // Load data
    await fetchRoomStatuses();
    await fetchAvailableRooms();
    
    const filteredRooms = ROOMS.filter(room => room.type === filter);
    
    if (filteredRooms.length === 0) {
        roomList.innerHTML = `
            <div class="text-center p-5">
                <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Tidak ada ruangan</p>
            </div>
        `;
        return;
    }
    
    // Render room cards dengan informasi detail
    roomList.innerHTML = filteredRooms.map(room => {
        const statusData = appState.roomStatuses[room.name];
        const status = typeof statusData === 'object' ? statusData.status : statusData;
        const bookingInfo = typeof statusData === 'object' ? statusData.booking_info : null;
        
        const statusInfo = getStatusDisplay(status);
        const roomInfo = getRoomInfo(room.name);
        
        return `
<div class="room-card" data-room="${room.name}" data-status="${status}">
    <div class="room-image-container">
        <img src="/img/ruangan.jpg" 
             alt="${room.name}" 
             class="room-image"
             onerror="this.src='https://via.placeholder.com/400x200/3B82F6/FFFFFF?text=Ruangan+JTIK'">
        <div class="room-status-badge ${statusInfo.class}">
            <i class="fas ${statusInfo.icon} me-1"></i>${statusInfo.text}
        </div>
        <div class="room-image-overlay">
            <div class="room-type-badge">
                <i class="fas ${room.type === 'lab' ? 'fa-flask' : room.type === 'other' ? 'fa-building' : 'fa-chalkboard'} me-1"></i>
                ${room.type === 'lab' ? 'Laboratorium' : room.type === 'other' ? 'Ruangan Khusus' : 'Ruang Kelas'}
            </div>
        </div>
    </div>
    
    <div class="room-info">
        <div class="room-header">
            <img src="${getIconPath(room.type, room.name)}" 
                 alt="${room.type}" 
                 class="room-icon"
                 onerror="this.style.display='none'">
            <div class="room-title">
                <h3 class="room-name">${roomInfo.display_name}</h3>
                <div class="room-code">${room.name}</div>
            </div>
        </div>

        ${bookingInfo ? `
        <!-- Informasi Booking Aktif -->
        <div class="booking-info-alert">
            <div class="booking-info-header">
                <i class="fas fa-users me-2"></i>
                <strong>Sedang Digunakan</strong>
            </div>
            <div class="booking-details">
                <div class="booking-detail-item">
                    <span class="label">Kelas:</span>
                    <span class="value">${bookingInfo.username}</span>
                </div>
                <div class="booking-detail-item">
                    <span class="label">Mata Kuliah:</span>
                    <span class="value">${bookingInfo.mata_kuliah}</span>
                </div>
                <div class="booking-detail-item">
                    <span class="label">Dosen:</span>
                    <span class="value">${bookingInfo.dosen}</span>
                </div>
                <div class="booking-detail-item">
                    <span class="label">Waktu:</span>
                    <span class="value">${bookingInfo.waktu_mulai} - ${bookingInfo.waktu_berakhir}</span>
                </div>
                <div class="booking-detail-item">
                    <span class="label">Selesai:</span>
                    <span class="value text-warning">${bookingInfo.time_left}</span>
                </div>
            </div>
        </div>
        ` : ''}

        <div class="room-meta-grid">
            <div class="meta-item">
                <i class="fas fa-building text-primary"></i>
                <span>Lantai ${room.floor}</span>
            </div>
            ${roomInfo.capacity ? `
            <div class="meta-item">
                <i class="fas fa-users text-success"></i>
                <span>${roomInfo.capacity} orang</span>
            </div>
            ` : ''}
            <div class="meta-item">
                <i class="fas fa-map-marker-alt text-warning"></i>
                <span>${roomInfo.location}</span>
            </div>
        </div>

        ${roomInfo.description ? `
        <div class="room-description">
            <p>${roomInfo.description}</p>
        </div>
        ` : ''}

        ${roomInfo.facilities && roomInfo.facilities.length > 0 ? `
        <div class="room-facilities">
            <div class="facilities-label">Fasilitas:</div>
            <div class="facilities-list">
                ${roomInfo.facilities.slice(0, 3).map(facility => `
                    <span class="facility-pill">${facility}</span>
                `).join('')}
                ${roomInfo.facilities.length > 3 ? `
                    <span class="facility-more">+${roomInfo.facilities.length - 3} more</span>
                ` : ''}
            </div>
        </div>
        ` : ''}

        <div class="room-actions">
            <button class="btn-info-detail" onclick="handleRoomClick('${room.name}')">
                <i class="fas fa-info-circle me-2"></i>
                Info Selengkapnya
            </button>
        </div>
    </div>
</div>
        `;
    }).join('');
    
    appState.isLoading = false;
    console.log(`✅ Rendered ${filteredRooms.length} rooms`);
}

// ===== EVENT HANDLERS =====

/**
 * Handle room click - SELALU BOLEH BUKA INFO MESKI TIDAK AVAILABLE
 */
function handleRoomClick(roomName) {
    const status = getRoomStatus(roomName);
    const statusInfo = getStatusDisplay(status);
    
    // Tampilkan konfirmasi jika status tidak available
    if (status !== 'available') {
        const userChoice = confirm(
            `Ruangan "${roomName}" sedang dalam status:\n` +
            `📊 ${statusInfo.text}\n\n` +
            `Apakah Anda tetap ingin melihat informasi ruangan?`
        );
        
        if (userChoice) {
            // User memilih untuk tetap melihat info
            window.location.href = `/room/${encodeURIComponent(roomName)}`;
        }
        // Jika user cancel, tidak melakukan apa-apa
    } else {
        // Langsung redirect jika available
        window.location.href = `/room/${encodeURIComponent(roomName)}`;
    }
}

/**
 * Setup filter buttons
 */
function setupFilterButtons() {
    const filterButtons = document.querySelectorAll('.filter-button');
    
    filterButtons.forEach(button => {
        button.addEventListener('click', function() {
            // Update active state
            filterButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            
            // Render new filter
            const filter = this.dataset.filter;
            renderRooms(filter);
        });
    });
}

/**
 * Setup search functionality
 */
function setupSearch() {
    const searchInput = document.querySelector('.search-input');
    const searchButton = document.querySelector('.search-button');
    
    if (!searchInput || !searchButton) return;
    
    const performSearch = () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const currentFilter = appState.currentFilter;
        
        if (!searchTerm) {
            renderRooms(currentFilter);
            return;
        }
        
        const filteredRooms = ROOMS.filter(room => 
            room.type === currentFilter && 
            (room.name.toLowerCase().includes(searchTerm) || 
             room.display_name.toLowerCase().includes(searchTerm))
        );
        
        const roomList = document.querySelector('.room-list');
        if (filteredRooms.length === 0) {
            roomList.innerHTML = `
                <div class="text-center p-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Ruangan tidak ditemukan</p>
                    <button class="btn btn-primary mt-2" onclick="renderRooms('${currentFilter}')">
                        Tampilkan Semua
                    </button>
                </div>
            `;
            return;
        }
        
        // Render search results
        roomList.innerHTML = filteredRooms.map(room => {
            const status = getRoomStatus(room.name);
            const statusInfo = getStatusDisplay(status);
            const roomInfo = getRoomInfo(room.name);
            
            return `
    <div class="room-card" data-room="${room.name}" data-status="${status}">
        <div class="room-image-container">
            <img src="/img/ruangan.jpg" alt="${room.name}" class="room-image">
            <div class="room-status-badge ${statusInfo.class}">
                <i class="fas ${statusInfo.icon} me-1"></i>${statusInfo.text}
            </div>
        </div>
        <div class="room-info">
            <div class="room-header">
                <img src="${getIconPath(room.type, room.name)}" alt="${room.type}" class="room-icon">
                <div class="room-title">
                    <h3>${roomInfo.display_name}</h3>
                    <div class="room-code">${room.name}</div>
                </div>
            </div>
            <div class="room-actions">
                <button class="btn-info-detail" onclick="handleRoomClick('${room.name}')">
                    <i class="fas fa-info-circle me-2"></i>
                    Info Selengkapnya
                </button>
            </div>
        </div>
    </div>
            `;
        }).join('');
    };
    
    searchButton.addEventListener('click', performSearch);
    searchInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter') performSearch();
    });
    searchInput.addEventListener('input', (e) => {
        if (e.target.value === '') performSearch();
    });
}

// ===== MAP FUNCTIONS =====

/**
 * Change floor map
 */
function changeFloor(floor) {
    const buttons = document.querySelectorAll('.btn-group .btn');
    const img = document.getElementById('floorMap');
    
    if (!img) return;
    
    // Update active button
    buttons.forEach(btn => btn.classList.remove('active'));
    buttons[floor - 1]?.classList.add('active');
    
    // Update map image
    img.src = `/img/lantai${floor}.png`;
    img.alt = `Denah Lantai ${floor}`;
}

/**
 * Adjust map container height
 */
function adjustMapContainer(img) {
    const container = img.parentElement;
    if (container && img.complete) {
        container.style.height = img.offsetHeight + 'px';
    }
}

// ===== UTILITY FUNCTIONS =====

/**
 * Show alert message
 */
function showAlert(title, message, type = 'info') {
    // Try to use Bootstrap modal first
    const modal = document.getElementById('alertModal');
    if (modal && typeof bootstrap !== 'undefined') {
        const modalTitle = modal.querySelector('.modal-title');
        const modalBody = modal.querySelector('.modal-body');
        
        modalTitle.textContent = title;
        modalBody.innerHTML = message;
        
        const bsModal = new bootstrap.Modal(modal);
        bsModal.show();
        return;
    }
    
    // Fallback to browser alert
    alert(`${title}\n\n${message}`);
}

/**
 * Show access denied message
 */
function showAccessMessage(roleType) {
    const messages = {
        'admin': 'WAJIB LOGIN SEBAGAI ADMINISTRATOR UNTUK MENGAKSES BAGIAN INI',
        'kelas': 'WAJIB LOGIN SEBAGAI PERWAKILAN KELAS UNTUK MENGAKSES BAGIAN INI'
    };
    
    const message = messages[roleType] || 'Akses ditolak. Silakan login terlebih dahulu.';
    
    const accessModal = document.getElementById('accessAlertModal');
    if (accessModal) {
        document.getElementById('accessMessage').textContent = message;
        const modal = new bootstrap.Modal(accessModal);
        modal.show();
    } else {
        alert(message);
    }
}

// ===== INITIALIZATION =====

/**
 * Initialize the Dasher application
 */
async function initializeDasher() {
    console.log('🚀 Initializing Dasher System...');
    
    try {
        // Initialize room system if on home page
        if (document.querySelector('.room-list')) {
            console.log('🏠 Home page detected');
            
            await fetchRoomStatuses();
            await fetchAvailableRooms();
            
            setupFilterButtons();
            setupSearch();
            renderRooms('kelas');
            
            // Auto-refresh every 30 seconds
            setInterval(async () => {
                if (!appState.isLoading) {
                    await fetchRoomStatuses();
                    renderRooms(appState.currentFilter);
                }
            }, CONFIG.AUTO_REFRESH);
        }
        
        // Initialize admin features if on admin page
        if (document.querySelector('.room-management')) {
            console.log('⚙️ Admin page detected');
            // Admin-specific initialization can go here
        }
        
        console.log('✅ Dasher System Ready!');
        
    } catch (error) {
        console.error('❌ Failed to initialize Dasher:', error);
        showAlert('System Error', 'Gagal memuat sistem. Silakan refresh halaman.');
    }
}

// ===== GLOBAL EXPORTS =====
window.handleLogin = () => window.location.href = '/login';
window.handleLogout = () => {
    const form = document.querySelector('form[action="/logout"]');
    if (form) form.submit();
};
window.changeFloor = changeFloor;
window.adjustMapContainer = adjustMapContainer;
window.showRoomInfo = handleRoomClick;
window.showAccessMessage = showAccessMessage;
window.renderRooms = renderRooms;
window.handleRoomClick = handleRoomClick;

// ===== START APPLICATION =====
document.addEventListener('DOMContentLoaded', initializeDasher);

console.log('📦 Dasher System Loaded - Ready for DOMContentLoaded');