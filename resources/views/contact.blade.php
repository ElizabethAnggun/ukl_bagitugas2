@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-[#1d61bd] to-[#0ea0d8] flex flex-col items-center justify-center px-10 pt-20 text-white">
    <h1 class="text-5xl font-bold mb-4">Hubungi Kami</h1>
    <p class="text-xl mb-12 opacity-90">Ada pertanyaan? kami siap membantu kamu kapan saja</p>

    <div class="grid md:grid-cols-2 gap-8 w-full max-w-4xl">
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-[40px] p-10 text-center shadow-xl">
            <div class="flex justify-center mb-6">
                <i class="fas fa-comment-dots text-5xl"></i>
            </div>
            <h3 class="text-3xl font-bold mb-2">Admin 1</h3>
            <p class="text-lg mb-8 opacity-80">+62 895-6052-74321</p>
            <a href="https://wa.me/62895605274321?text=Halo%20Admin,%20saya%20mau%20tanya%20soal%20BagiTugas" target="_blank" class="bg-[#2F5CB4] text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition inline-block">Chat Sekarang</a>
        </div>

        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-[40px] p-10 text-center shadow-xl">
            <div class="flex justify-center mb-6">
                <i class="fas fa-comment-dots text-5xl"></i>
            </div>
            <h3 class="text-3xl font-bold mb-2">Admin 2</h3>
            <p class="text-lg mb-8 opacity-80">+62 895-3874-90033</p>
            <a href="https://wa.me/62895387490033?text=Halo%20Admin,%20saya%20mau%20tanya%20soal%20BagiTugas" target="_blank" class="bg-[#2F5CB4] text-white px-8 py-3 rounded-full font-bold hover:bg-blue-700 transition inline-block">Chat Sekarang</a>
    </div>
</div>
@endsection