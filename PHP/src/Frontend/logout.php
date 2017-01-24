<?php
/**
 * Created by PhpStorm.
 * User: minecraft
 * Date: 2017-01-15
 * Time: 14:45
 */
session_start();
session_destroy();
header("location:index.php");