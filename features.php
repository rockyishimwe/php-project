<?php
/**
 * ENTERPRISE OS - FEATURES PAGE
 * Detailed features showcase with categories and comparisons
 * 
 * @version 2.0
 */

session_start();

// Redirect to dashboard if already logged in - REMOVED for public pages
// if (isset($_SESSION['active_user'])) {
//     header("Location: dashboard.php?page=home");
//     exit();
// }

$featureCategories = [
    'security' => [
        'title' => 'Enterprise Security',
        'icon' => 'fas fa-shield-alt',
        'color' => '#00d4ff',
        'features' => [
            [
                'name' => 'Biometric Authentication',
                'description' => 'Multi-factor authentication with facial recognition and fingerprint scanning.',
                'pro' => true,
                'popular' => true
            ],
            [
                'name' => 'End-to-End Encryption',
                'description' => 'AES-256 encryption for all data in transit and at rest.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'Role-Based Access Control',
                'description' => 'Granular permissions with admin, manager, and user roles.',
                'pro' => false,
                'popular' => true
            ],
            [
                'name' => 'Audit Logging',
                'description' => 'Complete trail of all system activities and access attempts.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'IP Whitelisting',
                'description' => 'Restrict access to trusted IP addresses only.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'Session Management',
                'description' => 'Automatic timeout and concurrent session control.',
                'pro' => false,
                'popular' => true
            ]
        ]
    ],
    'analytics' => [
        'title' => 'Analytics & Insights',
        'icon' => 'fas fa-chart-line',
        'color' => '#00ff88',
        'features' => [
            [
                'name' => 'Real-Time Dashboards',
                'description' => 'Live metrics and customizable dashboard widgets.',
                'pro' => false,
                'popular' => true
            ],
            [
                'name' => 'Predictive Analytics',
                'description' => 'AI-powered forecasts and trend predictions.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'Custom Reports',
                'description' => 'Generate and export detailed reports in multiple formats.',
                'pro' => false,
                'popular' => true
            ],
            [
                'name' => 'Performance Metrics',
                'description' => 'Track KPIs, response times, and system health.',
                'pro' => false,
                'popular' => true
            ],
            [
                'name' => 'Data Visualization',
                'description' => 'Interactive charts, graphs, and heat maps.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'Anomaly Detection',
                'description' => 'AI algorithms identify unusual patterns and outliers.',
                'pro' => true,
                'popular' => false
            ]
        ]
    ],
    'management' => [
        'title' => 'Project Management',
        'icon' => 'fas fa-tasks',
        'color' => '#ffa500',
        'features' => [
            [
                'name' => 'Task Tracking',
                'description' => 'Create, assign, and track tasks with deadlines.',
                'pro' => false,
                'popular' => true
            ],
            [
                'name' => 'Team Collaboration',
                'description' => 'Real-time updates, comments, and file sharing.',
                'pro' => false,
                'popular' => true
            ],
            [
                'name' => 'Gantt Charts',
                'description' => 'Visual project timelines and dependency tracking.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'Resource Allocation',
                'description' => 'Optimize team workload and resource distribution.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'Milestone Tracking',
                'description' => 'Set and monitor key project milestones.',
                'pro' => false,
                'popular' => true
            ],
            [
                'name' => 'Budget Management',
                'description' => 'Track project costs and budget utilization.',
                'pro' => true,
                'popular' => false
            ]
        ]
    ],
    'integration' => [
        'title' => 'Integrations',
        'icon' => 'fas fa-plug',
        'color' => '#ff006e',
        'features' => [
            [
                'name' => 'API Access',
                'description' => 'RESTful API for custom integrations.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'Webhook Support',
                'description' => 'Real-time event notifications to external services.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'Cloud Sync',
                'description' => 'Automatic sync with cloud storage providers.',
                'pro' => false,
                'popular' => true
            ],
            [
                'name' => 'Third-Party Apps',
                'description' => 'Pre-built integrations with popular tools.',
                'pro' => false,
                'popular' => true
            ],
            [
                'name' => 'Database Connectors',
                'description' => 'Connect to MySQL, PostgreSQL, MongoDB, and more.',
                'pro' => true,
                'popular' => false
            ],
            [
                'name' => 'SSO Integration',
                'description' => 'Single Sign-On with major identity providers.',
                'pro' => true,
                'popular' => false
            ]
        ]
    ]
];

