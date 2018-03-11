<?PHP
include ("loginconfig.php");
?>
<HTML>
	<HEAD>
		<TITLE><?PHP echo get_setting($mysqli, "es_page_title", "value"); ?></TITLE>
		<LINK rel="stylesheet" type="text/css" href="style.css" />
		<META charset="utf-8" />
	</HEAD>
	<BODY>
		<?php include("header.php"); ?>
		<?PHP include("categories.php"); ?>
			<DIV id="whats_hot">
			<?PHP
				$i = 0;
				while ($i < 8)
				{
					$offerQuery = mysqli_query ($mysqli, "SELECT * FROM `eshop_items` WHERE `price_percent`!='100'");
					while (($offer = mysqli_fetch_array($offerQuery)) && $i < 8)
					{
						echo '<DIV class="hot">
							<A HREF="item.php?item='.$offer["id"].'"><IMG src="'.$offer["image"].'" class="hot_image"/>
							<BR />';
							print_even($offer["name"]);
							echo '</A>
							<BR />
							<DIV class="old_price">'.$offer["price"].get_setting($mysqli, "es_currency", "value").'</DIV>
							<DIV class="price">'.((int)$offer["price_percent"]*(int)$offer["price"]/100).get_setting($mysqli, "es_currency", "value").'</DIV>
						</DIV>';
						$i++;
					}
					if ($i < 8)
					{
						$otherQuery = mysqli_query ($mysqli, "SELECT * FROM `eshop_items` WHERE `price_percent`='100'");
						while (($other = mysqli_fetch_array($otherQuery)) && $i < 8)
						{
							echo '<DIV class="hot">
								<A HREF="item.php?item='.$other["id"].'"><IMG src="'.$other["image"].'" class="hot_image"/>
								<BR />';
								print_even($other["name"]);
							echo '</A>
								<BR />
								<DIV class="hot_selling">Hot selling:</DIV>
								<DIV class="price_simple">'.(int)$other["price"].get_setting($mysqli, "es_currency", "value").'</DIV>
							</DIV>';
							$i++;
						}
					}
					if ($i < 8)
					{
						while ($i < 8)
						{
							echo '<DIV class="hot">
								<A HREF="#"><IMG src="'.get_setting($mysqli, "es_missing_offer_logo", "value").'" class="hot_image"/>
								<BR />';
								print_even(get_setting($mysqli, "es_missing_offer_text", "value"));
							echo '</A>
								<BR />
								<DIV class="old_price">Good price</DIV>
								<DIV class="price">Even better price</DIV>
							</DIV>';
							$i++;
						}
					}
				}
			?>	
			</DIV>
		</DIV>
		<?PHP include("footer.php"); ?>
	</BODY>
</HTML>