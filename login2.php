<?php ob_start(); ?>
	<meta charset="utf-8" />
<?php
//A session kezelés érdekében
session_start();

//Mysql beállítások
include_once('config.php');

function generate_token(){
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
	$kod="";
	for($i=0;$i<32;++$i){
		$randomString .= $characters[rand(0, $charactersLength - 1)];
	}
	return $randomString;
}

$log_nev=mysqli_real_escape_string($mysqli, $_POST['email']);
$log_jelszo=mysqli_real_escape_string($mysqli, $_POST['pass']);

//MYSQL
$lekerdezes = mysqli_prepare($mysqli, "SELECT * FROM `eshop_users` WHERE name = ?");
mysqli_stmt_bind_param($lekerdezes, 's', $log_nev);
mysqli_stmt_execute($lekerdezes);
$result = mysqli_stmt_get_result($lekerdezes);
$db = mysqli_num_rows($result);
$leker = mysqli_fetch_array($result);
mysqli_stmt_close($lekerdezes);
if ($db == 1)
{

$passw = hash("whirlpool",$log_jelszo.$leker['skey']);
	if($leker['pass'] == $passw){
/* Sikeres bejelentkezés
(Session és átirányitás a configban.php-ban megadott helyre */
if($leker['rank'] != 1){
$_SESSION['nev'] = $log_nev;
$_SESSION['id'] = $leker['skey'];
$_SESSION['token'] = generate_token();
header ('Location:'.$location);
}else{
header ('Location:login.php?error=2');
}
	}else{
	 header ('Location:login.php?error=1');	
	}
}
else
    {
/* Sikertelen bejelentkezés
(Session és átirányitás a configban.php-ban megadott helyre)*/



header ('Location:login.php?error=1');

    }
	mysqli_close($mysqli);
?>
