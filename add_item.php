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
if ($sess_jog <= 2){
	print "Access has been denied";
	exit();
}
else
{	
	echo '<A HREF="index.php">Back to the site</A><BR />';
	if(isset($_POST['delete'])){
		validate_token($_POST['token'],$sess_token);
		$db = mysqli_query ($mysqli, "SELECT * FROM `eshop_item` WHERE `id`='".$_POST['id']."'");
		$itemData = mysqli_fetch_array($db);
		if ($sess_jog == 3 || $itemData['seller'] == $dataAboutLoggedInUser['id'])
		{
			$parancs="DELETE FROM eshop_items WHERE id = ".$_POST['id'];
			if (!mysql_queryi($mysqli,$parancs)){
				die('Error: ' . mysqli_error($mysqli));
			}else{
				print  "<TABLE border='1' bgcolor='lightblue'>
				<TR><TD>An item has been successfully deleted!</TD></TR></table>";
			}
		}
	}
	if(isset($_POST['modify'])){
		validate_token($_POST['token'],$sess_token);
		$db = mysqli_query ($mysqli, "SELECT * FROM `eshop_items` WHERE `id`='".$_POST['id']."'");
		$itemData = mysqli_fetch_array($db);
		if ($sess_jog == 3 || $itemData['seller'] == $dataAboutLoggedInUser['id'])
		{
			$parancs="UPDATE eshop_items SET `name`='".$_POST['name']."', `description`='".$_POST['description']."', `specs`='".$_POST['specs']."', `categories`='".$_POST['categories']."', `price`='".$_POST['price']."' , `price_percent`='".$_POST['price_percent']."', `image`='".$_POST['image']."' WHERE id=".$_POST['id'];
			if (!mysqli_query($mysqli,$parancs)){
				die('Error: ' . mysqli_error($mysqli));
			}else{
				print  "<TABLE border='1' bgcolor='lightblue'>
				<TR><TD>An item has been successfully changed!</TD></TR></table>";
			}
		}
	}
	if(isset($_POST['add'])){
			validate_token($_POST['token'],$sess_token);
		$parancs="INSERT INTO eshop_items (`id`, `name`, `description`, `specs`, `categories`, `price`, `price_percent`, `image`, `seller`) VALUES
				('0', '".$_POST['name']."', '".$_POST['description']."',  '".$_POST['specs']."', '".$_POST['categories']."', ".$_POST['price'].", ".$_POST['price_percent'].", '".$_POST['image']."', ".$dataAboutLoggedInUser['id'].")";
		if (!mysqli_query($mysqli,$parancs)){
		  die('Error: ' . mysqli_error($mysqli));
		  }else{
		 print  "<TABLE border='1' bgcolor='lightblue'>
		 <TR><TD>Item successfully added!</TD></TR></table>";
		  }
	}
	if ($sess_jog == 3)
		$adat=mysqli_query($mysqli,"SELECT * FROM eshop_items");
	else
		$adat=mysqli_query($mysqli, "SELECT * FROM eshop_items WHERE `seller`='".$dataAboutLoggedInUser['id']."'");

	print "<div> <H1>Items</H1>
			<TABLE BORDER=1>
	<TR><TD>ID</TD><TD>Name</TD><TD>Description</TD><TD>Specs</TD><TD>Categories</TD><TD>Price</TD><TD>Price %</TD><TD>Image</TD><TD>Seller</TD><TD>Actions</TD></TR>";
	while($data = mysqli_fetch_array($adat)){
		$userQuery = mysqli_query ($mysqli, "SELECT * FROM `eshop_users` WHERE `id`='".$data["seller"]."'");
		$sellerData = mysqli_fetch_array($userQuery);
		print "<TR>
		<TD>
		<FORM method='POST' action='add_item.php'><input type='input' value='".$sess_token."' name='token' class='jelszo'>
		<input type='input' value='".$data['id']."' name='id' readonly></TD>
		<TD><input type='input' value='".$data['name']."' name='name'></TD>
		<TD><textarea name='description'>".$data['description']."</textarea></TD>
		<TD><textarea name='specs'>".$data['specs']."</textarea></TD>
		<TD><input type='input' value='".$data['categories']."' name='categories'></TD>
		<TD><input type='input' value='".$data['price']."' name='price'></TD>
		<TD><input type='input' value='".$data['price_percent']."' name='price_percent'></TD>
		<TD><input type='input' value='".$data['image']."' name='image'></TD>
		<TD><input type='input' value='".$sellerData['name']."' name='seller_id' readonly></TD></TD>
		<TD><input type='submit' name='delete' value='Remove'>
		<input type='submit' name='modify' value='Modify'>
		</TD></form></TR>";
	}
	print "
	<TR>
	<TH colspan='10'> New Item </TH>
	</TR>
	<TR>
		<TD>
		<FORM method='POST' action='add_item.php'><input type='input' value='".$sess_token."' name='token' class='jelszo'>
		<input type='input' value='' name='id' readonly></TD>
		<TD><input type='input' value='' name='name'></TD>
		<TD><textarea name='description'></textarea></TD>
		<TD><textarea name='specs'>example(:)spec(;)example2(:)spec2</textarea></TD>
		<TD><input type='input' value=',0,' name='categories'></TD>
		<TD><input type='input' value='' name='price'></TD>
		<TD><input type='input' value='100' name='price_percent'></TD>
		<TD><input type='input' value='./img/sun.png' name='image'></TD>
		<TD><input type='input' value='$sess_nev' name='seller_id' readonly></TD>
		<TD><input type='submit' name='add' value='Add'>
		</TD></form></TR>";
	print "</table></div>";
}
mysqli_close($mysqli);
?>
</CENTER>
</BODY>
</HTML>