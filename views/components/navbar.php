<header class="flex items-center justify-between px-6 py-4 bg-white border-b border-gray-200 shadow-sm">
    <div class="flex items-center">
        <button class="text-gray-500 focus:outline-none md:hidden">
            <i class="fas fa-bars"></i>
        </button>
        <div class="relative mx-4 md:mx-0">
            <span class="absolute inset-y-0 left-0 flex items-center pl-3">
                <i class="fas fa-search text-gray-400"></i>
            </span>
            <input class="w-full py-2 pl-10 pr-4 text-gray-700 bg-gray-100 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent" type="search" placeholder="Search...">
        </div>
    </div>
    
    <div class="flex items-center">
        <button class="text-gray-500 focus:outline-none mx-4">
            <i class="fas fa-bell"></i>
        </button>
        <button class="text-gray-500 focus:outline-none mx-4">
            <i class="fas fa-envelope"></i>
        </button>
        
        <!-- dropdown -->
        <div class="relative ml-3" id="user-dropdown">
          <div>
            <button type="button" id="user-menu-button" class="flex items-center focus:outline-none cursor-pointer">
              <img class="w-8 h-8 rounded-full"  src="https://ui-avatars.com/api/?name=<?= $_SESSION['user_name'] ?>&background=6366f1&color=fff" ?>
              <span class="ml-2 text-sm font-medium text-gray-700"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Guest') ?></span>
              <i class="fas fa-chevron-down ml-1 text-gray-500 text-xs"></i>
            </button>
          </div>
            
            <!-- dropdown menu -->
            <div id="user-menu" class="hidden absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50 focus:outline-none" role="menu">
                <a href="profile.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                    <i class="fas fa-user mr-2"></i> Profile
                </a>
                <a href="settings.php" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                    <i class="fas fa-cog mr-2"></i> Settings
                </a>
                <div class="border-t border-gray-100"></div>
                <form method="POST" action="<?= url('src/helpers/logout.php') ?>" class="w-full">
                    <button type="submit" class="w-full text-left block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer" role="menuitem" onclick="return confirm('Are you sure you want to logout?')">
                        <i class="fas fa-sign-out-alt mr-2"></i> Logout
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    document.getElementById('user-menu-button').addEventListener('click', function() {
        const menu = document.getElementById('user-menu');
        menu.classList.toggle('hidden');
    });

    // close when click outside
    document.addEventListener('click', function(event) {
        const dropdown = document.getElementById('user-dropdown');
        const button = document.getElementById('user-menu-button');
        const menu = document.getElementById('user-menu');
        
        if (!dropdown.contains(event.target) && !menu.classList.contains('hidden')) {
            menu.classList.add('hidden');
        }
    });
</script>