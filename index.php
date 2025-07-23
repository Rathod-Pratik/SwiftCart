<?php
require_once 'vendor/autoload.php';

use Bramus\Router\Router;

$router = new Router();

// Auth Pages
$router->get('/login', function () {
    require __DIR__ . '/Page/Auth/Login.php';
});
$router->get('/signup', function () {
    require __DIR__ . '/Page/Auth/SignUp.php';
});


// Admin Pages
$router->get('/admin/dashboard', function () {
    require __DIR__ . '/Page/Admin/DashBoard.php';
});

$router->get('/admin/products', function () {
    require __DIR__ . '/Page/Admin/Products.php';
});

$router->get('/admin/categories', function () {
    require __DIR__ . '/Page/Admin/Categories.php';
});

$router->get('/admin/users', function () {
    require __DIR__ . '/Page/Admin/User.php';
});

$router->get('/admin/venders', function () {
    require __DIR__ . '/Page/Admin/Vender.php';
});

$router->get('/admin/Rating', function () {
    require __DIR__ . '/Page/Admin/Rating.php';
});

$router->get('/admin/Contact', function () {
    require __DIR__ . '/Page/Admin/ContactUs.php';
});

$router->get('/admin/logout', function () {
    require __DIR__ . '/Page/Admin/Logout.php';
});

$router->get('/admin/order', function () {
    require __DIR__ . '/Page/Admin/Order.php';
});

// Vender Pages
$router->get('/vender/dashboard', function () {
    require __DIR__ . '/Page/Vender/DashBoard.php';
});

$router->get('/vender/order', function () {
    require __DIR__ . '/Page/Vender/Order.php';
});

$router->get('/vender/profile', function () {
    require __DIR__ . '/Page/Vender/Profile.php';
});

$router->get('/vender/Query', function () {
    require __DIR__ . '/Page/Vender/Queries.php';
});

$router->get('/vender/product', function () {
    require __DIR__ . '/Page/Vender/Product.php';
});

$router->get('/vender/rating', function () {
    require __DIR__ . '/Page/Vender/Rating.php';
});

$router->get('/vender/logout', function () {
    require __DIR__ . '/Page/Vender/Logout.php';
});




// 404 Handler
$router->set404(function () {
    http_response_code(404);
    echo '404 - Page Not Found';
});

$router->run();
