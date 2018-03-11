<?php
	//Kimenet pufferelés
	ob_start();
	//Session kezelés
	session_start();
	//Mysql és átirányitási beállítások
	include_once('config.php');

	function get_setting($mysqli,$name, $data){
		$db = mysqli_query ($mysqli, "SELECT * FROM `eshop_settings` WHERE `setting_name`='".$name."'");
		$beall = mysqli_fetch_array($db);
		return $beall[$data];
	}
	
	function validate_token($a, $b){
		if($a != $b){
			print 'There has been a validation error. The requested action cannot be executed.';
			exit();
		}
	}
	function print_even($str)
	{
		if (strlen($str) > 67)
			echo substr($str, 0, 67)."...";
		else if (strlen($str) < 32)
		{
			echo $str."<BR />";
		}
		else
			echo $str;
	}
	
	function list_ranks($mysqli,$main){
		$string="<select name='rang'>";
		$db = mysqli_query ($mysqli, "SELECT * FROM `eshop_ranks`");
		while($db2= mysqli_fetch_array($db)){
			if($main == $db2['rank_id']){
				$string.= "<option selected='selected' value='".$db2['rank_id']."'>".$db2['rank_name']."</option>";			
			}else{
				$string.= "<option value='".$db2['rank_id']."'>".$db2['rank_name']."</option>";
			}
		}
		$string.="</select>";
		return $string;
	}
	
	if (!isset($_SESSION['cart']))
		$_SESSION['cart'] = "";
	$cartNum = count(explode("(|)", $_SESSION['cart'])) - 1;
	if ($cartNum < 0)
		$cartNum = 0;
	if ($_SESSION['cart'] == "(|)")
	{
		$_SESSION['cart'] = "";
		$cartNum = 0;
	}
	//Állapot megvizsgálása(Be / Ki jelentkezett)
	if (isset($_SESSION['nev'])){
		//Változók megadása 1
		
		$sess_nev=mysqli_real_escape_string($mysqli, $_SESSION['nev']);
		$sess_id=mysqli_real_escape_string($mysqli, $_SESSION['id']);
		$sess_token=mysqli_real_escape_string($mysqli, $_SESSION['token']);
		
		$queryToCheckAuthenticity = mysqli_prepare($mysqli, "SELECT * FROM eshop_users WHERE `name`=?");
		mysqli_stmt_bind_param($queryToCheckAuthenticity, 's', $sess_nev);
		mysqli_stmt_execute($queryToCheckAuthenticity);
		
		$resultToCheckAuthenticity = mysqli_stmt_get_result($queryToCheckAuthenticity);
		$dataAboutLoggedInUser = mysqli_fetch_array($resultToCheckAuthenticity);
		mysqli_stmt_close($queryToCheckAuthenticity);
		
		if ($sess_id != $dataAboutLoggedInUser['skey']){
			unset($_SESSION['nev']);
			unset($_SESSION['id']);
			unset($_SESSION['token']);
		}

		//Változók megadása 2
		$sess_jog=$dataAboutLoggedInUser['rank'];
		
		if($dataAboutLoggedInUser['rank'] == 1){
			unset($_SESSION['nev']);
			unset($_SESSION['id']);
			unset($_SESSION['token']);
			header('Location:index.php');
		}
		//Be van jelentkezve
		if (isset($_GET['kilep'])){
			//Rányomott a kilépés gombra
			unset($_SESSION['nev']);
			unset($_SESSION['id']);
			unset($_SESSION['token']);
			header('Location:index.php');
			exit();
		}else{
			//Kilépésgomb
			//print "<A href='?kilep=1'><img src=img/logout.gif border= 0></A><br>";
			$hozzaferes = TRUE;
		}
	}else{
		$hozzaferes = FALSE;
	}
	if(!isset($sess_jog)){
		$sess_jog = 1;
	}

?>

