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
		// $session_user_login = $login
        $password = $_SESSION['password'];
        $result2 = mysql_query("SELECT id FROM users WHERE login='$login' AND password='$password'",$db);
        $myrow2 = mysql_fetch_array($result2);
		$subscriber_id = $myrow2['id'];
    //���� �� ������������� (����� �� ������� ����� ������������ �� ���� �� ������ ���������)
		if (empty($myrow2['id']))
        {
        exit("���� �� ��� �������� �������� ������ ������������������ �������������!");
        }
    }
	
	// ���������, ��������������� �� ��������
        else 
		{
        exit("���� �� ��� �������� �������� ������ ������������������ �������������!"); 
		}
		
// ��������� ��� ������ ������������ � ������ id
    $result = mysql_query("SELECT * FROM users WHERE id='$id'",$db); 
    $myrow = mysql_fetch_array($result);
	$owner_login = $myrow['login'];
	$followed_id = $myrow['id']; // ��� ��� ���������� followed,������ ��� ������ ��������� ������������ �� ����� ��������
	
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
		<?php 
		if ($myrow['login'] == $login) {
			print <<<HERE
		<!--// ������� ����-->
		| <a href='page_data_edit.php?id=$_SESSION[id]'>�������� ��������������� ������</a> | <a href='index.php'>������� ��������</a> | <a href='all_users.php'>������ �������������</a> | <a href='exit.php'>�����</a> |<br>
HERE;
			}
		else {
		print <<<HERE
		<!--// ������� ����-->
		| <a href='page.php?id=$_SESSION[id]'>��� ��������</a> | <a href='index.php'>������� ��������</a> | <a href='all_users.php'>������ �������������</a> | <a href='exit.php'>�����</a> |<br>
HERE;
		}
		?>
	<!-- <h2>����� ������������ "<?php echo $myrow['login']; ?>"</h2> -->
	<h2>����� ������������ "<?php print ("<a href='page.php?id=$followed_id'>$owner_login</a>"); ?>"</h2>
		<?php print <<<HERE
            <img alt='������' src='$myrow[avatar]'><br>
HERE;
	?>

<!-- ///////////////////////////////////////  ������� ������� � ������������ ��������� � �������� ////////////////////////////////////////////// -->
	
<?php

if ($myrow['login'] == $login) {

// ������ ���������� ��������
$tmp2 = mysql_query("SELECT * FROM readers WHERE subscriber_id='$subscriber_id'",$db); 
$numbers2 = mysql_fetch_array($tmp2); // ��������� ���������� ���������
	if (!empty($numbers2['id'])) {
    
	// ������� ��� ��������� � �����
		do {
		$followed_users_num = $followed_users_num + 1;
		}
			while($numbers2 = mysql_fetch_array($tmp2)); 
	}
    else {
// ���� ������ �� ������
		$followed_users_num = 0;
        }
}
		else {
		// ������ ���������� ��������
			$tmp2 = mysql_query("SELECT * FROM readers WHERE subscriber_id='$followed_id'",$db); 
			$numbers2 = mysql_fetch_array($tmp2); // ��������� ���������� ���������
				if (!empty($numbers2['id'])) {
    
				// ������� ��� ��������� � �����
					do {
					$followed_users_num = $followed_users_num + 1;
					}
						while($numbers2 = mysql_fetch_array($tmp2)); 
				}
				else {
			// ���� ������ �� ������
					$followed_users_num = 0;
					}
}
		
// ������ ���������� ���������
$tmp3 = mysql_query("SELECT * FROM readers WHERE the_followed_id='$followed_id'",$db); 
$numbers3 = mysql_fetch_array($tmp3); // ��������� ���������� ���������
	if (!empty($numbers3['id'])) {
    
	// ������� ��� ��������� � �����
		do {
		$read_users_num = $read_users_num + 1;
		}
			while($numbers3 = mysql_fetch_array($tmp3)); 
	}
    else {
// ���� ������ �� ������
		$read_users_num = 0;
        }

