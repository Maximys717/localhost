<?php
    if (isset($_POST['login'])) 
	{ 
		$login = $_POST['login'];
		if ($login == '') 
		{ 
			unset($login);
		} 
	}
//������� ��������� ������������� ����� � ���������� $login, ���� �� ������, �� ���������� ����������


    if (isset($_POST['password'])) { 
		$password=$_POST['password']; 
		if ($password =='') { 
			unset($password);
		} 
	}
//������� ��������� ������������� ������ � ���������� $password, ���� �� ������, �� ���������� ����������

    if (empty($login) or empty($password)) //���� ������������ �� ���� ����� ��� ������, �� ������ ������ � ������������� ������
{
    exit ("Some fields are empty, please fill all of them!");
}

//���� ����� � ������ �������, �� ������������ ��, ����� ���� � ������� �� ��������, ���� �� ��� ���� ����� ������
    $login = stripslashes($login);
    $login = htmlspecialchars($login);
    $password = stripslashes($password);
    $password = htmlspecialchars($password);
//������� ������ �������
    $login = trim($login);
    $password = trim($password);

//��������� �������� �� ����� ������ � ������
if (strlen($login) < 3 or strlen($login) > 15) 
{
    exit ("Login must have min 3 or max 15 charsets.");
}
if (strlen($password) < 3 or strlen($password) > 15) 
{
    exit ("Password must have min 3 or max 15 charsets");
}

//���������, �������� �� ������������ �����������
if (!empty($_POST['fupload'])) 
{
    $fupload=$_POST['fupload'];    
	$fupload = trim($fupload);
    if ($fupload =='' or empty($fupload)) 
	{
        unset($fupload); // ���� ���������� $fupload �����, �� ������� ��
    }
}
//���� ���������� �� ���������� (������������ �� �������� �����������),
if (!isset($fupload) or empty($fupload) or $fupload =='')
{
    // �� ����������� ��� ������� �������������� �������� � �������� "��� �������"
    $avatar = "avatars/net-avatara.jpg"; //������ ���������� net-avatara.jpg ��� ����� � ����������
}

$password = md5($password); //������� ������
$password = strrev($password); //��� ���������� ������� ������

// ������������ � ����
    include ("bd.php"); //���� bd.php ������ ���� � ��� �� �����, ��� � ��� ���������, ���� ��� �� ���, �� ������ �������� ����

// �������� �� ������������� ������������ � ����� �� �������
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
	$avatar = "avatars/net-avatara.jpg"; //������ ���������� net-avatara.jpg ��� ����� � ����������
	}

// ���� ������ ���, �� ��������� ������
    $result2 = mysql_query ("INSERT INTO users (login,password,avatar) VALUES('$login','$password','$avatar')");
// ���������, ���� �� ������
    if ($result2=='TRUE')
	{
    echo "Registration is successful! Now you can enter to site. <a href='index.php'>Home page</a>";
	}
		else 
		{
		echo "Error! You are not registered!";
		}
?>