<?php
@ob_start();
session_start();
include("config.php");


if(php_sapi_name() == 'cli'){$xxx = $_SERVER["SHELL"];}
else{$xxx = $_SERVER["REQUEST_METHOD"];}


if($xxx == "POST") {
    $given_aid = $_POST['given_aid'];
    $customer_id = $_SESSION['cid'];

    $result = mysqli_query($db,"DELETE FROM owns WHERE cid ='$customer_id' AND aid='$given_aid'");

    if (!$result) {
        printf("Error: %s\n", mysqli_error($db));
        exit();
    }else{
        echo "<script LANGUAGE='JavaScript'>
            window.alert('Successfully deleted.');
            window.location.href = 'welcome.php'; 
        </script>";
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Accounts</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <style type="text/css">
        body{ font: 14px sans-serif; text-align: center; }
        p { margin-bottom: 10px; }
        th, td { padding: 5px; text-align: left; }
    </style>
</head>
<body style="background-color:powderblue;">
<div class="container">
    <nav class="navbar navbar-expand-md">
        <h5 class="navbar-text">Welcome</h5>
        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="index.php" class="nav-link">Back</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>

    </nav>
    <div class="panel container-fluid">
        <h3 class="page-header" style="font-weight: bold;">My Accounts</h3>
        <?php
		$customer_id = $_SESSION['cid'];
		$customer_name = $_SESSION['name'];
        echo "<p><b></b> " . $customer_name . "</p>";
        $result = mysqli_query($db, "SELECT aid, branch, balance, openDate FROM customer NATURAL JOIN owns NATURAL JOIN account WHERE cid = '$customer_id'");
       

        if (!$result) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }

        echo "<table class=\"table table-lg table-striped\">
            <tr>
            <th>Account ID</th>
            <th>Branch</th>
            <th>Balance</th>
			<th>Creation Date</th>
            <th>Cancel</th>
            </tr>";

        while($row = mysqli_fetch_array($result)) {
            echo "<tr>";
            echo "<td>" . $row['aid'] . "</td>";
            echo "<td>" . $row['branch'] . "</td>";
            echo "<td>" . $row['balance'] . "</td>";
			echo "<td>" . $row['openDate'] . "</td>";
            echo "<td> <form action=\"\" METHOD=\"POST\">
                    <button type=\"submit\" name = \"given_aid\"class=\"btn btn-danger btn-sm\" value =".$row['aid'] .">X</button>
                    </form>
                     
                  </td>";
            echo "</tr>";
        }

        echo "</table>";
        ?>
		</div>
			<p><a href="transfer.php" class="btn btn-success">Money Transfer</a></p>
		</div>
	</body>
</html>

