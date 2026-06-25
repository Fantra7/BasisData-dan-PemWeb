<div class="flex min-h-screen bg-gray-50 font-sans antialiased">
    
    <div class="hidden lg:flex w-1/2 bg-[#1e62d0] text-white flex-col justify-between p-12 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10 bg-[radial-gradient(#ffffff_1px,transparent_1px)] [background-size:16px_16px]"></div>
        
        <div class="flex items-center space-x-3 z-10">
            <div class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-[#1e62d0] font-black text-lg shadow-md">
                NF
            </div>
            <div>
                <h1 class="font-bold text-lg tracking-wide leading-tight">SIAKAD PANEL</h1>
                <p class="text-xs text-blue-200">STT Terpadu Nurul Fikri</p>
            </div>
        </div>

        <div class="z-10 max-w-md mb-12">
            <h2 class="text-4xl font-extrabold tracking-tight leading-tight mb-4">
                Sistem Informasi Akademik Terintegrasi
            </h2>
            <p class="text-blue-100 text-sm leading-relaxed opacity-90">
                Selamat datang di platform manajemen akademik modern. Kelola nilai, Kartu Rencana Studi (KRS), jadwal kuliah, dan data kemahasiswaan dalam satu pintu akses yang cepat dan aman.
            </p>
        </div>

        <div class="text-xs text-blue-200/70 z-10">
            &copy; 2026 STT Terpadu Nurul Fikri. All Rights Reserved.
        </div>
    </div>

    <div class="w-full lg:w-1/2 flex flex-col justify-center px-6 py-12 md:px-24 bg-white relative">
        <div class="mx-auto w-full max-w-md">
            
            <div class="flex lg:hidden items-center space-x-3 mb-8">
                <div class="w-10 h-10 bg-[#1e62d0] rounded-full flex items-center justify-center text-white font-black text-lg shadow-md">
                    NF
                </div>
                <div>
                    <h1 class="font-bold text-lg tracking-wide text-gray-900 leading-tight">SIAKAD</h1>
                    <p class="text-xs text-gray-500">STT Terpadu Nurul Fikri</p>
                </div>
            </div>

            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Selamat Datang</h2>
                <p class="text-sm text-gray-500 mt-1">Silakan masukkan email dan kata sandi Anda untuk masuk ke sistem.</p>
            </div>

            <x-filament-panels::form wire:submit="authenticate" class="space-y-6">
                {{ $this->form }}

                <div class="pt-2">
                    <x-filament-panels::form.actions
                        :actions="$this->getCachedFormActions()"
                        :full-width="$this->hasFullWidthFormActions()"
                    />
                </div>
            </x-filament-panels::form>

        </div>
    </div>
</div>