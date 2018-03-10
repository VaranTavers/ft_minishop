<?php ob_start(); ?>
<link rel="stylesheet" type="text/css" href="css.css" />
<?php

include ('config.php');
print "<TABLE BORDER=0 CELLPADDING=4>
		<TR>
			<TH>Registration</TH>
		</TR>
<FORM method ='POST' action = 'register2.php'>
		<TR>
			<TD><center>Login Name:
				<br /><input type='input' name='nev' >
				<br />Real Name:
				<br /><input type='input' name='real_name' >
				<br />Password:
				<br /><input type=password name='koder' >
				<br />Password again:
				<br /><input type=password name='koderre' >
				<br />E-Mail:
				<br /><input type='email' name='email' >
				<br />E-Mail again:
				<br /><input type='email' name='emailre' >";

print "<br /><input type='submit' name='submit' value='Registration' ></center></TD></TR>
</FORM>
</table>";
	mysqli_close($mysqli);
?>