@extends('layouts.app')

@section('title', 'Manajemen Project')



@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Daftar Project</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola data project, lokasi absensi, dan status operasional.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button id="btn-add-project" data-modal-target="add-project-modal" data-modal-toggle="add-project-modal" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition shadow-sm shadow-indigo-100">
                <i class="fa-solid fa-plus mr-2"></i> Tambah Project
            </button>
        </div>
    </div>

    <div class="bg-white border border-slate-200 rounded-2xl shadow-sm overflow-hidden mb-8">
        
        <div class="p-4 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Tombol Bulk Delete -->
            <div class="flex items-center gap-2">
                <button id="bulkDeleteBtn" type="button" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-red-600 border border-rose-700 rounded-xl hover:bg-rose-700 focus:z-10 focus:ring-2 focus:ring-rose-100 transition shadow-sm w-full sm:w-auto justify-center opacity-50 cursor-not-allowed" disabled>
                    <i class="fa-regular fa-trash-can mr-2"></i>
                    Hapus Terpilih
                </button>
            </div>

            <div class="flex items-center justify-end gap-2 w-full sm:w-auto sm:ml-auto">
                <button id="dropdownSortButton" data-dropdown-toggle="dropdownSort" data-dropdown-placement="bottom-end" class="inline-flex items-center px-4 py-2 text-sm font-medium text-slate-700 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 hover:text-indigo-600 focus:z-10 focus:ring-2 focus:ring-indigo-100 transition shadow-sm w-full sm:w-auto justify-center" type="button">
                    <i class="fa-solid fa-arrow-down-short-wide mr-2 text-slate-400"></i>
                    Urutkan: {{ request('sort') == 'date' ? 'Tanggal' : (request('sort') == 'value' ? 'Nilai Project' : 'Nama') }}
                    <svg class="w-2.5 h-2.5 ms-2.5 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="dropdownSort" class="z-10 hidden bg-white divide-y divide-slate-100 rounded-xl shadow-xl w-44 border border-slate-100">
                    <ul class="py-2 text-sm text-slate-700" aria-labelledby="dropdownSortButton">
                        <li><a href="{{ route('projects.index', ['sort' => 'name']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Nama (A-Z)</a></li>
                        <li><a href="{{ route('projects.index', ['sort' => 'date']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Tanggal (Terbaru)</a></li>
                        <li><a href="{{ route('projects.index', ['sort' => 'value']) }}" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Nilai (Tertinggi)</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="relative overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50/80 border-b border-slate-200">
                    <tr>
                        <!-- Checkbox Header -->
                        <th scope="col" class="w-6 px-6 py-4">
                            <input id="selectAllCheckbox" type="checkbox" class="w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </th>
                        <th scope="col" class="px-6 py-4 font-semibold">Info Project</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Klien & Lokasi</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Keuangan</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Status</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($projects as $project)
                    <tr class="hover:bg-slate-50/80 transition group/row" id="row-{{ $project->id }}">
                        <!-- Checkbox Baris -->
                        <td class="px-6 py-4">
                            <input type="checkbox" name="selectedProjects[]" value="{{ $project->id }}" class="project-checkbox w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-lg shrink-0 mt-1">
                                    <i class="fa-regular fa-building"></i>
                                </div>
                                <div>
                                    <span class="font-bold text-slate-900 text-[15px] block">{{ $project->name }}</span>
                                    <span class="text-xs font-mono text-slate-500 bg-slate-100 px-1.5 py-0.5 rounded border border-slate-200">{{ $project->project_code }}</span>
                                    <div class="text-xs text-slate-400 mt-1">
                                        {{ $project->start_date ? \Carbon\Carbon::parse($project->start_date)->format('d M Y') : '-' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-slate-700 font-medium text-sm">
                                    <i class="fa-regular fa-user mr-1 text-slate-400"></i> {{ $project->client_name ?? '-' }}
                                </span>
                                @if($project->latitude && $project->longitude)
                                    <a href="https://maps.google.com/?q={{ $project->latitude }},{{ $project->longitude }}" target="_blank" class="text-xs text-blue-600 hover:underline flex items-center">
                                        <i class="fa-solid fa-location-dot mr-1"></i> Lihat Peta
                                    </a>
                                @else
                                    <span class="text-xs text-slate-400 italic">Lokasi belum diatur</span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="text-slate-900 font-medium">Rp {{ number_format($project->project_value, 0, ',', '.') }}</span>
                                @php
                                    $paymentClass = match($project->payment_status) {
                                        'paid' => 'text-emerald-600 bg-emerald-50 border-emerald-100',
                                        'partial' => 'text-amber-600 bg-amber-50 border-amber-100',
                                        default => 'text-rose-600 bg-rose-50 border-rose-100'
                                    };
                                @endphp
                                <span class="w-fit text-[10px] uppercase font-bold px-2 py-0.5 rounded-full border {{ $paymentClass }}">
                                    {{ $project->payment_status }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $statusClass = match($project->status) {
                                    'ongoing' => 'bg-emerald-50 text-emerald-700 border-emerald-100',
                                    'planned' => 'bg-blue-50 text-blue-700 border-blue-100',
                                    'completed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                    'cancelled' => 'bg-rose-50 text-rose-700 border-rose-100',
                                    default => 'bg-slate-50 text-slate-600 border-slate-200'
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClass }}">
                                {{ ucfirst($project->status) }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-end gap-1">
                                <button type="button" 
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition btn-view"
                                    data-modal-target="view-project-modal" 
                                    data-modal-toggle="view-project-modal"
                                    data-code="{{ $project->project_code }}"
                                    data-name="{{ $project->name }}"
                                    data-client="{{ $project->client_name }}"
                                    data-desc="{{ $project->description }}"
                                    data-address="{{ $project->address }}"
                                    data-lat="{{ $project->latitude }}"
                                    data-long="{{ $project->longitude }}"
                                    data-value="{{ $project->project_value }}"
                                    data-payment="{{ $project->payment_status }}"
                                    data-status="{{ $project->status }}"
                                    data-start="{{ $project->start_date }}"
                                    data-end="{{ $project->end_date }}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                                <button type="button" 
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-full transition btn-edit"
                                    data-modal-target="edit-project-modal" 
                                    data-modal-toggle="edit-project-modal"
                                    data-id="{{ $project->id }}" 
                                    data-code="{{ $project->project_code }}"
                                    data-name="{{ $project->name }}"
                                    data-client="{{ $project->client_name }}"
                                    data-desc="{{ $project->description }}"
                                    data-address="{{ $project->address }}"
                                    data-lat="{{ $project->latitude }}"
                                    data-long="{{ $project->longitude }}"
                                    data-value="{{ $project->project_value }}"
                                    data-payment="{{ $project->payment_status }}"
                                    data-status="{{ $project->status }}"
                                    data-start="{{ $project->start_date }}"
                                    data-end="{{ $project->end_date }}">
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                </button>

                                <button type="button"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition btn-delete"
                                    data-modal-target="delete-project-modal" 
                                    data-modal-toggle="delete-project-modal"
                                    data-id="{{ $project->id }}">
                                    <i class="fa-regular fa-trash-can text-[13px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-8 text-slate-500">Belum ada data project.</td> <!-- Kolom menjadi 6 -->
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($projects->hasPages())
        <div class="p-4 border-t border-slate-200 bg-white">
            {{ $projects->links() }}
        </div>
        @endif
    </div>


    @include('components.modal.modal-project.add-project')
    @include('components.modal.modal-project.edit-project')
    @include('components.modal.modal-project.delete-project')
    @include('components.modal.modal-project.view-project')

@endsection

@push('scripts')
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- KONFIGURASI UMUM MAP ---
        const defaultLat = -6.200000; // Jakarta
        const defaultLng = 106.816666;
        let mapAdd, markerAdd;
        let mapEdit, markerEdit;

        // --- 1. LOGIC MODAL ADD (TAMBAH) ---
        const btnAdd = document.getElementById('btn-add-project');
        if(btnAdd) {
            btnAdd.addEventListener('click', function() {
                // Timeout penting agar modal render dulu, baru map size dihitung
                setTimeout(() => {
                    if (!mapAdd) {
                        mapAdd = L.map('map-add').setView([defaultLat, defaultLng], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        }).addTo(mapAdd);

                        // Klik pada peta menambah project
                        mapAdd.on('click', function(e) {
                            const lat = e.latlng.lat.toFixed(6);
                            const lng = e.latlng.lng.toFixed(6);

                            if (markerAdd) markerAdd.setLatLng(e.latlng);
                            else markerAdd = L.marker(e.latlng).addTo(mapAdd);

                            document.getElementById('add-latitude').value = lat;
                            document.getElementById('add-longitude').value = lng;
                        });
                    }
                    mapAdd.invalidateSize(); // Render ulang ukuran
                }, 300);
            });
        }

        // --- 2. LOGIC MODAL EDIT ---
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const lat = this.getAttribute('data-lat');
                const lng = this.getAttribute('data-long');

                // Isi Form Standar
                document.getElementById('edit-project-form').action = `/admin/projects/${id}`;
                document.getElementById('edit-project_code').value = this.getAttribute('data-code');
                document.getElementById('edit-name').value = this.getAttribute('data-name');
                document.getElementById('edit-client_name').value = this.getAttribute('data-client');
                document.getElementById('edit-description').value = this.getAttribute('data-desc');
                document.getElementById('edit-address').value = this.getAttribute('data-address');
                document.getElementById('edit-latitude').value = lat;
                document.getElementById('edit-longitude').value = lng;
                document.getElementById('edit-project_value').value = this.getAttribute('data-value');
                document.getElementById('edit-payment_status').value = this.getAttribute('data-payment');
                document.getElementById('edit-status').value = this.getAttribute('data-status');
                document.getElementById('edit-start_date').value = this.getAttribute('data-start');
                document.getElementById('edit-end_date').value = this.getAttribute('data-end');

                // Logic Map Edit
                setTimeout(() => {
                    const curLat = lat ? parseFloat(lat) : defaultLat;
                    const curLng = lng ? parseFloat(lng) : defaultLng;

                    if (!mapEdit) {
                        mapEdit = L.map('map-edit').setView([curLat, curLng], 13);
                        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                            attribution: '© OpenStreetMap'
                        }).addTo(mapEdit);

                        mapEdit.on('click', function(e) {
                            const newLat = e.latlng.lat.toFixed(6);
                            const newLng = e.latlng.lng.toFixed(6);

                            if (markerEdit) markerEdit.setLatLng(e.latlng);
                            else markerEdit = L.marker(e.latlng).addTo(mapEdit);

                            document.getElementById('edit-latitude').value = newLat;
                            document.getElementById('edit-longitude').value = newLng;
                        });
                    } else {
                        mapEdit.setView([curLat, curLng], 13);
                    }

                    // Reset Marker Edit
                    if (markerEdit) mapEdit.removeLayer(markerEdit);
                    
                    // Jika ada data Lat/Long, pasang marker
                    if (lat && lng) {
                        markerEdit = L.marker([curLat, curLng]).addTo(mapEdit);
                    }

                    mapEdit.invalidateSize();
                }, 300);
            });
        });

        // --- 3. LOGIC MODAL DELETE ---
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete-project-form').action = `/admin/projects/${id}`;
            });
        });

        // --- 4. LOGIC MODAL VIEW ---
        const viewButtons = document.querySelectorAll('.btn-view');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                // Populate View Data
                document.getElementById('view-name').textContent = this.getAttribute('data-name');
                document.getElementById('view-code').textContent = this.getAttribute('data-code');
                document.getElementById('view-client').textContent = this.getAttribute('data-client') || '-';
                document.getElementById('view-description').textContent = this.getAttribute('data-desc') || '-';
                document.getElementById('view-address').textContent = this.getAttribute('data-address') || '-';
                
                // Format Currency
                const val = this.getAttribute('data-value');
                document.getElementById('view-value').textContent = new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(val);

                // View Map Link
                const lat = this.getAttribute('data-lat');
                const lng = this.getAttribute('data-long');
                const mapLink = document.getElementById('view-maps-link');
                const mapText = document.getElementById('view-coords');
                
                if(lat && lng) {
                    mapLink.classList.remove('hidden');
                    mapLink.classList.add('inline-flex');
                    mapLink.href = `https://maps.google.com/?q=${lat},${lng}`;
                    mapText.textContent = `${lat}, ${lng}`;
                } else {
                    mapLink.classList.add('hidden');
                }
            });
        });
    });
</script>
@endpush