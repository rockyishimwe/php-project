<?php
/**
 * HOME PAGE
 * Main landing page for the application
 */

require_once '../includes/config.php';
require_once '../includes/session_handler.php';
require_once '../includes/functions.php';

// Initialize session handling
if (function_exists('initDatabaseSessions')) {
    initDatabaseSessions();
} else {
    session_start();
}

$pageTitle = 'Enterprise OS - Next Generation Operating System';
require_once '../includes/header.php';
?>

<style>
    .hero-section {
        min-height: 80vh;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 3rem;
        position: relative;
        overflow: hidden;
    }

    .hero-content {
        flex: 1;
        max-width: 600px;
    }

    .hero-content h1 {
        font-family: 'Orbitron', sans-serif;
        font-size: 3.5rem;
        font-weight: 800;
        line-height: 1.2;
        margin-bottom: 1.5rem;
        background: linear-gradient(45deg, #00d4ff, #00ff88);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    .hero-content p {
        font-size: 1.125rem;
        color: #94a3b8;
        margin-bottom: 2rem;
        line-height: 1.8;
    }

    .hero-buttons {
        display: flex;
        gap: 1rem;
    }

    .hero-visual {
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .hero-visual i {
        font-size: 15rem;
        color: #00d4ff;
        opacity: 0.6;
        filter: drop-shadow(0 0 50px rgba(0, 212, 255, 0.5));
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }

    .features-section {
        padding: 4rem 3rem;
    }

    .section-title {
        text-align: center;
        margin-bottom: 3rem;
    }

    .section-title h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        color: #00d4ff;
        margin-bottom: 1rem;
    }

    .section-title p {
        color: #94a3b8;
        font-size: 1.125rem;
    }

    .features-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
        max-width: 1200px;
        margin: 0 auto;
    }

    .feature-card {
        background: rgba(15, 20, 32, 0.6);
        border: 1px solid rgba(0, 212, 255, 0.1);
        border-radius: 16px;
        padding: 2rem;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .feature-card:hover {
        border-color: #00d4ff;
        transform: translateY(-5px);
        box-shadow: 0 10px 40px rgba(0, 212, 255, 0.2);
    }

    .feature-icon {
        width: 60px;
        height: 60px;
        background: rgba(0, 212, 255, 0.1);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 1.5rem;
    }

    .feature-icon i {
        font-size: 2rem;
        color: #00d4ff;
    }

    .feature-card h3 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: #e0e7ff;
    }

    .feature-card p {
        color: #94a3b8;
        font-size: 0.875rem;
        line-height: 1.6;
    }

    .cta-section {
        padding: 4rem 3rem;
        text-align: center;
        background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 255, 136, 0.1));
        border-radius: 24px;
        margin: 2rem 3rem;
    }

    .cta-section h2 {
        font-family: 'Orbitron', sans-serif;
        font-size: 2.5rem;
        color: #e0e7ff;
        margin-bottom: 1rem;
    }

    .cta-section p {
        color: #94a3b8;
        font-size: 1.125rem;
        margin-bottom: 2rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .cta-buttons {
        display: flex;
        gap: 1rem;
        justify-content: center;
    }

    @media (max-width: 768px) {
        .hero-section {
            flex-direction: column;
            text-align: center;
            padding: 2rem;
        }

        .hero-content h1 {
            font-size: 2.5rem;
        }

        .hero-buttons {
            justify-content: center;
        }

        .hero-visual i {
            font-size: 10rem;
        }

        .features-grid {
            grid-template-columns: 1fr;
        }

        .cta-section {
            margin: 2rem 1rem;
            padding: 3rem 1rem;
        }

        .cta-buttons {
            flex-direction: column;
        }
    }
</style>

<!-- Hero Section -->
<section class="hero-section">
    <div class="hero-content">
        <h1>Welcome to the Future of Enterprise Computing</h1>
        <p>Enterprise OS is a next-generation operating system designed for modern businesses. Secure, scalable, and intelligent - built to handle the challenges of tomorrow.</p>
        <div class="hero-buttons">
            <?php if (isLoggedIn()): ?>
                <a href="../dashboard.php" class="btn btn-primary">
                    <i class="fas fa-tachometer-alt"></i>
                    Go to Dashboard
                </a>
            <?php else: ?>
                <a href="login.php" class="btn btn-primary">
                    <i class="fas fa-rocket"></i>
                    Get Started
                </a>
                <a href="register.php" class="btn btn-outline">
                    <i class="fas fa-user-plus"></i>
                    Create Account
                </a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero-visual">
        <i class="fas fa-cube"></i>
    </div>
</section>

<!-- Features Section -->
<section class="features-section">
    <div class="section-title">
        <h2>Powerful Features</h2>
        <p>Everything you need to run your enterprise efficiently</p>
    </div>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-shield-alt"></i>
            </div>
            <h3>Enterprise Security</h3>
            <p>Bank-level encryption, multi-factor authentication, and real-time threat detection to keep your data safe.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-chart-line"></i>
            </div>
            <h3>Advanced Analytics</h3>
            <p>Real-time insights and predictive analytics to help you make data-driven decisions.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-cloud-upload-alt"></i>
            </div>
            <h3>Cloud Integration</h3>
            <p>Seamless integration with major cloud providers for scalable infrastructure.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-users"></i>
            </div>
            <h3>Team Collaboration</h3>
            <p>Built-in tools for team communication, project management, and file sharing.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-robot"></i>
            </div>
            <h3>AI-Powered</h3>
            <p>Intelligent automation and machine learning to streamline your workflows.</p>
        </div>

        <div class="feature-card">
            <div class="feature-icon">
                <i class="fas fa-sync-alt"></i>
            </div>
            <h3>Real-time Updates</h3>
            <p>Instant synchronization across all devices with real-time data updates.</p>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section">
    <h2>Ready to Transform Your Business?</h2>
    <p>Join thousands of enterprises that trust Enterprise OS for their critical operations.</p>
    <div class="cta-buttons">
        <?php if (isLoggedIn()): ?>
            <a href="../dashboard.php" class="btn btn-primary">
                <i class="fas fa-tachometer-alt"></i>
                Go to Dashboard
            </a>
        <?php else: ?>
            <a href="register.php" class="btn btn-primary">
                <i class="fas fa-rocket"></i>
                Start Free Trial
            </a>
            <a href="../contact.php" class="btn btn-outline">
                <i class="fas fa-headset"></i>
                Contact Sales
            </a>
        <?php endif; ?>
    </div>
</section>

<?php require_once '../includes/footer.php'; ?>