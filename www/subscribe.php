<?php
    session_start(); // запускаем сессию. Обязательно в начале страницы
    include ("bd.php"); // соединяемся с базой, укажите свой путь, если у вас уже есть соединение
	
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
    {
// если существует логин и пароль в сессиях, то проверяем, действительны ли они
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db); 
    $myrow2 = mysql_fetch_array($result2); 
	$subscriber_id = $myrow2['id'];
        if (empty($myrow2['id'])) {
    // если логин или пароль не действителен
			exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
            }
    }
else {
// проверяем, зарегистрирован ли вошедший 
    exit("Вход на эту страницу разрешен только зарегистрированным пользователям!"); 
	}
// получаем идентификатор страницы того, кого будут читать
if (isset($_POST['id'])) {
	$the_followed_id = $_POST['id'];
	$id=$the_followed_id; // иначе не перенаправится на страничку назад
	}
// логин того, кого будут читать
if (isset($_POST['the_followed'])) {
	$the_followed = $_POST['the_followed'];
	}
// логин читателя
$subscriber = $_SESSION['login'];
	$number = 1; // это для подсчёта
	// $date2 = date("Y-m-d H:i:s"); // дата добавления
// есть ли все необходимые данные, если нет, то останавливаем
if (empty($subscriber_id) or empty($the_followed_id) or empty($number)) {
    exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля");
	}
// заносим в базу сообщение 
    $result2 = mysql_query("INSERT INTO readers (subscriber_id, the_followed_id, number) VALUES ('$subscriber_id','$the_followed_id','$number')",$db);
// перенаправляем пользователя
	echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$id."'></head><body>Поздравляем, вы подписались на страничку $the_followed! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$id."'>нажмите сюда.</a></body></html>";
?>