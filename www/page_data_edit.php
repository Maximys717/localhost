<?php
// ��� ��������� �������� �� �������. ������ � ��� �������� ������ ������������, ���� �� ��������� �� �����.
// ����� ����� ��������� �� � ����� ������ ���������!!!
session_start();
	
include ("bd.php"); // ���� bd.php ������ ���� � ��� �� �����, ��� � ��� ���������, ���� ��� �� ���, �� ������ �������� ����
			
	//id "�������" ��������� 
        if (isset($_GET['id']))
		{
		$id =$_GET['id'];
		} 
	//���� �� ������� id, �� ������ ������
		else
		{ 
		exit("�� ����� �� �������� ��� ���������!");
		}
	//���� id �� �����, �� ������ ������		
        if (!preg_match("|^[\d]+$|", $id))
		{
        exit("<p>�������� ������ �������! ��������� URL</p>"); 
        }
		
//���� ���������� ����� � ������ � �������, �� ���������, ������������� �� ���	
if (!empty($_SESSION['login']) and !empty($_SESSION['password']))
    {
        $login = $_SESSION['login'];
        $password = $_SESSION['password'];
        $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db);
        $myrow2 = mysql_fetch_array($result2); 
    //���� �� ������������� (����� �� ������� ����� ������������ �� ���� �� ������ ���������)
		if (empty($myrow2['id']))
        {
        exit("���� �� ��� �������� �������� ������ ������������������ �������������!");
        }
    }
	
	//���������, ��������������� �� ��������
        else 
		{
        exit("���� �� ��� �������� �������� ������ ������������������ �������������!"); 
		}
		
	//��������� ��� ������ ������������ � ������ id
        $result = mysql_query("SELECT * FROM users WHERE id='$id'",$db); 
        $myrow = mysql_fetch_array($result); 

//���� ������ �� ����������			
if (empty($myrow['login'])) 
{ 
exit("������������ �� ����������! �������� �� ��� ������.");
} 
?>
<html>
    <head>
        <title><?php echo $myrow['login']; ?> - �����</title>
    </head>
    <body>
		<?php print <<<HERE
	<!--// ������� ����-->
	| <a href='page.php?id=$_SESSION[id]'>��� ��������</a> | <a href='index.php'>������� ��������</a> | <a href='all_users.php'>������ �������������</a> | <a href='exit.php'>�����</a> |<br>
HERE;
		?>
	<h2>��������� ������������ "<?php echo $myrow['login']; ?>"</h2>
 
<?php

//���� ��������� ����������� ���������, �� ���������� �������� ������
if ($myrow['login'] == $login)
{


print <<<HERE
<form action='update_user.php' method='post'>
            ��� ����� <strong>$myrow[login]</strong>.<br> �������� �����:<br>
            <input name='login' type='text'>
            <input type='submit' name='submit' value='��������'>
            </form>
            <br>
<form action='update_user.php' method='post'>
            �������� ������:<br>
            <input name='password' type='password'>
            <input type='submit' name='submit' value='��������'>
            </form>
            <br>
<form action='update_user.php' method='post' enctype='multipart/form-data'>
            ��� ������:<br>
            <img alt='������' src='$myrow[avatar]'><br>
            ����������� ������ ���� ������� jpg, gif ��� png. �������� ������:<br>
            <input type="FILE" name="fupload">
            <input type='submit' name='submit' value='��������'>
            </form>
            <br>

HERE;

            }
?>
        </body>
    </html>