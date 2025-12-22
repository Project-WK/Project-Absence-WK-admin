@extends('layouts.app')

@section('title', 'Manajemen Hari Libur')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Hari Libur & Cuti Bersama</h1>
            <p class="text-sm text-slate-500 mt-1">Atur jadwal tanggal merah dan cuti bersama perusahaan.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button data-modal-target="add-holiday-modal" data-modal-toggle="add-holiday-modal" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition shadow-sm shadow-indigo-100">
                <i class="fa-solid fa-calendar-plus mr-2"></i> Tambah Libur
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
                    Urutkan: Tanggal
                    <svg class="w-2.5 h-2.5 ms-2.5 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="dropdownSort" class="z-10 hidden bg-white divide-y divide-slate-100 rounded-xl shadow-xl w-44 border border-slate-100">
                    <ul class="py-2 text-sm text-slate-700" aria-labelledby="dropdownSortButton">
                        <li><a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600 font-medium text-indigo-600 bg-indigo-50/30">Terdekat</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Terjauh</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Libur Nasional</a></li>
                        <li><a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Cuti Bersama</a></li>
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
                        <th scope="col" class="px-6 py-4 font-semibold">Nama Hari Libur</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Tanggal</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Jenis</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Keterangan</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($holidays as $holiday)
                    <tr class="hover:bg-slate-50/80 transition group/row" id="row-{{ $holiday->id }}">
                        <!-- Checkbox Baris -->
                        <td class="px-6 py-4">
                            <input type="checkbox" name="selectedHolidays[]" value="{{ $holiday->id }}" class="holiday-checkbox w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </td>
                        
                        <td class="px-6 py-4 font-medium text-slate-900">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-rose-50 border border-rose-100 text-rose-500 flex items-center justify-center text-lg shrink-0 group-hover/row:bg-rose-100 transition">
                                    <i class="fa-regular fa-calendar-check"></i>
                                </div>
                                <span class="font-bold text-[15px]">{{ $holiday->title }}</span>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex flex-col">
                                <span class="font-semibold text-slate-700">
                                    {{ \Carbon\Carbon::parse($holiday->holiday_date)->isoFormat('D MMMM Y') }}
                                </span>
                                <span class="text-xs text-slate-400">
                                    {{ \Carbon\Carbon::parse($holiday->holiday_date)->isoFormat('dddd') }}
                                </span>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($holiday->type == 'national')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    Nasional
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-100">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    Cuti Bersama
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4">
                            <span class="text-slate-500 truncate max-w-xs block">
                                {{ $holiday->description ?? '-' }}
                            </span>
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-end gap-1 opacity-80 group-hover/row:opacity-100 transition-opacity">
                                <button type="button" 
                                    data-modal-target="view-holiday-modal" 
                                    data-modal-toggle="view-holiday-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition btn-view"
                                    data-title="{{ $holiday->title }}"
                                    data-date="{{ \Carbon\Carbon::parse($holiday->holiday_date)->isoFormat('D MMMM Y') }}"
                                    data-day="{{ \Carbon\Carbon::parse($holiday->holiday_date)->isoFormat('dddd') }}"
                                    data-type="{{ $holiday->type == 'national' ? 'Libur Nasional' : 'Cuti Bersama' }}"
                                    data-desc="{{ $holiday->description ?? 'Tidak ada keterangan tambahan.' }}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                                <button type="button"
                                    data-modal-target="edit-holiday-modal" 
                                    data-modal-toggle="edit-holiday-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-full transition btn-edit"
                                    data-id="{{ $holiday->id }}"
                                    data-title="{{ $holiday->title }}"
                                    data-date="{{ $holiday->holiday_date }}"
                                    data-type="{{ $holiday->type }}"
                                    data-desc="{{ $holiday->description }}">
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                </button>

                                <button type="button"
                                    data-modal-target="delete-holiday-modal" 
                                    data-modal-toggle="delete-holiday-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition btn-delete"
                                    data-id="{{ $holiday->id }}">
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
                                    <i class="fa-regular fa-calendar-xmark text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700">Belum Ada Data Libur</h3>
                                <p class="text-sm text-slate-500 mt-2 max-w-xs">Tambahkan tanggal merah atau cuti bersama untuk mengatur kalender kerja.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($holidays->hasPages())
        <div class="p-4 border-t border-slate-200 bg-white">
            {{ $holidays->links() }}
        </div>
        @endif
    </div>

    @include('components.modal.modal-holiday.add-holiday')
    @include('components.modal.modal-holiday.edit-holiday')
    @include('components.modal.modal-holiday.delete-holiday')
    @include('components.modal.modal-holiday.view-holiday')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Handle Edit
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('edit-form').action = `/admin/holidays/${id}`;
                document.getElementById('edit-title').value = this.getAttribute('data-title');
                document.getElementById('edit-date').value = this.getAttribute('data-date');
                document.getElementById('edit-type').value = this.getAttribute('data-type');
                document.getElementById('edit-desc').value = this.getAttribute('data-desc');
            });
        });

        // Handle Delete
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete-form').action = `/admin/holidays/${id}`;
            });
        });

        // Handle View
        const viewButtons = document.querySelectorAll('.btn-view');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('view-title').textContent = this.getAttribute('data-title');
                document.getElementById('view-date').textContent = this.getAttribute('data-date');
                document.getElementById('view-day').textContent = this.getAttribute('data-day');
                document.getElementById('view-desc').textContent = this.getAttribute('data-desc');
                
                const typeSpan = document.getElementById('view-type');
                if(this.getAttribute('data-type') === 'Libur Nasional') {
                    typeSpan.className = "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-rose-50 text-rose-700 border border-rose-200";
                    typeSpan.textContent = "Libur Nasional";
                } else {
                    typeSpan.className = "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200";
                    typeSpan.textContent = "Cuti Bersama";
                }
            });
        });
    });
</script>
@endpush