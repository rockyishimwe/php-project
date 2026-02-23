<?php
/**
 * HEADER INCLUDE
 * Common header and navigation for all pages
 */

// Set security headers if function exists
if (function_exists('setSecurityHeaders')) {
    setSecurityHeaders();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Enterprise OS | Intelligent Operating System'; ?></title>

    <!-- Security Meta Tags -->
    <meta name="referrer" content="strict-origin-when-cross-origin">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <!-- External Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">

    <!-- Custom Styles -->
    <?php if (file_exists('../assets/css/styles.css')): ?>
        <link rel="stylesheet" href="../assets/css/styles.css">
    <?php endif; ?>

    <style>
        /* Base styles that might not be in styles.css */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'JetBrains Mono', monospace;
            background: #0a0e1a;
            color: #e0e7ff;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .background-grid {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(0, 212, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(0, 212, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            animation: gridPulse 20s ease-in-out infinite;
            pointer-events: none;
            z-index: -1;
        }

        @keyframes gridPulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1.5rem 3rem;
            background: rgba(15, 20, 32, 0.8);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(0, 212, 255, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .logo i {
            font-size: 1.8rem;
            color: #00d4ff;
            text-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        }

        .logo h1 {
            font-family: 'Orbitron', sans-serif;
            font-size: 1.5rem;
            font-weight: 800;
            background: linear-gradient(45deg, #00d4ff, #00ff88);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .logo span {
            font-size: 0.875rem;
            color: #64748b;
            margin-left: 0.5rem;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
        }

        .nav-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }

        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, #00d4ff, #00ff88);
            transition: width 0.3s ease;
        }

        .nav-links a:hover,
        .nav-links a.active {
            color: #00d4ff;
        }

        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }

        .nav-buttons {
            display: flex;
            gap: 1rem;
        }

        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: 8px;
            font-family: 'Orbitron', sans-serif;
            font-size: 0.875rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #00d4ff;
            color: #00d4ff;
        }

        .btn-outline:hover {
            background: rgba(0, 212, 255, 0.1);
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.3);
            transform: translateY(-2px);
        }

        .btn-primary {
            background: linear-gradient(135deg, #00d4ff, #00ff88);
            color: #0a0e1a;
            box-shadow: 0 0 20px rgba(0, 212, 255, 0.5);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(0, 212, 255, 0.7);
        }

        .footer {
            background: rgba(15, 20, 32, 0.95);
            border-top: 1px solid rgba(0, 212, 255, 0.1);
            padding: 4rem 3rem 2rem;
            margin-top: 4rem;
            backdrop-filter: blur(10px);
        }

        .footer-content {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 3rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }

        .footer-logo i {
            font-size: 1.5rem;
            color: #00d4ff;
        }

        .footer-logo span {
            font-family: 'Orbitron', sans-serif;
            font-weight: 700;
            color: #00d4ff;
        }

        .footer-description {
            color: #94a3b8;
            font-size: 0.875rem;
            line-height: 1.6;
            margin-bottom: 1.5rem;
        }

        .footer-social {
            display: flex;
            gap: 1rem;
        }

        .footer-social a {
            color: #64748b;
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }

        .footer-social a:hover {
            color: #00d4ff;
            transform: translateY(-2px);
        }

        .footer-column h4 {
            color: #e0e7ff;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            font-family: 'Orbitron', sans-serif;
        }

        .footer-links {
            list-style: none;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.875rem;
            transition: all 0.3s ease;
        }

        .footer-links a:hover {
            color: #00d4ff;
            padding-left: 0.25rem;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(0, 212, 255, 0.1);
            color: #64748b;
            font-size: 0.75rem;
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 1rem;
                padding: 1rem;
            }

            .nav-links {
                flex-wrap: wrap;
                justify-content: center;
            }

            .footer-content {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
        }
    </style>
</head>
<body>
    <div class="background-grid"></div>

    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-cube"></i>
            <h1>ENTERPRISE OS</h1>
        </div>

        <div class="nav-links">
            <a href="../home.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'home.php') ? 'class="active"' : ''; ?>>Home</a>
            <a href="../features.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'features.php') ? 'class="active"' : ''; ?>>Features</a>
            <a href="../demo.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'demo.php') ? 'class="active"' : ''; ?>>Demo</a>
            <a href="../testimonials.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'testimonials.php') ? 'class="active"' : ''; ?>>Testimonials</a>
            <a href="../contact.php" <?php echo (basename($_SERVER['PHP_SELF']) == 'contact.php') ? 'class="active"' : ''; ?>>Contact</a>
        </div>

        <div class="nav-buttons">
            <?php if (isset($_SESSION['active_user'])): ?>
                <a href="../dashboard.php" class="btn btn-outline">
                    <i class="fas fa-tachometer-alt"></i> Dashboard
                </a>
                <a href="../dashboard.php?page=logout" class="btn btn-primary">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline">
                    <i class="fas fa-lock"></i> Login
                </a>
                <a href="register.php" class="btn btn-primary">
                    <i class="fas fa-rocket"></i> Get Started
                </a>
            <?php endif; ?>
        </div>
    </nav>

    <!-- Main content will go here -->
    <main>