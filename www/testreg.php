<?php
// ��� ��������� �������� �� �������. ������ � ��� �������� ������ ������������, ���� �� ��������� �� �����. ����� ����� ��������� �� � ����� ������ ���������!!!
session_start();

// ������� ��������� ������������� ����� � ���������� $login, ���� �� ������, �� ���������� ����������
    if (isset($_POST['login']))
	{ 
	$login=$_POST['login']; 
		if ($login == '') 
		{ 
		unset($login);
		} 
	} 
// ������� ��������� ������������� ������ � ���������� $password, ���� �� ������, �� ���������� ����������
    if (isset($_POST['password'])) 
	{ 
	$password=$_POST['password']; 
		if ($password =='') 
		{ 
		unset($password);
		} 
	}
//���� ������������ �� ���� ����� ��� ������, �� ������ ������ � ������������� ������
    if (empty($login) or empty($password)) 
{
    exit ("Some fields are empty, please fill all of them!");
}
// ���� ����� � ������ �������,�� ������������ ��, ����� ���� � ������� �� ��������, ���� �� ��� ���� ����� ������
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
//������� ������ �������
    $login = trim($login);
    $password = trim($password);

//$password = md5($password); //������� ������
$password = strrev($password); //��� ���������� ������� ������


// ���������� ���� bd.php ������ ���� � ��� �� �����, ��� � ��� ���������, ���� ��� �� ���, �� ������ �������� ������� � ����
	include ("bd.php"); 

// ��������� �� ���� ��� ������ � ������������ � ��������� ������� � �������
$result = mysql_query("SELECT * FROM users WHERE login='$login'",$db); // ��������� �� ���� ��� ������ � ������������ � ��������� �������
$myrow = mysql_fetch_array($result);
	if (empty($myrow['password']))
	{
//���� ������������ � ��������� ������� �� ����������
	exit ("Entered loggin is incorrect. <a href='index.php'>��������� �����!</a><br>");
	}
else
{
//���� ����������, �� ������� ������
	if ($myrow['password']==$password) 
	{
//���� ������ ���������, �� ��������� ������������ ������! ������ ��� ����������, �� �����!


    //nbsp; // ���� ������ ���������, �� ��������� ������������ ������! ������ ��� ����������, �� �����!
		
		$_SESSION['password']=$myrow['password'];
		$_SESSION['login']=$myrow['login'];

		$_SESSION['id']=$myrow['id']; // ��� ������ ����� ����� ������������, ��� �� � ����� "������ � �����" �������� ������������

	}
else 
{
//���� ������ �� �������
exit ("Entered password is incorrect. <a href='index.php'>��������� ������!</a><br>");
}
		
//����� �� ���������� ������ � ����, ��� ������������ �����.
//��������!!! ������� ��� �� ���� ����������, ��� ��� ������ �������� � ����� ��� ��������

	if ($_POST['save'] == 1) 
	{
// ���� ������������ �����, ����� ��� ������ ����������� ��� ������������ �����, �� ��������� � ����� ��� ��������
		setcookie("login", $_POST["login"], time()+9999999);
		setcookie("password", $_POST["password"], time()+9999999);
	}
}

// �������������� ������������ �� ������� ���������, ��� ��� � ������� �� ������� �����
echo "<html><head><meta http-equiv='Refresh' content='0; URL=index.php'></head></html>"; 
?>
