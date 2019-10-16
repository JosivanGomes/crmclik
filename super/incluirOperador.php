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
  $sql = "SELECT * FROM supervisor WHERE id = '$id'";
  $resultado = mysqli_query($connect, $sql);
  $dados = mysqli_fetch_array($resultado);
  $idSuper = $dados['id'];
  $qbrLinha = '\n';

  // Dados proposta
    //Nome
    if(isset($_POST['nome'])):
      $nomeOperador = $_POST['nome'];
      if (empty($nomeOperador)):
          echo json_encode(array('retorno' => 'Nome não incluido. Cadastro não efetuado!'));
      else:
        // Login
          if(isset($_POST['login'])):
            $loginOperador = $_POST['login'];
            if (empty($loginOperador)):
                echo json_encode(array('retorno' => 'Login não incluido. Cadastro não efetuado!'));
            else:
              // Senha
                if(isset($_POST['senha'])):
                  $senhaOperador = $_POST['senha'];
                  if (empty($senhaOperador)):
                      echo json_encode(array('retorno' => 'Senha não incluida. Cadastro não efetuado!'));
                  else:
                    mysqli_query($connect, "INSERT INTO operador (nome, login, senha, cargo, id_super)
                    VALUES ('$nomeOperador', '$loginOperador', md5('$senhaOperador'),'VENDEDOR', '$idSuper' )");
                    echo json_encode(array('retorno' => "Cadastro Efetuado com sucesso!
Repasse os seguintes dados ao seu novo operador:
Nome: $nomeOperador
Login: $loginOperador
Senha: $senhaOperador"));
                  endif;
                endif;
            endif;
          endif;
      endif;
    endif;

 ?>
