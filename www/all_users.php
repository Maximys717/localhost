<?php
// вс€ процедура работает на сесси€х. »менно в ней хран€тс€ данные пользовател€, пока он находитс€ на сайте. ќчень важно запустить их в самом начале странички!!!
session_start();

include ("bd.php"); // файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
{
//если существует логин и пароль в сесси€х, то провер€ем, действительны ли они

    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db); 
    $myrow2 = mysql_fetch_array($result2); 
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
        <title>—писок пользователей</title>
    </head>
    <body>
		<?php print <<<HERE
<!--// выводим меню -->
    | <a href='page.php?id=$_SESSION[id]'>ћо€ страница</a> | <a href='index.php'>√лавна€ страница</a> | <a href='exit.php'>¬ыход</a> |<br>
HERE;
		?>
    <h2>—писок пользователей</h2>

<?php

$result = mysql_query("SELECT login,id FROM users ORDER BY login",$db); // извлекаем логин и идентификатор пользователей 
$myrow = mysql_fetch_array($result);
    do
    {
// выводим их в цикле 
    printf("<a href='page.php?id=%s'>%s</a><br>",$myrow['id'],$myrow['login']);
    }
    while($myrow = mysql_fetch_array($result));

?>

    </body>
</html>