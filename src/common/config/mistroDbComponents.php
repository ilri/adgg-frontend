<?php
/**
 * Created by PhpStorm.
 * @author: Fred <mconyango@gmail.com>
 * Date: 2020-04-29
 * Time: 9:41 PM
 */

//mistroDb components
$mistroDbKlba = __DIR__ . DIRECTORY_SEPARATOR . 'mistroDbKlba.php';
$mistroDbStanley1 = __DIR__ . DIRECTORY_SEPARATOR . 'mistroDbStanley1.php';
$mistroDbStanley2 = __DIR__ . DIRECTORY_SEPARATOR . 'mistroDbStanley2.php';
$mistroDbKalro = __DIR__ . DIRECTORY_SEPARATOR . 'mistroDbKalro.php';
$dbs = [];
if (file_exists($mistroDbKlba)) {
    $dbs['mistroDbKlba'] = require($mistroDbKlba);
}
if (file_exists($mistroDbStanley1)) {
    $dbs['mistroDbStanley1'] = require($mistroDbStanley1);
}
if (file_exists($mistroDbStanley2)) {
    $dbs['mistroDbStanley2'] = require($mistroDbStanley2);
}
if (file_exists($mistroDbKalro)) {
    $dbs['mistroDbKalro'] = require($mistroDbKalro);
}

return $dbs;