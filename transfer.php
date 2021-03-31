<?php
@ob_start();
session_start();
include("config.php");


if(php_sapi_name() == 'cli'){$xxx = $_SERVER["SHELL"];}
else{$xxx = $_SERVER["REQUEST_METHOD"];}


$input_success = true;
if($xxx == "POST") {
    $input_success = true;
    $given_aid = $_POST['aidMy'];
	$given_aid2 = $_POST['aidTo'];
	$given_amount = $_POST['amount'];
    $customer_id = $_SESSION['cid'];
	
    
    $result1 = mysqli_query($db, "SELECT COUNT(*) AS cnt1 FROM owns WHERE cid = '$customer_id' AND aid = '$given_aid'");
	$result2 = mysqli_query($db, "SELECT COUNT(*) AS cnt2 FROM account WHERE aid = '$given_aid' AND balance >= '$given_amount'");
	$result3 = mysqli_query($db, "SELECT COUNT(*) AS cnt3  FROM owns WHERE aid = '$given_aid2'");
    if (!$result1 || !$result3){
        printf("Error: %s\n", mysqli_error($db));
        exit();
    }
	
    
    $cnt1 = mysqli_fetch_array($result1)['cnt1'];
	$cnt2 = mysqli_fetch_array($result2)['cnt2'];
	$cnt3 = mysqli_fetch_array($result3)['cnt3'];
    if($cnt1 == 0){  // whether corresponding account is owned
        $input_success = false;
        echo "<script LANGUAGE='JavaScript'>
            window.alert('Wrong owned account information.');
        </script>";
    }
	else if($cnt2 == 0){ //whether balance is enough
        $input_success = false;
        echo "<script LANGUAGE='JavaScript'>
            window.alert('Not enough balance.');
        </script>";
    }
	else if($cnt3 == 0){ //whether destination account is valid
        $input_success = false;
        echo "<script LANGUAGE='JavaScript'>
            window.alert('Wrong destination account information.');
        </script>";
    }
	else {
		$resultDecrease = mysqli_query($db,"UPDATE account SET balance = balance - $given_amount WHERE aid = '$given_aid'");
		$resultIncrease = mysqli_query($db,"UPDATE account SET balance = balance + $given_amount WHERE aid = '$given_aid2'");	
		echo "<script LANGUAGE='JavaScript'>
           window.alert('Successfully Transferred Money.');
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
		body{ font: 13px sans-serif; text-align: center; }
        p { margin-bottom: 10px; position: absolute; left: 50%;}
        th, td { padding: 5px; text-align: left; }
		
		.custom {
		font: 20px sans-serif; text-align: center;
		}
		
		#one { text-align: center; margin-bottom: 10px; }
        #two { display: inline-block; }
    </style>
</head>
<body style="background-color:powderblue;";">
<div class="container">
    <nav class="navbar navbar-expand-md">
        <h5 class="navbar-text">Welcome</h5>
        <div class="navbar-collapse collapse w-100 order-3 dual-collapse2">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a href="welcome.php" class="nav-link">Back</a>
                </li>
                <li class="nav-item">
                    <a href="logout.php" class="nav-link">Logout</a>
                </li>
            </ul>
        </div>

    </nav>
    <div class="panel container-fluid">
        <h3 class="custom" style="font-weight: bold;">My Accounts</h3>
        <?php
		$customer_id = $_SESSION['cid'];
        echo "<table class=\"table table-lg table-striped\">
        <tr>
            <th>Account ID</th>
            <th>Balance</th>
        </tr>";

		$resultMine = mysqli_query($db, "SELECT aid as aidMy, balance FROM customer NATURAL JOIN owns NATURAL JOIN account WHERE cid = '$customer_id'");
        if (!$resultMine) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }

        while($row = mysqli_fetch_array($resultMine)){
            echo "<tr>";
            echo "<td>" . $row['aidMy'] . "</td>";
            echo "<td>" . $row['balance'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
    </div>
	<div class="panel container-fluid">
	<h3 class="custom" style="font-weight: bold;">Other Avaible Accounts</h3>
	<?php
	$customer_id = $_SESSION['cid'];
        echo "<table class=\"table table-lg table-striped\">
        <tr>
            <th>Account ID</th>
        </tr>";

		$resultOthers = mysqli_query($db, "SELECT DISTINCT aid AS aidOthers FROM owns WHERE owns.cid <> '$customer_id'");
        if (!$resultOthers) {
            printf("Error: %s\n", mysqli_error($db));
            exit();
        }

        while($row = mysqli_fetch_array($resultOthers)){
            echo "<tr>";
            echo "<td>" . $row['aidOthers'] . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        ?>
	</div>
		<div id="one">
			<div id="two">
			<form id="fieldForm" action="" METHOD="post">
				<div class = "form-row">
					<input type="text"  class="form-control" name="aidMy" id="ownedAccount" placeholder="Transfer From">
					<input type="text"  class="form-control" name="amount" id="amountField" placeholder="Amount">
					<input type="text"  class="form-control" name="aidTo" id="destinationAccount" placeholder="Transfer To">
				</div>
				<button onclick="checkEmpty()" type="submit" class="btn btn-success">Transfer</button>
			</form>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
    function checkEmpty() {
        var ownedAc = document.getElementById("ownedAccount").value;
		var amount = document.getElementById("amountField").value;
        var destAc = document.getElementById("destinationAccount").value;
        if (ownedAc === "" || destAc === "" || amount === "") {
            alert("Fill all the fields.");
        }
    }
</script>
</body>
</html>
