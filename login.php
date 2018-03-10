<?php ob_start(); ?>
	<meta charset="utf-8" />
<link rel="stylesheet" type="text/css" href="css.css" />
<?php

//Hibaüzenet kezelés
if (isset($_GET['error'])){
$error = $_GET['error'];
if ($error == 1)
{
print "<TABLE border=1 bgcolor='red'>
<TR><TD>Incorrect credintials!</TD></TR>
</TABLE><BR />";
}
elseif ($error == 2)
{
print "<TABLE border=1 bgcolor='red'>
<TR><TD>This user has been banned!</TD></TR>
</TABLE><BR />";
}
elseif ($error == 3)
{
print "<TABLE border=1 bgcolor='green'>
<TR><TD>Logging in is down due to maintenance!</TD></TR>
</TABLE><BR />";
}
}
//Bejelentkezo Form
print "<FORM method ='POST' action = 'login2.php'>
 Login:
 <input type='input' name='email'>
 Password:
 <input type='password' name='pass' >
<input type=submit name=submit value='Log in'>
<A HREF=register.php>Sign Up</A>
</FORM>";
?>