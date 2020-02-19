<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-02-18
 * Time: 11:40 AM
 */
return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=192.168.5.7;port=3306;dbname=kenyadb',
    'username' => 'dmogaka',
    'password' => 'PxHgP5uW9x',
    'charset' => 'utf8mb4',
    'on afterOpen' => function ($event) {
        $event->sender->createCommand("SET time_zone = '+00:00'")->execute();
    }
];