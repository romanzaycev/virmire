<?php declare(strict_types = 1);

require __DIR__ . '/../virmire/Autoloader.php';

$autoloader = Virmire\Autoloader::getInstance();

$autoloader->addNamespace('Virmire', __DIR__ . '/../virmire');
