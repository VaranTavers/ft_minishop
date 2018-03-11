<?PHP
include ("loginconfig.php");
if (isset($_GET['del']))
{
	$str = str_replace((int)$_GET['del'], "", $_SESSION['cart']);
	$_SESSION['cart'] = str_replace("(|)(|)", "(|)", $str);
}
if (isset($_POST['preset']))
{
	$shipQuery=mysqli_query($mysqli, "SELECT * FROM eshop_shipping_data WHERE `id`='".(int)$_POST['preset']."'");
	if ($_POST['preset'] != 0)
		$shipData = mysqli_fetch_array($shipQuery);
	$text = "";
	
	if ($_POST['name'] != "")
		$text .= $_POST['name']."(;)";
	else if ($_POST['preset'] != 0)
		$text .= $shipData['name']."(;)";
	else
		$text .= "-(;)";
	
	if ($_POST['address'] != "")
		$text .= $_POST['address']."(;)";
	else if ($_POST['preset'] != 0)
		$text .= $shipData['address']."(;)";
	else
		$text .= "-(;)";
	
	if ($_POST['phone'] != "")
		$text .= $_POST['phone']."(;)";
	else if ($_POST['preset'] != 0)
		$text .= $shipData['phone']."(;)";
	else
		$text .= "-(;)";
	
	if (isset($_POST['email']))
		$text .= $_POST['email']."(;)";
	else if ($_POST['preset'] != 0)
		$text .= $shipData['email']."(;)";
	else
		$text .= "-(;)";
	
	$quant_local = 0;
	$last = 0;
	$cart = explode("(|)", $_SESSION['cart']);
	foreach($cart as $ertek)
	{
		if ($last != $ertek && $last != 0)
		{
			$itemQuery=mysqli_query($mysqli, "SELECT * FROM eshop_items WHERE `id`='".(int)$last."'");
			if (mysqli_num_rows($itemQuery) != 0)
			{
				$thisItem = mysqli_fetch_array($itemQuery);
				$text.=$ertek."(:)".$quant_local."(:)".($thisItem['price'] * $thisItem['price_percent'] / 100)."(;)";
			}
		}
		if ($last != $ertek)
		{
			$last = $ertek;
			$quant_local = 1;
		}
		else
		{
			$quant_local++;
		}
	}
		if ($last != 0 && $quant_local > 0)
		{
			$itemQuery=mysqli_query($mysqli, "SELECT * FROM eshop_items WHERE `id`='".(int)$last."'");
			if (mysqli_num_rows($itemQuery) != 0)
			{
				$thisItem = mysqli_fetch_array($itemQuery);
				$text.=$ertek."(:)".$quant_local."(:)".($thisItem['price'] * $thisItem['price_percent'] / 100)."(;)";
			}				
		}
	$update1 ="INSERT INTO eshop_orders (`id`, `item_list`, `account`, `shipping`, `sold_by`) VALUES 
		('0', '".$text."', '".$dataAboutLoggedInUser['id']."',  '".(int)$_POST['preset']."', '0')";
	if (!mysqli_query($mysqli,$update1))
	{
		die('Error while putting up order: ' . mysqli_error($mysqli));
	}
	$_SESSION['cart'] = "";
}
?>
<HTML>
	<HEAD>
		<TITLE>Cart - <?PHP echo get_setting($mysqli, "es_page_title", "value"); ?></TITLE>
		<LINK rel="stylesheet" type="text/css" href="style.css" />
		<META charset="utf-8" />
	</HEAD>
	<BODY>
	<?php include("header.php"); ?>
		<DIV id="body">
			<?PHP include("categories.php"); ?>
			<DIV id="whats_hot">
				<TABLE border="1" style="background: #FFFFFF; width:100%; text-align:center;">
					<TR>
						<TH>-</TH><TH width="100%">Product name</TH><TH>Quantity</TH><TH>Price</TH>	
					</TR>
					<?PHP
						$cart = explode("(|)", $_SESSION['cart']);
						$quant_total = 0;
						$price_total = 0;
						$saved_total = 0;
						$last = 0;
						$quant_local = 0;
						sort($cart);
						foreach($cart as $ertek)
						{
							if ($last != $ertek && $last != 0)
							{
								$itemQuery=mysqli_query($mysqli, "SELECT * FROM eshop_items WHERE `id`='".(int)$last."'");
								if (mysqli_num_rows($itemQuery) != 0)
								{
									$thisItem = mysqli_fetch_array($itemQuery);
									$quant_total += $quant_local;
									$price_local = $quant_local * ($thisItem["price"] * $thisItem["price_percent"] / 100);
									if ($thisItem["price_percent"] < 100)
										$saved_total += $quant_local * $thisItem["price"] - $price_local;
									$price_total += $price_local;
									echo '<TR>
										<TD><A HREF="?del='.$thisItem['id'].'"><BUTTON>X</BUTTON></A></TD><TD style="text-align:left;"><A HREF="item.php?item='.$thisItem['id'].'">'.$thisItem['name'].'</A></TD><TD>'.$quant_local.'x</TD><TD';
									if ($thisItem["price_percent"] < 100)
										echo ' style="color:#FF0000;"';
									echo	'>'.$price_local.get_setting($mysqli, "es_currency", "value").'</TD>
										</TR>';
								}
							}
							if ($last != $ertek)
							{
								$last = $ertek;
								$quant_local = 1;
							}
							else
							{
								$quant_local++;
							}
						}
						if ($last != 0 && $quant_local > 0)
						{
							$itemQuery=mysqli_query($mysqli, "SELECT * FROM eshop_items WHERE `id`='".(int)$last."'");
							if (mysqli_num_rows($itemQuery) != 0)
							{
								$thisItem = mysqli_fetch_array($itemQuery);
								$quant_total += $quant_local;
								$price_local = $quant_local * ($thisItem["price"] * $thisItem["price_percent"] / 100);
								if ($thisItem["price_percent"] < 100)
									$saved_total += $quant_local * $thisItem["price"] - $price_local;
								$price_total += $price_local;
								echo '<TR>
										<TD><A HREF="?del='.$thisItem['id'].'"><BUTTON>X</BUTTON></A></TD><TD style="text-align:left;"><A HREF="item.php?item='.$thisItem['id'].'">'.$thisItem['name'].'</A></TD><TD>'.$quant_local.'x</TD><TD';
								if ($thisItem["price_percent"] < 100)
									echo ' style="color:#FF0000;"';
								echo	'>'.$price_local.get_setting($mysqli, "es_currency", "value").'</TD>
										</TR>';
							}
						}
						echo '<TR>
								<TH style="text-align:right" colspan="2"> Total:</TH><TH>'.$quant_total.'</TH><TH>'.$price_total.get_setting($mysqli, "es_currency", "value").'</TH>
							</TR>';
						if ($saved_total > 0)
							echo '<TR>
								<TH style="text-align:right" colspan="2"> Money saved:</TH><TH colspan="2" style="color:#FF0000;">'.$saved_total.get_setting($mysqli, "es_currency", "value").'</TH>
							</TR>';
							
						echo '<TR>
							<TD colspan="4">';
						if (!isset($_SESSION["nev"]))
							echo 'To proceed with the purchase you must log in to the site!';
						else
						{
							echo 'Currently we can only accept cash as a payment method.
							<BR />Select a preset shipping destination or add a new one:
							<FORM method="post" action="cart.php">
							<BR /> Preset:
							<select name="preset">
							<option value="0"> New Destination</option>';
							if (get_setting($mysqli, "es_show_shops", "int_value") == 1)
								$sUserQuery = mysqli_query($mysqli, "SELECT * FROM eshop_users WHERE `id`='1' OR `id`='".$dataAboutLoggedInUser['id']."'");
							else
								$sUserQuery = mysqli_query($mysqli, "SELECT * FROM eshop_users WHERE `id`='".$dataAboutLoggedInUser['id']."'");
							while ($sUserData = mysqli_fetch_array($sUserQuery))
							{
								$bombed = explode(",", $sUserData['shipping_data']);
								foreach ($bombed as $id)
								{
									$shippingQuery = mysqli_query($mysqli, "SELECT * FROM eshop_shipping_data WHERE `id`='".$id."'");
									if (mysqli_num_rows($shippingQuery) > 0)
									{
										$shippingData = mysqli_fetch_array($shippingQuery);
										echo "<option value='".$id."'>".$shippingData['name']."</option>";
									}
								}
								
							}
							echo '</select>
							<BR />Name of receiver:
							<BR /><INPUT type="input" name="name"/>
							<BR />Address:
							<BR /><TEXTAREA name="address"></TEXTAREA>
							<BR />Phone number:
							<BR /><INPUT type="input" name="phone"/>
							<BR />E-Mail:
							<BR /><INPUT type="email" name="mail"/>
							<BR /><INPUT type="submit" value="Proceed"></FORM>';
						}
						echo '</TD>
							</TR>
							';
					?>
				</TABLE>
			</DIV>
		</DIV>
	<?PHP include("footer.php"); ?>
	</BODY>
</HTML>