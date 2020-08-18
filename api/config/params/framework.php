<?php

if (!defined('ENV')) {
    define('ENV', 'dev');
}

return [
    'debug' => ENV and ENV === 'dev' ? true : false
];