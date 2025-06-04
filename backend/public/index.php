<?php
// Simple front controller placeholder for CodeIgniter 4
require dirname(__DIR__) . '/vendor/autoload.php';
$app = Config\Services::codeigniter();
$app->run();
