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

if(isset($_POST['statusContrato'])):
  $contratostatus = $_POST['statusContrato'];
  if (!empty($contratostatus)):
    $sql = "UPDATE proposta SET sitctrt = '$contratostatus' WHERE id = $idvenda";
    mysqli_query($connect, $sql);
  endif;
endif;

$sql = "UPDATE proposta SET id_bkoPend = 0 WHERE id = $idvenda";
mysqli_query($connect, $sql);



echo json_encode(array('retorno' => "Retornou: Proposta atualizada com sucesso!"));


 ?>
