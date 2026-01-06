<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log In</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background-color: #0d0d0d;
            color: #e5e5e5;
            font-family: 'Instrument Sans', sans-serif;
        }

        .input-field {
            background-color: #1a1a1a;
            border: 1px solid #333;
            color: #fff;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            outline: none;
            transition: border-color 0.2s;
        }

        .input-field:focus {
            border-color: #555;
        }

        .btn-primary {
            background-color: #fff;
            color: #000;
            padding: 10px;
            width: 100%;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            transition: opacity 0.2s;
        }

        .btn-primary:hover {
            opacity: 0.9;
        }
    </style>
</head>

<body class="flex items-center justify-center min-h-screen">
    <div class="w-full max-w-sm p-8 bg-[#161616] rounded-xl shadow-lg border border-[#333]">
        <h2 class="text-2xl font-semibold text-center mb-6">Welcome Back</h2>

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Username -->
            <div>
                <label for="username" class="block text-sm font-medium text-gray-400 mb-1">Username</label>
                <input id="username" type="text" name="username" value="{{ old('username') }}" required autofocus
                    class="input-field">
                @error('username')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-400 mb-1">Password</label>
                <input id="password" type="password" name="password" required class="input-field">
                @error('password')
                    <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-primary mt-6">
                Log In
            </button>

            <div class="text-center text-sm text-gray-500 mt-4">
                Don't have an account? <a href="{{ route('register') }}" class="text-white hover:underline">Register</a>
            </div>
        </form>
    </div>
</body>

</html>