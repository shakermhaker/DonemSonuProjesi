<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>My Movies</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: '#121212',
                        darker: '#0a0a0a',
                        card: '#1e1e1e',
                    }
                }
            }
        }
    </script>
    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>
<body class="bg-gray-100 dark:bg-darker text-gray-900 dark:text-gray-100 font-sans antialiased min-h-screen transition-colors duration-300"
      x-data="{ 
          editModalOpen: false, 
          editingMovie: { id: null, movie_name: '', release_year: '', rating: 1, image: null } 
      }">

    <!-- Header -->
    <header class="w-full p-4 flex justify-between items-center fixed top-0 z-40 bg-white/80 dark:bg-darker/80 backdrop-blur-md border-b border-gray-200 dark:border-gray-800">
        <h1 class="text-xl font-bold tracking-tighter">My Favorite Movies</h1>

        <div class="flex items-center gap-4">
            @auth
                <!-- User Dropdown -->
                <div x-data="{ open: false }" class="relative">
                    <button @click="open = !open" @click.outside="open = false" class="flex items-center gap-2 focus:outline-none hover:bg-gray-200 dark:hover:bg-gray-800 p-2 rounded-full transition-colors">
                        <span class="font-medium hidden sm:block">{{ Auth::user()->username }}</span>
                        <div class="w-8 h-8 rounded-full bg-indigo-500 flex items-center justify-center text-white font-bold text-sm">
                            {{ substr(Auth::user()->username, 0, 1) }}
                        </div>
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="open" x-cloak 
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-48 bg-white dark:bg-card rounded-md shadow-lg py-1 border border-gray-200 dark:border-gray-700 ring-1 ring-black ring-opacity-5 z-50">
                        
                        <div class="px-4 py-2 border-b border-gray-200 dark:border-gray-700">
                            <p class="text-sm text-gray-500 dark:text-gray-400">Signed in as</p>
                            <p class="text-sm font-medium truncate">{{ Auth::user()->username }}</p>
                        </div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-left max-w-full block px-4 py-2 text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-red-400">
                                Log Out
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <div class="space-x-4">
                    <a href="{{ route('login') }}" class="text-sm hover:underline">Log in</a>
                    <a href="{{ route('register') }}" class="text-sm hover:underline">Register</a>
                </div>
            @endauth
        </div>
    </header>

    <!-- Main Content -->
    <main class="pt-24 pb-12 px-4 max-w-7xl mx-auto">
        <!-- Movies Grid will go here -->
        @auth
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                <!-- Example Placeholder (Remove when real data is connected) -->
                @php
                    $movies = \App\Models\FavoriteMovie::where('user_id', Auth::id())->get();
                @endphp

                @forelse($movies as $movie)
                    <div class="bg-white dark:bg-card rounded-xl overflow-hidden shadow-lg border border-gray-200 dark:border-gray-800 transition-transform hover:scale-105 group relative">
                        <!-- Action Buttons -->
                        <div class="absolute top-2 right-2 flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                            <!-- Edit Button -->
                            <button @click="editingMovie = {{ $movie->toJson() }}; editModalOpen = true" 
                                    class="p-2 bg-black/50 hover:bg-black/70 text-white rounded-full cursor-pointer" title="Edit">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                                    <path d="M13.586 3.586a2 2 0 112.828 2.828l-.793.793-2.828-2.828.793-.793zM11.379 5.793L3 14.172V17h2.828l8.38-8.379-2.83-2.828z" />
                                </svg>
                            </button>

                            <!-- Delete Button -->
                            <form action="{{ route('movies.destroy', $movie->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this movie?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="p-2 bg-red-600/80 hover:bg-red-700 text-white rounded-full cursor-pointer" title="Delete">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        </div>

                        @if($movie->image)
                            <img src="{{ asset('storage/' . $movie->image) }}" alt="{{ $movie->movie_name }}" class="w-full h-64 object-cover">
                        @else
                            <div class="w-full h-64 bg-gray-300 dark:bg-gray-700 flex items-center justify-center text-gray-500">
                                <span>No Image</span>
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="text-lg font-semibold truncate">{{ $movie->movie_name }}</h3>
                            <div class="flex justify-between items-center mt-2 text-sm text-gray-500 dark:text-gray-400">
                                <span>{{ $movie->release_year }}</span>
                                <div class="flex items-center text-yellow-500">
                                    <span>★</span>
                                    <span class="ml-1 text-gray-700 dark:text-gray-300">{{ $movie->rating }}/5</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full flex flex-col items-center justify-center h-64 text-center text-gray-500 dark:text-gray-400">
                        <svg class="w-16 h-16 mb-4 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4v16M17 4v16M3 8h4m10 0h4M3 12h18M3 16h4m10 0h4M4 20h16a1 1 0 001-1V5a1 1 0 00-1-1H4a1 1 0 00-1 1v14a1 1 0 001 1z" />
                        </svg>
                        <p class="text-lg">No movies added yet.</p>
                        <p class="text-sm">Click the + button to add your first watched movie!</p>
                    </div>
                @endforelse
            </div>
        @else
            <div class="flex items-center justify-center h-[60vh] flex-col text-center">
                <h2 class="text-3xl font-bold mb-4">Welcome to MyMovies</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md">Your personal movie tracker. Login to start building your collection.</p>
                <div class="flex gap-4">
                    <a href="{{ route('login') }}" class="px-6 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg transition-colors">Get Started</a>
                </div>
            </div>
        @endauth
    </main>

    @auth
        <!-- Add Movie Floating Action Button -->
        <button onclick="document.getElementById('addMovieModal').classList.remove('hidden')" 
                class="fixed bottom-8 right-8 bg-green-600 hover:bg-green-700 text-white rounded-full p-4 shadow-xl transition-transform hover:scale-110 cursor-pointer z-50 group">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 group-hover:rotate-90 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
        </button>

        <!-- Add Movie Modal -->
        <div id="addMovieModal" class="hidden fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
            <div class="bg-white dark:bg-card w-full max-w-md rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 relative transform transition-all scale-100">
                
                <!-- Header -->
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-xl font-bold">Add Watched Movie</h2>
                    <button onclick="document.getElementById('addMovieModal').classList.add('hidden')" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <div class="p-6">
                    <form action="{{ route('movies.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        
                        <!-- Movie Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Movie Name</label>
                            <input type="text" name="movie_name" required placeholder="e.g. Inception"
                                class="w-full bg-gray-50 dark:bg-[#2a2a2a] border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all dark:text-white">
                        </div>

                        <!-- Release Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Release Year</label>
                            <input type="text" name="release_year" required maxlength="4" placeholder="e.g. 2010"
                                class="w-full bg-gray-50 dark:bg-[#2a2a2a] border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-green-500 focus:border-transparent outline-none transition-all dark:text-white">
                        </div>

                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <div class="flex gap-4">
                                @for ($i = 1; $i <= 5; $i++)
                                    <label class="cursor-pointer flex flex-col items-center group">
                                        <input type="radio" name="rating" value="{{ $i }}" class="peer sr-only">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center peer-checked:border-green-500 peer-checked:bg-green-500 peer-checked:text-white text-gray-500 dark:text-gray-400 hover:border-green-400 transition-all">
                                            {{ $i }}
                                        </div>
                                    </label>
                                @endfor
                            </div>
                        </div>

                        <!-- Image (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Image (Optional)</label>
                            <input type="file" name="image" accept="image/*"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-300
                                hover:file:bg-gray-200 dark:hover:file:bg-gray-600
                                cursor-pointer">
                        </div>

                        <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-green-900/20">
                            Add Movie
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Movie Modal -->
        <div x-show="editModalOpen" x-cloak 
             class="fixed inset-0 bg-black/80 backdrop-blur-sm flex items-center justify-center z-50 p-4 transition-opacity">
            <div class="bg-white dark:bg-card w-full max-w-md rounded-2xl shadow-2xl border border-gray-200 dark:border-gray-800 relative transform transition-all scale-100"
                 @click.outside="editModalOpen = false">
                
                <!-- Header -->
                <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-800">
                    <h2 class="text-xl font-bold">Edit Movie</h2>
                    <button @click="editModalOpen = false" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <!-- Form -->
                <div class="p-6">
                    <form :action="'/movies/' + (editingMovie ? editingMovie.id : '')" method="POST" enctype="multipart/form-data" class="space-y-5">
                        @csrf
                        @method('PUT')
                        
                        <!-- Movie Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Movie Name</label>
                            <input type="text" name="movie_name" required placeholder="e.g. Inception" x-model="editingMovie.movie_name"
                                class="w-full bg-gray-50 dark:bg-[#2a2a2a] border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all dark:text-white">
                        </div>

                        <!-- Release Year -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Release Year</label>
                            <input type="text" name="release_year" required maxlength="4" placeholder="e.g. 2010" x-model="editingMovie.release_year"
                                class="w-full bg-gray-50 dark:bg-[#2a2a2a] border border-gray-300 dark:border-gray-600 rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 focus:border-transparent outline-none transition-all dark:text-white">
                        </div>

                        <!-- Rating -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                            <div class="flex gap-4">
                                <template x-for="i in 5">
                                    <label class="cursor-pointer flex flex-col items-center group">
                                        <input type="radio" name="rating" :value="i" class="peer sr-only" x-model="editingMovie.rating">
                                        <div class="w-10 h-10 rounded-full border-2 border-gray-300 dark:border-gray-600 flex items-center justify-center peer-checked:border-indigo-500 peer-checked:bg-indigo-500 peer-checked:text-white text-gray-500 dark:text-gray-400 hover:border-indigo-400 transition-all" x-text="i">
                                        </div>
                                    </label>
                                </template>
                            </div>
                        </div>

                        <!-- Image (Optional) -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Cover Image (Optional)</label>
                            <input type="file" name="image" accept="image/*"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-gray-100 dark:file:bg-gray-700 file:text-gray-700 dark:file:text-gray-300
                                hover:file:bg-gray-200 dark:hover:file:bg-gray-600
                                cursor-pointer">
                        </div>

                        <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-indigo-900/20">
                            Update Movie
                        </button>
                    </form>
                </div>
            </div>
        </div>
    @endauth

    <!-- Close Modal on Escape -->
    <script>
        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") {
                document.getElementById('addMovieModal').classList.add('hidden');
            }
        });
    </script>
</body>
</html>