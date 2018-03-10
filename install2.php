<HTML>
	<HEAD>
		<META charset="utf-8" />
		<TITLE>Setting up your E-shop</TITLE>
		<STYLE>
			body
			{
				text-align:center;
			}
		</STYLE>
	</HEAD>
	<BODY>
<?php
if(!isset($_POST['conf']))
{
print '<FORM method="POST" action="#">
MySQL host:
<br /><input type="text" name="host">
<br />Database name:
<br /><input type="text" name="dn">
<br />Database login:
<br /><input type="text" name="du">
<br />Database password:
<br /><input type="password" name="dp">
<br />Redirect in case of successful login
<br />(default: index.php):
<br /><input type="text" name="loc">
<br /><input type="submit" value="Apply settings" name="conf">
</form>';
}else{


	$megnyit = fopen("config.php","w");


	fwrite($megnyit, '<?php
	$host = "'.$_POST['host'].'";
	$dbname ="'.$_POST['dn'].'";
	$dbuser ="'.$_POST['du'].'";
	$dbpass ="'.$_POST['dp'].'";
	$location = "'.$_POST['loc'].'";
	//csatlakozás

	$mysqli= mysqli_connect ($host,$dbuser,$dbpass);
	mysqli_select_db($mysqli, $dbname);
	?>');

	fclose($megnyit);
	print "File has been changed successfully. <A href='install.php'>Return to installation</a>";
    }
?>
	</BODY>
</HTML>