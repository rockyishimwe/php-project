<?php
/**
 * INDEX FILE
 * Main entry point for the application
 */

// Start session
session_start();

// Include configuration
require_once 'includes/config.php';
require_once 'includes/functions.php';

// Redirect to home page
header("Location: pages/home.php");
exit();