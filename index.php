<?php
define('DIRECT_ACCESS', true);

error_reporting(E_ALL);
ini_set('display_errors', true);

define('BASE_PATH', dirname(__FILE__));
define('BASE_URL', 'https://shop.web-xperts.xyz');

define('ADMIN_PATH', dirname(__FILE__) . "/admin");
define('ADMIN_URL', 'https://shop.web-xperts.xyz/admin');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include __DIR__ . '/classes/router.php';

$request = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uriSegments = explode('/', $request);

route::get('/', function () { require __DIR__."/home.php"; exit; });
route::get('', function () { require __DIR__."/home.php"; exit; });

route::get('/shop', function () { require __DIR__."/ecommerce/shop.php"; exit; });

route::get('/cart', function () { require __DIR__."/ecommerce/cart.php"; exit; });
route::post('/cart', function () { require __DIR__."/ecommerce/cart.php"; exit; });
route::post('/cart-add/:any', function () { require __DIR__."/ecommerce/action/cart-add.php"; exit; });
route::post('/cart-delete/:any', function () { require __DIR__."/ecommerce/action/cart-delete.php"; exit; });
route::post('/cart-update', function () { require __DIR__."/ecommerce/action/cart-update.php"; exit; });
route::post('/cart-clear', function () { require __DIR__."/ecommerce/action/cart-clear.php"; exit; });
route::get('/cart-count', function () { require __DIR__."/ecommerce/action/cart-count.php"; exit; });

route::get('/checkout', function () { require __DIR__."/ecommerce/checkout.php"; exit; });
route::post('/checkout', function () { require __DIR__."/ecommerce/checkout.php"; exit; });

route::get('/login', function () { require __DIR__."/auth/login.php"; exit; });
route::post('/login', function () { require __DIR__."/auth/login.php"; exit; });

route::get('/register', function () { require __DIR__."/auth/register.php"; exit; });
route::post('/register', function () { require __DIR__."/auth/register.php"; exit; });

route::get('/profile', function () { require __DIR__."/auth/profile.php"; exit; });

route::get('/edit-profile', function () { require __DIR__."/auth/edit-profile.php"; exit; });
route::post('/edit-profile', function () { require __DIR__."/auth/edit-profile.php"; exit; });

route::get('/address', function () { require __DIR__."/auth/address.php"; exit; });
route::post('/address', function () { require __DIR__."/auth/address.php"; exit; });

route::get('/edit_address', function () { require __DIR__."/ecommerce/edit_address.php"; exit; });

route::get('/wishlist', function () { require __DIR__."/auth/wishlist.php"; exit; });
route::post('/wishlist-add/:any', function () { require __DIR__."/auth/action/wishlist-add.php"; exit; });

route::get('/logout', function () { require __DIR__."/auth/logout.php"; exit; });

route::get('/reviews', function () { require __DIR__."/auth/review.php"; exit; });
route::post('/reviews', function () { require __DIR__."/auth/review.php"; exit; });
route::post('/review-delete/:any', function () { require __DIR__."/auth/action/review-delete.php"; exit; });

route::get('/wishlist', function () { require __DIR__."/partials/product/wishlist-card.php"; exit; });
route::post('/wishlist', function () { require __DIR__."/partials/product/wishlist-card.php"; exit; });

route::get('/product/:any', function () { require __DIR__."/ecommerce/single-product.php"; exit; });
route::post('/product/:any', function () { require __DIR__."/ecommerce/single-product.php"; exit; });
route::get('/product/:any', function () { require __DIR__."/ecommerce/cart.php"; exit; });

route::get('/thank-you', function () { require __DIR__."/ecommerce/thank-you.php"; exit; });
route::post('/thank-you', function () { require __DIR__."/ecommerce/thank-you.php"; exit; });

route::get('/search', function () { require __DIR__."/search.php"; exit; });

route::get('/custom-calculator', function () { require __DIR__."/calculator/index.php"; exit; });
route::post('/custom-calculator', function () { require __DIR__."/calculator/index.php"; exit; });

// Admin URLs
route::get('/admin/orders/ajax-data', function () { require __DIR__."/admin/order/order_data.php"; exit; });
route::post('/admin/orders/ajax-data', function () { require __DIR__."/admin/order/order_data.php"; exit; });
//product urls
route::get('/admin/product/ajax-product-data', function () { require __DIR__."/admin/product/product_data.php"; exit; });
route::post('/admin/product/ajax-product-data', function () { require __DIR__."/admin/product/product_data.php"; exit; });

// user urls
route::get('/admin/users/ajax-users-data', function () { require __DIR__."/admin/users/user_data.php"; exit; });
route::post('/admin/users/ajax-users-data', function () { require __DIR__."/admin/users/user_data.php"; exit; });
// review  urls
route::get('/admin/review/ajax-review-data', function () { require __DIR__."/admin/review/review_data.php"; exit; });
route::post('/admin/review/ajax-review-data', function () { require __DIR__."/admin/review/review_data.php"; exit; });

// Default 404
require __DIR__."/404.php"; exit;