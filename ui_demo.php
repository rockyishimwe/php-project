<?php
/**
 * UI/UX DEMO PAGE
 * Showcases the improved cyber-themed design system
 */

$pageTitle = 'UI/UX Demo - Enterprise OS';
$basePath = '';
require_once 'includes/header.php';
?>

<style>
    .demo-container {
        min-height: 100vh;
        padding: 2rem;
        background: var(--bg-primary);
    }

    .demo-header {
        text-align: center;
        margin-bottom: 4rem;
        padding: 2rem 0;
    }

    .demo-header h1 {
        font-family: var(--font-display);
        font-size: 3rem;
        font-weight: 800;
        color: var(--accent-primary);
        text-transform: uppercase;
        letter-spacing: 4px;
        margin-bottom: 1rem;
        text-shadow: var(--glow-lg);
        background: linear-gradient(45deg, var(--accent-primary), var(--accent-secondary));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .demo-header p {
        color: var(--text-secondary);
        font-size: 1.2rem;
        font-family: var(--font-mono);
        max-width: 600px;
        margin: 0 auto;
    }

    .demo-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
        gap: 2rem;
        margin-bottom: 4rem;
    }

    .demo-card {
        background: var(--glass-bg);
        border: 2px solid var(--glass-border);
        border-radius: 20px;
        padding: 2rem;
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-elevated);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }

    .demo-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary), var(--accent-warning));
        animation: shimmer 3s linear infinite;
    }

    .demo-card:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-elevated), var(--glow-md);
        border-color: var(--accent-primary);
    }

    .demo-card h3 {
        font-family: var(--font-display);
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--accent-primary);
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .demo-card p {
        color: var(--text-secondary);
        line-height: 1.6;
        margin-bottom: 1.5rem;
    }

    .demo-buttons {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        margin-bottom: 1.5rem;
    }

    .demo-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 10px;
        font-family: var(--font-display);
        font-size: 0.9rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        overflow: hidden;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .demo-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }

    .demo-btn:hover::before {
        left: 100%;
    }

    .demo-btn-primary {
        background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
        color: var(--bg-primary);
        box-shadow: var(--glow-sm);
    }

    .demo-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: var(--glow-md);
    }

    .demo-btn-secondary {
        background: rgba(15, 20, 32, 0.8);
        color: var(--text-primary);
        border: 1px solid var(--glass-border);
    }

    .demo-btn-secondary:hover {
        background: rgba(15, 20, 32, 0.9);
        border-color: var(--accent-primary);
        color: var(--accent-primary);
    }

    .demo-form-group {
        margin-bottom: 1.5rem;
    }

    .demo-form-label {
        display: block;
        font-family: var(--font-mono);
        font-size: 0.75rem;
        text-transform: uppercase;
        color: var(--text-secondary);
        margin-bottom: 0.5rem;
        letter-spacing: 1px;
        font-weight: 600;
    }

    .demo-form-control {
        width: 100%;
        padding: 1rem;
        background: rgba(15, 20, 32, 0.8);
        border: 2px solid var(--glass-border);
        border-radius: 10px;
        color: var(--text-primary);
        font-family: var(--font-mono);
        font-size: 0.9rem;
        outline: none;
        transition: all 0.3s ease;
        backdrop-filter: blur(10px);
    }

    .demo-form-control:focus {
        border-color: var(--accent-primary);
        box-shadow: 0 0 0 4px rgba(0, 212, 255, 0.15), var(--glow-sm);
        background: rgba(15, 20, 32, 0.9);
        transform: translateY(-2px);
    }

    .demo-alert {
        padding: 1rem 1.25rem;
        border-radius: 10px;
        margin-bottom: 1rem;
        border: 1px solid;
        font-family: var(--font-mono);
        font-size: 0.875rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
        animation: slideIn 0.3s ease-out;
    }

    .demo-alert-success {
        background: rgba(0, 255, 136, 0.1);
        border-color: var(--accent-secondary);
        color: var(--accent-secondary);
    }

    .demo-alert-error {
        background: rgba(255, 0, 110, 0.1);
        border-color: var(--accent-danger);
        color: var(--accent-danger);
    }

    .demo-showcase {
        background: var(--glass-bg);
        border: 2px solid var(--glass-border);
        border-radius: 20px;
        padding: 3rem;
        margin-top: 4rem;
        backdrop-filter: blur(20px);
        box-shadow: var(--shadow-elevated);
        text-align: center;
    }

    .demo-showcase h2 {
        font-family: var(--font-display);
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--accent-primary);
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 2px;
    }

    .demo-showcase p {
        color: var(--text-secondary);
        font-size: 1.1rem;
        max-width: 600px;
        margin: 0 auto 2rem;
    }

    .demo-links {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
    }

    .demo-link {
        color: var(--accent-primary);
        text-decoration: none;
        font-weight: 500;
        font-size: 1.1rem;
        transition: all 0.3s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .demo-link:hover {
        color: var(--accent-secondary);
        transform: translateX(5px);
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }

    @media (max-width: 768px) {
        .demo-grid {
            grid-template-columns: 1fr;
        }

        .demo-header h1 {
            font-size: 2rem;
        }

        .demo-links {
            flex-direction: column;
            align-items: center;
        }
    }
</style>

<div class="demo-container">
    <div class="demo-header">
        <h1>UI/UX Enhancements</h1>
        <p>Experience the modern cyber-themed design system with enhanced animations, micro-interactions, and improved user experience.</p>
    </div>

    <div class="demo-grid">
        <!-- Form Controls Demo -->
        <div class="demo-card">
            <h3><i class="fas fa-keyboard"></i> Form Controls</h3>
            <p>Enhanced form inputs with real-time validation, smooth animations, and visual feedback.</p>

            <div class="demo-form-group">
                <label class="demo-form-label">Email Address</label>
                <input type="email" class="demo-form-control" placeholder="Enter your email" value="user@example.com">
            </div>

            <div class="demo-form-group">
                <label class="demo-form-label">Password</label>
                <input type="password" class="demo-form-control" placeholder="Enter password" value="password123">
            </div>

            <div class="demo-buttons">
                <button class="demo-btn demo-btn-primary">
                    <i class="fas fa-paper-plane"></i> Submit
                </button>
                <button class="demo-btn demo-btn-secondary">
                    <i class="fas fa-times"></i> Cancel
                </button>
            </div>
        </div>

        <!-- Alert Messages Demo -->
        <div class="demo-card">
            <h3><i class="fas fa-bell"></i> Alert Messages</h3>
            <p>Beautiful animated alert messages with contextual colors and smooth transitions.</p>

            <div class="demo-alert demo-alert-success">
                <i class="fas fa-check-circle"></i>
                <div>
                    <strong>Success!</strong> Your account has been created successfully.
                </div>
            </div>

            <div class="demo-alert demo-alert-error">
                <i class="fas fa-exclamation-triangle"></i>
                <div>
                    <strong>Error!</strong> Please check your input and try again.
                </div>
            </div>
        </div>

        <!-- Button Interactions Demo -->
        <div class="demo-card">
            <h3><i class="fas fa-mouse-pointer"></i> Button Interactions</h3>
            <p>Interactive buttons with hover effects, loading states, and smooth animations.</p>

            <div class="demo-buttons">
                <button class="demo-btn demo-btn-primary">
                    <i class="fas fa-rocket"></i> Launch
                </button>
                <button class="demo-btn demo-btn-secondary">
                    <i class="fas fa-download"></i> Download
                </button>
                <button class="demo-btn demo-btn-primary" disabled>
                    <i class="fas fa-spinner fa-spin"></i> Processing...
                </button>
            </div>

            <p style="color: var(--text-muted); font-size: 0.8rem; margin-top: 1rem;">
                Hover over buttons to see the shimmer effect and lift animation.
            </p>
        </div>

        <!-- Color System Demo -->
        <div class="demo-card">
            <h3><i class="fas fa-palette"></i> Color System</h3>
            <p>Cohesive cyber-themed color palette with accent colors and glassmorphism effects.</p>

            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div style="background: var(--accent-primary); padding: 1rem; border-radius: 8px; text-align: center; color: white; font-family: var(--font-mono); font-size: 0.8rem;">
                    Primary<br>#00d4ff
                </div>
                <div style="background: var(--accent-secondary); padding: 1rem; border-radius: 8px; text-align: center; color: white; font-family: var(--font-mono); font-size: 0.8rem;">
                    Secondary<br>#00ff88
                </div>
                <div style="background: var(--accent-warning); padding: 1rem; border-radius: 8px; text-align: center; color: white; font-family: var(--font-mono); font-size: 0.8rem;">
                    Warning<br>#ffa500
                </div>
            </div>

            <p style="color: var(--text-secondary); font-size: 0.9rem;">
                Glassmorphism backgrounds with backdrop blur and animated gradients.
            </p>
        </div>
    </div>

    <!-- Showcase Section -->
    <div class="demo-showcase">
        <h2>Try the Enhanced Authentication</h2>
        <p>Experience the improved login and registration system with modern UI/UX design, real-time validation, and smooth animations.</p>

        <div class="demo-links">
            <a href="dashboard.php?page=login" class="demo-link">
                <i class="fas fa-sign-in-alt"></i> Login Page
            </a>
            <a href="pages/register.php" class="demo-link">
                <i class="fas fa-user-plus"></i> Registration Page
            </a>
            <a href="dashboard.php" class="demo-link">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add hover effects to demo cards
    const cards = document.querySelectorAll('.demo-card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-8px) scale(1.02)';
        });

        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0) scale(1)';
        });
    });

    // Add click effects to buttons
    const buttons = document.querySelectorAll('.demo-btn:not([disabled])');
    buttons.forEach(button => {
        button.addEventListener('click', function(e) {
            if (!this.disabled) {
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = '';
                }, 150);
            }
        });
    });

    // Add focus effects to form controls
    const formControls = document.querySelectorAll('.demo-form-control');
    formControls.forEach(control => {
        control.addEventListener('focus', function() {
            this.parentElement.style.transform = 'scale(1.02)';
        });

        control.addEventListener('blur', function() {
            this.parentElement.style.transform = '';
        });
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>