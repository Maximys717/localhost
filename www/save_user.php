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

/////////////// AVATAR //////////////////////////////////////////////////

if (isset($_FILES['fupload']['name'])) //������������ �� ����������
{
if (empty($_FILES['fupload']['name']))
{
//���� ���������� ������ (������������ �� �������� �����������), �� ����������� ��� ������� �������������� �������� � �������� "��� �������"
    $avatar = "avatars/net-avatara.jpg"; //������ ���������� net-avatara.jpg ��� ����� � ����������
}

else 
{
//����� - ��������� ����������� ������������ ��� ����������
    $path_to_90_directory = 'avatars/'; //�����, ���� ����� ����������� ��������� �������� � �� ������ �����
                
    if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES['fupload']['name'])) //�������� ������� ��������� �����������
    {             
        $filename = $_FILES['fupload']['name'];
        $source = $_FILES['fupload']['tmp_name'];
        $target = $path_to_90_directory . $filename;
    //�������� ��������� � ����� $path_to_90_directory 
		move_uploaded_file($source, $target); 
            //���� �������� ��� � ������� gif, �� ������� ����������� � ���� �� �������. ���������� ��� ������������ ������
				if(preg_match('/[.](GIF)|(gif)$/', $filename)) 
				{
                $im = imagecreatefromgif($path_to_90_directory.$filename); 
                }
            //���� �������� ��� � ������� png, �� ������� ����������� � ���� �� �������. ���������� ��� ������������ ������
				if(preg_match('/[.](PNG)|(png)$/', $filename)) {
					$im = imagecreatefrompng($path_to_90_directory.$filename);
					}
            //���� �������� ��� � ������� jpg, �� ������� ����������� � ���� ��    �������. ���������� ��� ������������ ������
                if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/',    $filename)) {
                    $im = imagecreatefromjpeg($path_to_90_directory.$filename);
                    }

    // �������� ����������� ����������� � ��� ����������� ������ ����� � ����� www.codenet.ru

// �������� �������� 90x90
    // dest - �������������� ����������� 
    // w - ������ ����������� 
    // ratio - ����������� ������������������ 

// ���������� 90x90. ����� ��������� � ������ ������.
$w = 90;

// ������ �������� ����������� �� ������
    // ��������� ����� � ���������� ��� �������
    $w_src = imagesx($im); //��������� ������
    $h_src = imagesy($im); //��������� ������ �����������
    // ������ ������ ���������� ��������
    // ����� ������ truecolor!, ����� ����� ����� 8-������ ���������
        $dest = imagecreatetruecolor($w,$w);
nbsp;   // �������� ���������� ��������� �� x, ���� ���� �������������� 
        if ($w_src>$h_src) 
            imagecopyresampled($dest, $im, 0, 0,
                round((max($w_src,$h_src)-min($w_src,$h_src))/2),
                    0, $w, $w, min($w_src,$h_src), min($w_src,$h_src)); 
        // �������� ���������� �������� �� y, 
        // ���� ���� ������������ (���� ����� ���� ���������) 
        if ($w_src<$h_src) 
            imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w,
                min($w_src,$h_src), min($w_src,$h_src)); 
        // ���������� �������� �������������� ��� ������� 
        if ($w_src==$h_src) 
            imagecopyresampled($dest, $im, 0, 0, 0, 0, $w, $w, $w_src, $w_src); 
                                            
$date=time(); //��������� ����� � ��������� ������.

// ��������� ����������� ������� jpg � ������ �����, ������ ����� ������� �����. �������, ����� � �������� �� ���� ���������� ����.
    imagejpeg($dest, $path_to_90_directory.$date.".jpg"); 
// ������  ������ jpg? �� �������� ����� ���� ����� + ������������ ������������ gif �����������, ������� ��������� ������������. �� ����� ������� ������ ��� �����������, ����� ����� ����� ��������� �����-�� ��������.

// ������� � ���������� ���� �� �������.
$avatar = $path_to_90_directory.$date.".jpg"; 
$delfull = $path_to_90_directory.$filename; 
    // ������� �������� ������������ �����������, �� ��� ������ �� �����. ������� ���� - �������� ���������.
	unlink ($delfull); 
            }
            else {
            // � ������ �������������� �������, ������ ��������������� ���������
                exit ("������ ������ ���� � ������� <strong>JPG,GIF ��� PNG</strong>");
            }
        //����� �������� �������� � ���������� ���������� $avatar ������ ����������� ���
            }

			
//$password = md5($password); //������� ������
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
// ���� ������ ���, 
/*
if (isset($fupload)) 
{ 
$avatar=$fupload; 
}
	else 
	{ 
	$avatar = "avatars/net-avatara.jpg"; //������ ���������� net-avatara.jpg ��� ����� � ����������
	}
*/
// �� ��������� ������
    $result2 = mysql_query ("INSERT INTO users (login,password,avatar) VALUES('$login','$password','$avatar')");
}
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