////////////////////////////////////////////////////////// ������� �� ����� ����� �����������/�������� //////////
if ($myrow['login'] == $login) {

	print <<<HERE
	| <a href='followed_users.php?id=$_SESSION[id]'>��������: $followed_users_num</a> | <a href='read_users.php?id=$_SESSION[id]'>��������: $read_users_num</a> |<br>
HERE;
}
else {

	print <<<HERE
	| <a href='followed_users.php?id=$followed_id'>��������: $followed_users_num</a> | <a href='read_users.php?id=$followed_id'>��������: $read_users_num</a> |<br>
HERE;
}

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
?>

<?php

// ���� ��������� ����������� ���������, ��

if ($myrow['login'] == $login) {

// �� ������� ����� ��� �������� ��������� �� ���� �����
	print <<<HERE
            <form action='post.php' method='post'>
            <br>
            <h2>��������� ��������� �� ���� �����:</h2>
            <textarea cols='70' rows='2'name='text'></textarea><br>
            <!-- <input type='hidden' name='author' value='$myrow[login]'> -->
            <input type='hidden' name='id' 	   value='$myrow[id]'>
            <input type='submit' name='submit' value='���������'>
            </form>
HERE;

//////////////////////// � ��������� ������ (����� ���������) ///////////////////////////////////////////////////////////////// 


	// � ������ ������ ������� ��������� ��� ����� �� ���� ���� - �������� � �������� ��������
	//$tmp = mysql_query("SELECT readers.the_followed_id,users.id,users.login,messages.* FROM readers,users,messages WHERE readers.subscriber_id='$followed_id' AND users.id=readers.the_followed_id AND (messages.author=users.login OR messages.author='$owner_login') ORDER BY messages.id DESC",$db);
	
	// � ������ ������ ������� ��������� ��� ������ ��������
	$tmp = mysql_query("SELECT readers.the_followed_id,users.id,users.login,messages.* FROM readers,users,messages WHERE readers.subscriber_id='$followed_id' AND users.id=readers.the_followed_id AND messages.author=users.login ORDER BY messages.id DESC",$db);
	
	// � ������ ������ ������� ��������� ��� ������ ��������� ��������
	//$tmp = mysql_query("SELECT * FROM messages WHERE author='$login' ORDER BY id DESC",$db); 
	$messages = mysql_fetch_array($tmp); // ��������� ��������� ������������, ��������� �� �������������� � �������� �������, �.�. ����� ����� ��������� ����� ������
	
	if (!empty($messages['id'])) {
    
	// ������� ��� ��������� � �����
		do {
			$author = $messages['author'];
			$result4 = mysql_query("SELECT avatar,id FROM users WHERE login='$author'",$db); //��������� ������ ������ 
			$myrow4 = mysql_fetch_array($result4);
			if (!empty($myrow4['avatar'])) { //���� �������� ���, �� ������� ����������� (����� ����� ������������ ��� ����� �������)
		$avatar = $myrow4['avatar'];
		}
		else {
			$avatar = "avatars/net-avatara.jpg";
			}
			if ($author == $owner_login) { // ������� "�������" ������ ���� ������ �� ��� ����������, ��� �� ��������� �������
				
				printf("
				<table>
					<tr>
            
					<td><a href='page.php?id=%s'><img alt='������' src='%s'></a></td>

					<td>�����: <a href='page.php?id=%s'>%s</a><br>
						����: %s<br>
						���������:<br>

							%s<br>
								<a href='drop_post.php?id=%s'>�������</a>

					</td>
					</tr>
					</table><br>
						",$myrow4['id'],$avatar,$myrow4['id'],$author,$messages['date'],$messages['text'],$messages['id']);
				}
				else {
					printf("
					<table>
						<tr>
            
						<td><a href='page.php?id=%s'><img alt='������' src='%s'></a></td>

						<td>�����: <a href='page.php?id=%s'>%s</a><br>
							����: %s<br>
							���������:<br>

								%s<br>
									

						</td>
						</tr>
						</table><br>
							",$myrow4['id'],$avatar,$myrow4['id'],$author,$messages['date'],$messages['text'],$messages['id']);
				}
					//������� ���� ��������� 
		}
	
			while($messages = mysql_fetch_array($tmp));
	}
    else {
//���� ��������� �� �������
		echo "��������� ���";
        }
}
else {
///////////////////////////////////// ��� ��������� �� ��������� /////////////////////////////////////////////////////////////////////////////////////
//���� ��������� �����, �� ������� ����� ���������, ���������� ��� ��������� � ���, ���� �� ������, � ����������� �����������/���������� ��/�� ����

///////////////////////// ��������� �������� �� �������� �� ��������� ��������� ��� ��� ////////////////////////////////////////////////////////////////////

	$result4 = mysql_query("SELECT id FROM readers WHERE subscriber_id='$subscriber_id' AND the_followed_id='$followed_id'",$db);
    $myrow4 = mysql_fetch_array($result4); 
    // ���� ���, �� ��������� ��� �����������
		if (empty($myrow4['id'])) {
			print <<<HERE
				<form action='subscribe.php' method='post'>
					<br>
					<h3>������ ����������� �� ����� ������������ $owner_login?</h3>
					<input type='hidden' name='the_followed' value='$myrow[login]'>
					<input type='hidden' name='id' 	   		 value='$myrow[id]'> <!-- ����� �� ����, �� ���� ������������� -->
					<input type='submit' name='submit' 		 value='�����������'>
					
HERE;
		}
	// ���� ��������, �� ��������� ��� ����������
		else {
		// $id = $myrow4['id']; ����� �� ������ ��������
			print <<<HERE
				<form action='drop_subscribe.php' method='get'>
					<br>
					<h3>������ ���������� �� ����� ������������ $owner_login?</h3>
					<input type='hidden' name='id' 	   		 value='$myrow4[id]'>
					<!-- <input type='hidden' name='owner_login'  value='$owner_login'> -->
					<!-- <input type='hidden' name='owner_id' 	 value='$myrow[id]'> -->
					<input type='submit' name='submit' 		 value='����������'>
					
HERE;
		}


///////////////////////////////// ������� ��� ��������� ��� ��������� //////////////////////////////////////////////////////////////////////////////


print <<<HERE
	<h2>��������� ������������ "$owner_login"</h2>
HERE;

$tmp = mysql_query("SELECT readers.the_followed_id,users.id,users.login,messages.* FROM readers,users,messages WHERE readers.subscriber_id='$followed_id' AND users.id=readers.the_followed_id AND (messages.author=users.login OR messages.author='$owner_login') ORDER BY messages.id DESC",$db);


	//$tmp = mysql_query("SELECT * FROM messages WHERE author='$owner_login' ORDER BY id DESC",$db); 
	$messages = mysql_fetch_array($tmp); // ��������� ��������� ������������, ��������� �� �������������� � �������� �������, �.�. ����� ����� ��������� ����� ������
	if (!empty($messages['id'])) {
    
	// ������� ��� ��������� � �����
		do {
			$author = $messages['author'];
			$result4 = mysql_query("SELECT avatar,id FROM users WHERE login='$author'",$db); //��������� ������ ������ 
			$myrow4 = mysql_fetch_array($result4);
			if (!empty($myrow4['avatar'])) { //���� �������� ���, �� ������� ����������� (����� ����� ������������ ��� ����� �������)
		$avatar = $myrow4['avatar'];
		}
		else {
			$avatar = "avatars/net-avatara.jpg";
			}
		printf("
			<table>
				<tr>
            
				<td><a href='page.php?id=%s'><img alt='������' src='%s'></a></td>

				<td>�����: <a href='page.php?id=%s'>%s</a><br>
					����: %s<br>
					���������:<br>

						%s<br>
							
				</td>
				</tr>
				</table><br>
					",$myrow4['id'],$avatar,$myrow4['id'],$author,$messages['date'],$messages['text'],$messages['id']);
				//������� ���� ��������� 
		}
	
			while($messages = mysql_fetch_array($tmp)); 
	}
    else {
//���� ��������� �� �������
		echo "��������� ���";
        }

		
	}
?>
    </body>
</html>