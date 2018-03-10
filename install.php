<HTML>
	<HEAD>
		<META charset="utf-8" />
		<TITLE>Setting up your E-shop</TITLE>
		<STYLE>
			body
			{
				text-align:center;
			}
		</STYLE>
	</HEAD>
	<BODY>
<?php

include ('config.php');
if(!isset($_POST['login'])){
print "<H2>1<sup>st</sup> step:</H2> 
<A HREF='install2.php'>Configure MySQL settings</a>
<H2>2<sup>nd</sup> step:</H2> 
<FORM method='POST' action='#'>

	Site title:<br /><input type=text name='title'>
	<br />Author:<br /><input type=text name='author'>
	
	<br />Please enter the admin credintials for your site:
	<br />Login:<br /><input type=text name='login'>
	<br />Password:<br /><input type=password name='passwd'>
	<br />E-Mail:<br /><input type=email name='email'>
	<br />
	<br /><B>Below this point DO NOT share confidential information since it will be visible to everyone!</B>
	<br />Please enter information about one of your shops / headquarters
	<br />(you can add more later)
	<br />Name of shop and/or location: (ex. MicroPC Cluj)<br /><input type=text name='shop_name'>
	<br />Address:<br /><textarea name='shop_address'></textarea>
	<br />E-Mail:<br /><input type=email name='shop_email'>
	<br />Phone number:<br /><input type=name name='shop_phone'>
	
	<br /><input type=submit value='Install'>
	
</from>";
}else{
	$my ="CREATE TABLE eshop_users(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(50),
		visible_name VARCHAR(100),
		pass VARCHAR(200),
		email VARCHAR(50),
		skey VARCHAR(10),
		rank INTEGER(10),
		shipping_data VARCHAR(100)
		);";
	if (!mysqli_query($mysqli,$my))
	{
		die('Error while creating table "users": ' . mysqli_error($mysqli));
	}
	$my_sd ="CREATE TABLE eshop_shipping_data(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(100),
		address TEXT(1000),
		email VARCHAR(50),
		phone VARCHAR(20)
		);";
	if (!mysqli_query($mysqli,$my_sd))
	{
		die('Error while creating table "shipping_data": ' . mysqli_error($mysqli));
	}
	$my2 ="CREATE TABLE eshop_settings(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		setting_name VARCHAR(50),
		value VARCHAR(500),
		int_value INTEGER(10),
		id_string VARCHAR(25),
		comment TEXT(1000)
		);";
	if (!mysqli_query($mysqli,$my2))
	{
		die('Error while creating table "settings": ' . mysqli_error($mysqli));
	}
	$my7 ="CREATE TABLE eshop_ranks(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		rank_name VARCHAR(100),
		color_code VARCHAR(10),
		rank_id INTEGER(25)
		);";
	if (!mysqli_query($mysqli,$my7))
	{
		die('Error while creating table "ranks": ' . mysqli_error($mysqli));
	}
	$my11 ="CREATE TABLE eshop_category(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(100),
		parent INT(10)
		);";
	if (!mysqli_query($mysqli,$my11))
	{
		die('Error while creating table "categories": ' . mysqli_error($mysqli));
	}
	$my12 ="CREATE TABLE eshop_items(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		name VARCHAR(500),
		description TEXT(10000),
		specs TEXT(5000),
		categories VARCHAR(100),
		price INT(30),
		price_percent INT (10),
		image VARCHAR(100),
		seller INT(30)
		);";
	if (!mysqli_query($mysqli,$my12))
	{
		die('Error while creating table "items": ' . mysqli_error($mysqli));
	}
	$my_order="CREATE TABLE eshop_orders(
		id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
		item_list TEXT(10000),
		account INT(30),
		shipping INT(30),
		sold_by INT(30)
		);";
	if (!mysqli_query($mysqli,$my_order))
	{
		die('Error while creating table "orders": ' . mysqli_error($mysqli));
	}
	$arrr = array("0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","l","m","n","o","p","q","r","s","t","u","v","w","x","y","z");
	$u = 0;
	$uu=rand(5,9);
	$kod="";
	while($u < $uu)
	{
		$rnd = rand(0,35);
		$kod = $kod.$arrr[$rnd];
		++$u;
	}
	$pass = hash("whirlpool",$_POST['passwd'].$kod);
	$update1 ="INSERT INTO eshop_users (`id`, `name`, `visible_name`, `pass`, `email`, `skey`, `rank`, `shipping_data`) VALUES ('1', '".$_POST['login']."', '".$_POST['author']."',  '".$pass."', '".$_POST['email']."', '".$kod."', 3, '1')";
	if (!mysqli_query($mysqli,$update1))
	{
		die('Error while initializing users: ' . mysqli_error($mysqli));
	}
	$update2 ="INSERT INTO eshop_settings (`id`, `setting_name`, `value`, `int_value`, `id_string`, `comment`) VALUES
		('1', 'login_delay',  '500', '1', 'BASE', 'Against brute-force attacks'),
		('2', 'es_home_page',  'index.php', '1', 'BASE', 'Main page url'),
		('3', 'es_page_title',  '".$_POST['title']."', '1', 'BASE', 'The title of the site'),
		('4', 'es_page_footer',  '© 2018 ".$_POST['author']."<BR /> ".$_POST['title']." is a trademark of ".$_POST['author']."', '1', 'BASE', 'The footer of the site'),
		('5', 'es_page_keywords',  'eshop', '1', 'BASE', 'Keywords'),
		('6', 'es_page_author',  '".$_POST['author']."', '1', 'BASE', 'Author'),
		('7', 'es_page_robots',  'all', '1', 'BASE', 'Enable bots'),
		('8', 'es_page_logo',  'img/logo.png', '0', 'BASE', 'Logo'),
		('9', 'es_missing_offer_text',  'Stay tuned for offers hotter than the sun!', '1', 'BASE', 'Text displayed if there are no offers available'),
		('10', 'es_missing_offer_logo',  'img/sun.png', '1', 'BASE', 'Logo displayed if there are no offers available'),
		('11', 'es_categories_text',  'Are you looking for one of these:', '1', 'BASE', 'Text displayed in front of categories'),
		('12', 'es_search_text',  'Didn\'t find what you were looking for? No worries, we can find it for you:', '0', 'BASE', 'Text displayed before search'),
		('13', 'es_categories_missing',  'Yikes! Playful spirits have stolen our categories!', '0', 'BASE', 'Text displayed when there are no categories'),
		('14', 'es_show_shops',  '-', '1', 'BASE', 'Show the main accounts addresses as available shipping locations'),
		('15', 'es_currency',  '$', '1', 'BASE', 'What currency is shown at the costs')
		
		";
	if (!mysqli_query($mysqli,$update2))
	{
		die('Error while initializing settings: ' . mysqli_error($mysqli));
	}
	$update5 ="INSERT INTO eshop_ranks (`id`, `rank_name`, `color_code`, `rank_id`) VALUES 
		('1', 'Banned User', '#3B3131','1'),
		('2', 'User','#000000','2'),
		('3', 'Admin','#f00000','3'),
		('4', 'Supplier','#006600','4')";
	if (!mysqli_query($mysqli,$update5))
	{
		die('Error while initializing ranks: ' . mysqli_error($mysqli));
	}
	$update6 ="INSERT INTO eshop_shipping_data (`id`, `name`, `address`, `email`, `phone`) VALUES 
		('1', '".$_POST['shop_name']."', '".$_POST['shop_address']."', '".$_POST['shop_email']."', '".$_POST['shop_phone']."')";
	if (!mysqli_query($mysqli,$update6))
	{
		die('Error while initializing shipping data: ' . mysqli_error($mysqli));
	}   
	print "<br />Installation has been completed! <BR /> <A HREF='index.php'>Take me to my site!</A>";
}
mysqli_close($mysqli);
?>
	</BODY>
</HTML>
