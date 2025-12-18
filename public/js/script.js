// ===== DASHER ROOM MANAGEMENT SYSTEM =====
// File: public/js/script.js
// Version: 3.0 - Fully Dynamic from Database

console.log('🚀 Dasher System Loading...');

// ===== CONFIGURATION =====
const CONFIG = {
    API_TIMEOUT: 5000,
    AUTO_REFRESH: 30000,
    FALLBACK_MODE: true
};

let dashboardInterval = null;

// ===== ROOM DATA (DYNAMIC) =====
// KITA UBAH JADI LET DAN KOSONGKAN KARENA AKAN DIISI DARI DATABASE
let ROOMS = []; 

// ===== STATE MANAGEMENT =====
let appState = {
    roomStatuses: {},
    availableRooms: [], // Ini raw data dari DB
    currentFilter: 'kelas',
    isOnline: true,
    isLoading: false
};

// ===== CORE FUNCTIONS =====

/**
 * 1. FUNGSI BARU: Mengambil Data Ruangan Master dari Database
 * Ini menggantikan const ROOMS manual yang lama.
 */
async function fetchMasterRoomList() {
    try {
        console.log('📥 Fetching master room list from DB...');
        const response = await fetch('/api/rooms/list'); // Pastikan endpoint ini mengembalikan semua ruangan
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        
        const result = await response.json();
        
        if (result.success && Array.isArray(result.data)) {
            // Konversi data Database ke format yang dimengerti JS Front-end
            ROOMS = result.data.map(dbRoom => {
                // Deteksi tipe otomatis jika tidak ada di DB
                let type = 'kelas';
                const nameLower = dbRoom.name.toLowerCase();
                const descLower = (dbRoom.display_name || '').toLowerCase();
                
                if (nameLower.includes('lab') || descLower.includes('laboratorium')) {
                    type = 'lab';
                } else if (nameLower.includes('ruang') || nameLower.includes('sekret') || nameLower.includes('perpus')) {
                    type = 'other';
                }

                // Deteksi lantai dari lokasi string
                let floor = '1';
                if (dbRoom.location && dbRoom.location.includes('2')) floor = '2';
                if (dbRoom.location && dbRoom.location.includes('3')) floor = '3';

                return {
                    name: dbRoom.name, // Kode Ruangan (AE 101)
                    type: dbRoom.type || type, // kelas/lab/other
                    floor: dbRoom.floor || floor, 
                    display_name: dbRoom.display_name,
                    // Simpan data asli juga
                    description: dbRoom.description,
                    capacity: dbRoom.capacity,
                    facilities: dbRoom.facilities,
                    location: dbRoom.location
                };
            });
            console.log(`✅ Loaded ${ROOMS.length} rooms from database.`);
        }
    } catch (error) {
        console.error('❌ Error loading rooms:', error);
        // Jika error, fallback ke data dummy agar tidak kosong melompong
        if(ROOMS.length === 0) {
            ROOMS = [
                { name: 'AE 101', type: 'kelas', floor: '1', display_name: 'Ruangan AE 101 (Offline)' },
            ];
        }
    }
}

async function fetchRoomStatuses() {
    try {
        const response = await fetch('/api/rooms/status');
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success) {
            appState.roomStatuses = result.data;
            appState.isOnline = true;
        }
    } catch (error) {
        console.warn('⚠️ Using fallback statuses');
        appState.isOnline = false;
    }
}

function getStatusDisplay(status) {
    const statusMap = {
        'available': { text: 'Tersedia', class: 'status-available', icon: 'fa-check-circle', color: 'success'},
        'occupied': { text: 'Terpakai', class: 'status-occupied', icon: 'fa-users', color: 'warning'},
        'maintenance': { text: 'Maintenance', class: 'status-maintenance', icon: 'fa-tools', color: 'info'}
    };
    return statusMap[status] || statusMap['available'];
}

function getRoomStatus(roomName) {
    return appState.roomStatuses[roomName] || 'available';
}

function getIconPath(type, name = '') {
    // Deteksi icon berdasarkan nama atau tipe
    const n = name.toLowerCase();
    if(n.includes('himatik')) return '/img/icon/sekret.png';
    if(n.includes('admin')) return '/img/icon/admin.png';
    if(n.includes('perpus')) return '/img/icon/perpus.png';
    if(n.includes('dosen')) return '/img/icon/dosen.png';
    
    const icons = { kelas: '/img/icon/class.png', lab: '/img/icon/lab.png', other: '/img/icon/kantor.png' };
    return icons[type] || icons.other;
}

