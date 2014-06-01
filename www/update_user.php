<?php
session_start();
include ("bd.php"); // файл bd.php должен быть в той же папке, что и все остальные, если это не так, то измените путь

// если существует логин и пароль в сессиях, то проверяем, действительны ли они
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
{
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db); 
    $myrow2 = mysql_fetch_array($result2); 
    //Если не действительны, то закрываем доступ
		if (empty($myrow2['id']))
        {
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
        }
}
else 
{
//Проверяем, зарегистрирован ли вошедший
exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
}

	$old_login = $_SESSION['login']; //Старый логин нам пригодиться
    $id = $_SESSION['id']; //идентификатор пользователя тоже нужен
    $ava = "avatars/net-avatara.jpg"; //стандартное изображение будет кстати

//////////////////////////////// ИЗМЕНЕНИЕ ЛОГИНА ////////////////////////

if (isset($_POST['login'])) //Если существует логин
{
    $login = $_POST['login'];
//удаляем все лишнее
    $login = stripslashes($login); 
	$login = htmlspecialchars($login); 
	$login = trim($login); 
    //Если логин пустой, то останавливаем 
		if ($login == '') 
		{
		exit("Вы не ввели логин");
		}
	// проверяем дину 
		if (strlen($login) < 3 or strlen($login) > 15) 
		{
        exit ("Логин должен состоять не менее чем из 3 символов и не более чем из 15."); //останавливаем выполнение сценариев
        }
	// проверка на существование пользователя с таким же логином
        $result = mysql_query("SELECT id FROM users WHERE login='$login'",$db);
        $myrow = mysql_fetch_array($result);
        if (!empty($myrow['id'])) 
		{
        exit ("Извините, введённый вами логин уже зарегистрирован. Введите другой логин."); //останавливаем выполнение сценариев
        }
		
$result4 = mysql_query("UPDATE users SET login='$login' WHERE login='$old_login'",$db); //обновляем в базе логин пользователя 

//если выполнено верно, то обновляем все сообщения, которые отправлены ему
    if ($result4=='TRUE') 
	{ 
    mysql_query("UPDATE messages SET author='$login' WHERE author='$old_login'",$db); 
    $_SESSION['login'] = $login; //Обновляем логин в сессии 
    echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$_SESSION['id']."'></head><body>Ваш логин изменен! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите сюда.</a></body></html>"; //отправляем пользователя назад
	}
}

//////////////////////////////// ИЗМЕНЕНИЕ ПАРОЛЯ ////////////////////////

else if (isset($_POST['password'])) //Если существует пароль
{
// удаляем все лишнее 
    $password = $_POST['password'];
    $password = stripslashes($password);
	$password = htmlspecialchars($password);
	$password = trim($password);
    // если пароль не введен, то выдаем ошибку
		if ($password == '') 
		{
		exit("Вы не ввели пароль");
		}
// проверка на количество символов	
if (strlen($password) < 3 or strlen($password) > 15) 
{
exit ("Пароль должен состоять не менее чем из 3 символов и не более чем из 15."); //останавливаем выполнение сценариев
}

	// $password = md5($password); //шифруем пароль
    $password = strrev($password);// для надежности добавим реверс
    
//обновляем пароль 
$result4 = mysql_query("UPDATE users SET password='$password' WHERE login='$old_login'",$db); 

//если верно, то обновляем его в сессии
    if ($result4=='TRUE') 
	{
    $_SESSION['password'] = $password;
    echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$_SESSION['id']."'></head><body>Ваш пароль изменен! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите сюда.</a></body></html>"; //отправляем обратно на его страницу
	}
}

//////////////////////////////// ИЗМЕНЕНИЕ АВАТАРА ////////////////////////

