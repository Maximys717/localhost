<?php
// ��� ��������� �������� �� �������. ������ � ��� �������� ������ ������������, ���� �� ��������� �� �����. ����� ����� ��������� �� � ����� ������ ���������!!!
session_start();

include ("bd.php"); // ���� bd.php ������ ���� � ��� �� �����, ��� � ��� ���������, ���� ��� �� ���, �� ������ �������� ����
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
{
//���� ���������� ����� � ������ � �������, �� ���������, ������������� �� ���

    $login = $_SESSION['login'];
    $password = $_SESSION['password'];
    $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db); 
    $myrow2 = mysql_fetch_array($result2); 
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
        <title>������ �������������</title>
    </head>
    <body>
		<?php print <<<HERE
<!--// ������� ���� -->
    | <a href='page.php?id=$_SESSION[id]'>��� ��������</a> | <a href='index.php'>������� ��������</a> | <a href='exit.php'>�����</a> |<br>
HERE;
		?>
    <h2>������ �������������</h2>

<?php

$result = mysql_query("SELECT login,id FROM users ORDER BY login",$db); // ��������� ����� � ������������� ������������� 
$myrow = mysql_fetch_array($result);
    do
    {
// ������� �� � ����� 
    printf("<a href='page.php?id=%s'>%s</a><br>",$myrow['id'],$myrow['login']);
    }
    while($myrow = mysql_fetch_array($result));

?>

    </body>
</html>