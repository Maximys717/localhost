<?php
    session_start(); // ��������� ������
    include ("bd.php"); //������������ � ����
	
if (!empty($_SESSION['login']) and !empty($_SESSION['password'])) {
//���� ���������� ����� � ������ � �������, �� ���������, ������������� �� ���
    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db); 
    $myrow2 = mysql_fetch_array($result2);
	$user_id = $_SESSION['id'];
		if (empty($myrow2['id'])) {
    // ������ ������������ �������. 
            exit("���� �� ��� �������� �������� ������ ������������������ �������������!");
            }
    }
else {
// ���������, ��������������� �� ��������
    exit("���� �� ��� �������� �������� ������ ������������������ �������������!"); 
	}
$id2 = $_SESSION['id']; // �������� ������������� ����� ��������

// �������� ����� GET ������ ������������� ������ ��������, ������� ����� �������
if (isset($_GET['id'])) {
	$id = $_GET['id'];
	}
// ����� ��������, ��� ��������
$result = mysql_query("SELECT subscriber_id FROM readers WHERE id='$id'",$db); 
$myrow = mysql_fetch_array($result);
// ���� ����� GET ������ ������������ ����� ������ ����� ������������� � ��� ��������� ������� ���������, ������� ���������� �� ���.

// ���� �������� ������� ������ �������������, �� ��������� � �������
if ($user_id == $myrow['subscriber_id']) {
	
	$result3 = mysql_query("SELECT the_followed_id FROM readers WHERE id='$id'",$db); 
	$myrow3 = mysql_fetch_array($result3);
	$id3 = $myrow3['the_followed_id'];
	
	$result4 = mysql_query("SELECT login FROM users WHERE id='$id3'",$db); 
	$myrow4 = mysql_fetch_array($result4);
	$the_followed = $myrow4['login'];

// ������� �������
$result = mysql_query ("DELETE FROM readers WHERE id = '$id' LIMIT 1");
// ���� ������� - �������������� �� ��������� ������������
	if ($result == 'true') {
        echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$id3."'></head><body>�� ������� ���������� �� $the_followed! �� ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a href='page.php?id=".$id3."'>������� ����.</a></body></html>";
        }
//���� �� �������, �� ��������������, �� ������ ��������� � �������
    else {
        echo "<html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$id3."'></head><body>������! �� ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a href='page.php?id=".$id3."'>������� ����.</a></body></html>";
		}
}
// ���� �������� ������� �� ���� �������������. ������, ���-�� ��������� ������� ���, ����� � �������� ������ �����-�� ������ �������������
else {
	exit("�� ��������� ������� ��������, ��������� �� ����!");
	}
?>