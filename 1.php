<?php
require './vendor/autoload.php';
$a = file_get_contents('/Users/whoyummy/Nextcloud/Work/tms-api/www/api/storage/common/permission.json');

$a = json_decode($a, TRUE);
foreach ($a as $k => $v) {
    $a[$k] = \Illuminate\Support\Arr::except($v, ['created_at', 'updated_at']);
}
$a = json_encode($a,256);
file_put_contents('2.json', $a);
