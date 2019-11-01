<?php
  //Conexão
  require_once 'db_connect.php';

  //Sessão
  session_start();

  //Botão enviar
  if(isset($_POST['btn-entrar'])):
    $erros = array();
    $login = mysqli_escape_string($connect, $_POST['login']);
    $senha = mysqli_escape_string($connect, $_POST['senha']);

    if(empty($login) or empty($senha)):
      $erros[] = "<li> Os campos Login e/ou Senha não foram preenchidos!</li>";
    else:
      $sql = "SELECT login FROM supervisor WHERE login = '$login'";
      $resultado = mysqli_query($connect, $sql);

      if(mysqli_num_rows($resultado) > 0):
        $senha = md5($senha);
        $sql = "SELECT * FROM supervisor WHERE login = '$login' and senha = '$senha'";
        $resultado = mysqli_query($connect, $sql);

        if(mysqli_num_rows($resultado) == 1):
          $dados = mysqli_fetch_array($resultado);
          if ($dados['cargo'] == "SUPER OP"):
              $_SESSION['logado'] = true;
              $_SESSION['id_usuario'] = $dados['id'];

                header('Location: super/super.php');
          else:
            $erros[] = "<li>Usuário e Senha não conferem!</li>";
          endif;

      else:
        $erros[] = "<li>Usuário não cadastrado!</li>";
      endif;

    endif;
  endif;

  endif;
?>

<!doctype html>
<html lang="pt-br">
  <head>
    <!-- Meta tags Obrigatórias -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

    <!--Css interno-->
    <link rel="stylesheet" href="estilo/estiloLogin.css">
    <title>Acompanhamento de vendas - Cli-K</title>

  </head>
  <body>
    <div class="text-center">
      <img src="imagens/LOGO.PNG" class="rounded" alt="Logo Cli-k" width="250" height="200">


      <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
        <h1 style="color: #007bff;">SUPERVISÃO</h1>
        <?php
          if(!empty($erros)):
            foreach ($erros as $erro):
              echo "<div class='alert alert-danger' role='alert'>
                      $erro
                    </div>";
            endforeach;
          endif;
         ?>
        <div class="form-group">
          <label for="exampleInputEmail1">Login:</label>
          <input type="text" class="form-control" name="login">
        </div>
        <div class="form-group">
          <label for="exampleInputPassword1">Senha:</label>
          <input type="password" class="form-control" name="senha">
        </div>

        <button type="submit" class="btn btn-primary" name="btn-entrar">Entrar</button>


      </form>

    </div>

    <nav class="navbar navbar-expand" style="position: fixed; bottom: 0; right: 0;">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="index.php">OPERAÇÃO</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="loginGerencia.php">GERÊNCIA</a>
        </li>
      </ul>
    </nav>



    <!-- JavaScript (Opcional) -->
    <script src"https://code.jquery.com/jquery-3.4.1.js"></script>
    <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
  </body>
</html>
