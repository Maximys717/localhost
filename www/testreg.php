<?php
// вся процедура работает на сессиях. Именно в ней хранятся данные пользователя, пока он находится на сайте. Очень важно запустить их в самом начале странички!!!
session_start();

// заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
    if (isset($_POST['login']))
	{ 
	$login=$_POST['login']; 
		if ($login == '') 
		{ 
		unset($login);
		} 
	} 
// заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную
    if (isset($_POST['password'])) 
	{ 
	$password=$_POST['password']; 
		if ($password =='') 
		{ 
		unset($password);
		} 
	}
//если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
    if (empty($login) or empty($password)) 
{
    exit ("Some fields are empty, please fill all of them!");
}
// если логин и пароль введены,то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
//удаляем лишние пробелы
    $login = trim($login);
    $password = trim($password);

//$password = md5($password); //шифруем пароль
$password = strrev($password); //для надежности добавим реверс


// заменяем новым********************************************
// подключаем файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путьмся к базе
	include ("bd.php"); 

// извлекаем из базы все данные о пользователе с введенным логином и паролем
$result = mysql_query("SELECT * FROM users WHERE login='$login'",$db); // извлекаем из базы все данные о пользователе с введенным логином
$myrow = mysql_fetch_array($result);
	if (empty($myrow['password']))
	{
//если пользователя с введенным логином не существует
	exit ("Entered loggin is incorrect.");
	}
else
{
//если существует, то сверяем пароли
	if ($myrow['password']==$password) 
	{
//если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!


    //nbsp; // если пароли совпадают, то запускаем пользователю сессию! Можете его поздравить, он вошел!
		
		$_SESSION['password']=$myrow['password'];
		$_SESSION['login']=$myrow['login'];

		$_SESSION['id']=$myrow['id']; // эти данные очень часто используются, вот их и будет "носить с собой" вошедший пользователь

	}
else 
{
//если пароли не сошлись
exit ("Entered password is incorrect.");
}
		
//Далее мы запоминаем данные в куки, для последующего входа.
//ВНИМАНИЕ!!! ДЕЛАЙТЕ ЭТО НА ВАШЕ УСМОТРЕНИЕ, ТАК КАК ДАННЫЕ ХРАНЯТСЯ В КУКАХ БЕЗ ШИФРОВКИ

	if ($_POST['save'] == 1) 
	{
// Если пользователь хочет, чтобы его данные сохранились для последующего входа, то сохраняем в куках его браузера
		setcookie("login", $_POST["login"], time()+9999999);
		setcookie("password", $_POST["password"], time()+9999999);
	}
}

// перенаправляем пользователя на главную страничку, там ему и сообщим об удачном входе
echo "<html><head><meta http-equiv='Refresh' content='0; URL=index.php'></head></html>"; 
?>
