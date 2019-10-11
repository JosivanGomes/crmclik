<?php
require_once 'db_connect.php';

//Sessão
session_start();

//Verificação
if(!isset($_SESSION['logado'])):
  header('Location: index.php');
endif;

//Dados
$turno = $_POST['turnoVenda'];
$dtInstal = $_POST['instalacaoVenda'];
$idVenda = $_POST['idVenda'];


mysqli_query($connect, "UPDATE proposta SET situacao = 'APROVADO', id_bkoPend = 0, turnoinst = '$turno', data_instalacao = '$dtInstal' WHERE id = $idVenda");





echo json_encode(array('retorno' => "Retornou: Proposta atualizada"));


 ?>
