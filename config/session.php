<?php
//session.php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function flash_message($key, $defaultClass = 'info') {
    if (!empty($_SESSION[$key])) {
        $class = match($key) {
            'success' => 'success',
            'error'   => 'danger',
            'warning' => 'warning',
            default   => $defaultClass,
        };

        echo "<div class='alert alert-{$class} text-center'>" . $_SESSION[$key] . "</div>";
        unset($_SESSION[$key]);
    }
}