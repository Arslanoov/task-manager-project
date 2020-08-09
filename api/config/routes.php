<?php

use Framework\Http\Application;
use App\Http\Action;

/** @var Application $app */

$app->get('home', '/api', Action\HomeAction::class);

// Auth

$app->post('api.auth.signup', '/api/auth/signup', Action\Auth\SignUpAction::class);
$app->post('api.oauth.auth', '/api/oauth/auth', Action\Auth\OAuthAction::class);

// Todo

$app->get('api.todo.main.index', '/api/todo/main', Action\Todo\Main\IndexAction::class);

// Tasks
$app->post('api.todo.main.tasks.create', '/api/todo/main/task/create', Action\Todo\Main\Task\CreateAction::class);
