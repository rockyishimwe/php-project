<?php
/**
 * ENTERPRISE OS - DEMO PAGE
 * Interactive demo and video showcase
 * 
 * @version 2.0
 */

session_start();

// Redirect to dashboard if already logged in - REMOVED for public pages
// if (isset($_SESSION['active_user'])) {
//     header("Location: dashboard.php?page=home");
//     exit();
// }

$systemStats = [
    'videos' => '24+',
    'views' => '50K+',
    'duration' => '3.5 hrs',
    'users' => '10,000+'
];

$demoVideos = [
    [
        'title' => 'Dashboard Overview',
        'description' => 'Get a complete overview of the main dashboard and key metrics',
        'duration' => '2:45',
        'thumbnail' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800',
        'views' => '12.5K'
    ],
    [
        'title' => 'Security Features',
        'description' => 'Learn about biometric authentication and security protocols',
        'duration' => '3:30',
        'thumbnail' => 'https://images.unsplash.com/photo-1563013544-824ae1b704d3?w=800',
        'views' => '8.2K'
    ],
    [
        'title' => 'Project Management',
        'description' => 'See how to manage projects, tasks, and team collaboration',
        'duration' => '4:15',
        'thumbnail' => 'https://images.unsplash.com/photo-1454165804606-c3d57bc86b40?w=800',
        'views' => '15.8K'
    ],
    [
        'title' => 'Analytics Dashboard',
        'description' => 'Explore real-time analytics and reporting features',
        'duration' => '3:45',
        'thumbnail' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800',
        'views' => '9.3K'
    ],
    [
        'title' => 'User Management',
        'description' => 'Learn how to manage users, roles, and permissions',
        'duration' => '2:55',
        'thumbnail' => 'https://images.unsplash.com/photo-1522071820081-009f0129c71c?w=800',
        'views' => '6.7K'
    ],
    [
        'title' => 'System Settings',
        'description' => 'Configure system parameters and security settings',
        'duration' => '3:20',
        'thumbnail' => 'https://images.unsplash.com/photo-1558494949-ef010cbdcc31?w=800',
        'views' => '5.1K'
    ]
];

