<?php
    session_start(); // ��������� ������. ����������� � ������ ��������
    include ("bd.php"); // ����������� � �����, ������� ���� ����, ���� � ��� ��� ���� ����������
	
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
    {
// ���� ���������� ����� � ������ � �������, �� ���������, ������������� �� ���
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db); 
    $myrow2 = mysql_fetch_array($result2); 
        if (empty($myrow2['id'])) {
    // ���� ����� ��� ������ �� ������������
			exit("���� �� ��� �������� �������� ������ ������������������ �������������!");
            }
    }
else {
// ���������, ��������������� �� �������� 
    exit("���� �� ��� �������� �������� ������ ������������������ �������������!"); 
	}

// �������� ������������� �������� ������ (����������)
if (isset($_POST['id'])) {
	$id = $_POST['id'];
	}

/* // ����� ����������
if (isset($_POST['author'])) {
	$author = $_POST['author'];
	}
*/

// �������� ����� ���������
if (isset($_POST['text'])) {
	$text = $_POST['text'];
	}
// ����� ������ 
$author = $_SESSION['login'];

	$date = date("H:i:s d/m/Y "); // ���� ����������
	$date2 = date("Y-m-d H:i:s"); // ���� ����������
// ���� �� ��� ����������� ������, ���� ���, �� �������������
if (empty($author) or empty($text) or empty($date)) {
    exit ("�� ����� �� ��� ����������, ��������� ����� � ��������� ��� ����");
	}
// ������� �������� �����
$text = stripslashes($text); 
$text = htmlspecialchars($text); //�������������� ������������ � �� HTML �����������
// ������� � ���� ��������� 
    $result2 = mysql_query("INSERT INTO messages (author, date, date2, text) VALUES ('$author','$date','$date2','$text')",$db);
// �������������� ������������
	echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$id."'></head><body>���� ��������� ��������! �� ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a href='page.php?id=".$id."'>������� ����.</a></body></html>";
?>