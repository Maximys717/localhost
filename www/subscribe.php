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
	$subscriber_id = $myrow2['id'];
        if (empty($myrow2['id'])) {
    // ���� ����� ��� ������ �� ������������
			exit("���� �� ��� �������� �������� ������ ������������������ �������������!");
            }
    }
else {
// ���������, ��������������� �� �������� 
    exit("���� �� ��� �������� �������� ������ ������������������ �������������!"); 
	}
// �������� ������������� �������� ����, ���� ����� ������
if (isset($_POST['id'])) {
	$the_followed_id = $_POST['id'];
	$id=$the_followed_id; // ����� �� �������������� �� ��������� �����
	}
// ����� ����, ���� ����� ������
if (isset($_POST['the_followed'])) {
	$the_followed = $_POST['the_followed'];
	}
// ����� ��������
$subscriber = $_SESSION['login'];
	$number = 1; // ��� ��� ��������
	// $date2 = date("Y-m-d H:i:s"); // ���� ����������
// ���� �� ��� ����������� ������, ���� ���, �� �������������
if (empty($subscriber_id) or empty($the_followed_id) or empty($number)) {
    exit ("�� ����� �� ��� ����������, ��������� ����� � ��������� ��� ����");
	}
// ������� � ���� ��������� 
    $result2 = mysql_query("INSERT INTO readers (subscriber_id, the_followed_id, number) VALUES ('$subscriber_id','$the_followed_id','$number')",$db);
// �������������� ������������
	echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$id."'></head><body>�����������, �� ����������� �� ��������� $the_followed! �� ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a href='page.php?id=".$id."'>������� ����.</a></body></html>";
?>