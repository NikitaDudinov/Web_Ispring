<?php
require_once  __DIR__ . "/User.php";
$user = new User;
$user->firstName = 'Ivan';
$user->secondName = 'Petrov';
echo $user->firstName; 

