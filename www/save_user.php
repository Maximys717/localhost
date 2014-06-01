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

/////////////// AVATAR //////////////////////////////////////////////////

if (isset($_FILES['fupload']['name'])) //отправлялась ли переменная
{
if (empty($_FILES['fupload']['name']))
{
//если переменная пустая (пользователь не отправил изображение), то присваиваем ему заранее приготовленную картинку с надписью "нет аватара"
    $avatar = "avatars/net-avatara.jpg"; //можете нарисовать net-avatara.jpg или взять в исходниках
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
            }
            else {
            // в случае несоответствия формата, выдаем соответствующее сообщение
                exit ("Аватар должен быть в формате <strong>JPG,GIF или PNG</strong>");
            }
        //конец процесса загрузки и присвоения переменной $avatar адреса загруженной авы
            }

			
//$password = md5($password); //шифруем пароль
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
// если такого нет, 
/*
if (isset($fupload)) 
{ 
$avatar=$fupload; 
}
	else 
	{ 
	$avatar = "avatars/net-avatara.jpg"; //можете нарисовать net-avatara.jpg или взять в исходниках
	}
*/
// то сохраняем данные
    $result2 = mysql_query ("INSERT INTO users (login,password,avatar) VALUES('$login','$password','$avatar')");
}
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