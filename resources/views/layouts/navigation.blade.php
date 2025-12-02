<nav class="w-full bg-white shadow-md">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="flex justify-between items-center h-16">
      <!-- Logo -->
      <div class="flex-shrink-0">
        <a href="{{ route('home') }}" class="flex items-center">
          <img src="{{ asset('assets/img/logo/vps.png') }}" alt="Vipers" class="h-8 w-8">
          <span class="ml-2 text-xl font-bold text-gray-800">Vipers</span>
        </a>
      </div>

      <!-- Centered Menu (hidden on mobile) -->
      <div class="hidden md:flex flex-1 justify-center">
        <div class="flex space-x-8">
          <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300">Home</a>
          <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300">About</a>
          <a href="{{ route('programs') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300">Programs</a>
          <a href="{{ route('gallery') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300">Gallery</a>
          <a href="{{ route('news') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300">News</a>
          <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 px-3 py-2 rounded-md text-sm font-medium transition duration-300">Contact</a>
        </div>
      </div>

      <!-- Search Bar (hidden on mobile) -->
      <div class="hidden md:flex items-center">
        <form action="{{ route('search') }}" method="GET" class="flex">
          <input type="text" name="q" class="px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" placeholder="Search...">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition duration-300">
            <i class="fas fa-search"></i>
          </button>
        </form>
      </div>

      <!-- Mobile menu button -->
      <div class="md:hidden">
        <button id="mobile-menu-button" class="text-gray-700 hover:text-blue-600 focus:outline-none focus:text-blue-600 transition duration-300">
          <i class="fas fa-bars text-xl"></i>
        </button>
      </div>
    </div>

    <!-- Mobile menu -->
    <div id="mobile-menu" class="md:hidden hidden">
      <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
        <a href="{{ route('home') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Home</a>
        <a href="{{ route('about') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-300">About</a>
        <a href="{{ route('programs') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Programs</a>
        <a href="{{ route('gallery') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Gallery</a>
        <a href="{{ route('news') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-300">News</a>
        <a href="{{ route('contact') }}" class="text-gray-700 hover:text-blue-600 block px-3 py-2 rounded-md text-base font-medium transition duration-300">Contact</a>
      </div>
      <div class="px-2 pt-2 pb-3">
        <form action="{{ route('search') }}" method="GET" class="flex">
          <input type="text" name="q" class="flex-1 px-4 py-2 border border-gray-300 rounded-l-md focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-300" placeholder="Search...">
          <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-r-md hover:bg-blue-700 transition duration-300">
            <i class="fas fa-search"></i>
          </button>
        </form>
      </div>
    </div>
  </div>
</nav>

<script>
document.getElementById('mobile-menu-button').addEventListener('click', function() {
  const menu = document.getElementById('mobile-menu');
  menu.classList.toggle('hidden');
});
</script>