$comparisonFeatures = [
    'Biometric Authentication',
    'Real-Time Analytics',
    'Project Management',
    'API Access',
    'Audit Logs',
    'Custom Reports',
    'Team Collaboration',
    'Security Monitoring',
    'Mobile Access',
    '24/7 Support'
];

$systemStats = [
    'features' => '50+',
    'integrations' => '100+',
    'users' => '10,000+',
    'uptime' => '99.99%'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise OS | Features</title>
    
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
           FEATURE CATEGORIES
           ======================= */
        .categories {
            padding: 2rem 2rem 5rem;
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .category-tabs {
            display: flex;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 3rem;
            flex-wrap: wrap;
        }
        
        .category-tab {
            padding: 1rem 2rem;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 40px;
            color: var(--text-secondary);
            font-family: var(--font-display);
            font-size: 0.875rem;
            cursor: pointer;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .category-tab i {
            font-size: 1rem;
        }
        
        .category-tab:hover,
        .category-tab.active {
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.2), rgba(0, 255, 136, 0.1));
            border-color: var(--accent-primary);
            color: var(--accent-primary);
            transform: translateY(-2px);
        }
        
        .category-content {
            display: none;
        }
        
        .category-content.active {
            display: block;
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        .category-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .category-header h2 {
            font-family: var(--font-display);
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .category-header p {
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .features-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        
        .feature-item {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 2rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .feature-item:hover {
            transform: translateY(-5px);
            border-color: var(--accent-primary);
            box-shadow: 0 20px 40px rgba(0, 212, 255, 0.2);
        }
        
        .feature-popular {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--accent-primary);
            color: var(--bg-primary);
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
        }
        
        .feature-pro {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: var(--accent-warning);
            color: var(--bg-primary);
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
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
        }
        
        .feature-name {
            font-family: var(--font-display);
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 0.75rem;
        }
        
        .feature-desc {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.7;
        }
        
        /* =======================
           COMPARISON TABLE
           ======================= */
        .comparison {
            padding: 5rem 2rem;
            background: linear-gradient(180deg, transparent, rgba(0, 212, 255, 0.05));
        }
        
        .comparison-container {
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        
        .section-header h2 {
            font-family: var(--font-display);
            font-size: 2.5rem;
            color: var(--text-primary);
            margin-bottom: 1rem;
        }
        
        .section-header p {
            color: var(--text-secondary);
            max-width: 600px;
            margin: 0 auto;
        }
        
        .comparison-table {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            overflow: hidden;
        }
        
        .comparison-row {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1fr;
            border-bottom: 1px solid var(--glass-border);
        }
        
        .comparison-row:last-child {
            border-bottom: none;
        }
        
        .comparison-row.header {
            background: rgba(0, 212, 255, 0.1);
            font-family: var(--font-display);
            font-weight: 600;
        }
        
        .comparison-cell {
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .comparison-cell i {
            font-size: 1.25rem;
        }
        
        .comparison-cell .check {
            color: var(--accent-secondary);
        }
        
        .comparison-cell .minus {
            color: var(--text-muted);
        }
        
        .comparison-badge {
            background: linear-gradient(135deg, var(--accent-primary), var(--accent-secondary));
            color: var(--bg-primary);
            padding: 0.25rem 1rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
            margin-left: 1rem;
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
            
            .features-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .comparison-row {
                grid-template-columns: 2fr 1fr 1fr;
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
            
            .features-grid {
                grid-template-columns: 1fr;
            }
            
            .comparison-row {
                grid-template-columns: 1fr;
                gap: 1rem;
                padding: 1rem;
            }
            
            .comparison-row.header {
                display: none;
            }
            
            .comparison-cell {
                padding: 0.5rem;
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
            <a href="features.php" class="active">Features</a>
            <a href="demo.php">Demo</a>
            <a href="testimonials.php">Testimonials</a>
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
        <h1>Enterprise <span>Features</span></h1>
        <p>Powerful tools designed to transform your business operations</p>
    </header>
    
    <!-- Stats Bar -->
    <section class="stats-bar">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['features'] ?></div>
                <div class="stat-label">Powerful Features</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['integrations'] ?></div>
                <div class="stat-label">Integrations</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['users'] ?></div>
                <div class="stat-label">Happy Users</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['uptime'] ?></div>
                <div class="stat-label">Uptime</div>
            </div>
        </div>
    </section>
    
    <!-- Feature Categories -->
    <section class="categories">
        <div class="category-tabs">
            <?php 
            $first = true;
            foreach($featureCategories as $key => $category): 
            ?>
            <div class="category-tab <?= $first ? 'active' : '' ?>" data-category="<?= $key ?>">
                <i class="<?= $category['icon'] ?>" style="color: <?= $category['color'] ?>"></i>
                <?= $category['title'] ?>
            </div>
            <?php 
            $first = false;
            endforeach; 
            ?>
        </div>
        
        <?php 
        $first = true;
        foreach($featureCategories as $key => $category): 
        ?>
        <div class="category-content <?= $first ? 'active' : '' ?>" id="category-<?= $key ?>">
            <div class="category-header">
                <h2 style="color: <?= $category['color'] ?>"><?= $category['title'] ?></h2>
                <p>Comprehensive <?= strtolower($category['title']) ?> tools for enterprise needs</p>
            </div>
            
            <div class="features-grid">
                <?php foreach($category['features'] as $feature): ?>
                <div class="feature-item">
                    <?php if(isset($feature['popular']) && $feature['popular']): ?>
                        <span class="feature-popular">Popular</span>
                    <?php elseif(isset($feature['pro']) && $feature['pro']): ?>
                        <span class="feature-pro">PRO</span>
                    <?php endif; ?>
                    
                    <div class="feature-icon">
                        <i class="<?= $category['icon'] ?>" style="color: <?= $category['color'] ?>"></i>
                    </div>
                    <h3 class="feature-name"><?= $feature['name'] ?></h3>
                    <p class="feature-desc"><?= $feature['description'] ?></p>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php 
        $first = false;
        endforeach; 
        ?>
    </section>
    
    <!-- Comparison Table -->
    <section class="comparison">
        <div class="comparison-container">
            <div class="section-header">
                <h2>Compare Plans</h2>
                <p>Choose the perfect plan for your organization</p>
            </div>
            
            <div class="comparison-table">
                <div class="comparison-row header">
                    <div class="comparison-cell">Feature</div>
                    <div class="comparison-cell">Basic</div>
                    <div class="comparison-cell">Professional</div>
                    <div class="comparison-cell">Enterprise</div>
                </div>
                
                <?php foreach($comparisonFeatures as $feature): ?>
                <div class="comparison-row">
                    <div class="comparison-cell"><?= $feature ?></div>
                    <div class="comparison-cell">
                        <?php if(in_array($feature, ['Project Management', 'Team Collaboration', 'Mobile Access'])): ?>
                            <i class="fas fa-check check"></i>
                        <?php else: ?>
                            <i class="fas fa-minus minus"></i>
                        <?php endif; ?>
                    </div>
                    <div class="comparison-cell">
                        <?php if(!in_array($feature, ['API Access', 'Custom Reports'])): ?>
                            <i class="fas fa-check check"></i>
                        <?php else: ?>
                            <i class="fas fa-minus minus"></i>
                        <?php endif; ?>
                    </div>
                    <div class="comparison-cell">
                        <i class="fas fa-check check"></i>
                        <?php if($feature === '24/7 Support'): ?>
                            <span class="comparison-badge">24/7</span>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section class="cta">
        <div class="cta-container">
            <h2>Ready to Get Started?</h2>
            <p>Join thousands of enterprises already using our platform</p>
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
    
    <!-- Category Tabs Script -->
    <script>
        document.querySelectorAll('.category-tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.category-tab').forEach(t => t.classList.remove('active'));
                document.querySelectorAll('.category-content').forEach(c => c.classList.remove('active'));
                
                this.classList.add('active');
                
                const category = this.dataset.category;
                document.getElementById(`category-${category}`).classList.add('active');
            });
        });
        
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
        
        document.querySelectorAll('.feature-item, .comparison-table').forEach(el => {
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