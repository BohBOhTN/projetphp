<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Middleware to protect routes and redirect based on authentication
session_start();

// Define public routes that don't require authentication
$publicRoutes = ['auth/login', 'auth/logout', 'auth/change-password'];

// Get the current route
$currentRoute = trim($_SERVER['REQUEST_URI'], '/');

// Debugging: Output session variables and current route
error_log('Current Route: ' . $currentRoute);
error_log('Session Variables: ' . print_r($_SESSION, true));
// End debugging

// Improved middleware to handle multiple roles
if (!isset($_SESSION['id_utilisateur']) && !in_array($currentRoute, $publicRoutes)) {
    // Redirect unauthenticated users to the login page
    if ($currentRoute !== 'app/auth/login') {
        header('Location: /app/auth/login');
        exit;
    }
}

if (isset($_SESSION['id_utilisateur']) && $currentRoute === 'app/auth/login') {
    // Redirect authenticated users away from the login page
    header('Location: /app/dashboard');
    exit;
}

// Check for specific roles dynamically
function hasRole($role) {
    return isset($_SESSION['roles']) && in_array($role, $_SESSION['roles']);
}

// Load routes
$routes = include __DIR__ . '/routes.php';

// Get the requested URL path
$requestUri = trim($_SERVER['REQUEST_URI'], '/');
$scriptName = trim(dirname($_SERVER['SCRIPT_NAME']), '/');

// Remove the script name from the request URI if present
if (strpos($requestUri, $scriptName) === 0) {
    $requestUri = substr($requestUri, strlen($scriptName));
}

$requestUri = trim($requestUri, '/');

// Check if the route exists
if (isset($routes[$requestUri])) {
    $controllerAction = explode('@', $routes[$requestUri]);
    $controllerName = $controllerAction[0];
    $actionName = $controllerAction[1];

    // Include the controller file
    $controllerFile = __DIR__ . '/Controllers/' . $controllerName . '.php';
    if (file_exists($controllerFile)) {
        include $controllerFile;

        // Instantiate the controller and call the action
        $controller = new $controllerName();
        if (method_exists($controller, $actionName)) {
            $controller->$actionName();
            exit;
        } else {
            http_response_code(404);
            echo "Error: Method $actionName not found in controller $controllerName.";
        }
    } else {
        http_response_code(404);
        echo "Error: Controller file $controllerFile not found.";
    }
} else {
    http_response_code(404);
    echo "Error: Route not found.";
}