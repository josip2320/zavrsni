<?php

    require_once "config.php";

    $uid_kartice= $_POST['uid_kartice'];
    $uid_kartice=$link->real_escape_string($uid_kartice);  
    $result1=$result2="";
    $sql="SELECT COUNT(ID) AS ulazi FROM Ulazi WHERE uid_kartice=? AND status='correct'";
    if($stmt=mysqli_prepare($link,$sql))
    {
        mysqli_stmt_bind_param($stmt,"s",$param_uid_kartice);
        $param_uid_kartice = $uid_kartice;
        
        if(mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$result1);
            if(mysqli_stmt_fetch($stmt))
            {
                
            }
        }
    }
    $sql="SELECT COUNT(ID) AS izlazi FROM Izlazi WHERE uid_kartice=?";
    if($stmt=mysqli_prepare($link,$sql))
    {
        mysqli_stmt_bind_param($stmt,"s",$param_uid_kartice);
        $param_uid_kartice = $uid_kartice;
        
        if(mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);
            mysqli_stmt_bind_result($stmt,$result2);
            if(mysqli_stmt_fetch($stmt))
            {
                
            }
        }
        
    }
    
    if(!(strcmp($result1,$result2)))
    {
        echo "0";
    }
    else
    {
        echo "1";
    }
   
    ?>