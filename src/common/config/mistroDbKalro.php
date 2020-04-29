<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-04-29
 * Time: 09:30 PM
 */
return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=localhost;port=3306;dbname=kenyadb',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8mb4',
    'on afterOpen' => function ($event) {
        $event->sender->createCommand("SET time_zone = '+00:00'")->execute();
    }
];