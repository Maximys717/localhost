<?php
// вс€ процедура работает на сесси€х. »менно в ней хран€тс€ данные пользовател€, пока он находитс€ на сайте. ќчень важно запустить их в самом начале странички!!!
session_start();

include ("bd.php"); // файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
{

// если существует логин и пароль в сесси€х, то провер€ем, действительны ли они

    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db); 
    $myrow2 = mysql_fetch_array($result2); 
	$id = $myrow2['id'];
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// сохраним полученные данные о пользователей

if (isset($_GET['id'])) {
	$got_id = $_GET['id'];
	}

// нужно уточнить, кто подписан
$result5 = mysql_query("SELECT subscriber_id,the_followed_id FROM readers WHERE subscriber_id='$got_id'",$db); 
$myrow5 = mysql_fetch_array($result5);
// ведь через GET запрос пользователь может ввести любой идентификатор и как следствие удалить сообщени€, которые отправл€ли не ему.

// если подписка есть, то выведем список, 
if ($got_id == $myrow5['subscriber_id'] OR $got_id == $myrow5['the_followed_id']) {
	
	$result4 = mysql_query("SELECT login FROM users WHERE id='$got_id'",$db); 
	$myrow4 = mysql_fetch_array($result4);

	$got_login = $myrow4['login'];
	}
	// если нет, то пишем
	else {
	$result4 = mysql_query("SELECT login FROM users WHERE id='$got_id'",$db); 
	$myrow4 = mysql_fetch_array($result4);

	$got_login = $myrow4['login'];
	exit("ѕользователь $got_login пока никого не читает! <html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$got_id."'></head><body>¬ы будете перемещены через 5 сек. ≈сли не хотите ждать, то <a href='page.php?id=".$got_id."'>нажмите сюда.</a></body></html>");
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if (empty($myrow2['id']))
        {
    //если данные пользовател€ не верны
        exit("¬ход на эту страницу разрешен только зарегистрированным пользовател€м!");
        }
}
else 
{
//ѕровер€ем, зарегистрирован ли вошедший
exit("¬ход на эту страницу разрешен только зарегистрированным пользовател€м!"); 
}
?>
<html>
    <head>
        <title>—писок читаемых "<?php echo $got_login; ?>"</title>
    </head>
    <body>
		<?php print <<<HERE
<!--// выводим меню -->
    | <a href='page.php?id=$_SESSION[id]'>ћо€ страница</a> | <a href='index.php'>√лавна€ страница</a> | <a href='all_users.php'>—писок пользователей</a> | <a href='exit.php'>¬ыход</a> |<br>
HERE;
		?>
    <h2>—писок читаемых "<?php print ("<a href='page.php?id=$got_id'>$got_login</a>"); ?>"</h2>

<?php

$result7 = mysql_query("SELECT readers.the_followed_id,users.id,users.login FROM readers,users WHERE readers.subscriber_id='$got_id' AND users.id=readers.the_followed_id",$db); // извлекаем логин и идентификатор пользователей 
$myrow7 = mysql_fetch_array($result7);
	
	do {
	// выводим их в цикле
		
		printf("<a href='page.php?id=%s'>%s</a><br>",$myrow7['the_followed_id'],$myrow7['login']);
		}
		while($myrow7 = mysql_fetch_array($result7));

//$result = mysql_query("SELECT login,id FROM users WHERE id='$got_the_followed_id' ORDER BY login",$db); // извлекаем логин и идентификатор пользователей 
//$myrow = mysql_fetch_array($result);
//    do {
	// выводим их в цикле 
//		printf("<a href='page.php?id=%s'>%s</a><br>",$myrow['id'],$myrow['login']);
//		}
//		while($myrow = mysql_fetch_array($result));

?>

    </body>
</html>