function getRoomInfo(roomName) {
    // Karena ROOMS sekarang sudah dinamis, kita bisa ambil langsung dari sana
    const room = ROOMS.find(r => r.name === roomName);
    if (room) {
        return {
            display_name: room.display_name,
            description: room.description || 'Fasilitas Lengkap',
            capacity: room.capacity || 40,
            facilities: room.facilities || ['AC', 'WiFi'],
            location: room.location || 'Gedung JTIK',
            existsInDB: true
        };
    }
    return {
        display_name: roomName,
        description: '-',
        capacity: '-',
        facilities: [],
        location: '-',
        existsInDB: false
    };
}

// ===== ROOM RENDERING =====

function showLoading() {
    const roomList = document.querySelector('.room-list');
    if (roomList) {
        roomList.innerHTML = `
            <div class="text-center p-5">
                <div class="spinner-border text-primary mb-3" style="width: 3rem; height: 3rem;" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="text-muted">Sinkronisasi Data Database...</p>
            </div>
        `;
    }
}

async function renderRooms(filter = 'kelas') {
    const roomList = document.querySelector('.room-list');
    if (!roomList) return;
    
    appState.isLoading = true;
    appState.currentFilter = filter;
    
    if(roomList.innerHTML.trim() === "") showLoading();
    
    // 1. Pastikan Data Ruangan Master sudah termuat
    if (ROOMS.length === 0) {
        await fetchMasterRoomList();
    }
    
    // 2. Ambil Status terbaru
    await fetchRoomStatuses();
    
    // 3. Filter
    const filteredRooms = ROOMS.filter(room => room.type === filter);
    
    if (filteredRooms.length === 0) {
        roomList.innerHTML = `
            <div class="text-center p-5">
                <i class="fas fa-door-open fa-3x text-muted mb-3"></i>
                <p class="text-muted">Belum ada ruangan tipe "${filter}"</p>
                <small>Tambahkan ruangan baru di menu Administrator</small>
            </div>
        `;
        return;
    }
    
    roomList.innerHTML = filteredRooms.map(room => {
        const statusData = appState.roomStatuses[room.name];
        const status = typeof statusData === 'object' ? statusData.status : (statusData || room.status || 'available');
        const bookingInfo = typeof statusData === 'object' ? statusData.booking_info : null;
        
        const statusInfo = getStatusDisplay(status);
        // Jika room belum punya deskripsi detail, gunakan default
        const roomInfo = getRoomInfo(room.name);
        
        return `
<div class="room-card" data-room="${room.name}" data-status="${status}">
    <div class="room-image-container">
        <img src="/img/ruangan.jpg" 
             alt="${room.name}" 
             class="room-image"
             onerror="this.src='https://via.placeholder.com/400x200/3B82F6/FFFFFF?text=Dasher+Room'">
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
                    <span class="label">Waktu:</span>
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
        </div>

        <div class="room-actions">
            <button class="btn-info-detail" onclick="handleRoomClick('${room.name}')">
                <i class="fas fa-info-circle me-2"></i>
                Detail
            </button>
        </div>
    </div>
</div>
        `;
    }).join('');
    
    appState.isLoading = false;
}

// ===== EVENT HANDLERS =====

function handleRoomClick(roomName) {
    if(typeof Livewire !== 'undefined') {
        Livewire.navigate(`/room/${encodeURIComponent(roomName)}`);
    } else {
        window.location.href = `/room/${encodeURIComponent(roomName)}`;
    }
}

function setupFilterButtons() {
    const filterButtons = document.querySelectorAll('.filter-button');
    filterButtons.forEach(button => {
        const newBtn = button.cloneNode(true);
        button.parentNode.replaceChild(newBtn, button);
        newBtn.addEventListener('click', function() {
            document.querySelectorAll('.filter-button').forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            renderRooms(this.dataset.filter);
        });
    });
}

