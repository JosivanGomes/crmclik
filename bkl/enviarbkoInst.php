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

  $sql = "UPDATE proposta SET situacao = 'Em Tratamento', id_bkoPend = $id WHERE id = $proposta";
  mysqli_query($connect, $sql);

  echo json_encode(array('retorno' => "Proposta selecionada para tratamento"));


 ?>
