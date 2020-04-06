<?php
    require_once "config.php";
    
    
    $password = $_POST["password"];
    $uid_kartice= $_POST["uid_kartice"];
    $sql= "SELECT Zaporka FROM Kartice WHERE uid_kartice LIKE ?";
    if($stmt = mysqli_prepare($link,$sql))
    {
        mysqli_stmt_bind_param($stmt,"s",$param_uid);
        $param_uid=$uid_kartice;
        $param_uid= $link->real_escape_string($param_uid);
        if(mysqli_stmt_execute($stmt))
        {
            mysqli_stmt_store_result($stmt);

            if(mysqli_stmt_num_rows($stmt)==1)
            {
                mysqli_stmt_bind_result($stmt,$hashed_password);
                if(mysqli_stmt_fetch($stmt))
                {
                    if(password_verify($password,$hashed_password))
                    {
                        echo "1";
                    }
                    else
                    {
                        echo "0";
                    }
                }
            }
            else 
            {
                echo "0";
            }
        }
        mysqli_stmt_close($stmt);
    }
    


?>