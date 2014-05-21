<?php
    if (isset($_POST['login'])) { $login = $_POST['login']; if ($login == '') { unset($login);} }
//заносим введенный пользователем логин в переменную $login, если он пустой, то уничтожаем переменную
    if (isset($_POST['password'])) { $password=$_POST['password']; if ($password =='') { unset($password);} }
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
//удал€ем лишние пробелы
    $login = trim($login);
    $password = trim($password);


// дописываем новое********************************************

//добавл€ем проверку на длину логина и парол€
if    (strlen($login) < 3 or strlen($login) > 15) {
    exit    ("Login must have min 3 or max 15 charsets.");
}
if    (strlen($password) < 3 or strlen($password) > 15) {
    exit    ("Password must have min 3 or max 15 charsets");
}

if    (!empty($_POST['fupload'])) //провер€ем, отправил ли пользователь изображение
{
    $fupload=$_POST['fupload'];    $fupload = trim($fupload);
    if ($fupload =='' or empty($fupload)) {
        unset($fupload);// если переменна€ $fupload пуста, то удал€ем ее
    }
}
if    (!isset($fupload) or empty($fupload) or $fupload =='')
{
    //если переменной не существует (пользователь не отправил изображение), то присваиваем ему заранее приготовленную картинку с надписью "нет аватара"
    $avatar    = "avatars/net-avatara.jpg"; //можете нарисовать net-avatara.jpg или вз€ть в исходниках
}

$password    = md5($password);//шифруем пароль
$password    = strrev($password);// дл€ надежности добавим реверс
$password    = $password."maxwell";

//можно добавить несколько своих символов по вкусу, например, вписав "maxwell". ≈сли этот пароль будут взламывать методом подбора у себ€ на сервере этой же md5,то €вно ничего хорошего не выйдет. Ќо советую ставить другие символы, можно в начале строки или в середине.
//ѕри этом необходимо увеличить длину пол€ password в базе. «ашифрованный пароль может получитс€ гораздо большего размера.
// дописали новое********************************************


// подключаемс€ к базе
    include ("bd.php");// файл bd.php должен быть в той же папке, что и все остальные, если это не так, то просто измените путь
// проверка на существование пользовател€ с таким же логином
    $result = mysql_query("SELECT id FROM users WHERE login='$login'",$db);
    $myrow = mysql_fetch_array($result);
    if (!empty($myrow['id'])) {
    exit ("Sorry, entered login is reserved. Choose other login.");
}


// если такого нет, то сохран€ем данные
    $result2 = mysql_query ("INSERT INTO users (login,password,avatar) VALUES('$login','$password','$avatar')");
// ѕровер€ем, есть ли ошибки
    if ($result2=='TRUE')
{
    echo "Registration is successful! Now you can enter to site. <a href='index.php'>Home page</a>";
}
else {
    echo "Error! You are not registered!";
}
?>