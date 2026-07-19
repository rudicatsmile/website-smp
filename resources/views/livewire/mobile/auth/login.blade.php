<div class="min-h-screen flex flex-col justify-center px-6 py-12 lg:px-8 bg-gray-50">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
        <div class="flex justify-center mb-6">
            <div class="h-16 w-16 bg-primary-600 rounded-full flex items-center justify-center shadow-lg">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
            </div>
        </div>
        <h2 class="mt-2 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Aplikasi Guru</h2>
        <p class="text-center text-sm text-gray-500 mt-1">Silakan masuk menggunakan akun Anda</p>
    </div>

    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-sm">
        <form wire:submit="login" class="space-y-6 bg-white p-6 rounded-xl shadow-sm border border-gray-100">
            <div>
                <label for="email" class="block text-sm font-medium leading-6 text-gray-900">Email address</label>
                <div class="mt-2">
                    <input wire:model="email" id="email" type="email" autocomplete="email" required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                </div>
                @error('email') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <label for="password" class="block text-sm font-medium leading-6 text-gray-900">Password</label>
                </div>
                <div class="mt-2">
                    <input wire:model="password" id="password" type="password" autocomplete="current-password" required class="block w-full rounded-md border-0 py-2.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 sm:text-sm sm:leading-6">
                </div>
            </div>

            <div class="flex items-center">
                <input wire:model="remember" id="remember" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-primary-600 focus:ring-primary-600">
                <label for="remember" class="ml-3 block text-sm leading-6 text-gray-900">Ingat saya</label>
            </div>

            <div>
                <button type="submit" class="flex w-full justify-center rounded-md bg-primary-600 px-3 py-2.5 text-sm font-semibold leading-6 text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600 transition-colors">
                    <span wire:loading.remove wire:target="login">Masuk</span>
                    <span wire:loading wire:target="login">Memproses...</span>
                </button>
            </div>
        </form>
    </div>
</div>
