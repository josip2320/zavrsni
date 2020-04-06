<?php

    require_once "config.php";

    $uid_kartice = $_POST['uid_kartice'];
    $uid_kartice=$link->real_escape_string($uid_kartice);
    $sql="INSERT INTO Ulazi(uid_kartice,status) VALUES(?,'unknown')";
    
    if($stmt=mysqli_prepare($link,$sql))
    {
        mysqli_stmt_bind_param($stmt,"s",$param_uid_kartice);
        $param_uid_kartice = $uid_kartice;

        if(mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);
            echo "1";
        }
        else
        {
            echo "0";
        }
       
    }
?>