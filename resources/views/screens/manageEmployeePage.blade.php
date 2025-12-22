@extends('layouts.app')

@section('title', 'Manajemen Karyawan')

@section('content')
    <div class="mb-8 flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 tracking-tight">Data Karyawan</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola seluruh data pegawai, jabatan, dan akses aplikasi.</p>
        </div>
        
        <div class="flex items-center gap-3">
            <button data-modal-target="add-employee-modal" data-modal-toggle="add-employee-modal" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 transition shadow-sm shadow-indigo-100">
                <i class="fa-solid fa-user-plus mr-2"></i> Tambah Karyawan
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
                    Urutkan: Relevansi
                    <svg class="w-2.5 h-2.5 ms-2.5 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>

                <div id="dropdownSort" class="z-10 hidden bg-white divide-y divide-slate-100 rounded-xl shadow-xl w-44 border border-slate-100">
                    <ul class="py-2 text-sm text-slate-700" aria-labelledby="dropdownSortButton">
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600 font-medium text-indigo-600 bg-indigo-50/30">Relevansi</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Nama (A-Z)</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Nama (Z-A)</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Terbaru</a>
                        </li>
                        <li>
                            <a href="#" class="block px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600">Terlama</a>
                        </li>
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
                        <th scope="col" class="px-6 py-4 font-semibold">Karyawan</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Kontak & Akun</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Jabatan & Role</th>
                        <th scope="col" class="px-6 py-4 font-semibold">Shift Kerja</th>
                        <th scope="col" class="px-6 py-4 font-semibold text-center w-[120px]">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($employees as $employee)
                    <tr class="hover:bg-slate-50/80 transition group/row" id="row-{{ $employee->id }}">
                        <!-- Checkbox Baris -->
                        <td class="px-6 py-4">
                            <input type="checkbox" name="selectedEmployees[]" value="{{ $employee->id }}" class="employee-checkbox w-4 h-4 text-indigo-600 bg-gray-100 border-gray-300 rounded focus:ring-indigo-500 focus:ring-2">
                        </td>
                        
                        <td class="px-6 py-4 font-medium text-slate-900">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 flex items-center justify-center text-sm font-bold shrink-0 ring-4 ring-white group-hover/row:ring-indigo-50 transition">
                                    {{ substr($employee->name, 0, 1) }}
                                </div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-[15px]">{{ $employee->name }}</span>
                                    <span class="text-xs text-slate-400 font-normal font-mono mt-0.5">ID: EMP-{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}</span>
                                </div>
                            </div>
                        </td>
                        
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <div class="flex items-center text-slate-700 text-[13px]">
                                    <i class="fa-regular fa-envelope text-slate-400 w-4 mr-1.5"></i>
                                    {{ $employee->email }}
                                </div>
                                <div class="flex items-center text-slate-600 text-[13px]">
                                    <i class="fa-brands fa-whatsapp text-emerald-500 w-4 mr-1.5"></i>
                                    {{ $employee->phone ?? '-' }}
                                </div>
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            <div class="flex flex-col items-start gap-1.5">
                                <span class="font-semibold text-slate-800">{{ $employee->position ?? 'Belum ada jabatan' }}</span>
                                
                                @if($employee->role === 'leader')
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-50 text-purple-700 border border-purple-100">
                                        <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>
                                        Leader App
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                        Karyawan
                                    </span>
                                @endif
                            </div>
                        </td>

                        <td class="px-6 py-4">
                            @if($employee->shift)
                                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg text-xs font-medium bg-white border border-slate-200 text-slate-700 shadow-sm">
                                    <div class="p-1 bg-indigo-50 text-indigo-600 rounded">
                                        <i class="fa-regular fa-clock"></i>
                                    </div>
                                    <div class="flex flex-col">
                                        <span class="font-bold">{{ $employee->shift->name }}</span>
                                        <span class="text-[11px] text-slate-500">
                                            {{ \Carbon\Carbon::parse($employee->shift->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($employee->shift->end_time)->format('H:i') }}
                                        </span>
                                    </div>
                                </div>
                            @else
                                <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-medium text-amber-600 bg-amber-50 border border-amber-100">
                                    <i class="fa-solid fa-triangle-exclamation"></i> Belum diatur
                                </span>
                            @endif
                        </td>

                        <td class="px-6 py-4 text-center">
                            <div class="flex items-center justify-end gap-1 opacity-80 group-hover/row:opacity-100 transition-opacity">
                                <button type="button" 
                                    data-modal-target="view-employee-modal" 
                                    data-modal-toggle="view-employee-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 rounded-full transition btn-view"
                                    data-name="{{ $employee->name }}"
                                    data-id="EMP-{{ str_pad($employee->id, 4, '0', STR_PAD_LEFT) }}"
                                    data-email="{{ $employee->email }}"
                                    data-phone="{{ $employee->phone ?? '-' }}"
                                    data-position="{{ $employee->position ?? 'Belum ada' }}"
                                    data-role="{{ ucfirst($employee->role) }}"
                                    data-shift="{{ $employee->shift->name ?? 'Tidak ada' }}">
                                    <i class="fa-regular fa-eye"></i>
                                </button>

                                <button type="button"
                                    data-modal-target="edit-employee-modal" 
                                    data-modal-toggle="edit-employee-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-full transition btn-edit"
                                    data-id="{{ $employee->id }}"
                                    data-name="{{ $employee->name }}"
                                    data-email="{{ $employee->email }}"
                                    data-phone="{{ $employee->phone }}"
                                    data-position="{{ $employee->position }}"
                                    data-role="{{ $employee->role }}"
                                    data-shift="{{ $employee->shift_id }}">
                                    <i class="fa-solid fa-pen-to-square text-[13px]"></i>
                                </button>

                                <button type="button"
                                    data-modal-target="delete-employee-modal" 
                                    data-modal-toggle="delete-employee-modal"
                                    class="w-8 h-8 flex items-center justify-center text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-full transition btn-delete"
                                    data-id="{{ $employee->id }}">
                                    <i class="fa-regular fa-trash-can text-[13px]"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6"> <!-- Kolom menjadi 6 karena ada kolom checkbox -->
                            <div class="flex flex-col items-center justify-center py-12 px-4 text-center">
                                <div class="w-16 h-16 bg-slate-100 rounded-full flex items-center justify-center mb-4 text-slate-400">
                                    <i class="fa-solid fa-users-slash text-3xl"></i>
                                </div>
                                <h3 class="text-lg font-bold text-slate-700">Belum Ada Data Karyawan</h3>
                                <p class="text-sm text-slate-500 mt-2 max-w-xs">Silakan tambahkan karyawan baru untuk mulai mengelola absensi dan data mereka.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($employees->hasPages())
        <div class="p-4 border-t border-slate-200 bg-white">
            {{ $employees->links() }}
        </div>
        @endif
    </div>

    @include('components.modal.modal-employee.add-employee')
    @include('components.modal.modal-employee.edit-employee')
    @include('components.modal.modal-employee.delete-employee')
    @include('components.modal.modal-employee.view-employee')

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // Handle Edit Button Click
        const editButtons = document.querySelectorAll('.btn-edit');
        editButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('edit-form').action = `/admin/employees/${id}`;
                document.getElementById('edit-name').value = this.getAttribute('data-name');
                document.getElementById('edit-email').value = this.getAttribute('data-email');
                document.getElementById('edit-phone').value = this.getAttribute('data-phone');
                document.getElementById('edit-position').value = this.getAttribute('data-position');
                document.getElementById('edit-role').value = this.getAttribute('data-role');
                document.getElementById('edit-shift').value = this.getAttribute('data-shift');
            });
        });

        // Handle Delete Button Click
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                document.getElementById('delete-form').action = `/admin/employees/${id}`;
            });
        });

        // Handle View Button Click
        const viewButtons = document.querySelectorAll('.btn-view');
        viewButtons.forEach(button => {
            button.addEventListener('click', function() {
                document.getElementById('view-name').textContent = this.getAttribute('data-name');
                document.getElementById('view-id').textContent = this.getAttribute('data-id');
                document.getElementById('view-email').textContent = this.getAttribute('data-email');
                document.getElementById('view-phone').textContent = this.getAttribute('data-phone');
                document.getElementById('view-position').textContent = this.getAttribute('data-position');
                document.getElementById('view-role').textContent = this.getAttribute('data-role');
                document.getElementById('view-shift').textContent = this.getAttribute('data-shift');

                // Update role badge style
                const roleSpan = document.getElementById('view-role');
                if(this.getAttribute('data-role') === 'Leader') {
                    roleSpan.className = "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-700 border border-purple-200";
                } else {
                    roleSpan.className = "inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200";
                }
            });
        });
    });
</script>
@endpush