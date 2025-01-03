<?php 
class FooterView {

    public function displayFooterMenu() {
        // echo '<link rel="stylesheet" href="../../../public/css/output.css">';
        $menuController = new MenuController();
        $menuItems = $menuController->getMainMenuItems();

        ?>
        <footer class="bg-gray-800 text-white py-8">
    <div class="container mx-auto">
        <!-- Footer Menu Links -->
        <div class="footer-menu w-full text-center mb-8">
            <ul class="flex flex-wrap gap-4 justify-center">
                <?php foreach ($menuItems as $item): ?>
                    <li>
                        <a href="<?php echo htmlspecialchars($item['link']); ?>" 
                           class="hover:text-gray-300">
                            <?php echo htmlspecialchars($item['name']); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- Follow Us and Contact Us -->
        <div class="flex flex-wrap justify-center gap-8">
            <!-- Contact Info -->
            <div class="text-center md:text-left">
                <h4 class="text-lg font-semibold mb-2">Contact Us</h4>
                <p class="text-sm">1234 Main Street, Anytown, USA</p>
                <p class="text-sm">Email: <a href="mailto:info@example.com" class="hover:text-gray-300">info@example.com</a></p>
                <p class="text-sm">Phone: <a href="tel:+1234567890" class="hover:text-gray-300">+1 234 567 890</a></p>
            </div>

            <!-- Social Media Links -->
            <div class="text-center md:text-left">
                <h4 class="text-lg font-semibold mb-2">Follow Us</h4>
                <div class="flex justify-center md:justify-start gap-4">
                    <a href="#" class="hover:text-gray-300"><i class="fab fa-facebook-f"></i> Facebook</a>
                    <a href="#" class="hover:text-gray-300"><i class="fab fa-twitter"></i> Twitter</a>
                    <a href="#" class="hover:text-gray-300"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="border-t border-gray-700 my-6"></div>

        <!-- Copyright -->
        <div class="text-center text-sm">
            <p>&copy; <?php echo date('Y'); ?> Your Company Name. All rights reserved.</p>
            <p>Powered by <a href="https://example.com" class="hover:text-gray-300">Your Platform</a>.</p>
        </div>
    </div>
</footer>



        <?php
    }
}
?>