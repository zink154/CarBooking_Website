<?php
// logout.php

/**
 * This script logs the user out of the system.
 * Features:
 *  - Destroy all session data to ensure the user is logged out.
 *  - Redirect the user to the login page after logging out.
 */

// Start the session
session_start();

// Destroy the current session, removing all stored user data
session_destroy();

// Redirect to the login page
header("Location: login.php");
exit;
