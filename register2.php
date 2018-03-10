<?php ob_start(); ?>
	<meta charset="utf-8" />
<?php
session_start();
include_once('config.php');

$reg_nev=$_POST['nev'];
$reg_jelszo=$_POST['koder'];
$reg_jelszo_re=$_POST['koderre'];
$reg_email=$_POST['email'];
$reg_email_re=$_POST['emailre'];
$reg_ered = $_POST['real_name'];

//Karakterszures

$reg_nev=mysqli_real_escape_string($mysqli, $reg_nev);
$reg_nev=mysqli_real_escape_string($mysqli, $reg_ered);
$reg_jelszo=mysqli_real_escape_string($mysqli, $reg_jelszo);
$reg_jelszo_re=mysqli_real_escape_string($mysqli, $reg_jelszo_re);
$reg_email=mysqli_real_escape_string($mysqli, $reg_email);
$reg_email_re=mysqli_real_escape_string($mysqli, $reg_email_re);


//Név mezok kitöltésének vizsgálata
if ($reg_nev == "" || $reg_jelszo == "" || $reg_email == ""){
print "There cannot be empty fields!
<br /> <A href=register.php>Back</A>";
exit();
}
//Név lekérdezése
$stmt = mysqli_prepare($mysqli,"SELECT * FROM `eshop_users` WHERE name = ?");
mysqli_stmt_bind_param($stmt, "s", $reg_nev);
mysqli_stmt_execute($stmt);
$resulttt = mysqli_stmt_get_result($stmt);
$szam = mysqli_num_rows($resulttt);
mysqli_stmt_close($stmt);
//Annak vizsgálata hogy már létezik ilyen név az adatbázisban
if ($szam != 0){
print "The name ".$reg_nev." is already taken <br /><A href=register.php>Back</A>";
exit();
}
//E-mail cím valódisága:Forma ellenőrzése
$let = explode("@",$reg_email);
if(isset($let[1]))
{

$let2 = explode(".",$reg_email);

if(!isset($let2[1]))
    {

  print "The email <i>".$reg_email."</i> is not valid!
  <br /><A href=register.php>Back</A>";
     exit();
 
    }
}
else
{
  print "The email <i>".$reg_email."</i> is not valid!
  <br /><A href=register.php>Back</A>";
     exit();
}
//Jelszó és Jelszó újra, E-mail és E-mail újra egyezésének vizsgálata
if ($reg_email != $reg_email_re){
print "The two e-mails do not match!
<br /><A href=register.php>Back</A>";
exit();
}
if ($reg_jelszo != $reg_jelszo_re){
print "The two passwords do not match!
<br /><A href=register.php>Back</A>";
exit();
}

//Kod
$arrr = array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
$u = 0;
$uu=rand(5,9);
$kod="";
while($u < $uu){
$rnd = rand(0,35);
$kod = $kod.$arrr[$rnd];
++$u;
}

//Jelszó SHA256 hashelése
$pass = hash("whirlpool",$reg_jelszo.$kod);
//Adatbázisba töltés kódja
$regend = mysqli_prepare($mysqli,"INSERT INTO eshop_users (`id`, `name`, `visible_name`, `pass`, `email`, `skey`, `rank`, `shipping_data`) VALUES ( '0', ?, ?, ?, ?, ?, 2, '')");
mysqli_stmt_bind_param($regend,"sssss",$reg_nev,$reg_ered,$pass,$reg_email,$kod);
if (!mysqli_stmt_execute($regend))
  {
  die('Error: ' . mysqli_error($mysqli));
  }
mysqli_stmt_close($regend);
mysqli_close($mysqli);
print 'Successful registration<br /><A href=login.php>Log in</A>';
?>
