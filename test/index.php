<?php

require '../vendor/autoload.php';

use Inn\Session\Session;

$sess = Session::instance([
    'id' => 'izisaurio',
    'secure' => false,
]);

var_dump($sess->id());

$sess->set(['id' => 1, 'name' => 'isaac']);

$sess['mail'] = 'izi.isaac@gmail.com';

var_dump($sess['name']);

var_dump($_SESSION);