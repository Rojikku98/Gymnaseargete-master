<?php
/**
 * Created by PhpStorm.
 * User: edvin.bergstrom
 * Date: 2016-11-11
 * Time: 12:07
 */
include "../Utils/User.php";
include "../Utils/Database.php";


use Game\User;

if (isset($_POST['username'])&&isset($_POST['password'])&&isset($_POST['name'])&&isset($_POST['epost'])){

    //echo $_POST['epost'];

    if (User::newUser($_POST['username'],$_POST['name'],$_POST['password'],($_POST['epost']))){

        header("location:index.php");

    }else $text = "Advandernamet är taget";
}else $text="";

    //echo "hfuisdhfishfsduihfisdhifu";

echo "<!DOCTYPE html>
<html>
    <head>
    <script>
        function f1() {
            var i1 = document.getElementsByName('password1')[0];
            var i2 = document.getElementsByName('password2')[0];
            //console.log(i1.value + '  hejhejhejhejehjehej    ' + i2.value);
            if (i1.value == i2.value){
                
                document.getElementsByName('password') [0].value=i1.value;
                return true;
            }
            else {
                return false;
            }
        }
</script>
    <title>newuser.php</title>
    <link rel='stylesheet' type='text/css' href='style.css'>
    <meta charset='utf-8'>
    </head>
    <body class='index'>
        <header>
            <div class='Titel'>Stratego</div>
        </header>
        ".$text."
        <form action='newUser.php' method='post' onsubmit='return f1();'>
        <input type='hidden' name='password'>
            <table id='inlogTabel'>
            <tr><td>Username</td><td><input type='text' name='username'></td></tr>
            <tr><td>Password</td><td><input type='password' name='password1'></td></tr>
            <tr><td>Password</td><td><input type='password' name='password2'></td></tr>
            
            <tr><td colspan='2'><input type='submit' name='newUser' ></td> </tr>
            </table>
";echo "

        <input type='hidden' name='epost' value='epost'>
        <input type='hidden' name='name' value='name'>
        </form>
        
        
        
        
    </body>
</html>";
/*<tr><td>ScrenName</td><td><input type='text' name='name'></td></tr>
            <tr><td>Epost</td><td><input type='text' name='epost'></td></tr>*/