<?php
require_once 'db_connect.php';

//Sessão
session_start();

//Verificação
if(!isset($_SESSION['logado'])):
  header('Location: index.php');
endif;

//Dados
$id = $_SESSION['id_usuario'];
$sql = "SELECT * FROM operador WHERE id = '$id'";
$resultado = mysqli_query($connect, $sql);
$dados = mysqli_fetch_array($resultado);

$idvenda = $_POST['idVenda'];

if(isset($_POST['sitVenda'])):
  $situacao = $_POST['sitVenda'];
  if (!empty($situacao)):
    $sql = "UPDATE proposta SET situacao = '$situacao' WHERE id = '$idvenda'";
    mysqli_query($connect, $sql);
  endif;
endif;

if(isset($_POST['dtInst'])):
  $dataInst = $_POST['dtInst'];
  if (!empty($dataInst)):
    $sql = "UPDATE proposta SET data_instalacao = '$dataInst' WHERE id = $idvenda";
    mysqli_query($connect, $sql);
  endif;
endif;

if(isset($_POST['turno'])):
  $turnoInst = $_POST['turno'];
  if (!empty($turnoInst)):
    $sql = "UPDATE proposta SET turnoinst = '$turnoInst' WHERE id = $idvenda";
    mysqli_query($connect, $sql);
  endif;
endif;

if(isset($_POST['chmdinst'])):
  $chamadoInst = $_POST['chmdinst'];
  if (!empty($chamadoInst)):
    $sql = "UPDATE proposta SET chamadoinst = '$chamadoInst' WHERE id = $idvenda";
    mysqli_query($connect, $sql);
  endif;
endif;

if(isset($_POST['contrato'])):
  $contratoInst = $_POST['contrato'];
  if (!empty($contratoInst)):
    $sql = "UPDATE proposta SET contrato = '$contratoInst' WHERE id = $idvenda";
    mysqli_query($connect, $sql);
  endif;
endif;

if(isset($_POST['statusContrato'])):
  $contratostatus = $_POST['statusContrato'];
  if (!empty($contratostatus)):
    $sql = "UPDATE proposta SET sitctrt = '$contratostatus' WHERE id = $idvenda";
    mysqli_query($connect, $sql);
  endif;
endif;

$sql = "UPDATE proposta SET id_bkoPend = 0 WHERE id = $idvenda";
mysqli_query($connect, $sql);

if($situacao == "SEM CONTATO"):
  $sql = "UPDATE proposta SET id_bko = 0 WHERE id = $idvenda";
  mysqli_query($connect, $sql);
endif;

echo json_encode(array('retorno' => "Retornou: Proposta atualizada com sucesso!"));


 ?>
