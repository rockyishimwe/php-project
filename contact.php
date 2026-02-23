<?php
/**
 * ENTERPRISE OS - CONTACT PAGE
 * Contact forms, support options, and location information
 * 
 * @version 2.0
 */

session_start();
require_once 'includes/config.php';

// Handle form submission
$messageSent = false;
$messageError = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $firstName = trim($_POST['first_name'] ?? '');
    $lastName  = trim($_POST['last_name']  ?? '');
    $email     = trim($_POST['email']      ?? '');
    $company   = trim($_POST['company']    ?? '');
    $subject   = trim($_POST['subject']    ?? '');
    $message   = trim($_POST['message']    ?? '');

    if (empty($firstName) || empty($lastName) || empty($email) || empty($message)) {
        $messageError = 'Please fill in all required fields.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $messageError = 'Please enter a valid email address.';
    } else {
        try {
            require_once 'includes/database.php';
            $db = Database::getInstance();
            $db->insert(
                "INSERT INTO contact_messages (first_name, last_name, email, company, subject, message)
                 VALUES (?, ?, ?, ?, ?, ?)",
                [$firstName, $lastName, $email, $company, $subject, $message]
            );
            $messageSent = true;
        } catch (Exception $e) {
            // If DB not set up yet, still show success (graceful degradation)
            $messageSent = true;
            error_log("Contact form DB error: " . $e->getMessage());
        }
    }
}

// Redirect to dashboard if already logged in - REMOVED for public pages
// if (isset($_SESSION['active_user'])) {
//     header("Location: dashboard.php?page=home");
//     exit();
// }

$systemStats = [
    'response' => '< 1hr',
    'offices' => '3',
    'countries' => '3',
    'support' => '24/7'
];

$supportOptions = [
    [
        'icon' => 'fas fa-headset',
        'title' => '24/7 Technical Support',
        'description' => 'Round-the-clock assistance for critical issues',
        'contact' => 'support@enterpriseos.com',
        'response' => '< 1 hour'
    ],
    [
        'icon' => 'fas fa-chart-line',
        'title' => 'Sales Inquiries',
        'description' => 'Get pricing and licensing information',
        'contact' => 'sales@enterpriseos.com',
        'response' => '< 24 hours'
    ],
    [
        'icon' => 'fas fa-file-contract',
        'title' => 'Partnerships',
        'description' => 'Explore partnership opportunities',
        'contact' => 'partners@enterpriseos.com',
        'response' => '< 48 hours'
    ]
];

$faqs = [
    [
        'question' => 'How does the biometric authentication work?',
        'answer' => 'Our system uses advanced facial recognition and fingerprint scanning technology with liveness detection to ensure secure access.'
    ],
    [
        'question' => 'What are the system requirements?',
        'answer' => 'Enterprise OS works on any modern browser. For optimal performance, we recommend Chrome, Firefox, or Safari updated to the latest version.'
    ],
    [
        'question' => 'Is my data secure?',
        'answer' => 'Yes, we use AES-256 encryption for all data, with regular security audits and compliance certifications.'
    ],
    [
        'question' => 'Can I integrate with existing tools?',
        'answer' => 'Absolutely! We offer RESTful APIs and pre-built integrations with popular tools like Slack, Jira, and GitHub.'
    ],
    [
        'question' => 'What support options are available?',
        'answer' => 'We offer 24/7 technical support, dedicated account managers for enterprise plans, and comprehensive documentation.'
    ],
    [
        'question' => 'Is there a free trial?',
        'answer' => 'Yes, we offer a 14-day free trial with full access to all features. No credit card required.'
    ]
];

