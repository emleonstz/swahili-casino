<?php

// Get the requested URL from the query string
$url = isset($_GET['url']) ? $_GET['url'] : '';

// If the URL is empty, redirect to the games route

// Define your routes
$routes = array(
    'users' => 'pages/users.php',
    'admin' => 'pages/admin.php',
    'ninja' => 'games/fruits/',
    'home'=>'/casino/games'
);

// Check if the requested URL exists in the routes array
if (array_key_exists($url, $routes)) {
    // If it exists, include the corresponding file
    header("Location: /casino/".$routes[$url]);
} else {
    // If the URL doesn't match any route, show a 404 error page or redirect to a default page
    header("Location: /casino/games");
}
