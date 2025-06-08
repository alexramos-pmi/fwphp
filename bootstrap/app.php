<?php

use Illuminate\Database\Capsule\Manager as Capsule;

// Carrega a configuração
$config = require __DIR__ . '/../config/database.php';

$capsule = new Capsule;

$capsule->addConnection($config);

// Torna global (para uso estático como User::find(1))
$capsule->setAsGlobal();

// Inicializa o Eloquent ORM
$capsule->bootEloquent();