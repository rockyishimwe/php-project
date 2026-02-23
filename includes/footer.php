<?php
/**
 * FOOTER INCLUDE
 * Common footer for all pages
 */
?>
    </main>

    <!-- Footer -->
    <footer class="footer">
        <div class="footer-content">
            <div>
                <div class="footer-logo">
                    <i class="fas fa-cube"></i>
                    <span>ENTERPRISE OS</span>
                </div>
                <p class="footer-description">
                    The next generation operating system for modern enterprises. Secure, scalable, intelligent.
                </p>
                <div class="footer-social">
                    <a href="#"><i class="fab fa-github"></i></a>
                    <a href="#"><i class="fab fa-linkedin"></i></a>
                    <a href="#"><i class="fab fa-twitter"></i></a>
                    <a href="#"><i class="fab fa-discord"></i></a>
                </div>
            </div>

            <div class="footer-column">
                <h4>Product</h4>
                <ul class="footer-links">
                    <li><a href="../features.php">Features</a></li>
                    <li><a href="../demo.php">Demo</a></li>
                    <li><a href="#">Pricing</a></li>
                    <li><a href="#">Security</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Company</h4>
                <ul class="footer-links">
                    <li><a href="#">About</a></li>
                    <li><a href="#">Blog</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Press</a></li>
                </ul>
            </div>

            <div class="footer-column">
                <h4>Support</h4>
                <ul class="footer-links">
                    <li><a href="../contact.php">Contact</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">API Reference</a></li>
                    <li><a href="#">Community</a></li>
                </ul>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Enterprise OS. All rights reserved. | Powered by Advanced Enterprise Solutions</p>
        </div>
    </footer>

    <!-- Custom JavaScript -->
    <?php if (file_exists('../assets/js/main.js')): ?>
        <script src="../assets/js/main.js"></script>
    <?php endif; ?>

    <!-- Display any flash messages -->
    <?php if (function_exists('displayFlashMessage')): ?>
        <?php displayFlashMessage(); ?>
    <?php endif; ?>
</body>
</html>