<?php
    if (isset($_POST['login'])) 
	{ 
		$login = $_POST['login'];
		if ($login == '') 
		{ 
			unset($login);
		} 
	}
//заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную


    if (isset($_POST['password'])) { 
		$password=$_POST['password']; 
		if ($password =='') { 
			unset($password);
		} 
	}
//заносим введенный пользователем пароль в переменную $password, если он пустой, то уничтожаем переменную

    if (empty($login) or empty($password)) //если пользователь не ввел логин или пароль, то выдаем ошибку и останавливаем скрипт
{
    exit ("Some fields are empty, please fill all of them!");
}

//если логин и пароль введены, то обрабатываем их, чтобы теги и скрипты не работали, мало ли что люди могут ввести
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
//удаляем лишние пробелы
    $login = trim($login);
    $password = trim($password);

//добавляем проверку на длину логина и пароля
if (strlen($login) < 3 or strlen($login) > 15) 
{
    exit ("Login must have min 3 or max 15 charsets.");
}
if (strlen($password) < 3 or strlen($password) > 15) 
{
    exit ("Password must have min 3 or max 15 charsets");
}

//проверяем, отправил ли пользователь изображение
if (!empty($_POST['fupload'])) 
{
    $fupload=$_POST['fupload'];    
	$fupload = trim($fupload);
    if ($fupload =='' or empty($fupload)) 
	{
        unset($fupload); // если переменная $fupload пуста, то удаляем ее
    }
}
//если переменной не существует (пользователь не отправил изображение),
if (!isset($fupload) or empty($fupload) or $fupload =='')
{
    // то присваиваем ему заранее приготовленную картинку с надписью "нет аватара"
    $avatar = "avatars/net-avatara.jpg"; //можете нарисовать net-avatara.jpg или взять в исходниках
}

$password = md5($password); //шифруем пароль
$password = strrev($password); //для надежности добавим реверс

// подключаемся к базе
    include ("bd.php"); //файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь

// проверка на существование пользователя с таким же логином
    $result = mysql_query("SELECT id FROM users WHERE login = '$login' ",$db);
    $myrow = mysql_fetch_array ($result);
    if (!empty($myrow['id']))
	{
    exit ("Sorry, entered login is reserved. Choose other login.");
	}

if (isset($fupload)) 
{ 
$avatar=$fupload; 
}
	else 
	{ 
	$avatar = "avatars/net-avatara.jpg"; //можете нарисовать net-avatara.jpg или взять в исходниках
	}

// если такого нет, то сохраняем данные
    $result2 = mysql_query ("INSERT INTO users (login,password,avatar) VALUES('$login','$password','$avatar')");
// Проверяем, есть ли ошибки
    if ($result2=='TRUE')
	{
    echo "Registration is successful! Now you can enter to site. <a href='index.php'>Home page</a>";
	}
		else 
		{
		echo "Error! You are not registered!";
		}
?>