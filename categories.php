<?PHP
	echo '<DIV id="categories">';
				
	$categoryQuery = mysqli_query ($mysqli, "SELECT * FROM eshop_category");
	if (mysqli_num_rows($categoryQuery) > 0)
	{
		echo '<UL><B>'.get_setting($mysqli, "es_categories_text", "value").'</B>';
		while ($category = mysqli_fetch_array($categoryQuery))
		{
			echo '<LI><A href="category.php?cat='.$category['id'].'"> '.$category['name'].' </A></LI>';	
		}
		echo '</UL>';
	}
	else
	{
		echo get_setting($mysqli, "es_categories_missing", "value").'<BR />';
	}
				
	echo '</DIV>';
?>