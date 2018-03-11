<?php ob_start(); ?>
<HTML>
	<HEAD>
<meta charset="utf-8" />
	<TITLE>Users and page settings</TITLE>
	<LINK rel="stylesheet" type="text/css" href="style.css" />
	</HEAD>
	<BODY bgcolor='#FFFFFF'>
	<center>
<?php
include ('loginconfig.php');
if ($sess_jog != 3){
	print "Access has been denied";
	exit();
}
else
{	
	echo '<A HREF="index.php">Back to the site</A><BR />';
	if(isset($_POST['delete'])){
		validate_token($_POST['token'],$sess_token);
		$parancs="DELETE FROM eshop_users WHERE id = ".$_POST['id'];
		if (!mysqli_query($mysqli,$parancs)){
			die('Error: ' . mysqli_error($mysqli));
		}else{
			print  "<TABLE border='1' bgcolor='lightblue'>
			<TR><TD>A user has been successfully deleted!</TD></TR></table>";
		}
	}
	if(isset($_POST['modify'])){
		validate_token($_POST['token'],$sess_token);
		$parancs="UPDATE eshop_users SET `name`='".$_POST['nev']."', `visible_name`='".$_POST['visible_nev']."', `email`='".$_POST['email']."', `rank`='".$_POST['rang']."' WHERE id=".$_POST['id'];
		if (!mysqli_query($mysqli,$parancs)){
			die('Error: ' . mysqli_error($mysqli));
		}else{
			print  "<TABLE border='1' bgcolor='lightblue'>
			<TR><TD>User data successfully changed!</TD></TR></table>";
		}
	}
	if(isset($_POST['modifyy'])){
			validate_token($_POST['token'],$sess_token);
		$parancs="UPDATE eshop_settings SET `value`='".$_POST['ertek']."', `int_value`='".$_POST['szertek']."' WHERE id=".$_POST['id'];
		if (!mysqli_query($mysqli,$parancs)){
		  die('Error: ' . mysqli_error($mysqli));
		  }else{
		 print  "<TABLE border='1' bgcolor='lightblue'>
		 <TR><TD>Site data successfully changed!</TD></TR></table>";
		  }
	}
	$adat=mysqli_query($mysqli,"SELECT * FROM eshop_users");

	print "<div> <H1>Users</H1>
			<TABLE BORDER=1>
	<TR><TD>ID</TD><TD>Name</TD><TD>Visible name</TD><TD>E-mail</TD><TD>Rank</TD><TD>Actions</TD></TR>";
	while($data = mysqli_fetch_array($adat)){

		print "<TR>
		<TD>
		<FORM method='POST' action='admin.php'><input type='input' value='".$sess_token."' name='token' class='jelszo'>
		<input type='input' value='".$data['id']."' name='id' readonly></TD>
		<TD><input type='input' value='".$data['name']."' name='nev'></TD>
		<TD><input type='input' value='".$data['visible_name']."' name='visible_nev'></TD>
		<TD><input type='input' value='".$data['email']."' name='email'></TD>
		<TD>".list_ranks($mysqli,$data['rank'])."</TD>
		<TD><input type='submit' name='delete' value='Remove'>
		<input type='submit' name='modify' value='Modify'>
		</TD></form></TR>";
	}
	print "</table></div><br />";
}

$adat2=mysqli_query($mysqli,"SELECT * FROM eshop_settings");

print "<div> <H1> Page Settings </H1>
		<TABLE border='1'><TR><TD>Id</TD><TD>Setting name</TD><TD>Value</TD><TD>Intval</TD><TD>Package</TD><TD>Description</TD><TD>Actions</TD></TR>";
