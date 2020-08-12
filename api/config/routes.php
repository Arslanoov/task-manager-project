<?php

use Framework\Http\Application;
use App\Http\Action;

/** @var Application $app */

$app->get('home', '/api', Action\HomeAction::class);

// Auth

$app->post('api.auth.signup', '/api/auth/signup', Action\Auth\SignUpAction::class);
$app->post('api.oauth.auth', '/api/oauth/auth', Action\Auth\OAuthAction::class);

// Todo

$app->get('api.todo.main.index', '/api/todo/main', Action\Todo\Schedule\Main\IndexAction::class);
$app->get('api.todo.main.tasks.count', '/api/todo/main/tasks/count', Action\Todo\Schedule\Main\TasksCountAction::class);

// Tasks
$app->post('api.todo.main.tasks.create', '/api/todo/task/create', Action\Todo\Task\CreateAction::class);
$app->patch('api.todo.main.tasks.edit', '/api/todo/task/edit', Action\Todo\Task\EditAction::class);
$app->delete('api.todo.main.tasks.remove', '/api/todo/task/remove', Action\Todo\Task\RemoveAction::class);

// Task steps
$app->get('api.todo.main.task.steps.index', '/api/todo/task/{id}/steps', Action\Todo\Task\Step\IndexAction::class);
$app->post('api.todo.main.task.steps.create', '/api/todo/task/step/create', Action\Todo\Task\Step\CreateAction::class);
$app->delete('api.todo.main.task.steps.remove', '/api/todo/task/step/remove', Action\Todo\Task\Step\RemoveAction::class);
