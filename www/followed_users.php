<?php
// ��� ��������� �������� �� �������. ������ � ��� �������� ������ ������������, ���� �� ��������� �� �����. ����� ����� ��������� �� � ����� ������ ���������!!!
session_start();

include ("bd.php"); // ���� bd.php ������ ���� � ��� �� �����, ��� � ��� ���������, ���� ��� �� ���, �� ������ �������� ����
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
{

// ���� ���������� ����� � ������ � �������, �� ���������, ������������� �� ���

    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db); 
    $myrow2 = mysql_fetch_array($result2); 
	$id = $myrow2['id'];
	
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
	// �������� ���������� ������ � �������������

if (isset($_GET['id'])) {
	$got_id = $_GET['id'];
	}

// ����� ��������, ��� ��������
$result5 = mysql_query("SELECT subscriber_id,the_followed_id FROM readers WHERE subscriber_id='$got_id'",$db); 
$myrow5 = mysql_fetch_array($result5);
// ���� ����� GET ������ ������������ ����� ������ ����� ������������� � ��� ��������� ������� ���������, ������� ���������� �� ���.

// ���� �������� ����, �� ������� ������, 
if ($got_id == $myrow5['subscriber_id'] OR $got_id == $myrow5['the_followed_id']) {
	
	$result4 = mysql_query("SELECT login FROM users WHERE id='$got_id'",$db); 
	$myrow4 = mysql_fetch_array($result4);

	$got_login = $myrow4['login'];
	}
	// ���� ���, �� �����
	else {
	$result4 = mysql_query("SELECT login FROM users WHERE id='$got_id'",$db); 
	$myrow4 = mysql_fetch_array($result4);

	$got_login = $myrow4['login'];
	exit("������������ $got_login ���� ������ �� ������! <html><head><meta http-equiv='Refresh' content='5; URL=page.php?id=".$got_id."'></head><body>�� ������ ���������� ����� 5 ���. ���� �� ������ �����, �� <a href='page.php?id=".$got_id."'>������� ����.</a></body></html>");
	}
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		
		if (empty($myrow2['id']))
        {
    //���� ������ ������������ �� �����
        exit("���� �� ��� �������� �������� ������ ������������������ �������������!");
        }
}
else 
{
//���������, ��������������� �� ��������
exit("���� �� ��� �������� �������� ������ ������������������ �������������!"); 
}
?>
<html>
    <head>
        <title>������ �������� "<?php echo $got_login; ?>"</title>
    </head>
    <body>
		<?php print <<<HERE
<!--// ������� ���� -->
    | <a href='page.php?id=$_SESSION[id]'>��� ��������</a> | <a href='index.php'>������� ��������</a> | <a href='all_users.php'>������ �������������</a> | <a href='exit.php'>�����</a> |<br>
HERE;
		?>
    <h2>������ �������� "<?php print ("<a href='page.php?id=$got_id'>$got_login</a>"); ?>"</h2>

<?php

$result7 = mysql_query("SELECT readers.the_followed_id,users.id,users.login FROM readers,users WHERE readers.subscriber_id='$got_id' AND users.id=readers.the_followed_id",$db); // ��������� ����� � ������������� ������������� 
$myrow7 = mysql_fetch_array($result7);
	
	do {
	// ������� �� � �����
		
		printf("<a href='page.php?id=%s'>%s</a><br>",$myrow7['the_followed_id'],$myrow7['login']);
		}
		while($myrow7 = mysql_fetch_array($result7));

//$result = mysql_query("SELECT login,id FROM users WHERE id='$got_the_followed_id' ORDER BY login",$db); // ��������� ����� � ������������� ������������� 
//$myrow = mysql_fetch_array($result);
//    do {
	// ������� �� � ����� 
//		printf("<a href='page.php?id=%s'>%s</a><br>",$myrow['id'],$myrow['login']);
//		}
//		while($myrow = mysql_fetch_array($result));

?>

    </body>
</html>