$demoSteps = [
    [
        'step' => '01',
        'title' => 'Authentication',
        'description' => 'Secure biometric login with facial recognition',
        'icon' => 'fas fa-fingerprint'
    ],
    [
        'step' => '02',
        'title' => 'Dashboard',
        'description' => 'Real-time metrics and system overview',
        'icon' => 'fas fa-chart-line'
    ],
    [
        'step' => '03',
        'title' => 'Projects',
        'description' => 'Create and manage projects with team collaboration',
        'icon' => 'fas fa-tasks'
    ],
    [
        'step' => '04',
        'title' => 'Analytics',
        'description' => 'Generate reports and analyze performance',
        'icon' => 'fas fa-chart-pie'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise OS | Live Demo</title>
    
    <!-- External Libraries -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;700&family=Orbitron:wght@400;600;800&display=swap" rel="stylesheet">
    
    <style>
        /* =======================
           TYPOGRAPHY & VARIABLES
           ======================= */
        :root {
            /* Colors */
            --bg-primary: #0a0e1a;
            --bg-secondary: #0f1420;
            --bg-tertiary: #141824;
            --glass-bg: rgba(15, 20, 32, 0.85);
            --glass-border: rgba(0, 212, 255, 0.15);
            --accent-primary: #00d4ff;
            --accent-secondary: #00ff88;
            --accent-warning: #ffa500;
            --accent-danger: #ff006e;
            --text-primary: #e0e7ff;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            
            /* Effects */
            --glow-sm: 0 0 10px rgba(0, 212, 255, 0.3);
            --glow-md: 0 0 20px rgba(0, 212, 255, 0.4);
            --glow-lg: 0 0 30px rgba(0, 212, 255, 0.5);
            --shadow-elevated: 0 10px 40px rgba(0, 0, 0, 0.5);
            
            /* Spacing */
            --spacing-xs: 0.5rem;
            --spacing-sm: 1rem;
            --spacing-md: 1.5rem;
            --spacing-lg: 2rem;
            --spacing-xl: 3rem;
            --spacing-2xl: 5rem;
            
            /* Typography */
            --font-display: 'Orbitron', sans-serif;
            --font-mono: 'JetBrains Mono', monospace;
            --font-body: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
        
        /* =======================
           GLOBAL STYLES
           ======================= */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: var(--font-body);
            color: var(--text-primary);
            background: var(--bg-primary);
            overflow-x: hidden;
            line-height: 1.6;
        }
        
        /* Animated background grid */
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
            z-index: 0;
        }
        
        @keyframes gridPulse {
            0%, 100% { opacity: 0.3; }
            50% { opacity: 0.6; }
        }
        
        /* =======================
           NAVIGATION
           ======================= */
        .navbar {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: var(--glass-bg);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--glass-border);
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            z-index: 1000;
            animation: slideDown 0.5s ease-out;
        }
        
        @keyframes slideDown {
            from {
                transform: translateY(-100%);
            }
            to {
                transform: translateY(0);
            }
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .logo i {
            font-size: 2rem;
            color: var(--accent-primary);
            text-shadow: var(--glow-sm);
            animation: rotate 10s linear infinite;
        }
        
        @keyframes rotate {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
        
        .logo h1 {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: 2px;
        }
        
        .logo span {
            color: var(--accent-primary);
            text-shadow: var(--glow-sm);
        }
        
        .nav-links {
            display: flex;
            align-items: center;
            gap: 2rem;
        }
        
        .nav-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
        }
        
        .nav-links a:hover,
        .nav-links a.active {
            color: var(--accent-primary);
        }
        
        .nav-links a::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: linear-gradient(90deg, var(--accent-primary), var(--accent-secondary));
            transition: width 0.3s ease;
        }
        
        .nav-links a:hover::after,
        .nav-links a.active::after {
            width: 100%;
        }
        
        .nav-buttons {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-family: var(--font-display);
            font-weight: 600;
            font-size: 0.875rem;
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
            border: 1px solid var(--accent-primary);
            color: var(--accent-primary);
        }
        
        .btn-outline:hover {
            background: rgba(0, 212, 255, 0.1);
            box-shadow: var(--glow-sm);
            transform: translateY(-2px);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            border: none;
            color: var(--bg-primary);
            box-shadow: var(--glow-md);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--glow-lg);
        }
        
        /* =======================
           PAGE HEADER
           ======================= */
        .page-header {
            padding: 8rem 2rem 3rem;
            text-align: center;
            position: relative;
        }
        
        .page-header h1 {
            font-family: var(--font-display);
            font-size: 3.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 1rem;
            animation: fadeInUp 0.8s ease-out;
        }
        
        .page-header h1 span {
            color: var(--accent-primary);
            text-shadow: var(--glow-md);
        }
        
        .page-header p {
            color: var(--text-secondary);
            font-size: 1.25rem;
            max-width: 700px;
            margin: 0 auto;
            animation: fadeInUp 0.8s ease-out 0.2s forwards;
            opacity: 0;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        /* =======================
           STATS BAR
           ======================= */
        .stats-bar {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 255, 136, 0.1));
            border-top: 1px solid var(--glass-border);
            border-bottom: 1px solid var(--glass-border);
            padding: 3rem 2rem;
        }
        
        .stats-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 2rem;
        }
        
        .stat-item {
            text-align: center;
        }
        
        .stat-value {
            font-family: var(--font-display);
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--accent-primary);
            margin-bottom: 0.5rem;
        }
        
        .stat-label {
            color: var(--text-secondary);
            text-transform: uppercase;
            font-size: 0.875rem;
            letter-spacing: 1px;
        }
        
        /* =======================
           DEMO-SPECIFIC STYLES
           ======================= */
        .demo-showcase {
            padding: 3rem 2rem 5rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .demo-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            margin-bottom: 4rem;
        }
        
        .featured-demo {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            overflow: hidden;
            position: relative;
        }
        
        .featured-demo img {
            width: 100%;
            height: auto;
            display: block;
        }
        
        .demo-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .featured-demo:hover .demo-overlay {
            opacity: 1;
        }
        
        .play-button {
            width: 80px;
            height: 80px;
            background: var(--accent-primary);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            animation: pulse 2s infinite;
        }
        
        .play-button i {
            color: white;
            font-size: 2rem;
            margin-left: 5px;
        }
        
        .demo-info {
            padding: 2rem;
        }
        
        .demo-info h2 {
            font-family: var(--font-display);
            font-size: 2rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .demo-info p {
            color: var(--text-secondary);
            margin-bottom: 1.5rem;
        }
        
        .demo-meta {
            display: flex;
            gap: 2rem;
            color: var(--text-muted);
            font-size: 0.875rem;
        }
        
        .demo-meta i {
            color: var(--accent-primary);
            margin-right: 0.5rem;
        }
        
        .demo-sidebar {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
        }
        
        .sidebar-title {
            font-family: var(--font-display);
            font-size: 1.25rem;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .sidebar-title i {
            color: var(--accent-primary);
        }
        
        .demo-steps {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
        }
        
        .demo-step {
            display: flex;
            gap: 1rem;
            align-items: flex-start;
        }
        
        .step-number {
            font-family: var(--font-display);
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--accent-primary);
            opacity: 0.5;
        }
        
        .step-content h4 {
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .step-content h4 i {
            color: var(--accent-primary);
            margin-right: 0.5rem;
        }
        
        .step-content p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .demo-videos {
            margin-top: 4rem;
        }
        
        .videos-title {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .videos-title h3 {
            font-family: var(--font-display);
            font-size: 2rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .videos-title p {
            color: var(--text-secondary);
        }
        
        .videos-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        
        .video-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .video-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-primary);
            box-shadow: 0 20px 40px rgba(0, 212, 255, 0.2);
        }
        
        .video-thumbnail {
            position: relative;
            height: 200px;
            overflow: hidden;
        }
        
        .video-thumbnail img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .video-card:hover .video-thumbnail img {
            transform: scale(1.1);
        }
        
        .video-duration {
            position: absolute;
            bottom: 1rem;
            right: 1rem;
            background: rgba(0, 0, 0, 0.8);
            color: white;
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
            font-size: 0.75rem;
        }
        
        .video-info {
            padding: 1.5rem;
        }
        
        .video-info h4 {
            font-family: var(--font-display);
            font-size: 1.125rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .video-info p {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .video-stats {
            display: flex;
            justify-content: space-between;
            color: var(--text-muted);
            font-size: 0.75rem;
        }
        
        .video-stats i {
            color: var(--accent-primary);
            margin-right: 0.25rem;
        }
        
        .live-demo {
            padding: 5rem 2rem;
            background: linear-gradient(180deg, transparent, rgba(0, 212, 255, 0.05));
            text-align: center;
        }
        
        .live-demo-container {
            max-width: 800px;
            margin: 0 auto;
        }
        
        .live-demo h2 {
            font-family: var(--font-display);
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .live-demo p {
            color: var(--text-secondary);
            margin-bottom: 2rem;
        }
        
        .demo-badge {
            display: inline-block;
            padding: 0.5rem 1.5rem;
            background: var(--accent-danger);
            border-radius: 30px;
            color: white;
            font-weight: 600;
            margin-bottom: 2rem;
            animation: pulse 2s infinite;
        }
        
        /* =======================
           CTA SECTION
           ======================= */
        .cta {
            padding: 5rem 2rem;
            text-align: center;
            position: relative;
        }
        
        .cta-container {
            max-width: 800px;
            margin: 0 auto;
            padding: 4rem;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.1), rgba(0, 255, 136, 0.1));
            border: 1px solid var(--glass-border);
            border-radius: 32px;
            backdrop-filter: blur(10px);
        }
        
        .cta h2 {
            font-family: var(--font-display);
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .cta p {
            color: var(--text-secondary);
            font-size: 1.125rem;
            margin-bottom: 2rem;
        }
        
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }
        
        /* =======================
           FOOTER
           ======================= */
        .footer {
            padding: 3rem 2rem;
            background: var(--bg-secondary);
            border-top: 1px solid var(--glass-border);
        }
        
        .footer-content {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            gap: 2rem;
        }
        
        .footer-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .footer-logo i {
            font-size: 1.5rem;
            color: var(--accent-primary);
        }
        
        .footer-logo span {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        .footer-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .footer-social {
            display: flex;
            gap: 1rem;
        }
        
        .footer-social a {
            color: var(--text-secondary);
            font-size: 1.25rem;
            transition: all 0.3s ease;
        }
        
        .footer-social a:hover {
            color: var(--accent-primary);
            transform: translateY(-2px);
        }
        
        .footer-column h4 {
            font-family: var(--font-display);
            font-size: 1rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .footer-links {
            list-style: none;
        }
        
        .footer-links li {
            margin-bottom: 0.5rem;
        }
        
        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.3s ease;
        }
        
        .footer-links a:hover {
            color: var(--accent-primary);
        }
        
        .footer-bottom {
            max-width: 1200px;
            margin: 2rem auto 0;
            padding-top: 2rem;
            border-top: 1px solid var(--glass-border);
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        /* =======================
           RESPONSIVE DESIGN
           ======================= */
        @media (max-width: 1024px) {
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .demo-grid {
                grid-template-columns: 1fr;
            }
            
            .videos-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .footer-content {
                grid-template-columns: repeat(2, 1fr);
            }
        }
        
        @media (max-width: 768px) {
            .navbar {
                padding: 1rem;
            }
            
            .nav-links {
                display: none;
            }
            
            .page-header h1 {
                font-size: 2.5rem;
            }
            
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .videos-grid {
                grid-template-columns: 1fr;
            }
            
            .cta-container {
                padding: 2rem;
            }
            
            .cta-buttons {
                flex-direction: column;
            }
            
            .footer-content {
                grid-template-columns: 1fr;
            }
        }
        
        /* =======================
           ANIMATIONS
           ======================= */
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes glow {
            0%, 100% { box-shadow: 0 0 20px rgba(0, 212, 255, 0.3); }
            50% { box-shadow: 0 0 40px rgba(0, 212, 255, 0.6); }
        }
        
        /* =======================
           SCROLLBAR
           ======================= */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-secondary);
        }
        
        ::-webkit-scrollbar-thumb {
            background: rgba(0, 212, 255, 0.3);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 212, 255, 0.5);
        }
    </style>
</head>
<body>
    <div class="background-grid"></div>
    
    <!-- Navigation -->
    <nav class="navbar">
        <div class="logo">
            <i class="fas fa-cube"></i>
            <h1>ENTERPRISE <span>OS</span></h1>
        </div>
        
        <div class="nav-links">
            <a href="home.php">Home</a>
            <a href="features.php">Features</a>
            <a href="demo.php" class="active">Demo</a>
            <a href="testimonials.php">Testimonials</a>
            <a href="contact.php">Contact</a>
        </div>
        
        <div class="nav-buttons">
            <a href="?page=login" class="btn btn-outline">
                <i class="fas fa-lock"></i> Login
            </a>
            <a href="?page=login" class="btn btn-primary">
                <i class="fas fa-rocket"></i> Get Started
            </a>
        </div>
    </nav>
    
    <!-- Page Header -->
    <header class="page-header">
        <h1>Live <span>Demo</span></h1>
        <p>See Enterprise OS in action with our interactive demonstrations</p>
    </header>
    
    <!-- Stats Bar -->
    <section class="stats-bar">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['videos'] ?></div>
                <div class="stat-label">Video Tutorials</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['views'] ?></div>
                <div class="stat-label">Total Views</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['duration'] ?></div>
                <div class="stat-label">Content Duration</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['users'] ?></div>
                <div class="stat-label">Happy Users</div>
            </div>
        </div>
    </section>
    
    <!-- Demo Showcase -->
    <section class="demo-showcase">
        <div class="demo-grid">
            <div class="featured-demo">
                <img src="https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800" alt="Dashboard Demo">
                <div class="demo-overlay">
                    <div class="play-button">
                        <i class="fas fa-play"></i>
                    </div>
                </div>
                <div class="demo-info">
                    <h2>Complete Platform Overview</h2>
                    <p>Watch our comprehensive demo covering all major features of Enterprise OS. See how our platform can transform your business operations.</p>
                    <div class="demo-meta">
                        <span><i class="fas fa-clock"></i> 12:45 min</span>
                        <span><i class="fas fa-eye"></i> 24.5K views</span>
                        <span><i class="fas fa-calendar"></i> Updated 2 days ago</span>
                    </div>
                </div>
            </div>
            
            <div class="demo-sidebar">
                <div class="sidebar-title">
                    <i class="fas fa-list-ol"></i>
                    <span>Quick Start Guide</span>
                </div>
                
                <div class="demo-steps">
                    <?php foreach($demoSteps as $step): ?>
                    <div class="demo-step">
                        <div class="step-number"><?= $step['step'] ?></div>
                        <div class="step-content">
                            <h4><i class="<?= $step['icon'] ?>"></i><?= $step['title'] ?></h4>
                            <p><?= $step['description'] ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                
                <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid var(--glass-border);">
                    <a href="?page=login" class="btn btn-primary" style="width: 100%;">
                        <i class="fas fa-fingerprint"></i> Try Live Demo
                    </a>
                </div>
            </div>
        </div>
        
        <div class="demo-videos">
            <div class="videos-title">
                <h3>Feature Tutorials</h3>
                <p>Detailed guides for every feature</p>
            </div>
            
            <div class="videos-grid">
                <?php foreach($demoVideos as $video): ?>
                <div class="video-card">
                    <div class="video-thumbnail">
                        <img src="<?= $video['thumbnail'] ?>" alt="<?= $video['title'] ?>">
                        <span class="video-duration"><?= $video['duration'] ?></span>
                    </div>
                    <div class="video-info">
                        <h4><?= $video['title'] ?></h4>
                        <p><?= $video['description'] ?></p>
                        <div class="video-stats">
                            <span><i class="fas fa-eye"></i> <?= $video['views'] ?> views</span>
                            <span><i class="fas fa-clock"></i> <?= $video['duration'] ?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- Live Demo CTA -->
    <section class="live-demo">
        <div class="live-demo-container">
            <div class="demo-badge">
                <i class="fas fa-circle" style="font-size: 0.5rem; margin-right: 0.5rem;"></i> LIVE NOW
            </div>
            <h2>Try It Yourself</h2>
            <p>Get hands-on experience with our interactive demo environment. No credit card required.</p>
            <a href="?page=login" class="btn btn-primary" style="padding: 1rem 3rem;">
                <i class="fas fa-play-circle"></i> Launch Live Demo
            </a>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-container">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of enterprises already using our platform</p>
            <div class="cta-buttons">
                <a href="?page=login" class="btn btn-primary">
                    <i class="fas fa-fingerprint"></i> Start Free Trial
                </a>
                <a href="contact.php" class="btn btn-outline">
                    <i class="fas fa-calendar-alt"></i> Contact Sales
                </a>
            </div>
        </div>
    </section>
    
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
                    <li><a href="features.php">Features</a></li>
                    <li><a href="demo.php">Demo</a></li>
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
                    <li><a href="contact.php">Contact</a></li>
                    <li><a href="#">Documentation</a></li>
                    <li><a href="#">API Reference</a></li>
                    <li><a href="#">Community</a></li>
                </ul>
            </div>
        </div>
        
        <div class="footer-bottom">
            <p>&copy; <?= date('Y') ?> Enterprise OS. All rights reserved. | Powered by Advanced Enterprise Solutions</p>
        </div>
    </footer>
    
    <!-- JavaScript -->
    <script>
        // Navbar scroll effect
        window.addEventListener('scroll', function() {
            const navbar = document.querySelector('.navbar');
            if (window.scrollY > 50) {
                navbar.style.background = 'rgba(10, 14, 26, 0.95)';
                navbar.style.backdropFilter = 'blur(20px)';
            } else {
                navbar.style.background = 'var(--glass-bg)';
                navbar.style.backdropFilter = 'blur(10px)';
            }
        });
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.2,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.style.opacity = '1';
                    entry.target.style.transform = 'translateY(0)';
                }
            });
        }, observerOptions);
        
        document.querySelectorAll('.featured-demo, .demo-sidebar, .video-card, .live-demo-container').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            observer.observe(el);
        });
        
        // Play button functionality
        document.querySelectorAll('.play-button').forEach(button => {
            button.addEventListener('click', function() {
                alert('Video player would open here. This is a demo simulation.');
            });
        });
    </script>
</body>
</html>