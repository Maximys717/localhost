<?php
  $i = 0;
  $i++;
  $cfg['Servers'][$i]['host'] = 'localhost';
  $cfg['Servers'][$i]['extension'] = 'mysqli';
  $cfg['Servers'][$i]['connect_type'] = 'tcp';
  $cfg['Servers'][$i]['compress'] = false;
  $cfg['Servers'][$i]['auth_type'] = 'config';
  $cfg['Servers'][$i]['user'] = 'root';
  $cfg['Servers'][$i]['password'] = '1'; /* Пароль пользователя root. */

#  $cfg['Servers'][$i]['controlhost'] = 'localhost';
#  $cfg['Servers'][$i]['controluser'] = 'pma'; /* Имя созданного вами только что пользователя для доступа к расширенным таблицам. */
#  $cfg['Servers'][$i]['controlpass'] = 'пароль'; /* А здесь его пароль. */
#  $cfg['Servers'][$i]['pmadb'] = 'phpmyadmin';
#  $cfg['Servers'][$i]['bookmarktable'] = 'pma_bookmark';
#  $cfg['Servers'][$i]['relation'] = 'pma_relation';
#  $cfg['Servers'][$i]['table_info'] = 'pma_table_info';
#  $cfg['Servers'][$i]['table_coords'] = 'pma_table_coords';
#  $cfg['Servers'][$i]['pdf_pages'] = 'pma_pdf_pages';
#  $cfg['Servers'][$i]['column_info'] = 'pma_column_info';
#  $cfg['Servers'][$i]['history'] = 'pma_history';
#  $cfg['Servers'][$i]['tracking'] = 'pma_tracking';
#  $cfg['Servers'][$i]['designer_coords'] = 'pma_designer_coords';
#  $cfg['Servers'][$i]['userconfig'] = 'pma_userconfig';
#  $cfg['Servers'][$i]['recent'] = 'pma_recent';
#  $cfg['Servers'][$i]['table_uiprefs'] = 'pma_table_uiprefs';
?>
