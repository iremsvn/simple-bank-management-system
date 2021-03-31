<?php
@ob_start();
session_start();
include("config.php");

$username = "";
$password = "";

if(php_sapi_name() == 'cli'){$xxx = $_SERVER["SHELL"];}
else{$xxx = $_SERVER["REQUEST_METHOD"];}
	

if($xxx == "POST"){
    $username = $_POST['username'];
    $password = $_POST['password'];

    if($stmt = mysqli_prepare($db, "SELECT name, cid FROM customer WHERE name = ? and cid = ?")){
        mysqli_stmt_bind_param($stmt, "ss", $entered_username, $entered_password);

        $entered_username = $username;
        $entered_password = $password;

        if(mysqli_stmt_execute($stmt)){
            mysqli_stmt_store_result($stmt);
            if(mysqli_stmt_num_rows($stmt) == 1){
                mysqli_stmt_bind_result($stmt, $username, $turned_pw);

                if(mysqli_stmt_fetch($stmt)){
                    if($turned_pw == $password){
                        session_start();
                        $_SESSION['name'] = $username;
                        $_SESSION['cid'] = $password;
                        header("location: welcome.php");
                    }
                }
            }else{
                echo "<script type='text/javascript'>alert('Invalid username or password entered.');</script>";
            }

        }
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        #one { text-align: center; margin-bottom: 10px; }
        #two { display: inline-block; }
    </style>
</head>
<body style="background-color:powderblue;">
<div class="container">
	<nav class="navbar navbar-expand-md">
        <h5 class="navbar-text">Bank Management System</h5>
        </div>
    </nav>	
    <div id="one">
	<p><a></a></p>
        <div id="two">
            <br><br>
			<br><br>
            <h2>Login</h2>
            <p></p>
			<br><br>
            <form id="logining" action="" method="post">
                <div class="form-group">
                    <input type="text" name="username" class="form-control" id="username" placeholder="Username">

                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">

                </div>
				<br><br>
                <div class="form-group">
                    <input onclick="checkEmpty()" class="btn btn-success" value="Login">
                </div>
            </form>
        </div>
    </div>
</div>


<script type="text/javascript">
    function checkEmpty() {
        var usernameVal = document.getElementById("username").value;
        var passwordVal = document.getElementById("password").value;
        if (usernameVal === "" || passwordVal === "") {
            alert("Fill username and password.");
        }
        else {
            var form = document.getElementById("logining").submit();
        }
    }
</script>
</body>
</html>