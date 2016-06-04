<?php declare(strict_types = 1);

require __DIR__ . '/../virmire/Autoloader.php';

$autoloader = Virmire\Autoloader::getInstance();

$autoloader->addNamespace('Virmire', __DIR__ . '/../virmire');
$autoloader->addNamespace('Virmire\\Interfaces', __DIR__ . '/../virmire/Interfaces');
$autoloader->addNamespace('Virmire\\Traits', __DIR__ . '/../virmire/Traits');
$autoloader->addNamespace('Virmire\\Exceptions', __DIR__ . '/../virmire/Exceptions');
$autoloader->addNamespace('Virmire\\Collections', __DIR__ . '/../virmire/Collections');
$autoloader->addNamespace('Virmire\\Http', __DIR__ . '/../virmire/Http');
$autoloader->addNamespace('Virmire\\Events', __DIR__ . '/../virmire/Events');