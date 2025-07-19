<?php
// session.php

/**
 * This file ensures that a PHP session is started and
 * provides a helper function `flash_message()` to display
 * and clear session-based notifications.
 */

// --- Start the session if it hasn't been started yet ---
if (session_status() === PHP_SESSION_NONE) {
    session_start(); // Initialize the session
}

/**
 * Display a flash message stored in the session and then remove it.
 *
 * @param string $key The session key used to retrieve the message (e.g., 'success', 'error').
 * @param string $defaultClass The default Bootstrap alert class to use if the key doesn't match common types.
 *
 * Usage:
 *   $_SESSION['success'] = "Data saved successfully!";
 *   flash_message('success');  // Displays the message and clears it.
 */
function flash_message($key, $defaultClass = 'info') {
    if (!empty($_SESSION[$key])) {
        // Determine the Bootstrap alert class based on message type
        $class = match($key) {
            'success' => 'success', // Green success message
            'error'   => 'danger',  // Red error message
            'warning' => 'warning', // Yellow warning message
            default   => $defaultClass,
        };

        // Output the message inside a Bootstrap alert div
        echo "<div class='alert alert-{$class} text-center'>" . $_SESSION[$key] . "</div>";

        // Remove the message from session so it's displayed only once
        unset($_SESSION[$key]);
    }
}
