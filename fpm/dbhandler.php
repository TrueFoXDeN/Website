<?php
$host_name = 'db5000286797.hosting-data.io';
$database = 'dbs280009';
$user_name = 'dbu394562';
$password = 'Mastermind1324!';
$connect = mysqli_connect($host_name, $user_name, $password, $database);

if (mysqli_connect_errno()) {
    die('<p>Verbindung zum MySQL Server fehlgeschlagen: '.mysqli_connect_error().'</p>');
} else {
    echo '<p>Verbindung zum MySQL Server erfolgreich aufgebaut.</p >';
}