<?php
// db.php

/**
 * This file establishes a database connection using MySQLi (Object-Oriented style).
 * It provides a global `$conn` variable that can be used to interact with the database.
 *
 * Steps:
 *  1. Define database connection parameters (host, user, password, database).
 *  2. Create a new MySQLi connection instance.
 *  3. Check for connection errors and stop execution if any occur.
 */

// --- Database connection parameters ---
$host = 'localhost';       // Database server hostname
$user = 'root';            // Database username
$password = '';            // Database password (empty by default for localhost)
$database = 'car_booking'; // Database name

// --- Create a new MySQLi connection ---
$conn = new mysqli($host, $user, $password, $database);

// --- Check if the connection failed ---
if ($conn->connect_error) {
    // If connection error occurs, terminate the script with an error message
    die("Connection failed: " . $conn->connect_error);
}
