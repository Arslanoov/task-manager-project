<?php

use Framework\Http\Application;
use App\Http\Action;

/** @var Application $app */

$app->get('home', '/api', Action\HomeAction::class);
$app->get('users.list', '/api/users', Action\User\ListAction::class);

// Auth

$app->post('api.auth.signup', '/api/auth/signup', Action\Auth\SignUpAction::class);

$app->post('api.oauth.auth', '/api/oauth/auth', Action\Auth\OAuthAction::class);

/*$app->get('api.profile', '/api/profile', Action\Profile\ShowAction::class, [
    $auth
]);
*/
