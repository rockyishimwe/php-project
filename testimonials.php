<?php
/**
 * ENTERPRISE OS - TESTIMONIALS PAGE
 * Customer success stories and case studies
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
    'users' => '10,000+',
    'clients' => '500+',
    'uptime' => '99.99%',
    'support' => '24/7'
];

$testimonials = [
    [
        'name' => 'Rocky Gen',
        'role' => 'System Architect',
        'company' => 'TechCorp Industries',
        'quote' => 'The Enterprise OS has revolutionized how we manage our infrastructure. The biometric security is unmatched, and the real-time analytics have helped us reduce downtime by 75%.',
        'avatar' => 'assets/images/slack.ico',
        'rating' => 5,
        'featured' => true
    ],
    [
        'name' => 'Marius Strategist',
        'role' => 'Chief Financial Officer',
        'company' => 'Global Finance Inc',
        'quote' => 'Real-time analytics and project tracking have improved our financial decision-making by 200%. The ROI we\'ve seen in just 6 months is incredible.',
        'avatar' => 'assets/images/Beach Scene 2.png',
        'rating' => 5,
        'featured' => true
    ],
    [
        'name' => 'Forever Smart',
        'role' => 'Lead Developer',
        'company' => 'Innovation Labs',
        'quote' => 'The development workflow has never been smoother. AI insights help us predict bottlenecks before they happen. Our team productivity has doubled.',
        'avatar' => 'assets/images/smooth_skin_portrait.jpg',
        'rating' => 5,
        'featured' => true
    ],
    [
        'name' => 'Niyompuhwe Robert',
        'role' => 'CTO',
        'company' => 'Backend Developer',
        'quote' => 'Security was our biggest concern, but Enterprise OS exceeded our expectations. The audit logs and access controls give us complete peace of mind.',
        'avatar' => 'assets/images/robert.jpg',
        'rating' => 5,
        'featured' => false
    ],
    [
        'name' => 'Marcus Rodriguez',
        'role' => 'Product Manager',
        'company' => 'Agile Dynamics',
        'quote' => 'The project management features are a game-changer. We\'ve reduced meeting times by 50% because everything is visible in real-time.',
        'avatar' => 'https://randomuser.me/api/portraits/men/32.jpg',
        'rating' => 4,
        'featured' => false
    ],
    [
        'name' => 'Uwumuremyi Albert',
        'role' => 'Research Director',
        'company' => 'BioTech Innovations',
        'quote' => 'Handling sensitive research data requires the highest security standards. Enterprise OS delivers that with an intuitive interface our team loves.',
        'avatar' => 'assets/images/albert.jpg',
        'rating' => 5,
        'featured' => false
    ]
];

$caseStudies = [
    [
        'title' => 'How TechCorp Reduced Security Incidents by 95%',
        'company' => 'TechCorp Industries',
        'industry' => 'Technology',
        'results' => ['95% fewer incidents', '40% cost reduction', '100% compliance'],
        'image' => 'https://images.unsplash.com/photo-1486406146926-c627a92ad1ab?w=800'
    ],
    [
        'title' => 'Global Finance Streamlines Operations with AI',
        'company' => 'Global Finance Inc',
        'industry' => 'Finance',
        'results' => ['200% ROI', '60% faster reporting', '99.9% uptime'],
        'image' => 'https://images.unsplash.com/photo-1460925895917-afdab827c52f?w=800'
    ],
    [
        'title' => 'Innovation Labs Doubles Developer Productivity',
        'company' => 'Innovation Labs',
        'industry' => 'Software',
        'results' => ['2x productivity', '50% fewer bugs', '30% faster releases'],
        'image' => 'https://images.unsplash.com/photo-1551288049-bebda4e38f71?w=800'
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise OS | Testimonials</title>
    
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
           SECTION HEADER
           ======================= */
        .section-header {
            text-align: center;
            max-width: 700px;
            margin: 0 auto 4rem;
        }
        
        .section-badge {
            display: inline-block;
            padding: 0.5rem 1rem;
            background: rgba(0, 212, 255, 0.1);
            border: 1px solid var(--accent-primary);
            border-radius: 20px;
            color: var(--accent-primary);
            font-family: var(--font-mono);
            font-size: 0.75rem;
            margin-bottom: 1rem;
        }
        
        .section-title {
            font-family: var(--font-display);
            font-size: 2.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .section-description {
            color: var(--text-secondary);
            font-size: 1.125rem;
        }
        
        /* =======================
           TESTIMONIALS-SPECIFIC STYLES
           ======================= */
        .featured-testimonials {
            padding: 5rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .featured-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 3rem;
        }
        
        .testimonial-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2rem;
            position: relative;
            transition: all 0.3s ease;
        }
        
        .testimonial-card.featured {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.15), rgba(0, 255, 136, 0.15));
            border-color: var(--accent-primary);
            transform: scale(1.02);
        }
        
        .testimonial-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-primary);
            box-shadow: 0 20px 40px rgba(0, 212, 255, 0.2);
        }
        
        .testimonial-card.featured:hover {
            transform: scale(1.02) translateY(-5px);
        }
        
        .quote-icon {
            position: absolute;
            top: 1.5rem;
            right: 1.5rem;
            font-size: 4rem;
            color: var(--accent-primary);
            opacity: 0.2;
            font-family: serif;
        }
        
        .testimonial-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .testimonial-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            border: 3px solid var(--accent-primary);
            object-fit: cover;
        }
        
        .testimonial-author h4 {
            font-family: var(--font-display);
            font-size: 1.125rem;
            color: var(--text-primary);
            margin-bottom: 0.25rem;
        }
        
        .testimonial-author p {
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        .testimonial-company {
            color: var(--accent-primary);
            font-size: 0.75rem;
            font-family: var(--font-mono);
            margin-top: 0.25rem;
        }
        
        .testimonial-rating {
            color: #ffd700;
            margin-bottom: 1rem;
        }
        
        .testimonial-rating i {
            margin-right: 0.25rem;
        }
        
        .testimonial-quote {
            color: var(--text-secondary);
            font-style: italic;
            line-height: 1.8;
            position: relative;
            z-index: 1;
        }
        
        .featured-badge {
            position: absolute;
            top: -10px;
            left: 2rem;
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: var(--bg-primary);
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        /* =======================
           CASE STUDIES
           ======================= */
        .case-studies {
            padding: 5rem 2rem;
            background: linear-gradient(180deg, transparent, rgba(0, 212, 255, 0.05));
        }
        
        .case-studies-grid {
            max-width: 1200px;
            margin: 3rem auto 0;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        
        .case-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .case-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-primary);
            box-shadow: 0 20px 40px rgba(0, 212, 255, 0.2);
        }
        
        .case-image {
            height: 200px;
            overflow: hidden;
        }
        
        .case-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        
        .case-card:hover .case-image img {
            transform: scale(1.1);
        }
        
        .case-content {
            padding: 2rem;
        }
        
        .case-industry {
            color: var(--accent-primary);
            font-size: 0.75rem;
            font-family: var(--font-mono);
            text-transform: uppercase;
            margin-bottom: 0.5rem;
        }
        
        .case-title {
            font-family: var(--font-display);
            font-size: 1.25rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .case-company {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        
        .case-results {
            list-style: none;
        }
        
        .case-results li {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .case-results li i {
            color: var(--accent-secondary);
        }
        
        /* =======================
           ALL TESTIMONIALS
           ======================= */
        .all-testimonials {
            padding: 5rem 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .testimonials-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 2rem;
            margin-top: 3rem;
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
            
            .featured-grid,
            .case-studies-grid,
            .testimonials-grid {
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
            
            .stats-grid,
            .featured-grid,
            .case-studies-grid,
            .testimonials-grid {
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
    
        /* Mobile hamburger menu */
        .hamburger {
            display: none;
            flex-direction: column;
            gap: 5px;
            cursor: pointer;
            padding: 4px;
            background: none;
            border: none;
        }
        .hamburger span {
            display: block;
            width: 24px;
            height: 2px;
            background: var(--accent-primary);
            transition: all 0.3s ease;
            border-radius: 2px;
        }
        .hamburger.open span:nth-child(1) { transform: rotate(45deg) translate(5px, 5px); }
        .hamburger.open span:nth-child(2) { opacity: 0; }
        .hamburger.open span:nth-child(3) { transform: rotate(-45deg) translate(5px, -5px); }

        @media (max-width: 768px) {
            .hamburger { display: flex !important; }
            .nav-links {
                display: none !important;
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: rgba(10, 14, 26, 0.98);
                border-bottom: 1px solid rgba(0, 212, 255, 0.2);
                flex-direction: column;
                padding: 1rem 2rem;
                gap: 1rem;
                backdrop-filter: blur(20px);
                z-index: 999;
            }
            .nav-links.open { display: flex !important; }
            .nav-buttons { gap: 0.5rem; }
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
        
        <button class="hamburger" id="hamburger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="features.php">Features</a>
            <a href="demo.php">Demo</a>
            <a href="testimonials.php" class="active">Testimonials</a>
            <a href="contact.php">Contact</a>
        </div>
        
        <div class="nav-buttons">
            <a href="pages/login.php" class="btn btn-outline">
                <i class="fas fa-lock"></i> Login
            </a>
            <a href="pages/login.php" class="btn btn-primary">
                <i class="fas fa-rocket"></i> Get Started
            </a>
        </div>
    </nav>
    
    <!-- Page Header -->
    <header class="page-header">
        <h1>Customer <span>Success</span></h1>
        <p>Trusted by leading enterprises worldwide</p>
    </header>
    
    <!-- Stats Bar -->
    <section class="stats-bar">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['users'] ?></div>
                <div class="stat-label">Active Users</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['clients'] ?></div>
                <div class="stat-label">Enterprise Clients</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['uptime'] ?></div>
                <div class="stat-label">Uptime</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['support'] ?></div>
                <div class="stat-label">Support</div>
            </div>
        </div>
    </section>
    
    <!-- Featured Testimonials -->
    <section class="featured-testimonials">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-star"></i> Featured Stories
            </div>
            <h2 class="section-title">What Our <span style="color: var(--accent-primary);">Top Customers</span> Say</h2>
            <p class="section-description">Real experiences from industry leaders</p>
        </div>
        
        <div class="featured-grid">
            <?php foreach(array_filter($testimonials, fn($t) => $t['featured']) as $testimonial): ?>
            <div class="testimonial-card featured">
                <div class="featured-badge">FEATURED</div>
                <div class="quote-icon">"</div>
                <div class="testimonial-header">
                    <img src="<?= $testimonial['avatar'] ?>" alt="<?= $testimonial['name'] ?>" class="testimonial-avatar">
                    <div class="testimonial-author">
                        <h4><?= $testimonial['name'] ?></h4>
                        <p><?= $testimonial['role'] ?></p>
                        <div class="testimonial-company"><?= $testimonial['company'] ?></div>
                    </div>
                </div>
                <div class="testimonial-rating">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?= $i <= $testimonial['rating'] ? '' : 'fa-regular' ?>"></i>
                    <?php endfor; ?>
                </div>
                <p class="testimonial-quote">"<?= $testimonial['quote'] ?>"</p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <!-- Case Studies -->
    <section class="case-studies">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-chart-line"></i> Case Studies
            </div>
            <h2 class="section-title">Success <span style="color: var(--accent-secondary);">Stories</span></h2>
            <p class="section-description">Real results from real customers</p>
        </div>
        
        <div class="case-studies-grid">
            <?php foreach($caseStudies as $case): ?>
            <div class="case-card">
                <div class="case-image">
                    <img src="<?= $case['image'] ?>" alt="<?= $case['title'] ?>">
                </div>
                <div class="case-content">
                    <div class="case-industry"><?= $case['industry'] ?></div>
                    <h3 class="case-title"><?= $case['title'] ?></h3>
                    <div class="case-company"><?= $case['company'] ?></div>
                    <ul class="case-results">
                        <?php foreach($case['results'] as $result): ?>
                        <li><i class="fas fa-check-circle"></i> <?= $result ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <!-- All Testimonials -->
    <section class="all-testimonials">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-users"></i> Customer Voices
            </div>
            <h2 class="section-title">More <span style="color: var(--accent-warning);">Testimonials</span></h2>
            <p class="section-description">Hear from our diverse customer base</p>
        </div>
        
        <div class="testimonials-grid">
            <?php foreach(array_filter($testimonials, fn($t) => !$t['featured']) as $testimonial): ?>
            <div class="testimonial-card">
                <div class="quote-icon">"</div>
                <div class="testimonial-header">
                    <img src="<?= $testimonial['avatar'] ?>" alt="<?= $testimonial['name'] ?>" class="testimonial-avatar">
                    <div class="testimonial-author">
                        <h4><?= $testimonial['name'] ?></h4>
                        <p><?= $testimonial['role'] ?></p>
                        <div class="testimonial-company"><?= $testimonial['company'] ?></div>
                    </div>
                </div>
                <div class="testimonial-rating">
                    <?php for($i = 1; $i <= 5; $i++): ?>
                        <i class="fas fa-star <?= $i <= $testimonial['rating'] ? '' : 'fa-regular' ?>"></i>
                    <?php endfor; ?>
                </div>
                <p class="testimonial-quote">"<?= $testimonial['quote'] ?>"</p>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-container">
            <h2>Join Our Success Story</h2>
            <p>Become the next enterprise to transform with Enterprise OS</p>
            <div class="cta-buttons">
                <a href="pages/login.php" class="btn btn-primary">
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
        
        document.querySelectorAll('.testimonial-card, .case-card, .cta-container').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            observer.observe(el);
        });
    </script>
    <script>
        const hamburger = document.getElementById('hamburger');
        const navLinks = document.querySelector('.nav-links');
        if (hamburger && navLinks) {
            hamburger.addEventListener('click', () => {
                hamburger.classList.toggle('open');
                navLinks.classList.toggle('open');
            });
        }
    </script>
</body>
</html>