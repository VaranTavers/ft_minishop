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
						print '<DIV class="hot">
							<A HREF="item.php?item='.$offer["id"].'"><IMG src="'.$offer["image"].'" class="hot_image"/>
							<BR />'.$offer["name"].'</A>
							<BR />
							<DIV class="old_price">'.$offer["price"].get_setting($mysqli, "es_currency", "value").'</DIV>
							<DIV class="price">'.((int)$offer["price_percent"]*(int)$offers["price"]/100).get_setting($mysqli, "es_currency", "value").'</DIV>
						</DIV>';
						$i++;
					}
					if ($i < 8)
					{
						$otherQuery = mysqli_query ($mysqli, "SELECT * FROM `eshop_items` WHERE `price_percent`='100'");
						while (($other = mysqli_fetch_array($otherQuery)) && $i < 8)
						{
							print '<DIV class="hot">
								<A HREF="item.php?item='.$other["id"].'"><IMG src="'.$other["image"].'" class="hot_image"/>
								<BR />'.$other["name"].'</A>
								<BR />
								Hot selling:
								<DIV class="price">'.(int)$other["price"].get_setting($mysqli, "es_currency", "value").'</DIV>
							</DIV>';
							$i++;
						}
					}
					if ($i < 8)
					{
						while ($i < 8)
						{
							print '<DIV class="hot">
								<A HREF="#"><IMG src="'.get_setting($mysqli, "es_missing_offer_logo", "value").'" class="hot_image"/>
								<BR />'.get_setting($mysqli, "es_missing_offer_text", "value").'</A>
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