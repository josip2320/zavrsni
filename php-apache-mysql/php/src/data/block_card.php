<?php
    require_once "config.php";
    
    
    $temp = $_POST["uid_kartice"];
    $temp = $link->real_escape_string($temp);
    $sql= "UPDATE Kartice SET dopusten=false WHERE uid_kartice=?";
    if($stmt = mysqli_prepare($link,$sql))
    {
        mysqli_stmt_bind_param($stmt,"s",$param_uid);
        $param_uid=$temp;
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