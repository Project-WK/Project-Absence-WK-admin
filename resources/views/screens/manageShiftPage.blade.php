@extends('layouts.app')

@section('title', 'Manajemen Shift Kerja')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Shift & Jam Kerja</h1>
            <p class="text-sm text-slate-500 mt-1">Atur jadwal masuk dan pulang untuk berbagai shift karyawan.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button data-modal-target="add-shift-modal" data-modal-toggle="add-shift-modal" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition shadow-sm shadow-indigo-100">
                <i class="fa-regular fa-clock mr-2"></i> Tambah Shift
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
                    Urutkan: Nama
                    <svg class="w-2.5 h-2.5 ms-2.5 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="dropdownSort" class="z-10 hidden bg-white divide-y divide-slate-100 rounded-xl shadow-xl w-44 border border-slate-100">
                    <ul class="py-2 text-sm text-slate-700" aria-labelledby="dropdownSortButton">
                        <li><a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600 font-medium text-indigo-600 bg-indigo-50/30">Nama (A-Z)</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Jam Masuk (Pagi-Sore)</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Durasi Terlama</a></li>
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
                        <th scope="col" class="px-6 py-4 font-semibold">Nama Shift</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Jam Masuk</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Jam Pulang</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Total Jam</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($shifts as $shift)
                    <tr class="hover:bg-slate-50/80 transition group/row" id="row-{{ $shift->id }}">
                        <!-- Checkbox Baris -->
                        <td class="px-6 py-4">
                            <input type="checkbox" name="selectedShifts[]" value="{{ $shift->id }}" class="shift-checkbox w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </td>
                        
                        <td class="px-6 py-4 font-medium text-slate-900">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-teal-50 border border-teal-100 text-teal-600 flex items-center justify-center text-lg shrink-0 group-hover/row:bg-teal-100 transition">
                                    <i class="fa-regular fa-clock"></i>
                                </div>
                                <span class="font-bold text-[15px]">{{ $shift->name }}</span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                {{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                {{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}
                            </span>
                        </td>

                        <td class="px-6 py-4">
                            @php
                                $start = \Carbon\Carbon::parse($shift->start_time);
                                $end = \Carbon\Carbon::parse($shift->end_time);
                                // Handle shift lintas hari jika perlu (misal malam ke pagi)
                                if ($end->lessThan($start)) {
                                    $end->addDay();
                                }
                                $diff = $start->diff($end);
                            @endphp
                            <span class="text-slate-500 text-sm font-mono">
                                {{ $diff->format('%h Jam %i Menit') }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-end gap-1 opacity-80 group-hover/row:opacity-100 transition-opacity">
                                <button type="button" 
                                    data-modal-target="view-shift-modal" 
                                    data-modal-toggle="view-shift-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition btn-view"
                                    data-name="{{ $shift->name }}"
                                    data-start="{{ \Carbon\Carbon::parse($shift->start_time)->format('H:i') }}"
                                    data-end="{{ \Carbon\Carbon::parse($shift->end_time)->format('H:i') }}"
                                    data-duration="{{ $diff->format('%h Jam %i Menit') }}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                                <button type="button"
                                    data-modal-target="edit-shift-modal" 
                                    data-modal-toggle="edit-shift-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-full transition btn-edit"
                                    data-id="{{ $shift->id }}"
                                    data-name="{{ $shift->name }}"
                                    data-start="{{ $shift->start_time }}"
                                    data-end="{{ $shift->end_time }}">
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                </button>

                                <button type="button"
                                    data-modal-target="delete-shift-modal" 
                                    data-modal-toggle="delete-shift-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition btn-delete"
                                    data-id="{{ $shift->id }}">
                                    <i class="fa-regular fa-trash-can text-[13px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6"> <!-- Kolom menjadi 6 -->
                            <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-400">
                                    <i class="fa-regular fa-clock text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700">Belum Ada Shift</h3>
                                <p class="text-sm text-slate-500 mt-2 max-w-xs">Tambahkan shift kerja (Pagi, Siang, Malam) untuk mengatur jam kerja karyawan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($shifts->hasPages())
        <div class="p-4 border-t border-slate-200 bg-white">
            {{ $shifts->links() }}
        </div>
        @endif
    </div>

    @include('components.modal.modal-shift.add-shift')
    @include('components.modal.modal-shift.edit-shift')
    @include('components.modal.modal-shift.delete-shift')
    @include('components.modal.modal-shift.view-shift')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Handle Edit
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('edit-form').action = `/admin/shifts/${id}`;
                document.getElementById('edit-name').value = this.getAttribute('data-name');
                document.getElementById('edit-start_time').value = this.getAttribute('data-start');
                document.getElementById('edit-end_time').value = this.getAttribute('data-end');
            });
        });

        // Handle Delete
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete-form').action = `/admin/shifts/${id}`;
            });
        });

        // Handle View
        const viewButtons = document.querySelectorAll('.btn-view');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('view-name').textContent = this.getAttribute('data-name');
                document.getElementById('view-start').textContent = this.getAttribute('data-start');
                document.getElementById('view-end').textContent = this.getAttribute('data-end');
                document.getElementById('view-duration').textContent = this.getAttribute('data-duration');
            });
        });
    });
</script>
@endpush