@extends('layouts.app')

@section('title', 'Live Absensi')

@push('style')
{{-- CSS Leaflet & Fix Z-Index --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
<style>
    .leaflet-pane { z-index: 0 !important; }
    .leaflet-top, .leaflet-bottom { z-index: 1 !important; }
    /* Agar peta Live tetap rapi */
    #live-map-container { height: 400px; width: 100%; border-radius: 1rem; overflow: hidden; z-index: 0; }
</style>
@endpush

@section('content')

    {{-- HEADER & FILTER --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-slate-900">Live Absensi & Rekap</h1>
            <p class="text-sm text-slate-500 mt-1">Pantau kehadiran karyawan secara real-time dan history.</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3">
            {{-- Form Filter Tanggal --}}
            <form action="{{ url()->current() }}" method="GET" class="flex items-center gap-2">
                <div class="relative">
                    <div class="absolute inset-y-0 start-0 flex items-center ps-3 pointer-events-none">
                        <i class="fa-regular fa-calendar text-slate-400"></i>
                    </div>
                    <input type="date" name="date" value="{{ $date }}" onchange="this.form.submit()"
                        class="bg-white border border-slate-300 text-slate-900 text-sm rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block w-full ps-10 p-2.5 shadow-sm">
                </div>
            </form>

            <button class="text-white bg-green-600 hover:bg-emerald-700 font-medium rounded-xl text-sm px-5 py-2.5 shadow-sm transition flex items-center justify-center gap-2">
                <i class="fa-solid fa-file-excel"></i> Export Excel
            </button>
        </div>
    </div>

    {{-- STATISTIK CARDS --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-indigo-50 text-indigo-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-users"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase">Total Hadir</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $attendances->count() }}</h3>
            </div>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-emerald-50 text-emerald-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-clock"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase">Tepat Waktu</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $attendances->where('status', 'on_time')->count() }}</h3>
            </div>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-rose-50 text-rose-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase">Terlambat</p>
                <h3 class="text-xl font-bold text-slate-900">{{ $attendances->where('status', 'late')->count() }}</h3>
            </div>
        </div>
        <div class="p-4 bg-white rounded-2xl border border-slate-200 shadow-sm flex items-center gap-4">
            <div class="w-12 h-12 rounded-full bg-amber-50 text-amber-600 flex items-center justify-center text-xl">
                <i class="fa-solid fa-person-walking-luggage"></i>
            </div>
            <div>
                <p class="text-xs text-slate-500 font-medium uppercase">Izin / Cuti</p>
                <h3 class="text-xl font-bold text-slate-900">0</h3> {{-- Ganti dinamis --}}
            </div>
        </div>
    </div>

    {{-- LIVE MAP SECTION --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm mb-6 overflow-hidden">
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex justify-between items-center">
            <h3 class="font-bold text-slate-800"><i class="fa-solid fa-map-location-dot mr-2 text-indigo-600"></i>Pantauan Lokasi ({{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }})</h3>
            <span class="text-xs text-slate-500 bg-white px-2 py-1 rounded border border-slate-200 shadow-sm">
                <span class="w-2 h-2 rounded-full bg-emerald-500 inline-block mr-1 animate-pulse"></span> Live Update
            </span>
        </div>
        <div class="p-1">
            <div id="live-map-container"></div>
        </div>
    </div>

    {{-- TABEL DATA ABSENSI --}}
    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-12">
        <div class="p-4 border-b border-slate-100 flex flex-col sm:flex-row gap-4 justify-between items-center">
            <div class="relative w-full sm:w-72">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <i class="fa-solid fa-search text-slate-400"></i>
                </div>
                <input type="text" id="table-search" class="bg-slate-50 border border-slate-300 text-slate-900 text-sm rounded-lg focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 p-2.5" placeholder="Cari nama karyawan...">
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50/80 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Karyawan</th>
                        <th class="px-6 py-4">Jam Masuk</th>
                        <th class="px-6 py-4">Jam Pulang</th>
                        <th class="px-6 py-4">Status</th>
                        <th class="px-6 py-4">Lokasi & Foto</th>
                        <th class="px-6 py-4 text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($attendances as $attendance)
                    <tr class="hover:bg-slate-50/50 transition">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">
                                    {{ substr($attendance->user->name, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-bold text-slate-900">{{ $attendance->user->name }}</div>
                                    <div class="text-xs text-slate-500">ID: {{ $attendance->user->id }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="font-mono text-slate-700 font-bold">
                                {{ \Carbon\Carbon::parse($attendance->clock_in_time)->format('H:i') }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($attendance->clock_out_time)
                                <span class="font-mono text-slate-700">
                                    {{ \Carbon\Carbon::parse($attendance->clock_out_time)->format('H:i') }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400 italic">--:--</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            @if($attendance->status == 'late')
                                <span class="bg-rose-100 text-rose-800 text-xs font-medium px-2.5 py-0.5 rounded border border-rose-200">
                                    Terlambat
                                </span>
                            @else
                                <span class="bg-emerald-100 text-emerald-800 text-xs font-medium px-2.5 py-0.5 rounded border border-emerald-200">
                                    Tepat Waktu
                                </span>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                {{-- Tombol Lihat Lokasi --}}
                                <button type="button" 
                                    class="btn-map text-blue-600 bg-blue-50 hover:bg-blue-100 p-2 rounded-lg transition"
                                    data-lat="{{ $attendance->latitude }}"
                                    data-lng="{{ $attendance->longitude }}"
                                    data-name="{{ $attendance->user->name }}"
                                    title="Lihat Peta">
                                    <i class="fa-solid fa-location-dot"></i>
                                </button>
                                
                                {{-- Tombol Lihat Foto --}}
                                <button type="button" 
                                    class="btn-photo text-indigo-600 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-lg transition"
                                    data-photo="{{ $attendance->image_url ?? 'https://via.placeholder.com/300?text=No+Selfie' }}"
                                    data-name="{{ $attendance->user->name }}"
                                    title="Lihat Selfie">
                                    <i class="fa-solid fa-camera"></i>
                                </button>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <button class="text-slate-400 hover:text-indigo-600 transition">
                                <i class="fa-solid fa-chevron-right"></i>
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-500">
                            Tidak ada data absensi untuk tanggal ini.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: MAP DETAIL --}}
    <div id="map-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-2xl max-h-full">
            <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200">
                <div class="flex items-start justify-between p-4 border-b border-slate-100 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-slate-900" id="map-modal-title">Lokasi Absen</h3>
                    <button type="button" data-modal-hide="map-modal" class="text-slate-400 bg-transparent hover:bg-slate-100 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-4">
                    <div id="detail-map" style="height: 350px; width: 100%;" class="rounded-xl border border-slate-200 overflow-hidden"></div>
                    <div class="mt-3 flex items-center text-sm text-slate-500">
                        <i class="fa-solid fa-location-arrow mr-2 text-indigo-500"></i>
                        <span id="map-modal-coords">-</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL: PHOTO DETAIL --}}
    <div id="photo-modal" tabindex="-1" aria-hidden="true" class="fixed top-0 left-0 right-0 z-50 hidden w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-md max-h-full">
            <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200">
                <div class="flex items-start justify-between p-4 border-b border-slate-100 rounded-t-2xl">
                    <h3 class="text-lg font-bold text-slate-900" id="photo-modal-title">Bukti Selfie</h3>
                    <button type="button" data-modal-hide="photo-modal" class="text-slate-400 bg-transparent hover:bg-slate-100 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 flex justify-center">
                    <img id="photo-modal-img" src="" alt="Selfie" class="rounded-xl shadow-md max-h-[60vh] object-cover border border-slate-200">
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // --- 1. INISIALISASI LIVE MAP (Peta Besar di Atas) ---
        // Data dari Controller (Blade to JS)
        const liveData = @json($mapData ?? []);
        
        // Default Jakarta
        const center = [-6.200000, 106.816666]; 
        
        const liveMap = L.map('live-map-container').setView(center, 11);
        
        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '© CARTO', subdomains: 'abcd', maxZoom: 19
        }).addTo(liveMap);

        // Tambahkan Marker untuk setiap absen hari ini
        if(liveData.length > 0) {
            const bounds = [];
            liveData.forEach(item => {
                if(item.lat && item.lng) {
                    const color = item.status === 'late' ? 'red' : 'green';
                    const marker = L.circleMarker([item.lat, item.lng], {
                        radius: 8,
                        fillColor: color,
                        color: "#fff",
                        weight: 2,
                        opacity: 1,
                        fillOpacity: 0.8
                    }).addTo(liveMap);
                    
                    marker.bindPopup(`
                        <div class="text-center">
                            <div class="font-bold text-slate-800">${item.name}</div>
                            <div class="text-xs text-slate-500">${item.time} WIB</div>
                            <div class="text-[10px] uppercase font-bold mt-1 ${item.status === 'late' ? 'text-rose-600' : 'text-emerald-600'}">
                                ${item.status === 'late' ? 'Terlambat' : 'Tepat Waktu'}
                            </div>
                        </div>
                    `);
                    bounds.push([item.lat, item.lng]);
                }
            });
            // Auto zoom agar semua marker terlihat
            if(bounds.length > 0) liveMap.fitBounds(bounds, {padding: [50, 50]});
        }

        // --- 2. MODAL MAP DETAIL (Per Baris Tabel) ---
        let detailMap;
        const mapModalEl = document.getElementById('map-modal');
        // Gunakan Flowbite Instance jika tersedia, atau manual toggle
        const mapModal = new Modal(mapModalEl);

        document.querySelectorAll('.btn-map').forEach(btn => {
            btn.addEventListener('click', function() {
                const lat = parseFloat(this.dataset.lat);
                const lng = parseFloat(this.dataset.lng);
                const name = this.dataset.name;

                document.getElementById('map-modal-title').textContent = "Lokasi: " + name;
                document.getElementById('map-modal-coords').textContent = `${lat}, ${lng}`;
                
                mapModal.show();

                // Logic Fix Map Size (Reusable)
                setTimeout(() => {
                    if(!detailMap) {
                        detailMap = L.map('detail-map').setView([lat, lng], 15);
                        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
                            subdomains: 'abcd', maxZoom: 19
                        }).addTo(detailMap);
                    } else {
                        detailMap.setView([lat, lng], 15);
                    }
                    
                    // Bersihkan marker lama
                    detailMap.eachLayer((layer) => {
                        if (layer instanceof L.Marker) detailMap.removeLayer(layer);
                    });

                    L.marker([lat, lng]).addTo(detailMap)
                        .bindPopup(`<b>${name}</b>`).openPopup();

                    detailMap.invalidateSize();
                }, 300); // Delay animasi modal
            });
        });

        // Close button handler (Manual karena JS Flowbite kadang bentrok)
        document.querySelectorAll('[data-modal-hide="map-modal"]').forEach(btn => {
            btn.addEventListener('click', () => mapModal.hide());
        });


        // --- 3. MODAL PHOTO DETAIL ---
        const photoModalEl = document.getElementById('photo-modal');
        const photoModal = new Modal(photoModalEl);

        document.querySelectorAll('.btn-photo').forEach(btn => {
            btn.addEventListener('click', function() {
                const src = this.dataset.photo;
                const name = this.dataset.name;
                
                document.getElementById('photo-modal-title').textContent = "Selfie: " + name;
                document.getElementById('photo-modal-img').src = src;
                
                photoModal.show();
            });
        });
        
        document.querySelectorAll('[data-modal-hide="photo-modal"]').forEach(btn => {
            btn.addEventListener('click', () => photoModal.hide());
        });

    });
</script>
@endpush