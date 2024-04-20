<?php
require_once('../model/db.php');
require_once('../plugins/phpqrcode/qrlib.php');

$ruta = '../view/img/users/';
$qrcode = $ruta.time()."png";

QRcode::png("23456", $qrcode, 'H', 40, 2);
echo "<img src='$qrcode'>";
?>