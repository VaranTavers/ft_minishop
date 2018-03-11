<?PHP
include ("loginconfig.php");
if (isset($_GET['item']))
{
	$sanitize = intval(mysqli_real_escape_string($mysqli,$_GET['item']));
	$itemQuery = mysqli_query ($mysqli, "SELECT * FROM `eshop_items` WHERE `id`='".$sanitize."'");
	if (mysqli_num_rows($itemQuery) == 0)
		header("Location: index.php");
	$thisItem = mysqli_fetch_array($itemQuery);
}
else
	header("Location: index.php");
if (isset($_GET['add']))
{
	$itemNum = intval(mysqli_real_escape_string($mysqli,$_GET['add']));
	$addQuery = mysqli_query ($mysqli, "SELECT * FROM `eshop_item` WHERE `id`='".$itemNum."'");
	if (mysqli_num_rows($itemQuery) != 0)
	{
		$_SESSION['cart'] = $_SESSION['cart'].$itemNum."(|)";
		$cartNum++;
	}
}
?>
<HTML>
	<HEAD>
		<TITLE><?PHP echo get_setting($mysqli, "es_page_title", "value"); ?></TITLE>
		<LINK rel="stylesheet" type="text/css" href="style.css" />
		<META charset="utf-8" />
	</HEAD>
	<BODY>
	<?php include("header.php"); ?>
		<DIV id="body">
			<?PHP include("categories.php"); ?>
			<DIV id="whats_hot">
				<TABLE style="background: #FFFFFF; width:100%">
				<TR>
					<TD colspan="2">
					<H1> <?PHP echo $thisItem['name']; ?> </H1>
					</TD>
				</TR>
				<TR>
					<TD>
						<IMG src='<?PHP echo $thisItem['image']; ?>' id='item_img' />
					</TD>
					<TD style="vertical-align:top; width:60%">
					<?PHP
						if ($thisItem['price_percent'] != 100)
						{
							echo '<DIV class="old_price_big">'.$thisItem["price"].get_setting($mysqli, "es_currency", "value").'</DIV>
							<DIV class="price_big">'.((int)$thisItem["price_percent"]*(int)$thisItem["price"]/100).get_setting($mysqli, "es_currency", "value").'</DIV>';
						}
						else
						{
							echo '<DIV class="price_simple_big">'.(int)$thisItem["price"].get_setting($mysqli, "es_currency", "value").'</DIV>';
						}
					?>
						<DIV class='actions'>
							Sold by:
							<?PHP
								$userQuery = mysqli_query ($mysqli, "SELECT * FROM `eshop_users` WHERE `id`='".$thisItem["seller"]."'");
								$sellerData = mysqli_fetch_array($userQuery);
								$rankQuery = mysqli_query ($mysqli, "SELECT * FROM `eshop_ranks` WHERE `rank_id`='".$sellerData["rank"]."'");
								$rankData = mysqli_fetch_array($rankQuery);
								echo '<B style="color:'.$rankData["color_code"].'">'.$sellerData["name"].'</B>';
							?>
							<BR /><BR /><?PHP echo"<A HREF='item.php?item=".$_GET['item']."&add=".$thisItem['id']."' id='addtocart'>"; ?> Add to Cart </A>
							<H2> Description: </H2>
							<p><?PHP echo $thisItem['description']; ?>  </p>
						</DIV>
					</TD>
			</TR>
			<TR>
			<TD colspan="2">
				<H2> Specifications: </H2>
				<?PHP
					echo '<TABLE BORDER="1" style="width:100%">';
					$specrows = explode("(;)", $thisItem['specs']);
					foreach($specrows as $val)
					{
						$parts = explode("(:)", $val);
						echo '<TR><TD>'.$parts[0].'</TD><TD>'.$parts[1].'</TD></TR>';
						
					}
					echo '</TABLE>';
				?>
			</TD>
			</TR>
		</TABLE>
		</DIV>
		</DIV>
	<?PHP include("footer.php"); ?>
	</BODY>
</HTML>