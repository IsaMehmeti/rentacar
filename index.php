<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

$uri = urldecode(
    parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)
);

// This file allows us to emulate Apache's "mod_rewrite" functionality from the
// built-in PHP web server. This provides a convenient way to test a Laravel
// application without having installed a "real" web server software here.
if ($uri !== '/' && file_exists(__DIR__.'/public'.$uri)) {
    return false;
}

require_once __DIR__.'/public/index.php';

// header("refresh: 5; http://www.rentacar-dushi.com/public/");

// 	echo '<title>Laravel Installed</title><div style="background: #e9ffed; border: 1px solid #b0dab7; padding: 15px;" align="center" >
// 	<font size="5" color="#182e7a">Laravel is installed successfully.</font><br /><br />
// 	<font size="4">Laravel is a Framework and doesn\'t have an index page.<br /><br />
// 	You will be redirected to its "public" folder in 5 seconds...<br /><br />
// 	Laravel is a clean and classy framework for PHP web development.



// Freeing you from spaghetti code, Laravel helps you create wonderful applications using simple, expressive syntax. Development should be a creative experience that you enjoy, not something that is painful. Enjoy the fresh air.

// </font></div>';