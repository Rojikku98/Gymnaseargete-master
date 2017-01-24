<?php

include_once 'User.php';
include_once 'Utils/Database.php';

use Game\User;
//use Game\Utils\Database;

$user = new User("123",123);



echo $user->getName();
