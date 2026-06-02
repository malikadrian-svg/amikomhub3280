@extends('layouts.app')

@section('content')
<div class="relative min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8 overflow-hidden">
    <!-- Background blobs (using animations defined in app.blade.php) -->
    <div class="absolute top-0 left-1/4 w-72 h-72 bg-indigo-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob"></div>
    <div class="absolute top-0 right-1/4 w-72 h-72 bg-purple-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-2000"></div>
    <div class="absolute -bottom-8 left-1/3 w-72 h-72 bg-pink-300 rounded-full mix-blend-multiply filter blur-xl opacity-70 animate-blob animation-delay-4000"></div>

    <div class="w-full max-w-md relative animate-fade-in-up">
        <div class="glass bg-white/80 p-8 rounded-3xl shadow-xl border border-white/40 backdrop-blur-xl">
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-indigo-100 text-indigo-600 mb-4 shadow-inner">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <h2 class="text-3xl font-extrabold text-slate-900 tracking-tight">Selamat Datang!</h2>
                <p class="text-slate-500 mt-2 text-sm">Silakan login ke akun Anda</p>
            </div>

            <form class="space-y-6" action="{{ route('login.post') }}" method="POST">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1">Alamat Email</label>
                    <input id="email" name="email" type="email" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white/50" 
                        placeholder="contoh@email.com">
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1">Password</label>
                    <input id="password" name="password" type="password" required 
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors bg-white/50" 
                        placeholder="Masukkan password Anda">
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-slate-300 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-slate-600">Ingat saya</label>
                    </div>
                    <div class="text-sm">
                        <a href="#" class="font-semibold text-indigo-600 hover:text-indigo-500 transition-colors">Lupa password?</a>
                    </div>
                </div>

                <button type="submit" 
                    class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all duration-300 hover:-translate-y-0.5">
                    Masuk
                </button>
            </form>

            <div class="mt-8 text-center text-sm text-slate-600">
                Belum punya akun? 
                <a href="#" class="font-bold text-indigo-600 hover:text-indigo-500 transition-colors">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection
