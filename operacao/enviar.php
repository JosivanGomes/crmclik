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



// Dados base
  $dataVenda = date("Y-m-d");
  $vendedor = $dados["id"];
  $situacao = "CADASTRO OK";

  // Dados proposta
    if(isset($_POST['tvplano'])):
      $tv = $_POST['tvplano'];
      if (empty($tv)):
          $tv = "NULL";
      endif;
    endif;

    if(isset($_POST['ptvplano'])):
      $pttv = $_POST['ptvplano'];
      if (empty($pttv)):
          $pttv = 0;
      endif;
    endif;

    if(isset($_POST['netplano'])):
      $net = $_POST['netplano'];
      if (empty($net)):
          $net = "NULL";
      endif;
    endif;

    if(isset($_POST['fixoplano'])):
      $fixo = $_POST['fixoplano'];
      if (empty($fixo)):
          $fixo = "NULL";
      endif;
    endif;

    if(isset($_POST['movelplano'])):
      $movel = $_POST['movelplano'];
      if (empty($movel)):
          $movel = "NULL";
      endif;
    endif;

    if(isset($_POST['depenmov'])):
      $depMvl = $_POST['depenmov'];
      if (empty($depMvl)):
          $depMvl = 0;
      endif;
    endif;

    if(isset($_POST['obsvenda'])):
      $obs = $_POST['obsvenda'];
      if (empty($obs)):
          $obs = "NULL";
      endif;
    endif;

    if(isset($_POST['precoPlano'])):
      $preco = $_POST['precoPlano'];
      if (empty($preco)):
          $preco = 0.00;
      endif;
    endif;

    if(isset($_POST['pontoPlano'])):
      $ponto = $_POST['pontoPlano'];
      if (empty($ponto)):
          $ponto = 0;
      endif;
    endif;

    if(isset($_POST['pontoPlanoM'])):
      $pontoM = $_POST['pontoPlanoM'];
      if (empty($pontoM)):
          $pontoM = 0;
      endif;
    endif;


  // Dados Cliente

    $cidade = $_POST['cidadeCliente'];
    $cpfCliente = $_POST['cpfCliente'];
    $nomeCliente = $_POST['nomeCliente'];

    if(isset($_POST['fixoCliente'])):
      $telfixo = $_POST['fixoCliente'];
      if (empty($telfixo)):
          $telfixo = "NULL";
      endif;
    endif;

    if(isset($_POST['movelCliente'])):
      $telmovel = $_POST['movelCliente'];
      if (empty($telmovel)):
          $telmovel = "NULL";
      endif;
    endif;

    if(isset($_POST['fone2Cliente'])):
      $telfone = $_POST['fone2Cliente'];
      if (empty($telfone)):
          $telfone = "NULL";
      endif;
    endif;



  //echo json_encode(array('tv' => "$cpfCliente"));



  // CPF CLIENTE

  $con = mysqli_connect("localhost", "root", "", "crmclik");
  $sql = mysqli_query($con, "SELECT * FROM cliente WHERE cpf = '{$cpfCliente}'") or print mysql_error();
  if(mysqli_num_rows($sql)>0):
      mysqli_query($con, "UPDATE cliente SET telfixo='$telfixo', telmovel='$telmovel', tel3='$telfone' WHERE CPF='$cpfCliente'");


      mysqli_query($con,"INSERT INTO `proposta`(`data_venda`, `situacao`, `tv`, `pt_adc_tv`, `internet`, `telefone`, `movel`, `depen_movel`, `observacao`, `preco`, `ponto`, `pontoM`, `id_vendedor`, `cpf_cliente`) VALUES ('$dataVenda','$situacao','$tv','$pttv','$net','$fixo','$movel', '$depMvl','$obs','$preco','$ponto', $pontoM,'$vendedor','$cpfCliente')");
      echo json_encode(array('retorno' => 'Cliente atualizado, nova proposta efetuada com sucesso!'));
  else:
      mysqli_query($con, "INSERT INTO cliente (cpf, nome, cidade, telfixo, telmovel, tel3) VALUES ('$cpfCliente', '$nomeCliente', '$cidade', '$telfixo', '$telmovel', '$telfone')");

      mysqli_query($con,"INSERT INTO `proposta`(`data_venda`, `situacao`, `tv`, `pt_adc_tv`, `internet`, `telefone`, `movel`, `depen_movel`, `observacao`, `preco`, `ponto`, `pontoM`, `id_vendedor`, `cpf_cliente`) VALUES ('$dataVenda','$situacao','$tv','$pttv','$net','$fixo','$movel', '$depMvl','$obs','$preco','$ponto', $pontoM,'$vendedor','$cpfCliente')");

      echo json_encode(array('retorno' => 'Novo Cliente e nova proposta efetuada com sucesso!'));
  endif;

  // CADASTRAR NOVA VENDA


// Perform queries
  #mysqli_query($connect,"SELECT * FROM `teste`");
  #mysqli_query($connect,"INSERT INTO `teste` (`nome`) VALUES ('$vendedor')");

  #mysqli_close($connect);

  #header('Location: /operacao.php');
 ?>
