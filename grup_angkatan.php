<?php


set_time_limit(0);
error_reporting("E_ALL ^ E_NOTICE");


echo "<option value=\"all\">Semua Grup</option>";
if ($_POST['stase'] == "M091" or $_POST['stase'] == "M101" or $_POST['stase'] == "M111" or $_POST['stase'] == "M113" or $_POST['stase'] == "M121") {
  echo "<option value=\"1\">Grup Senior</option>";
  echo "<option value=\"2\">Grup Yunior</option>";
}
