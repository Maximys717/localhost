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
        if (empty($myrow2['id'])) {
    // если логин или пароль не действителен
			exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
            }
    }
else {
// проверяем, зарегистрирован ли вошедший 
    exit("Вход на эту страницу разрешен только зарегистрированным пользователям!"); 
	}

// получаем идентификатор страницы автора (получателя)
if (isset($_POST['id'])) {
	$id = $_POST['id'];
	}

/* // логин получателя
if (isset($_POST['author'])) {
	$author = $_POST['author'];
	}
*/

// получаем текст сообщения
if (isset($_POST['text'])) {
	$text = $_POST['text'];
	}
// логин автора 
$author = $_SESSION['login'];

	$date = date("H:i:s d/m/Y "); // дата добавления
	$date2 = date("Y-m-d H:i:s"); // дата добавления
// есть ли все необходимые данные, если нет, то останавливаем
if (empty($author) or empty($text) or empty($date)) {
    exit ("Вы ввели не всю информацию, вернитесь назад и заполните все поля");
	}
// удаляем обратные слеши
$text = stripslashes($text); 
$text = htmlspecialchars($text); //преобразование спецсимволов в их HTML эквиваленты
// заносим в базу сообщение 
    $result2 = mysql_query("INSERT INTO messages (author, date, date2, text) VALUES ('$author','$date','$date2','$text')",$db);
// перенаправляем пользователя
	echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$id."'></head><body>Ваше сообщение передано! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$id."'>нажмите сюда.</a></body></html>";
?>