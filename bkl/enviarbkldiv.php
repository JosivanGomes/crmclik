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

  $proposta = $_POST['proposta'];
  $status = mysqli_query($connect, "SELECT * FROM proposta WHERE id = $proposta");
  $dadoSt = mysqli_fetch_array($status);

  $situacao = $dadoSt['situacao'];
  if ($situacao = "BACKLOG"):
    $sql = "UPDATE proposta SET situacao = 'TRATAMENTO BACKLOG', id_bkoPend = $id WHERE id = $proposta";
  else:
    $sql = "UPDATE proposta SET situacao = 'TRATAMENTO DIVERGENTE', id_bkoPend = $id WHERE id = $proposta";
  endif;

  mysqli_query($connect, $sql);

  echo json_encode(array('retorno' => "Proposta selecionada para tratamento"));


 ?>
