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

  $sitVenda = $_POST['situacao'];
  $venda = $_POST['vendedor'];
  $dataI = $_POST['data_i'];
  $dataF = $_POST['data_f'];



    echo json_encode(
      array('retorno'
      =>

      "

        <h1>Testando esta porra!</h1>



     "




    ));







 ?>