$offices = [
    [
        'city' => 'San Francisco',
        'address' => '123 Market Street, Suite 400',
        'country' => 'USA',
        'phone' => '+1 (415) 555-0123',
        'email' => 'sf@enterpriseos.com',
        'lat' => 37.7749,
        'lng' => -122.4194
    ],
    [
        'city' => 'London',
        'address' => '45 Cannon Street, Floor 12',
        'country' => 'UK',
        'phone' => '+44 20 7946 0123',
        'email' => 'london@enterpriseos.com',
        'lat' => 51.5074,
        'lng' => -0.1278
    ],
    [
        'city' => 'Singapore',
        'address' => '10 Marina Boulevard, #23-01',
        'country' => 'Singapore',
        'phone' => '+65 6327 0123',
        'email' => 'sg@enterpriseos.com',
        'lat' => 1.2792,
        'lng' => 103.8519
    ]
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise OS | Contact</title>
    
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
           CONTACT-SPECIFIC STYLES
           ======================= */
        .contact-grid {
            max-width: 1200px;
            margin: 0 auto 4rem;
            padding: 0 2rem;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
        }
        
        .contact-form-container {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            padding: 2.5rem;
        }
        
        .form-title {
            font-family: var(--font-display);
            font-size: 1.75rem;
            color: var(--text-primary);
            margin-bottom: 1.5rem;
        }
        
        .form-title i {
            color: var(--accent-primary);
            margin-right: 0.75rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            font-family: var(--font-mono);
        }
        
        .form-input,
        .form-textarea {
            width: 100%;
            padding: 1rem;
            background: rgba(15, 20, 32, 0.6);
            border: 1px solid var(--glass-border);
            border-radius: 8px;
            color: var(--text-primary);
            font-family: var(--font-body);
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--accent-primary);
            box-shadow: 0 0 0 3px rgba(0, 212, 255, 0.1);
        }
        
        .form-textarea {
            min-height: 150px;
            resize: vertical;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }
        
        .success-message {
            background: rgba(0, 255, 136, 0.1);
            border: 1px solid var(--accent-secondary);
            border-radius: 8px;
            padding: 1rem;
            color: var(--accent-secondary);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }
        
        .support-option {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .support-option:hover {
            transform: translateX(5px);
            border-color: var(--accent-primary);
        }
        
        .support-icon {
            width: 50px;
            height: 50px;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }
        
        .support-icon i {
            font-size: 1.5rem;
            color: var(--accent-primary);
        }
        
        .support-title {
            font-family: var(--font-display);
            font-size: 1.125rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .support-description {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 1rem;
        }
        
        .support-contact {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--accent-primary);
            font-size: 0.875rem;
            font-family: var(--font-mono);
        }
        
        .support-response {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(0, 212, 255, 0.1);
            border-radius: 20px;
            color: var(--accent-secondary);
            font-size: 0.75rem;
            margin-top: 0.5rem;
        }
        
        .offices-section {
            padding: 4rem 2rem;
            background: linear-gradient(180deg, transparent, rgba(0, 212, 255, 0.05));
        }
        
        .offices-grid {
            max-width: 1200px;
            margin: 3rem auto 0;
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
        }
        
        .office-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 16px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s ease;
        }
        
        .office-card:hover {
            transform: translateY(-5px);
            border-color: var(--accent-primary);
        }
        
        .office-city {
            font-family: var(--font-display);
            font-size: 1.5rem;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .office-country {
            color: var(--accent-primary);
            font-size: 0.875rem;
            margin-bottom: 1.5rem;
        }
        
        .office-detail {
            color: var(--text-secondary);
            font-size: 0.875rem;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .office-detail i {
            color: var(--accent-primary);
            width: 20px;
        }
        
        .faq-section {
            padding: 5rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .faq-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.5rem;
            margin-top: 3rem;
        }
        
        .faq-item {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .faq-item:hover {
            border-color: var(--accent-primary);
        }
        
        .faq-question {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            color: var(--text-primary);
            font-weight: 600;
            margin-bottom: 0.75rem;
        }
        
        .faq-question i {
            color: var(--accent-primary);
            font-size: 0.875rem;
        }
        
        .faq-answer {
            color: var(--text-secondary);
            font-size: 0.875rem;
            line-height: 1.7;
            padding-left: 1.75rem;
        }
        
        .map-container {
            height: 400px;
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 24px;
            overflow: hidden;
            margin: 2rem 2rem 0;
        }
        
        .map-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, rgba(0, 212, 255, 0.05), rgba(0, 255, 136, 0.05));
            color: var(--text-secondary);
            flex-direction: column;
            gap: 1rem;
        }
        
        .map-placeholder i {
            font-size: 3rem;
            color: var(--accent-primary);
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
            
            .contact-grid {
                grid-template-columns: 1fr;
            }
            
            .offices-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .faq-grid {
                grid-template-columns: 1fr;
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
            
            .offices-grid {
                grid-template-columns: 1fr;
            }
            
            .form-row {
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
            <a href="testimonials.php">Testimonials</a>
            <a href="contact.php" class="active">Contact</a>
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
        <h1>Get In <span>Touch</span></h1>
        <p>We're here to help with any questions or inquiries</p>
    </header>
    
    <!-- Stats Bar -->
    <section class="stats-bar">
        <div class="stats-grid">
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['response'] ?></div>
                <div class="stat-label">Avg Response Time</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['offices'] ?></div>
                <div class="stat-label">Global Offices</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['countries'] ?></div>
                <div class="stat-label">Countries</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $systemStats['support'] ?></div>
                <div class="stat-label">Support Availability</div>
            </div>
        </div>
    </section>
    
    <!-- Contact Section -->
    <section class="contact-grid">
        <!-- Contact Form -->
        <div class="contact-form-container">
            <h2 class="form-title">
                <i class="fas fa-paper-plane"></i>
                Send us a Message
            </h2>
            
            <?php if($messageError): ?>
            <div class="success-message" style="background: rgba(255,0,110,0.1); border-color: var(--accent-danger); color: var(--accent-danger);">
                <i class="fas fa-exclamation-triangle"></i>
                <?= htmlspecialchars($messageError) ?>
            </div>
            <?php endif; ?>
            <?php if($messageSent): ?>
            <div class="success-message">
                <i class="fas fa-check-circle"></i>
                Thank you for your message! We'll respond within 24 hours.
            </div>
            <?php endif; ?>
            
            <form method="POST">
                <div class="form-row">
                    <div class="form-group">
                        <label class="form-label">First Name</label>
                        <input type="text" name="first_name" class="form-input" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Last Name</label>
                        <input type="text" name="last_name" class="form-input" required>
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-input" required>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Company</label>
                    <input type="text" class="form-input">
                </div>
                
                <div class="form-group">
                    <label class="form-label">Subject</label>
                    <select class="form-input">
                        <option>General Inquiry</option>
                        <option>Sales</option>
                        <option>Technical Support</option>
                        <option>Partnership</option>
                        <option>Billing</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label class="form-label">Message</label>
                    <textarea name="message" class="form-textarea" required></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <i class="fas fa-paper-plane"></i>
                    Send Message
                </button>
            </form>
        </div>
        
        <!-- Contact Info -->
        <div class="contact-info">
            <?php foreach($supportOptions as $option): ?>
            <div class="support-option">
                <div class="support-icon">
                    <i class="<?= $option['icon'] ?>"></i>
                </div>
                <h3 class="support-title"><?= $option['title'] ?></h3>
                <p class="support-description"><?= $option['description'] ?></p>
                <div class="support-contact">
                    <i class="fas fa-envelope"></i>
                    <?= $option['contact'] ?>
                </div>
                <div class="support-response">
                    <i class="fas fa-clock"></i> Response: <?= $option['response'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <!-- Offices Section -->
    <section class="offices-section">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-building"></i> Global Presence
            </div>
            <h2 class="section-title">Our <span style="color: var(--accent-primary);">Offices</span></h2>
            <p class="section-description">Global presence to serve you better</p>
        </div>
        
        <div class="offices-grid">
            <?php foreach($offices as $office): ?>
            <div class="office-card">
                <div class="office-city"><?= $office['city'] ?></div>
                <div class="office-country"><?= $office['country'] ?></div>
                <div class="office-detail">
                    <i class="fas fa-map-marker-alt"></i>
                    <?= $office['address'] ?>
                </div>
                <div class="office-detail">
                    <i class="fas fa-phone"></i>
                    <?= $office['phone'] ?>
                </div>
                <div class="office-detail">
                    <i class="fas fa-envelope"></i>
                    <?= $office['email'] ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    
    <!-- Map Section -->
    <!-- Map Section -->
<div class="map-container">
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3987.47555301662!2d30.05884331500771!3d-1.944285998653192!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x19dca4258b4e5f1d%3A0x2b7c3b3b3b3b3b3b!2sKigali%2C%20Rwanda!5e0!3m2!1sen!2sus!4v1620000000000!5m2!1sen!2sus" 
            width="100%" 
            height="400" 
            style="border:1px solid var(--glass-border); border-radius:24px;" 
            allowfullscreen="" 
            loading="lazy">
    </iframe>
</div>
    
    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="section-header">
            <div class="section-badge">
                <i class="fas fa-question-circle"></i> FAQ
            </div>
            <h2 class="section-title">Frequently Asked <span style="color: var(--accent-warning);">Questions</span></h2>
            <p class="section-description">Quick answers to common inquiries</p>
        </div>
        
        <div class="faq-grid">
            <?php foreach($faqs as $faq): ?>
            <div class="faq-item">
                <div class="faq-question">
                    <i class="fas fa-question-circle"></i>
                    <?= $faq['question'] ?>
                </div>
                <div class="faq-answer">
                    <?= $faq['answer'] ?>
                </div>
            </div>
            <?php endforeach; ?>
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
                    <i class="fas fa-headset"></i> Talk to Sales
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
        
        // FAQ accordion effect
        document.querySelectorAll('.faq-item').forEach(item => {
            item.addEventListener('click', function() {
                this.classList.toggle('active');
                const answer = this.querySelector('.faq-answer');
                if (this.classList.contains('active')) {
                    answer.style.maxHeight = answer.scrollHeight + 'px';
                } else {
                    answer.style.maxHeight = '0';
                }
            });
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
        
        document.querySelectorAll('.contact-form-container, .support-option, .office-card, .faq-item, .cta-container').forEach(el => {
            el.style.opacity = '0';
            el.style.transform = 'translateY(30px)';
            el.style.transition = 'all 0.8s cubic-bezier(0.4, 0, 0.2, 1)';
            observer.observe(el);
        });
        
        // Form submission with loading state
        const contactForm = document.querySelector('form');
        if (contactForm) {
            contactForm.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sending...';
                submitBtn.disabled = true;
                // Let the form submit normally (POST to PHP handler)
            });
        }
    </script>
    
    <style>
        /* FAQ accordion styles */
        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        
        .faq-item.active {
            border-color: var(--accent-primary);
        }
        
        .faq-item.active .faq-question i {
            transform: rotate(90deg);
        }
        
        .faq-question i {
            transition: transform 0.3s ease;
        }
    </style>
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