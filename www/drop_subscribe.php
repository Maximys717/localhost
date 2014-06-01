<?php
    session_start(); // запускаем сессии
    include ("bd.php"); //подключаемся к базе
	
if (!empty($_SESSION['login']) and !empty($_SESSION['password'])) {
//если существует логин и пароль в сессиях, то проверяем, действительны ли они
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db); 
    $myrow2 = mysql_fetch_array($result2);
	$user_id = $_SESSION['id'];
		if (empty($myrow2['id'])) {
    // данные пользователя неверны. 
            exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
            }
    }
else {
// Проверяем, зарегистрирован ли вошедший
    exit("Вход на эту страницу разрешен только зарегистрированным пользователям!"); 
	}
$id2 = $_SESSION['id']; // получаем идентификатор своей страницы

// получаем через GET запрос идентификатор строки подписки, которую нужно удалить
if (isset($_GET['id'])) {
	$id = $_GET['id'];
	}
// нужно уточнить, кто подписан
$result = mysql_query("SELECT subscriber_id FROM readers WHERE id='$id'",$db); 
$myrow = mysql_fetch_array($result);
// ведь через GET запрос пользователь может ввести любой идентификатор и как следствие удалить сообщения, которые отправляли не ему.

// если подписка сделана данным пользователем, то разрешаем её удалить
if ($user_id == $myrow['subscriber_id']) {
	
	$result3 = mysql_query("SELECT the_followed_id FROM readers WHERE id='$id'",$db); 
	$myrow3 = mysql_fetch_array($result3);
	$id3 = $myrow3['the_followed_id'];
	
	$result4 = mysql_query("SELECT login FROM users WHERE id='$id3'",$db); 
	$myrow4 = mysql_fetch_array($result4);
	$the_followed = $myrow4['login'];

// удаляем строчку
$result = mysql_query ("DELETE FROM readers WHERE id = '$id' LIMIT 1");
// если удалено - перенаправляем на страничку пользователя
	if ($result == 'true') {
        echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$id3."'></head><body>Вы успешно отписались от $the_followed! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$id3."'>нажмите сюда.</a></body></html>";
        }
//если не удалено, то перенаправляем, но выдаем сообщение о неудаче
    else {
        echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$id3."'></head><body>Ошибка! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$id3."'>нажмите сюда.</a></body></html>";
		}
}
// если подписка сделана не этим пользователем. Значит, кто-то попытался удалить его, введя в адресной строке какой-то другой идентификатор
else {
	exit("Вы пытаетесь удалить подписку, сделанную не вами!");
	}
?>