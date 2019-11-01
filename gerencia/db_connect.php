<?php
//Conexão com o DB
$servername = "localhost";
$username = "root";
$password = "";
$db_name = "crmclik";

$connect = mysqli_connect($servername, $username, $password, $db_name);

if(mysqli_connect_error()):
  echo "Falha na conexão".mysqli_connect_error();
endif;
 ?>
