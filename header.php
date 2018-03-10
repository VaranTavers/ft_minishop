<?php
	echo '<DIV id="header">';

	if (get_setting($mysqli, "es_page_logo", "int_value") == 1)
		echo '<A HREF="index.php"><IMG src="'.get_setting($mysqli, "es_page_logo", "value").'" id="headerlogo"/></A>';
	if (get_setting($mysqli, "es_page_title", "int_value") == 1)
		echo "<H1 id='headertitle'><A HREF='index.php'>".get_setting($mysqli, "es_page_title", "value")."</A></H1>";

	echo '<DIV id="right-controls"><A href="cart.php"> Cart';
	
	if ($cartNum > 0)
		echo '('.$cartNum.')';
	
	echo '</A> | ';
	if (!isset($_SESSION['nev']))
	{
		echo '<A HREF="login.php">Sign In</A>';
	}
	else
	{
		echo '<A HREF="?kilep=1">Log out</A>';
	}
	if ($sess_jog == 3)
	{
		echo ' | <A HREF="admin.php">Admin panel</A>';
	}
	if ($sess_jog > 2)
	{
		echo ' | <A HREF="add_item.php">Add items</A>';
	}
	echo '</DIV>
		</DIV>';
?>