else if (isset($_FILES['fupload']['name'])) //отправлялась ли переменная
{
if (empty($_FILES['fupload']['name']))
{
//если переменная пустая (пользователь не отправил изображение),то присваиваем ему заранее приготовленную картинку с надписью "нет аватара"
    $avatar = "avatars/net-avatara.jpg"; //можете нарисовать net-avatara.jpg или взять в исходниках
    $result7 = mysql_query("SELECT avatar FROM users WHERE login='$old_login'",$db); //извлекаем текущий аватар 
    $myrow7 = mysql_fetch_array($result7);
    //если аватар был стандартный, то не удаляем его, ведь у нас одна картинка на всех.
		if ($myrow7['avatar'] == $ava)    
		{
        $ava = 1;
        }
        else 
		{
		unlink ($myrow7['avatar']); //если аватар был свой, то удаляем его, затем поставим стандарт
		}
}
else 
{
//иначе - загружаем изображение пользователя для обновления
    $path_to_90_directory = 'avatars/'; //папка, куда будет загружаться начальная картинка и ее сжатая копия
                
    if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['fupload']['name'])) //проверка формата исходного изображения
    {             
        $filename = $_FILES['fupload']['name'];
        $source = $_FILES['fupload']['tmp_name'];
        $target = $path_to_90_directory . $filename;
    //загрузка оригинала в папку $path_to_90_directory 
		move_uploaded_file($source, $target); 
            //если оригинал был в формате gif, то создаем изображение в этом же формате. Необходимо для последующего сжатия
				if(preg_match('/[.](GIF)|(gif)$/', $filename)) 
				{
                $im = imagecreatefromgif($path_to_90_directory.$filename); 
                }
            //если оригинал был в формате png, то создаем изображение в этом же формате. Необходимо для последующего сжатия
				if(preg_match('/[.](PNG)|(png)$/', $filename)) {
					$im = imagecreatefrompng($path_to_90_directory.$filename);
					}
            //если оригинал был в формате jpg, то создаем изображение в этом же    формате. Необходимо для последующего сжатия
                if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/',    $filename)) {
                    $im = imagecreatefromjpeg($path_to_90_directory.$filename);
                    }

    // СОЗДАНИЕ КВАДРАТНОГО ИЗОБРАЖЕНИЯ И ЕГО ПОСЛЕДУЮЩЕЕ СЖАТИЕ ВЗЯТО С САЙТА www.codenet.ru

// Создание квадрата 90x90
    // dest - результирующее изображение 
    // w - ширина изображения 
    // ratio - коэффициент пропорциональности 

// квадратная 90x90. Можно поставить и другой размер.
$w = 90;

// создаём исходное изображение на основе
    // исходного файла и определяем его размеры
    $w_src = imagesx($im); //вычисляем ширину
    $h_src = imagesy($im); //вычисляем высоту изображения
    // создаём пустую квадратную картинку
    // важно именно truecolor!, иначе будем иметь 8-битный результат
        $dest = imagecreatetruecolor($w,$w);
nbsp;   // вырезаем квадратную серединку по x, если фото горизонтальное 
        if ($w_src>$h_src) 
            imagecopyresampled($dest, $im, 0, 0,
                round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                    0, $w, $w, min($w_src,$h_src), min($w_src,$h_src)); 
        // вырезаем квадратную верхушку по y, 
        // если фото вертикальное (хотя можно тоже серединку) 
        if ($w_src<$h_src) 
            imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w,
                min($w_src,$h_src), min($w_src,$h_src)); 
        // квадратная картинка масштабируется без вырезок 
        if ($w_src==$h_src) 
            imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src); 
                                            
$date=time(); //вычисляем время в настоящий момент.

// сохраняем изображение формата jpg в нужную папку, именем будет текущее время. Сделано, чтобы у аватаров не было одинаковых имен.
    imagejpeg($dest, $path_to_90_directory.$date.".jpg"); 
// почему  именно jpg? Он занимает очень мало места + уничтожается анимирование gif изображения, которое отвлекает пользователя. Не очень приятно читать его комментарий, когда краем глаза замечаешь какое-то движение.

// заносим в переменную путь до аватара.
$avatar = $path_to_90_directory.$date.".jpg"; 
$delfull = $path_to_90_directory.$filename; 
    // удаляем оригинал загруженного изображения, он нам больше не нужен. Задачей было - получить миниатюру.
	unlink ($delfull); 
// извлекаем текущий аватар пользователя
$result7 = mysql_query("SELECT avatar FROM users WHERE login='$old_login'",$db); 
    $myrow7 = mysql_fetch_array($result7);

//если он стандартный, то не удаляем его, ведь у нас одна картинка на всех.
if ($myrow7['avatar'] == $ava) {
    $ava = 1;
    }
    //если аватар был свой, то удаляем его
	else {
		unlink ($myrow7['avatar']);
		}
	}
    // в случае несоответствия формата, выдаем соответствующее сообщение
	else {
        exit ("Аватар должен быть в формате <strong>JPG,GIF или PNG</strong>");
        }
}
//обновляем аватар в базе 
$result4 = mysql_query("UPDATE users SET avatar='$avatar' WHERE login='$old_login'",$db); 
    //если верно, то отправляем на личную страничку
	if ($result4=='TRUE') {
        echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$_SESSION['id']."'></head><body>Ваша аватарка изменена! Вы будете перемещены через 5 сек. Если не хотите ждать, то <a href='page.php?id=".$_SESSION['id']."'>нажмите сюда.</a></body></html>";
		}
}
?>