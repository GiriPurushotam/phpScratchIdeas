<?php

echo "<pre>";

// Check if session is already started
if (session_status() === PHP_SESSION_ACTIVE) {
    echo "✅ Session is ACTIVE\n";
} else {
    echo "❌ Session is NOT ACTIVE\n";
}

// Print current session name
echo "Session Name: " . session_name() . "\n";

// Print session ID
echo "Session ID: " . session_id() . "\n";

// Print session cookie content from $_COOKIE
echo "Session Cookie Value (from \$_COOKIE): " . ($_COOKIE[session_name()] ?? 'Not set') . "\n";

// Print all cookies
echo "\nAll Cookies:\n";
print_r($_COOKIE);

echo "</pre>";
