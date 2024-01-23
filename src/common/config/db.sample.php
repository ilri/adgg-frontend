<?php
/**
 * @author Fred <mconyango@gmail.com>
 * Date: 2016/01/11
 * Time: 3:58 PM
 */
return [
    'class' => \yii\db\Connection::class,
    'dsn' => 'mysql:host=dev.db.adgg.ilri.org;port=3306;dbname=adgg',
    'username' => 'dmogaka',
    'password' => '!Sc00by@2023',
    'charset' => 'utf8mb4',
    'on afterOpen' => function ($event) {
        $event->sender->createCommand("SET time_zone = '+00:00'")->execute();
    }
];