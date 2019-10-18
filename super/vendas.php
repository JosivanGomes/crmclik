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

   <header style="margin-bottom: 17px;">
     <nav class="navbar navbar-expand-lg">
       <a class="navbar-brand">
         <img src="../imagens/LOGO.png" width="30" height="30" class="rounded-circle">
         <p>Cli-K</p>
       </a>


         <ul class="navbar-nav" style="margin-left: 430px">

           <li class="nav-item dropdown">
             <a class="nav-link" href="super.php">Home</a>
           </li>

           <li class="nav-item dropdown">
             <a class="nav-link" href="vendas.php">Vendas</a>
           </li>


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

   <form style="margin: 10px; padding: 20px; background-color: #778899;">
    <div class="form-row">
      <div class="form-group col-md-4">
        <label class="mr-sm-2">Situação</label>
        <select class="custom-select mr-sm-2" id="situacao">
          <option value="GERAL">Geral</option>
          <option value="ATIVO">Ativo</option>
          <option value="APROVADO">Aprovado</option>
          <option value="CANCELADO">Cancelado</option>
          <option value="TECNICO">Cancelado Téc</option>
          <option value="COMERCIAL">Cancelado Com</option>
          <option value="BACKLOG">Backlog</option>
          <option value="PENDENCIAS">Pendencias</option>
        </select>
      </div>
      <div class="form-group col-md-4">
        <label class="mr-sm-2">Vendedor</label>
        <select class="custom-select mr-sm-2" id="vendedor">
          <option value="TODOS">Todos</option>
          <?php
            $supervisor = $dados['id'];
            $sql = mysqli_query($connect, "SELECT * FROM operador WHERE id_super = $supervisor AND cargo = 'VENDEDOR'");
            $linha = mysqli_fetch_array($sql);

            do{
              $idOperador = $linha['id'];
              $nomeOperador = $linha['login'];
              $lista1[] = array('id' => $idOperador, 'nome' => $nomeOperador);
            }while ($linha = mysqli_fetch_assoc($sql));
            $contador = count($lista1);
            for ($i = 0; $i < $contador; $i++){
              $idOperador2 = $lista1[$i]['id'];
              $nomeOperador2 = $lista1[$i]['nome'];
              echo "<option value=\"$idOperador2\">$nomeOperador2</option>";
            };

           ?>
        </select>
      </div>
      <div class="form-group col-md-2">
        <label for="inputEmail4">Data Inicial</label>
        <input type="date" class="form-control" id="dataI">
      </div>
      <div class="form-group col-md-2">
        <label for="inputEmail4">Data Fim</label>
        <input type="date" class="form-control" id="dataF">
      </div>
      <div style="width: 100%; text-align: center;">
        <a class="btn btn-dark" style="align: center; color: green;" onclick="pesquisar()">Pesquisar</a>
      </div>
    </div>
  </form>



  <?php
  echo "<div id=\"telaPrincipal\">


  </div> ";
   ?>


   <script>

     function pesquisar(){
       if ($("#dataI").val() <= $("#dataF").val()){

         if (($("#dataI").val() == '') && ($("#dataF").val() != '')){
              alert("Para filtrar por data preencha os dois campos!")
            }else{
              $.ajax({
                 url : "pesquisaSuper.php",
                 type : 'post',
                 data : {
                      situacao : $("#situacao").val(),
                      vendedor : $("#vendedor").val(),
                      data_i : $("#dataI").val(),
                      data_f : $("#dataF").val(),
                    },
                 success: function(data) {
                   console.log(data);
                   data = $.parseJSON(data);
                   $("#telaPrincipal").append(data.retorno);

               }

             });

            }


       }else{
         alert("A data inicial não pode ser maior que a data Fim!")
       }

     }
   </script>


   <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.1.1.min.js"></script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 </body>
</html>
