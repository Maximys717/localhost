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
		// $session_user_login = $login
        $password = $_SESSION['password'];
        $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db);
        $myrow2 = mysql_fetch_array($result2);
		$subscriber_id = $myrow2['id'];
    //Если не действительны (может мы удалили этого пользователя из базы за плохое поведение)
		if (empty($myrow2['id']))
        {
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!");
        }
    }
	
	// Проверяем, зарегистрирован ли вошедший
        else 
		{
        exit("Вход на эту страницу разрешен только зарегистрированным пользователям!"); 
		}
		
// Извлекаем все данные пользователя с данным id
    $result = mysql_query("SELECT * FROM users WHERE id='$id'",$db); 
    $myrow = mysql_fetch_array($result);
	$owner_login = $myrow['login'];
	$followed_id = $myrow['id']; // даём имя переменной followed,потому что данная страничка пользователя мб будет читаемой
	
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
		<?php 
		if ($myrow['login'] == $login) {
			print <<<HERE
		<!--// выводим меню-->
		| <a href='page_data_edit.php?id=$_SESSION[id]'>Изменить регистрационные данные</a> | <a href='index.php'>Главная страница</a> | <a href='all_users.php'>Список пользователей</a> | <a href='exit.php'>Выход</a> |<br>
HERE;
			}
		else {
		print <<<HERE
		<!--// выводим меню-->
		| <a href='page.php?id=$_SESSION[id]'>Моя страница</a> | <a href='index.php'>Главная страница</a> | <a href='all_users.php'>Список пользователей</a> | <a href='exit.php'>Выход</a> |<br>
HERE;
		}
		?>
	<!-- <h2>Лента пользователя "<?php echo $myrow['login']; ?>"</h2> -->
	<h2>Лента пользователя "<?php print ("<a href='page.php?id=$followed_id'>$owner_login</a>"); ?>"</h2>
		<?php print <<<HERE
            <img alt='аватар' src='$myrow[avatar]'><br>
HERE;
	?>

<!-- ///////////////////////////////////////  Считаем сколько у пользователя читателей и читаемых ////////////////////////////////////////////// -->
	
<?php

if ($myrow['login'] == $login) {

// узнаем количество читаемых
$tmp2 = mysql_query("SELECT * FROM readers WHERE subscriber_id='$subscriber_id'",$db); 
$numbers2 = mysql_fetch_array($tmp2); // извлекаем количество читатемых
	if (!empty($numbers2['id'])) {
    
	// выводим все сообщения в цикле
		do {
		$followed_users_num = $followed_users_num + 1;
		}
			while($numbers2 = mysql_fetch_array($tmp2)); 
	}
    else {
// если никого не читает
		$followed_users_num = 0;
        }
}
		else {
		// узнаем количество читаемых
			$tmp2 = mysql_query("SELECT * FROM readers WHERE subscriber_id='$followed_id'",$db); 
			$numbers2 = mysql_fetch_array($tmp2); // извлекаем количество читатемых
				if (!empty($numbers2['id'])) {
    
				// выводим все сообщения в цикле
					do {
					$followed_users_num = $followed_users_num + 1;
					}
						while($numbers2 = mysql_fetch_array($tmp2)); 
				}
				else {
			// если никого не читает
					$followed_users_num = 0;
					}
}
		
// узнаем количество читателей
$tmp3 = mysql_query("SELECT * FROM readers WHERE the_followed_id='$followed_id'",$db); 
$numbers3 = mysql_fetch_array($tmp3); // извлекаем количество читателей
	if (!empty($numbers3['id'])) {
    
	// выводим все сообщения в цикле
		do {
		$read_users_num = $read_users_num + 1;
		}
			while($numbers3 = mysql_fetch_array($tmp3)); 
	}
    else {
// если никого не читает
		$read_users_num = 0;
        }

