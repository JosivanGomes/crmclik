<?php
  //Conexão
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





 ?>
<!DOCTYPE html>
<html lang="pt-br">
 <head>
   <!-- Meta tags Obrigatórias -->
   <meta charset="utf-8">
   <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

   <script src="https://code.jquery.com/jquery-3.4.1.js"></script>


   <!--FONTES-->
   <script src="https://kit.fontawesome.com/d215fc05a6.js" crossorigin="anonymous"></script>


   <!-- Bootstrap CSS -->
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

   <!--Css Interno-->
   <link rel="stylesheet" href="estilo/menuGeral.css">


   <title>Acompanhamento do dia</title>
 </head>
 <body>
   <div class="usuarioID">
     <?php
         echo 'Logado como: '.$dados["login"];
     ?>
   </div>

   <header>
     <nav class="navbar navbar-expand-lg">
       <a class="navbar-brand">
         <img src="../imagens/LOGO.png" width="30" height="30" class="rounded-circle">
         <p>Cli-K</p>
       </a>


         <ul class="navbar-nav" style="margin-left: 430px">

           <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Operadores</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="ranking.php">Ranking</a>
                <a class="dropdown-item" href="#">Outra ação</a>
                <a class="dropdown-item" href="#">Algo mais aqui</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" data-target=".bd-example-modal-lg">
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target=".bd-example-modal-sm">Cadastrar novo operador</button>
                </a>
              </div>
           </li>

           <li class="nav-item">
             <a class="nav-link" href="logout.php">Sair</a>
           </li>

         </ul>

     </nav>
   </header>

   <div class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="novoOperador">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <form style="padding: 20px;">
          <div class="form-group">
            <label for="exampleInputEmail1">Nome Completo: </label>
            <input type="text" class="form-control" id="nomeOperador" placeholder="Nome Operador">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Login: </label>
            <input type="text" class="form-control" id="loginOperador" placeholder="Login Operador">
          </div>

          <div class="form-group">
            <label for="exampleInputEmail1">Senha: </label>
            <input type="password" class="form-control" id="senhaOperador">
          </div>

          <button type="button" class="btn btn-primary" data-toggle="modal" onclick="incluiOperador()">Enviar</button>

          <div id="resultado">

          </div>

        </form>
      </div>
    </div>
   </div>





   <!--TELA PRINCIPAL-->


   <script language="javascript">

             var nomeO = $("#nomeOperador");
             var loginO = $("#loginOperador");
             var senhaO = $("#senhaOperador");
             function incluiOperador(){
               $.ajax({
                  url : "incluirOperador.php",
                  type : 'post',
                  data : {
                       nome : nomeO.val(),
                       login : loginO.val(),
                       senha : senhaO.val()
                     },
                  success: function(data) {
                    console.log(data);
                    data = $.parseJSON(data);
                    alert(data.retorno);
                    $("#nomeOperador").val("");
                    $("#loginOperador").val("");
                    $("#senhaOperador").val("");
                    $("#novoOperador").modal("hide");




                }

              });

            }
  </script>


      <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
      <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    </body>
   </html>
