<?php 
session_start();
$userId = $_SESSION['USUARIO_ID'];
$userMail = $_SESSION['EMAIL'];
$userName = $_SESSION['NOMBRE'];
$userDoc = $_SESSION['DOCUMENTO'];
$userRole = $_SESSION['ROL'];
$userImg = $_SESSION['IMAGEN'];
$userStatus = $_SESSION['ESTADO'];
?>