////////////////////////////////////////////////////////// выведем на экран число подписчиков/читаемых //////////
if ($myrow['login'] == $login) {

	print <<<HERE
	| <a href='followed_users.php?id=$_SESSION[id]'>Читаемые: $followed_users_num</a> | <a href='read_users.php?id=$_SESSION[id]'>Читатели: $read_users_num</a> |<br>
HERE;
}
else {

	print <<<HERE
	| <a href='followed_users.php?id=$followed_id'>Читаемые: $followed_users_num</a> | <a href='read_users.php?id=$followed_id'>Читатели: $read_users_num</a> |<br>
HERE;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<?php

// Если страничка принадлежит вошедшему, то

if ($myrow['login'] == $login) {

// то выводим форму для отправки сообщений на свою ленту
	print <<<HERE
            <form action='post.php' method='post'>
            <br>
            <h2>Отправить сообщение на свою ленту:</h2>
            <textarea cols='70' rows='2'name='text'></textarea><br>
            <!-- <input type='hidden' name='author' value='$myrow[login]'> -->
            <input type='hidden' name='id' 	   value='$myrow[id]'>
            <input type='submit' name='submit' value='Отправить'>
            </form>
HERE;

//////////////////////// и некоторые данные (ленту сообщений) ///////////////////////////////////////////////////////////////// 


	// в данной строке выборка сообщений идёт сразу из двух граф - читаемые и владелец страницы
	// DISTINCT в купе с GROUP BY позволяют удалить дубликаты сообщений, которые получаются при выборке (если 2 и больше читаемых, то сообщения хозяина странички равны количеству читаемых им, см. код)
	$tmp = mysql_query("SELECT DISTINCT readers.the_followed_id,users.id,users.login,messages.* FROM readers,users,messages WHERE readers.subscriber_id='$followed_id' AND users.id=readers.the_followed_id AND (messages.author=users.login OR messages.author='$owner_login') GROUP BY messages.id ORDER BY messages.id DESC",$db);
	
	// в данной строке выборка сообщений идёт только читаемых
	//$tmp = mysql_query("SELECT readers.the_followed_id,users.id,users.login,messages.* FROM readers,users,messages WHERE readers.subscriber_id='$followed_id' AND users.id=readers.the_followed_id AND messages.author=users.login ORDER BY messages.id DESC",$db);
		
	// в данной строке выборка сообщений идёт только владельца страницы
	//$tmp = mysql_query("(SELECT * FROM messages WHERE author='$login') UNION (SELECT * FROM messages WHERE author='$login') ORDER BY id DESC",$db); 
	
	
	$messages = mysql_fetch_array($tmp); // извлекаем сообщения пользователя, сортируем по идентификатору в обратном порядке, т.е. самые новые сообщения будут вверху
	
	if (!empty($messages['id'])) {
    
	// выводим все сообщения в цикле
		do {
			$author = $messages['author'];
			$result4 = mysql_query("SELECT avatar,id FROM users WHERE login='$author'",$db); //извлекаем аватар автора 
			$myrow4 = mysql_fetch_array($result4);
			if (!empty($myrow4['avatar'])) { //если такового нет, то выводим стандартный (может этого пользователя уже давно удалили)
		$avatar = $myrow4['avatar'];
		}
		else {
			$avatar = "avatars/net-avatara.jpg";
			}
			if ($author == $owner_login) { // клавиша "удалить" должна быть только на тех сообщениях, где вы являетесь автором
				
				printf("
				<table>
					<tr>
            
					<td><a href='page.php?id=%s'><img alt='аватар' src='%s'></a></td>

					<td>Автор: <a href='page.php?id=%s'>%s</a><br>
						Дата: %s<br>
						Сообщение:<br>

							%s<br>
								<a href='drop_post.php?id=%s'>Удалить</a>

					</td>
					</tr>
					</table><br>
						",$myrow4['id'],$avatar,$myrow4['id'],$author,$messages['date'],$messages['text'],$messages['id']);
				}
				else {
					printf("
					<table>
						<tr>
            
						<td><a href='page.php?id=%s'><img alt='аватар' src='%s'></a></td>

						<td>Автор: <a href='page.php?id=%s'>%s</a><br>
							Дата: %s<br>
							Сообщение:<br>

								%s<br>
									

						</td>
						</tr>
						</table><br>
							",$myrow4['id'],$avatar,$myrow4['id'],$author,$messages['date'],$messages['text'],$messages['id']);
				}
					//выводим само сообщение 
		}

			while($messages = mysql_fetch_array($tmp));
			
	
			
			
	}
    else {
//если сообщений не найдено
		echo "Сообщений нет";
        }
}
else {
///////////////////////////////////// для вошедшего на страничку /////////////////////////////////////////////////////////////////////////////////////
//если страничка чужая, то выводим ленту владельца, количество его читателей и тех, кого он читает, и возможность подписаться/отписаться на/от него

///////////////////////// проверяем подписан ли зашедший на владельца странички или нет ////////////////////////////////////////////////////////////////////

	$result4 = mysql_query("SELECT id FROM readers WHERE subscriber_id='$subscriber_id' AND the_followed_id='$followed_id'",$db);
    $myrow4 = mysql_fetch_array($result4); 
    // если нет, то предложим ему подписаться
		if (empty($myrow4['id'])) {
			print <<<HERE
				<form action='subscribe.php' method='post'>
					<br>
					<h3>Хотите подписаться на ленту пользователя $owner_login?</h3>
					<input type='hidden' name='the_followed' value='$myrow[login]'>
					<input type='hidden' name='id' 	   		 value='$myrow[id]'> <!-- здесь ид того, на кого подписываются -->
					<input type='submit' name='submit' 		 value='Подписаться'>
					
HERE;
		}
	// если подписан, то предложим ему отписаться
		else {
		// $id = $myrow4['id']; здесь ид строки подписки
			print <<<HERE
				<form action='drop_subscribe.php' method='get'>
					<br>
					<h3>Хотите отписаться от ленты пользователя $owner_login?</h3>
					<input type='hidden' name='id' 	   		 value='$myrow4[id]'>
					<!-- <input type='hidden' name='owner_login'  value='$owner_login'> -->
					<!-- <input type='hidden' name='owner_id' 	 value='$myrow[id]'> -->
					<input type='submit' name='submit' 		 value='Отписаться'>
					
HERE;
		}


///////////////////////////////// выводим его сообщения для вошедшего //////////////////////////////////////////////////////////////////////////////


print <<<HERE
	<h2>Сообщения пользователя "$owner_login"</h2>
HERE;

// DISTINCT в купе с GROUP BY позволяют удалить дубликаты сообщений, которые получаются при выборке (если 2 и больше читаемых, то сообщения хозяина странички равны количеству читаемых им, см. код)
$tmp = mysql_query("SELECT DISTINCT readers.the_followed_id,users.id,users.login,messages.* FROM readers,users,messages WHERE readers.subscriber_id='$followed_id' AND users.id=readers.the_followed_id AND (messages.author=users.login OR messages.author='$owner_login') GROUP BY messages.id ORDER BY messages.id DESC",$db);

	//$tmp = mysql_query("SELECT * FROM messages WHERE author='$owner_login' ORDER BY id DESC",$db); 
	$messages = mysql_fetch_array($tmp); // извлекаем сообщения пользователя, сортируем по идентификатору в обратном порядке, т.е. самые новые сообщения будут вверху
	if (!empty($messages['id'])) {
    
	// выводим все сообщения в цикле
		do {
			$author = $messages['author'];
			$result4 = mysql_query("SELECT avatar,id FROM users WHERE login='$author'",$db); //извлекаем аватар автора 
			$myrow4 = mysql_fetch_array($result4);
			if (!empty($myrow4['avatar'])) { //если такового нет, то выводим стандартный (может этого пользователя уже давно удалили)
		$avatar = $myrow4['avatar'];
		}
		else {
			$avatar = "avatars/net-avatara.jpg";
			}
		printf("
			<table>
				<tr>
            
				<td><a href='page.php?id=%s'><img alt='аватар' src='%s'></a></td>

				<td>Автор: <a href='page.php?id=%s'>%s</a><br>
					Дата: %s<br>
					Сообщение:<br>

						%s<br>
							
				</td>
				</tr>
				</table><br>
					",$myrow4['id'],$avatar,$myrow4['id'],$author,$messages['date'],$messages['text'],$messages['id']);
				//выводим само сообщение 
		}
	
			while($messages = mysql_fetch_array($tmp)); 
	}
    else {
//если сообщений не найдено
		echo "Сообщений нет";
        }

		
	}
?>
    </body>
</html>