<?php

// Registration route
$router->post('/register', 'AuthController@register');

// Email verification route
$router->get('/verify-email', 'AuthController@verifyEmail');
