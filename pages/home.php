<?php
/**
 * ENTERPRISE OS - HOMEPAGE
 * Landing page for the Enterprise Operating System
 * @version 2.0
 */

require_once '../includes/config.php';
require_once '../includes/session_handler.php';
require_once '../includes/functions.php';

initDatabaseSessions();

// Redirect to dashboard if already logged in
if (isset($_SESSION['active_user'])) {
    header("Location: ../dashboard.php?page=home");
    exit();
}

// System metrics for homepage display
$systemStats = [
    'uptime' => '99.99%',
    'users' => '10,000+',
    'projects' => '500+',
    'response_time' => '< 100ms'
];

$features = [
    [
        'icon' => 'fas fa-shield-alt',
        'title' => 'Enterprise Security',
        'description' => 'Bank-level encryption with biometric authentication and real-time threat detection.',
        'color' => '#00d4ff'
    ],
    [
        'icon' => 'fas fa-chart-line',
        'title' => 'Real-time Analytics',
        'description' => 'Comprehensive dashboards with live metrics and predictive insights.',
        'color' => '#00ff88'
    ],
    [
        'icon' => 'fas fa-users-cog',
        'title' => 'Role-Based Access',
        'description' => 'Granular permissions with admin controls and user management.',
        'color' => '#ffa500'
    ],
    [
        'icon' => 'fas fa-project-diagram',
        'title' => 'Project Management',
        'description' => 'Track progress, deadlines, and team performance effortlessly.',
        'color' => '#ff006e'
    ],
    [
        'icon' => 'fas fa-robot',
        'title' => 'AI-Powered Insights',
        'description' => 'Machine learning algorithms predict trends and optimize workflows.',
        'color' => '#9b59b6'
    ],
    [
        'icon' => 'fas fa-cloud-upload-alt',
        'title' => 'Cloud Integration',
        'description' => 'Seamless sync across devices with automatic backups.',
        'color' => '#3498db'
    ]
];

$testimonials = [
    [
        'name' => 'Rocky Gen',
        'role' => 'System Architect',
        'quote' => 'The Enterprise OS has revolutionized how we manage our infrastructure. The biometric security is unmatched.',
        'avatar' => '../assets/images/slack.ico'
    ],
    [
        'name' => 'Marius Strategist',
        'role' => 'CFO',
        'quote' => 'Real-time analytics and project tracking have improved our financial decision-making by 200%.',
        'avatar' => '../assets/images/Beach Scene 2.png'
    ],
    [
        'name' => 'Forever Smart',
        'role' => 'Lead Developer',
        'quote' => 'The development workflow has never been smoother. AI insights help us predict bottlenecks.',
        'avatar' => '../assets/images/smooth_skin_portrait.jpg'
    ]
];

$pageTitle = 'Enterprise OS | Intelligent Operating System';
require_once '../includes/header.php';
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="hero-content">
            <div class="hero-text">
                <div class="hero-badge">
                    <i class="fas fa-shield-alt"></i> Enterprise Grade Security
                </div>
                <h1 class="hero-title">
                    The Future of<br>
                    <span>Intelligent Operations</span>
                </h1>
                <p class="hero-description">
                    Enterprise OS combines biometric security, real-time analytics, and AI-powered insights to transform how your organization operates.
                </p>

                <div class="hero-stats">
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $systemStats['uptime']; ?></div>
                        <div class="stat-label">Uptime</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $systemStats['users']; ?></div>
                        <div class="stat-label">Active Users</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?php echo $systemStats['response_time']; ?></div>
                        <div class="stat-label">Response Time</div>
                    </div>
                </div>

                <div class="hero-buttons">
                    <a href="../dashboard.php?page=login" class="btn btn-primary">
                        <i class="fas fa-sign-in-alt"></i> Login to Dashboard
                    </a>
                    <a href="register.php" class="btn btn-outline">
                        <i class="fas fa-user-plus"></i> Create Account
                    </a>
                    <a href="#features" class="btn btn-outline">
                        <i class="fas fa-play-circle"></i> Learn More
                    </a>
                </div>
            </div>

            <div class="hero-image">
                <img src="../assets/images/slack.ico" alt="Enterprise OS Dashboard">
                <div class="floating-card">
                    <div class="metric">
                        <i class="fas fa-check-circle"></i>
                        <div class="metric-info">
                            <h4>System Status</h4>
                            <p>All Systems Operational</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="features">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-cube"></i> Powerful Features
            </div>
            <h2 class="section-title">Everything You Need to Scale</h2>
            <p class="section-description">
                Comprehensive tools designed for modern enterprises
            </p>
        </div>

        <div class="features-grid">
            <?php foreach($features as $feature): ?>
            <div class="feature-card">
                <div class="feature-icon">
                    <i class="<?php echo $feature['icon']; ?>" style="color: <?php echo $feature['color']; ?>"></i>
                </div>
                <h3 class="feature-title"><?php echo $feature['title']; ?></h3>
                <p class="feature-description"><?php echo $feature['description']; ?></p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Demo Section -->
    <section id="demo" class="demo">
        <div class="demo-container">
            <div class="demo-content">
                <div class="section-badge">
                    <i class="fas fa-rocket"></i> Live Demo
                </div>
                <h2>See Enterprise OS in Action</h2>
                <p>
                    Experience the power of our intelligent operating system with real-time data and analytics.
                </p>

                <div class="demo-features">
                    <div class="demo-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Biometric authentication with live camera feed</span>
                    </div>
                    <div class="demo-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Real-time system monitoring and alerts</span>
                    </div>
                    <div class="demo-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Role-based access control (Admin vs Regular Users)</span>
                    </div>
                    <div class="demo-feature">
                        <i class="fas fa-check-circle"></i>
                        <span>Project management with progress tracking</span>
                    </div>
                </div>

                <a href="../dashboard.php?page=login" class="btn btn-primary">
                    <i class="fas fa-play-circle"></i> Launch Live Demo
                </a>
            </div>

            <div class="demo-preview">
                <img src="../assets/images/dashboard.png" alt="Dashboard Preview">
                <div class="demo-badge">
                    LIVE
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="testimonials">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-star"></i> Trusted by Leaders
            </div>
            <h2 class="section-title">What Our Users Say</h2>
            <p class="section-description">
                Join thousands of satisfied enterprise customers
            </p>
        </div>

        <div class="testimonials-grid">
            <?php foreach($testimonials as $testimonial): ?>
            <div class="testimonial-card">
                <div class="testimonial-header">
                    <img src="<?php echo $testimonial['avatar']; ?>" alt="<?php echo $testimonial['name']; ?>" class="testimonial-avatar">
                    <div class="testimonial-info">
                        <h4><?php echo $testimonial['name']; ?></h4>
                        <p><?php echo $testimonial['role']; ?></p>
                    </div>
                </div>
                <p class="testimonial-quote">"<?php echo $testimonial['quote']; ?>"</p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-container">
            <h2>Ready to Transform Your Operations?</h2>
            <p>
                Join the leading enterprises that trust Enterprise OS for their critical operations.
            </p>
            <div class="cta-buttons">
                <a href="../dashboard.php?page=login" class="btn btn-primary">
                    <i class="fas fa-fingerprint"></i> Get Started Now
                </a>
                <a href="#features" class="btn btn-outline">
                    <i class="fas fa-calendar-alt"></i> Schedule Demo
                </a>
            </div>
        </div>
    </section>

<?php require_once '../includes/footer.php'; ?>