<?php
require_once __DIR__ . '/../../controllers/SidebarController.php';

class SidebarView {
    private $sidebarController;

    public function __construct() {
        $this->sidebarController = new SidebarController();
    }

    public function displaySidebar($activePage = '') {
        $items = $this->sidebarController->getSidebarData();
        ?>
        <!-- Mobile Menu Button -->
        <button id="mobile-menu-button" 
                class="lg:hidden fixed top-4 left-4 z-50 p-2 rounded-md bg-gray-800 text-white hover:bg-gray-700 focus:outline-none">
            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
        </button>

        <!-- Sidebar -->
        <aside id="sidebar" 
            class="transform lg:transform-none lg:translate-x-0 -translate-x-full transition-transform duration-300 ease-in-out
                    fixed top-0 left-0 h-screen w-64 bg-gray-800 text-white z-40
                    lg:relative">
            <!-- Logo/Header (add it mmb3d-->
            <div class="flex items-center justify-between h-16 bg-gray-900 px-4">
                <h1 class="text-lg font-semibold">Admin Panel</h1>
                <!-- Close button for mobile -->
                <button id="close-sidebar" 
                        class="lg:hidden p-2 rounded-md hover:bg-gray-700 focus:outline-none">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Navigation -->
            <nav class="mt-4">
                <ul class="space-y-1">
                    <?php foreach ($items as $item): ?>
                        <li>
                            <a href="<?php echo htmlspecialchars($item['link']); ?>" 
                            class="flex items-center px-4 py-3 text-sm font-medium
                                    hover:bg-gray-700 hover:text-blue-400
                                    transition-all duration-200 ease-in-out
                                    <?php echo $activePage === $item['link'] 
                                            ? 'bg-gray-700 text-blue-400 border-l-4 border-blue-400' 
                                            : ''; ?>">
                                <span><?php echo htmlspecialchars($item['name']); ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </nav>
        </aside>

        <!-- Overlay for mobile -->
        <div id="sidebar-overlay"
            class="fixed inset-0 bg-black bg-opacity-50 z-30 lg:hidden hidden"
            aria-hidden="true">
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const sidebar = document.getElementById('sidebar');
                const mobileMenuButton = document.getElementById('mobile-menu-button');
                const closeSidebarButton = document.getElementById('close-sidebar');
                const overlay = document.getElementById('sidebar-overlay');

                function toggleSidebar() {
                    sidebar.classList.toggle('-translate-x-full');
                    overlay.classList.toggle('hidden');
                    document.body.classList.toggle('overflow-hidden');
                }

                mobileMenuButton.addEventListener('click', toggleSidebar);
                closeSidebarButton.addEventListener('click', toggleSidebar);
                overlay.addEventListener('click', toggleSidebar);

                // Close sidebar when clicking a link (mobile only)
                const sidebarLinks = sidebar.querySelectorAll('a');
                sidebarLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 1024) { // lg breakpoint
                            toggleSidebar();
                        }
                    });
                });

                // Handle resize events
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 1024) {
                        sidebar.classList.remove('-translate-x-full');
                        overlay.classList.add('hidden');
                        document.body.classList.remove('overflow-hidden');
                    }
                });
            });
        </script>
        <?php
    }
}
?>