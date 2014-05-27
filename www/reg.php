<html>
   <head>
      <title>Registration</title>
   </head>
   <body>
    <h2>Registration</h2>
    <form action="save_user.php" method="post" enctype="multipart/form-data">
    <!--**** save_user.php - это адрес обработчика.  То есть, после нажатия на кнопку "Зарегистрироваться", данные из полей  отправятся на страничку save_user.php методом "post" ***** -->
<p>
    <label>Your login:<br></label>
    <input name="login" type="text" size="15" maxlength="15">
    </p>
<!--**** В текстовое поле (name="login" type="text") пользователь вводит свой логин ***** -->
<p>
    <label>Your password:<br></label>
    <input name="password" type="password" size="15" maxlength="15">
    </p>
<!--**** В поле для паролей (name="password" type="password") пользователь вводит свой пароль ***** -->

 <p>

        <label>Choose the avatar. Photo must be in JPG, JPEG, GIF or PNG format:<br></label>
        <input type="FILE" name="fupload">
 </p>

        <!-- В переменную fupload отправится изображение, которое выбрал пользователь -->

<p>
    <input type="submit" name="submit" value="Register">
<!--**** Кнопочка (type="submit") отправляет данные на страничку save_user.php ***** -->



</p></form>
   </body>
    </html>