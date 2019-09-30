<?php
#Verifica se tem um email para pesquisa
    $connect = mysqli_connect("localhost", "root", "", "crmclik");

    #Recebe o Email Postado
    $login = mysqli_escape_string($connect, $_POST['login']);
    $senha = mysqli_escape_string($connect, $_POST['senha']);

    if(empty($login) or empty($senha)):
      echo json_encode(array('retorno' => 'Senha ou login não digitado!'));
    else:
      $sql = "SELECT login FROM supervisor WHERE login = '$login'";
      $resultado = mysqli_query($connect, $sql);

      if(mysqli_num_rows($resultado) > 0):
        $senha = md5($senha);
        $sql = "SELECT * FROM supervisor WHERE login = '$login' and senha = '$senha'";
        $resultado = mysqli_query($connect, $sql);
        if(mysqli_num_rows($resultado) > 0):
          echo json_encode(array('retorno' => 'Acesso ok'));
        else:
          echo json_encode(array('retorno' => 'Login e Senha não conferem!'));
        endif;
      endif;
    endif;





?>
