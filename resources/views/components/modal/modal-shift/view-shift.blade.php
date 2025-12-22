<div id="view-shift-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
    <div class="relative p-4 w-full max-w-md max-h-full">
        <div class="relative bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="h-24 bg-gradient-to-r from-teal-500 to-emerald-500 relative">
                <button type="button" class="absolute top-3 right-3 text-white/80 bg-transparent hover:bg-white/20 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center transition" data-modal-toggle="view-shift-modal">
                    <i class="fa-solid fa-xmark text-lg"></i>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            
            <div class="px-6 pb-6 relative">
                <div class="w-20 h-20 rounded-full bg-white p-1 absolute -top-10 left-6 shadow-md">
                    <div class="w-full h-full rounded-full bg-teal-100 text-teal-600 flex items-center justify-center text-3xl font-bold">
                        <i class="fa-regular fa-clock"></i>
                    </div>
                </div>

                <div class="mt-12 mb-6">
                    <h4 class="text-xl font-bold text-slate-900" id="view-name">-</h4>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 mt-2 rounded-full text-xs font-medium bg-slate-100 text-slate-600 border border-slate-200">
                        Shift Reguler
                    </span>
                </div>

                <div class="grid grid-cols-1 gap-4 text-sm border-t border-slate-100 pt-6">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                            <p class="text-[10px] uppercase text-slate-400 font-bold mb-1">Jam Masuk</p>
                            <p class="text-lg font-bold text-indigo-600" id="view-start">-</p>
                        </div>
                        <div class="p-3 bg-slate-50 rounded-xl border border-slate-100 text-center">
                            <p class="text-[10px] uppercase text-slate-400 font-bold mb-1">Jam Pulang</p>
                            <p class="text-lg font-bold text-rose-500" id="view-end">-</p>
                        </div>
                    </div>

                    <div class="flex items-center gap-4 mt-2">
                        <div class="w-10 h-10 rounded-lg bg-teal-50 border border-teal-100 flex items-center justify-center text-teal-500 shrink-0">
                            <i class="fa-solid fa-stopwatch text-lg"></i>
                        </div>
                        <div>
                            <p class="text-xs text-slate-400 font-medium uppercase tracking-wide">Total Durasi Kerja</p>
                            <p class="font-bold text-slate-800 text-lg" id="view-duration">-</p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>