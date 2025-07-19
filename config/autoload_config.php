<?php
// autoload_config.php

/**
 * This file is used to automatically load the main configuration,
 * session management, and database connection files.
 * 
 * Instead of requiring these files individually in every script,
 * you can include this file once to ensure all essential
 * configurations and connections are loaded.
 */

require_once __DIR__ . '/config.php';   // Load general configuration (constants, settings)
require_once __DIR__ . '/session.php';  // Start session and manage user session data
require_once __DIR__ . '/db.php';       // Database connection setup
