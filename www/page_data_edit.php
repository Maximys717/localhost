<?php
// вся процедура работает на сессиях. Именно в ней хранятся данные пользователя, пока он находится на сайте.
// Очень важно запустить их в самом начале странички!!!
session_start();
	
include ("bd.php"); // файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь
			
	//id "хозяина" странички 
        if (isset($_GET['id']))
		{
		$id =$_GET['id'];
		} 
	//если не указали id, то выдаем ошибку
		else
		{ 
		exit("Вы зашли на страницу без параметра!");
		}
	//если id не число, то выдаем ошибку		
        if (!preg_match("|^[\d]+$|", $id))
		{
        exit("<p>Неверный формат запроса! Проверьте URL</p>"); 
        }
		
//если существует логин и пароль в сессиях, то проверяем, действительны ли они	
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
    {
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db);
        $myrow2 = mysql_fetch_array($result2); 
    //Если не действительны (может мы удалили этого пользователя из базы за плохое поведение)
		if (empty($myrow2['id']))
        {
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
        }
    }
	
	//Проверяем, зарегистрирован ли вошедший
        else 
		{
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!"); 
		}
		
	//Извлекаем все данные пользователя с данным id
        $result = mysql_query("SELECT * FROM users WHERE id='$id'",$db); 
        $myrow = mysql_fetch_array($result); 

//если такого не существует			
if (empty($myrow['login'])) 
{ 
exit("Пользователя не существует! Возможно он был удален.");
} 
?>
<html>
    <head>
        <title><?php echo $myrow['login']; ?> - лента</title>
    </head>
    <body>
		<?php print <<<HERE
	<!--// выводим меню-->
	| <a href='page.php?id=$_SESSION[id]'>Моя страница</a> | <a href='index.php'>Главная страница</a> | <a href='all_users.php'>Список пользователей</a> | <a href='exit.php'>Выход</a> |<br>
HERE;
		?>
	<h2>Настройки пользователя "<?php echo $myrow['login']; ?>"</h2>
 
<?php

//Если страничка принадлежит вошедшему, то предлагаем изменить данные
if ($myrow['login'] == $login)
{


print <<<HERE
<form action='update_user.php' method='post'>
            Ваш логин <strong>$myrow[login]</strong>.<br> Изменить логин:<br>
            <input name='login' type='text'>
            <input type='submit' name='submit' value='изменить'>
            </form>
            <br>
<form action='update_user.php' method='post'>
            Изменить пароль:<br>
            <input name='password' type='password'>
            <input type='submit' name='submit' value='изменить'>
            </form>
            <br>
<form action='update_user.php' method='post' enctype='multipart/form-data'>
            Ваш аватар:<br>
            <img alt='аватар' src='$myrow[avatar]'><br>
            Изображение должно быть формата jpg, gif или png. Изменить аватар:<br>
            <input type="FILE" name="fupload">
            <input type='submit' name='submit' value='изменить'>
            </form>
            <br>

HERE;

            }
?>
        </body>
    </html>