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
$idVenda = $_POST['idVenda'];


mysqli_query($connect, "UPDATE proposta SET situacao = '$var', id_bkoPend = 0 WHERE id = $idVenda");





echo json_encode(array('retorno' => "Retornou: Proposta atualizada"));


 ?>