while($data2 = mysqli_fetch_array($adat2)){

	print "<TR>
	<TD><FORM method='POST' action='admin.php'><input type='input' value='".$sess_token."' name='token' class='jelszo'>
	<input type='input' value='".$data2['id']."' name='id' readonly></TD>
	<TD>".$data2['setting_name']."</TD>
	<TD><input type='input' value='".$data2['value']."' name='ertek'></TD>
	<TD><input type='input' value='".$data2['int_value']."' name='szertek'></TD>
	<TD>".$data2['id_string']."</TD>
	<TD>".$data2['comment']."</TD>
	<TD><input type='submit' name='modifyy' value='Modify'>
	</TD></form></TR>";
}
print "</table></div>";

		if(isset($_POST['delcat'])){
			validate_token($_POST['token'],$sess_token);
			$regend = mysqli_prepare($mysqli,"DELETE FROM eshop_category WHERE `id`=?");
			mysqli_stmt_bind_param($regend,"i",$_POST['id']);
			if (!mysqli_stmt_execute($regend)){
			die('Hiba: ' . mysqli_error($mysqli));
			}
	
		}
		if(isset($_POST['modcat'])){
			validate_token($_POST['token'],$sess_token);
			$regend = mysqli_prepare($mysqli,"UPDATE eshop_category SET `name`=? WHERE `id`=?");
			mysqli_stmt_bind_param($regend,"si",$_POST['nev'], $_POST['id']);
			if (!mysqli_stmt_execute($regend)){
			die('Hiba: ' . mysqli_error($mysqli));
			}
	
		}
		if(isset($_POST['addcat'])){
			validate_token($_POST['token'],$sess_token);
			$regend = mysqli_prepare($mysqli,"INSERT INTO eshop_category(`id`,`name`, `parent`) VALUES (0, ?, 0)");
			mysqli_stmt_bind_param($regend,"s", $_POST['nev']);
			if (!mysqli_stmt_execute($regend)){
			die('Hiba: ' . mysqli_error($mysqli));
			}
	
		}
		$mk = mysqli_query($mysqli,"SELECT * FROM eshop_category ORDER BY id DESC");	
		print'<div> <H1>Categories</H1>
		<TABLE border=1><TR><TD>ID</TD><TD>Name</TD><TD>Művelet</TD></TR>';
		while($mk2=mysqli_fetch_array($mk)){
		print '
		<TR>
		<TD><FORM method="POST" action="#"><input type="input" value="'.$sess_token.'" name="token" class="jelszo">
		<input type="input" value="'.$mk2['id'].'" name="id" readonly></TD>
		<TD><input type="input" value="'.$mk2['name'].'" name="nev"></TD>
		<TD><input type="submit" value="Delete" name="delcat">
		<input type="submit" value="Modify" name="modcat">
		</FORM></TD></TR>';
		}
				print '
		<TR>
		<TD><FORM method="POST" action="#"><input type="input" value="'.$sess_token.'" name="token" class="jelszo">
		<input type="input" value="" name="id" readonly></TD>
		<TD><input type="input" value="" name="nev"></TD>
		<TD><input type="submit" value="Add" name="addcat">
		</FORM></TD></TR>';
				print '</TABLE></div>';

if(isset($_POST['remord'])){
	validate_token($_POST['token'],$sess_token);
	$parancs="DELETE FROM eshop_orders WHERE id = ".$_POST['id'];
	if (!mysqli_query($mysqli,$parancs)){
		die('Error: ' . mysqli_error($mysqli));
	}else{
		print  "<TABLE border='1' bgcolor='lightblue'>
		<TR><TD>An order has been successfully deleted!</TD></TR></table>";
	}
}
				
$adat3=mysqli_query($mysqli,"SELECT * FROM eshop_orders");

print "<div> <H1> Current Orders </H1>
		<TABLE border='1'><TR><TD>Id</TD><TD>Order description</TD><TD>Buyer ID</TD><TD>Preset destination ID</TD><TD>Seller ID</TD><TD>Actions</TD></TR>";
while($data3 = mysqli_fetch_array($adat3)){

	print "<TR>
	<TD><FORM method='POST' action='admin.php'><input type='input' value='".$sess_token."' name='token' class='jelszo'>
	<input type='input' value='".$data3['id']."' name='id' readonly></TD>
	<TD>".$data3['item_list']."</TD>
	<TD><input type='input' value='".$data3['account']."' name='ertek'></TD>
	<TD><input type='input' value='".$data3['shipping']."' name='szertek'></TD>
	<TD>".$data3['sold_by']."</TD>
	<TD><input type='submit' name='remord' value='Remove'>
	</TD></form></TR>";
}
print "</table></div>";
				
mysqli_close($mysqli);
?>
</CENTER>
</BODY>
</HTML>