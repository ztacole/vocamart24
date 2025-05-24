<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | VocaMart24</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-yellow-400 min-h-screen flex items-center justify-center bg-cover bg-center" style="background-image: url('images/login-bg2.jpg');">
    <div class="bg-[#5882C1]/30 backdrop-blur-md shadow-lg rounded-xl w-full max-w-sm p-8 text-center border-2 border-[#5882C1]">
        <h1 class="text-2xl font-bold text-gray-800 mb-1">SELAMAT DATANG DI</h1>
        <h2 class="text-3xl font-extrabold text-black"><span class="text-black">VOCAMART</span><span class="text-orange-500">24</span></h2>

        <div class="flex justify-center my-4">
            <img src="images/logo.png" alt="VocaMart24 Logo" class="w-16 h-16">
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf
            <input type="text" name="name" placeholder="Nama Anda" required class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-70 placeholder-gray-700 ring-2 ring-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $errors->has('name') ? 'border-red-500' : '' }}">
            <input type="email" name="email" placeholder="E-Mail" required class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-70 placeholder-gray-700 ring-2 ring-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $errors->has('email') ? 'border-red-500' : '' }}">
            <input type="password" name="password" placeholder="Password" required class="w-full px-4 py-2 rounded-lg bg-white bg-opacity-70 placeholder-gray-700 ring-2 ring-gray-200 focus:outline-none focus:ring-2 focus:ring-orange-500 {{ $errors->has('password') ? 'border-red-500' : '' }}">
            @error('name')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('email')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            @error('password')
                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
            @enderror
            <button type="submit" class="w-full bg-orange-500 text-white py-2 rounded-full font-bold hover:bg-orange-600 transition">DAFTAR</button>
        </form>

        <p class="mt-4 text-sm text-gray-700">
            Sudah punya akun?
            <a href="{{ route('login') }}" class="text-orange-600 font-semibold hover:underline">Masuk</a>
        </p>
    </div>
</body>
</html>

