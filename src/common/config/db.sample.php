<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/01/11
 * Time: 3:58 PM
 */
return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=localhost;port=3306;dbname=adgg',
    'username' => 'root',
    'password' => 'root',
    'charset' => 'utf8mb4',
    'on afterOpen' => function ($event) {
        $event->sender->createCommand("SET time_zone = '+00:00'")->execute();
    }
];