<?php
require_once 'db_connect.php';

//Sessão
session_start();

//Verificação
if(!isset($_SESSION['logado'])):
  header('Location: index.php');
endif;

//Dados
$var = $_POST['sitVenda'];
$cham = $_POST['chmdinst'];
$idVenda = $_POST['idVenda'];


mysqli_query($connect, "UPDATE proposta SET situacao = '$var', chamadoinst = '$cham',id_bkoPend = 0 WHERE id = $idVenda");





echo json_encode(array('retorno' => "Retornou: Alterado com sucesso!"));


 ?>