function setupSearch() {
    const searchInput = document.querySelector('.search-input');
    const searchButton = document.querySelector('.search-button');
    
    if (!searchInput || !searchButton) return;
    
    const performSearch = () => {
        const searchTerm = searchInput.value.toLowerCase().trim();
        const roomList = document.querySelector('.room-list');
        
        if (!roomList) return;
        
        // Jika input kosong, tampilkan semua ruangan sesuai filter aktif
        if (!searchTerm) {
            renderRooms(appState.currentFilter);
            return;
        }
        
        // Filter dari ROOMS yang sudah dinamis
        const filteredRooms = ROOMS.filter(room => 
            room.name.toLowerCase().includes(searchTerm) || 
            (room.display_name && room.display_name.toLowerCase().includes(searchTerm)) ||
            (room.location && room.location.toLowerCase().includes(searchTerm))
        );
        
        // Jika tidak ada hasil
        if (filteredRooms.length === 0) {
            roomList.innerHTML = `
                <div class="text-center p-5">
                    <i class="fas fa-search fa-3x text-muted mb-3"></i>
                    <p class="text-muted">Tidak ada ruangan yang cocok dengan "<strong>${searchTerm}</strong>"</p>
                    <small>Coba cari dengan kode ruangan (AE 101) atau nama ruangan</small>
                </div>
            `;
            return;
        }
        
        // Render hasil pencarian
        roomList.innerHTML = filteredRooms.map(room => {
            const statusData = appState.roomStatuses[room.name];
            const status = typeof statusData === 'object' ? statusData.status : (statusData || room.status || 'available');
            const bookingInfo = typeof statusData === 'object' ? statusData.booking_info : null;
            
            const statusInfo = getStatusDisplay(status);
            const roomInfo = getRoomInfo(room.name);
            
            return `
<div class="room-card" data-room="${room.name}" data-status="${status}">
    <div class="room-image-container">
        <img src="/img/ruangan.jpg" 
             alt="${room.name}" 
             class="room-image"
             onerror="this.src='https://via.placeholder.com/400x200/3B82F6/FFFFFF?text=Dasher+Room'">
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
                    <span class="label">Waktu:</span>
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
        </div>

        <div class="room-actions">
            <button class="btn-info-detail" onclick="handleRoomClick('${room.name}')">
                <i class="fas fa-info-circle me-2"></i>
                Detail
            </button>
        </div>
    </div>
</div>
            `;
        }).join('');
    };
    
    // Event listener untuk tombol search
    searchButton.onclick = performSearch;
    
    // Event listener untuk Enter key
    searchInput.onkeypress = (e) => { 
        if (e.key === 'Enter') {
            e.preventDefault();
            performSearch();
        }
    };
    
    // Event listener real-time search (optional - menampilkan hasil saat user mengetik)
    searchInput.addEventListener('input', (e) => {
        // Jika user menghapus semua teks, tampilkan kembali daftar normal
        if (e.target.value.trim() === '') {
            renderRooms(appState.currentFilter);
        }
    });
}

function changeFloor(floor) {
    const img = document.getElementById('floorMap');
    const buttons = document.querySelectorAll('.btn-group .btn');
    if (!img) return;
    
    buttons.forEach(btn => btn.classList.remove('active'));
    if(buttons[floor - 1]) buttons[floor - 1].classList.add('active');
    
    img.src = `/img/lantai${floor}.png`;
}

function showAccessMessage(roleType) {
    alert('Akses ditolak: ' + roleType);
}

// ===== INITIALIZATION =====

async function initializeDasher() {
    if (dashboardInterval) {
        clearInterval(dashboardInterval);
        dashboardInterval = null;
    }

    try {
        // Initialize room system if on home page
        if (document.querySelector('.room-list')) {
            console.log('🏠 Home page detected');
            
            // 1. LOAD DATA DARI DB DULUAN
            await fetchMasterRoomList();
            
            setupFilterButtons();
            setupSearch();
            renderRooms('kelas');
            
            // Auto-refresh
            dashboardInterval = setInterval(async () => {
                if (!appState.isLoading) {
                    await fetchRoomStatuses(); 
                    renderRooms(appState.currentFilter);
                }
            }, CONFIG.AUTO_REFRESH);
        }
    } catch (error) {
        console.error('❌ Failed to initialize Dasher:', error);
    }
}

// ===== EXPORTS & START =====
window.handleLogin = () => Livewire.navigate('/login');
window.handleLogout = () => document.querySelector('form[action="/logout"]')?.submit();
window.changeFloor = changeFloor;
window.showRoomInfo = handleRoomClick;
window.showAccessMessage = showAccessMessage;
window.renderRooms = renderRooms;
window.handleRoomClick = handleRoomClick;

document.addEventListener('DOMContentLoaded', initializeDasher);
document.addEventListener('livewire:navigated', () => {
    initializeDasher();
});