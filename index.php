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


$router->get('/', function () {
    require __DIR__ . '/Page/Home/Home.php';
});

$router->get('/contact',function (){
    require __DIR__ . '/Page/ContactUS/Contact.php';
});
$router->get('/about',function (){
    require __DIR__ . '/Page/About/About.php';
});

$router->get('/product',function(){
 require __DIR__ . '/Page/Product/Product.php';
});

$router->get('/productdetails',function(){
 require __DIR__ . '/Page/Product/ProductDetail.php';
});

$router->get('/wishlist',function(){
 require __DIR__ . '/Page/WishList/WishList.php';
});

$router->get('/cart',function(){
 require __DIR__ . '/Page/Cart/Cart.php';
});

$router->get('/profile',function(){
 require __DIR__ . '/Page/Profile/Profile.php';
});

$router->get('/checkout',function(){
 require __DIR__ . '/Page/CheckOut/CheckOut.php';
});

$router->get('/order',function(){
 require __DIR__ . '/Page/Order/Order.php';
});

$router->get('/Completeorder',function(){
 require __DIR__ . '/Page/CheckOut/SuccessOrder.php';
});






// 404 Handler
$router->set404(function () {
    require __DIR__ . '/Page/NotFound/NotFound.php';
});

$router->run();
