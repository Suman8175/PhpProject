<?php
    session_start();
    $showmessage="";
    include 'bootstrap.php';
    if ($_SERVER['REQUEST_METHOD']=="POST"){
        $useremail=$_POST['logemail'];
        $userpass=$_POST['logpass'];
        require 'connection.php';
        $sql="select * from `logintable` WHERE Email = '$useremail'";
        $result=mysqli_query($conn,$sql);
        $noofrows=mysqli_num_rows($result);
        if ($noofrows == 1){
            $user = mysqli_fetch_assoc($result);
            if ($user["Role"] == "Patient" && password_verify($userpass, $user["Password"]) && $user["isverified"] == 1) {
                $_SESSION['username'] = $useremail;
                header("Location:Patient/welcome.php");
            }
            elseif ($user["Role"] == "Doctor" && password_verify($userpass, $user["Password"]) && $user["isverified"] == 1 && $user["verifieddoctor"] == 1) {
                $_SESSION['username'] = $useremail;
                header("Location:Doctor/docwelcome.php");
            }
            elseif ($user["Role"] == "Admin" && password_verify($userpass, $user["Password"]) && $user["isverified"] == 1) {
                $_SESSION['username'] = $useremail;
                header("Location:Admin/dashboard.php");
            }
            else{
                $showmessage='<div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Wrong!</strong> Email and password donot match.
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>';
                $_SESSION['nosigned']=$showmessage;
                header("Location:index.php");
            }
        }
        else{
            $showmessage='<div class="alert alert-warning alert-dismissible fade show" role="alert">
            <strong>Wrong!</strong> Email is not signed up.Please sign in.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>';
            $_SESSION['nosigned']=$showmessage;
            header("Location:index.php");
        }
    }
?>