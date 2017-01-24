<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-11-11
 * Time: 11:22
 */
include "../Utils/User.php";
include "../Utils/Database.php";

use Game\User;
session_start();
$text="";
if (isset($_POST['username'])&&isset($_POST['password'])){
    if (User::checkLogin($_POST['username'],$_POST['password'])){
        $temp = User::getId($_POST['username'],$_POST['password']);
        echo $temp;
        $_SESSION['id'] = $temp;
        echo $_SESSION['id'];
        header("location:main.php");
    }
    else {
        $text = "fel lösenord eller andvandarnamn";
    }
}

echo "<!DOCTYPE html>
<html>
    <head>
    <title>Index.php</title>
    <link rel='stylesheet' type='text/css' href='style.css'>

    <meta charset='utf-8'>
    </head>
    <body class='index'>
        <header >
            Stratego
        </header>
        
        
        <form action='index.php' method='post'>
            <div>".$text."</div>
            <table class='inlogTabel'>
            <tr><td colspan='2' align='center'>Logga in</td></tr>
            <tr><td>Username</td><td><input type='text' name='username'></td></tr>
            <tr><td>Password</td><td><input type='password' name='password'></td></tr>
            <tr><td><input type='submit' value='login'name='Loggin'></td><td><a href='newUser.php'><input type='button'  class='newUser' value='new user' id = 'newuser'></a></td> </tr>
            </table>
";echo "
        </form>
    </body>
</html>";
