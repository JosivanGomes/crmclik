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

   <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
     <script type="text/javascript">
       google.charts.load('current', {'packages':['bar']});
       google.charts.setOnLoadCallback(drawStuff);


       function drawStuff() {
         var data = new google.visualization.arrayToDataTable([

           <?php

           echo "['', ''],";
           $super = $dados['id'];

            $sql = mysqli_query($connect, "SELECT * FROM operador WHERE id_super = $super AND cargo = 'VENDEDOR'");
            $linha = mysqli_fetch_array($sql);
            do{
              $operadoresA[] = array('idOperador' => $linha['id'],'nomeOperador' => $linha['login']);
            }while($linha = mysqli_fetch_assoc($sql));
            $controle = count($operadoresA);

            for ($i=0; $i < $controle ; $i++) {
              $id_OP = $operadoresA[$i]['idOperador'];
              $nome_OP = $operadoresA[$i]['nomeOperador'];


              $sql2 = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $id_OP");
              $linha2 = mysqli_fetch_array($sql2);
              $ponto = 0;
              do{
                $sit = $linha2['situacao'];
                if (($sit == 'APROVADO') OR ($sit == 'ATIVO')):
                  $ponto += $linha2['ponto'];
                endif;

              }while($linha2 = mysqli_fetch_assoc($sql2));


              echo "['$nome_OP', $ponto],";
            }

            ?>



         ]);

         var options = {
           width: 600,
           height: 200,
           vAxis: {format: ''},
           legend: { position: 'none' },
           chart: {
             title: 'Ativos + Aprovados' },
           bar: { groupWidth: "95%" }

         };

         var chart = new google.charts.Bar(document.getElementById('top_x_div'));
         // Convert the Classic options to Material options.
         chart.draw(data, google.charts.Bar.convertOptions(options));
       };
     </script>
     <script type="text/javascript">
       google.charts.load('current', {'packages':['bar']});
       google.charts.setOnLoadCallback(drawStuff2);


       function drawStuff2() {
         var data = new google.visualization.arrayToDataTable([

           <?php

           echo "['', ''],";
           $super = $dados['id'];

            $sql = mysqli_query($connect, "SELECT * FROM operador WHERE id_super = $super AND cargo = 'VENDEDOR'");
            $linha = mysqli_fetch_array($sql);
            do{
              $operadoresA2[] = array('idOperador' => $linha['id'],'nomeOperador' => $linha['login']);
            }while($linha = mysqli_fetch_assoc($sql));
            $controle = count($operadoresA2);

            for ($i=0; $i < $controle ; $i++) {
              $id_OP = $operadoresA2[$i]['idOperador'];
              $nome_OP = $operadoresA2[$i]['nomeOperador'];


              $sql2 = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $id_OP");
              $linha2 = mysqli_fetch_array($sql2);
              $ponto = 0;
              do{
                $sit = $linha2['situacao'];
                if ($sit == 'ATIVO'):
                  $ponto += $linha2['ponto'];
                endif;

              }while($linha2 = mysqli_fetch_assoc($sql2));


              echo "['$nome_OP', $ponto],";
            }

            ?>



         ]);

         var options = {
           width: 600,
           height: 200,
           vAxis: {format: ''},
           legend: { position: 'none' },
           chart: {
             title: 'Ativos' },
           bar: { groupWidth: "95%" }

         };

         var chart = new google.charts.Bar(document.getElementById('top_y_div'));
         // Convert the Classic options to Material options.
         chart.draw(data, google.charts.Bar.convertOptions(options));
       };
     </script>

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



         <ul class="navbar-nav" style="margin-left: 390px">


           <li class="nav-item dropdown">
             <a class="nav-link" href="super.php">Home</a>
           </li>

           <li class="nav-item dropdown">
             <a class="nav-link" href="vendas.php">Vendas</a>
           </li>


           <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Operação</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="cancelado.php">Cancelados</a>
                <a class="dropdown-item" href="pendenciasuper.php">Pendências
                  <span class="badge badge-danger">
                    <?php
                      $supervisor = $dados['id'];
                      $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$supervisor' AND sitctrt = 'N-OK' AND situacao = 'APROVADO' OR situacao = 'CHAMADO' OR situacao = 'CHECK OK'") or print mysql_error();
                      echo mysqli_num_rows($sql);
                    ?>
                  </span>
                </a>
                <a class="dropdown-item" href="dashboard.php">DashBoard</a>
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

   <main style="padding: 10px;">
     <section style="display: flex; flex-flow: row wrap; justify-content: space-around; align-items: top; margin-bottom: 20px;">
       <section id="bruto" style="width: 100%;">
         <?php
         $super = $dados['id'];

          $sql = mysqli_query($connect, "SELECT * FROM operador WHERE id_super = $super AND cargo = 'VENDEDOR'");
          $linha = mysqli_fetch_array($sql);
          do{
            $operadores[] = array('idOperador' => $linha['id'],'nomeOperador' => $linha['login'] );
          }while($linha = mysqli_fetch_assoc($sql));
          $controle = count($operadores);

          echo "<div class=\"table-responsive-xl\">";
          echo "<table class=\"table table-hover text-center text-truncate\">";
          echo "<thead class=\"thead-dark\">";
          echo "<tr>";
          echo "<th>VENDEDOR</th>";
          echo "<th>ND's</th>";
          echo "<th>TV</th>";
          echo "<th>MÓVEL</th>";
          echo "<th>PONTUAÇÃO</th>";
          echo "<th>COMERCIAL</th>";
          echo "<th>%</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          for ($i=0; $i < $controle ; $i++) {
            echo "<tr>";
            $id_OP = $operadores[$i]['idOperador'];
            $nome_OP = $operadores[$i]['nomeOperador'];
            echo "<th scope=\"row\">$nome_OP</th>";
            $mes = date('m');


            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND (situacao = 'APROVADO' OR situacao = 'ATIVO') AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $qtd = mysqli_num_rows($sql);
            echo "<td scope=\"row\">$qtd</td>";

            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND (situacao = 'APROVADO' OR situacao = 'ATIVO') AND tv != 'NULL' AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $qtd = mysqli_num_rows($sql);
            echo "<td scope=\"row\">$qtd</td>";

            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND (situacao = 'APROVADO' OR situacao = 'ATIVO') AND movel != 'NULL' AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $qtd = mysqli_num_rows($sql);
            echo "<td scope=\"row\">$qtd</td>";

            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND (situacao = 'APROVADO' OR situacao = 'ATIVO') AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $linhaSUM = mysqli_fetch_array($sql);

            $pontuacao = 0;
            do {
              $ponto = $linhaSUM['ponto'];
              $pontuacao = $pontuacao + $ponto;

            } while ($linhaSUM = mysqli_fetch_array($sql));
            echo "<td scope=\"row\">$pontuacao</td>";

            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND situacao = 'COMERCIAL' AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $linhaSUM = mysqli_fetch_array($sql);

            $pontuacao = 0;
            do {
              $ponto = $linhaSUM['ponto'];
              $pontuacao = $pontuacao + $ponto;

            } while ($linhaSUM = mysqli_fetch_array($sql));
            echo "<td scope=\"row\">$pontuacao</td>";

            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND (situacao = 'APROVADO' OR situacao = 'ATIVO' OR situacao ='COMERCIAL') AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $linhaSUM = mysqli_fetch_array($sql);

            $ponto = 0;
            $pontoC = 0;
            do {
              $situacao = $linhaSUM['situacao'];
              if ($situacao == 'COMERCIAL'):
                $pontoC += $linhaSUM['ponto'];
              else:
                $ponto += $linhaSUM['ponto'];
              endif;

              if ($ponto != 0):
                $pontuacao = ($pontoC * 100)/$ponto;
              else:
                $pontuacao = 0;
              endif;

              $pontuacao = number_format($pontuacao, 2, ',', ' ');


            } while ($linhaSUM = mysqli_fetch_array($sql));
            echo "<td scope=\"row\">$pontuacao</td>";


            };
            echo "</tr>";



          echo "</tbody>";


          echo "</table>";
          echo "</div>";

          ?>

       </section>
       <section style="margin-right: 5px;">
        <div id="top_x_div"></div>
       </section>
     </section>

     <section style="display: flex; flex-flow: row wrap; justify-content: space-around; align-items: top; margin-bottom: 40px;">
       <section id="bruto" style="width: 100%;">
         <?php
         $super = $dados['id'];

          $sql = mysqli_query($connect, "SELECT * FROM operador WHERE id_super = $super AND cargo = 'VENDEDOR'");
          $linha = mysqli_fetch_array($sql);
          do{
            $operadores2[] = array('idOperador' => $linha['id'],'nomeOperador' => $linha['login'] );
          }while($linha = mysqli_fetch_assoc($sql));
          $controle = count($operadores2);

          echo "<div class=\"table-responsive-xl\">";
          echo "<table class=\"table table-hover text-center text-truncate\">";
          echo "<thead class=\"thead-dark\">";
          echo "<tr>";
          echo "<th>VENDEDOR</th>";
          echo "<th>ND's</th>";
          echo "<th>TV</th>";
          echo "<th>MÓVEL</th>";
          echo "<th>PONTUAÇÃO</th>";
          echo "</tr>";
          echo "</thead>";
          echo "<tbody>";

          for ($i=0; $i < $controle ; $i++) {
            echo "<tr>";
            $id_OP = $operadores2[$i]['idOperador'];
            $nome_OP = $operadores2[$i]['nomeOperador'];
            echo "<th scope=\"row\">$nome_OP</th>";
            $mes = date('m');


            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND situacao = 'ATIVO' AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $qtd = mysqli_num_rows($sql);
            echo "<td scope=\"row\">$qtd</td>";

            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND situacao = 'ATIVO' AND tv != 'NULL' AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $qtd = mysqli_num_rows($sql);
            echo "<td scope=\"row\">$qtd</td>";

            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND situacao = 'ATIVO' AND movel != 'NULL' AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $qtd = mysqli_num_rows($sql);
            echo "<td scope=\"row\">$qtd</td>";

            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$id_OP' AND situacao = 'ATIVO' AND MONTH(data_venda) = '$mes'") or print mysql_error();
            $linhaSUM = mysqli_fetch_array($sql);

            $pontuacao = 0;
            do {
              $ponto = $linhaSUM['ponto'];
              $pontuacao = $pontuacao + $ponto;

            } while ($linhaSUM = mysqli_fetch_array($sql));
            echo "<td scope=\"row\">$pontuacao</td>";

            };
            echo "</tr>";



          echo "</tbody>";


          echo "</table>";
          echo "</div>";

          ?>

       </section>
       <section style="margin-right: 5px;">
        <div id="top_y_div"></div>
       </section>
     </section>
     <section style="display: flex;">
       <table class="table table-bordered" style="width: 300px; text-align: center;">

           <?php
           $mes = date('m');
           $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes") or print mysql_error();
           $retorno = mysqli_num_rows($sql);
           echo "<tr>
             <td class=\"table-active\">Geral</td>
             <td>$retorno</td>
           </tr>";

           $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao = 'ATIVO'") or print mysql_error();
           $retorno = mysqli_num_rows($sql);
           echo "<tr>
             <td class=\"bg-primary\">Ativo</td>
             <td>$retorno</td>
           </tr>";

           $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao = 'APROVADO'") or print mysql_error();
           $retorno = mysqli_num_rows($sql);
           echo "<tr>
             <td class=\"bg-success\">Aprovado</td>
             <td>$retorno</td>
           </tr>";

           $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao = 'CANCELADO'") or print mysql_error();
           $retorno = mysqli_num_rows($sql);
           echo "<tr>
             <td class=\"bg-danger\">Cancelado</td>
             <td>$retorno</td>
           </tr>";

           $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao = 'COMERCIAL'") or print mysql_error();
           $retorno = mysqli_num_rows($sql);
           echo "<tr>
             <td class=\"table-danger\">Cancelado Com</td>
             <td>$retorno</td>
           </tr>";

           $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao = 'TECNICO'") or print mysql_error();
           $retorno = mysqli_num_rows($sql);
           echo "<tr>
             <td class=\"table-warning\">Cancelado Téc</td>
             <td>$retorno</td>
           </tr>";

           $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao = 'BACKLOG'") or print mysql_error();
           $retorno = mysqli_num_rows($sql);
           echo "<tr>
             <td class=\"table-dark\">Backlog</td>
             <td>$retorno</td>
           </tr>";

           $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao != 'BACKLOG' AND situacao != 'TECNICO' AND situacao != 'COMERCIAL' AND situacao != 'CANCELADO' AND situacao != 'APROVADO' AND situacao != 'ATIVO'") or print mysql_error();
           $retorno = mysqli_num_rows($sql);
           echo "<tr>
             <td class=\"table-info\">Pendências</td>
             <td>$retorno</td>
           </tr>
         ";

            ?>

        </table>
          <table class="table table-dark" style="text-align: center; width: 500px; margin-left: 5px;">
             <tr>
               <th scope="col" colspan="3">META EQUIPE</th>
             </tr>

             <tr>
               <th>ATIVOS</th>
               <th>TV</th>
               <th>MÓVEL</th>
             </tr>
             <tr>
               <th class="bg-danger">
                 <?php
                  $metaAtivo = 80000;
                  echo "$metaAtivo";
                  ?>
                </th>
               <th class="bg-info">
                 <?php
                  $metaTv = 140;
                  echo "$metaTv";
                  ?>
               </th>
               <th class="bg-warning">
                 <?php
                  $metaMov = 100;
                  echo "$metaMov";
                  ?>
               </th>
             </tr>
             <tr>
               <th colspan="3">Realizado</th>
             </tr>
             <tr>
               <?php

               $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND situacao = 'ATIVO' AND MONTH(data_venda) = '$mes'") or print mysql_error();
               $linhaSUM = mysqli_fetch_array($sql);

               $pontuacao = 0;
               do {
                 $ponto = $linhaSUM['ponto'];
                 $pontuacao = $pontuacao + $ponto;

               } while ($linhaSUM = mysqli_fetch_array($sql));
               echo "<td class=\"bg-danger\">$pontuacao</td>";

               $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = '$super' AND situacao = 'ATIVO' AND tv != 'NULL' AND MONTH(data_venda) = '$mes'") or print mysql_error();
               $qtdTv = mysqli_num_rows($sql);
               echo "<td class=\"bg-info\">$qtdTv</td>";

               $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $super AND situacao = 'ATIVO' AND movel != 'NULL' AND MONTH(data_venda) = '$mes'") or print mysql_error();
               $qtdMov = mysqli_num_rows($sql);
               echo "<td class=\"bg-warning\">$qtdMov</td>";

               if ($pontuacao != 0):
                 $pontuacao = $pontuacao*100/$metaAtivo;
               endif;

               $pontuacao = number_format($pontuacao, 2, ',', ' ');

               if ($qtdTv != 0):
                 $qtdTv = $qtdTv*100/$metaTv;
               endif;

               $qtdTv = number_format($qtdTv, 2, ',', ' ');

               if ($qtdMov != 0):
                 $qtdMov = $qtdMov*100/$metaMov;
               endif;

               $qtdMov = number_format($qtdMov, 2, ',', ' ');

               echo "<tr style=\"background-color: #993399;\">
                      <td>$pontuacao %</td>
                      <td>$qtdTv %</td>
                      <td>$qtdMov %</td>
                     </tr>";
                ?>
             </tr>
         </table>

         <table class="table table-dark" style="width: 200px; margin-left: 5px; text-align: center;">
           <tr>
             <th style="background-color: red;">Comercial</th>
             <th style="background-color: #831d1c;">Técnico</th>
           </tr>
           <tr>
             <?php
              $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao = 'APROVADO' OR situacao = 'ATIVO'") or print mysql_error();
              $vendas = mysqli_num_rows($sql);

              $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao = 'COMERCIAL'") or print mysql_error();
              $comercial = mysqli_num_rows($sql);

              $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = $supervisor AND MONTH(data_venda) = $mes AND situacao = 'TECNICO'") or print mysql_error();
              $tecnico = mysqli_num_rows($sql);

              $comercial = $comercial*100/$vendas;
              $comercial = number_format($comercial, 2, ',', ' ');
              $tecnico = $tecnico*100/$vendas;
              $tecnico = number_format($tecnico, 2, ',', ' ');

              echo "<td>$comercial %</td>
                    <td>$tecnico %</td>";

              ?>

           </tr>
         </table>
     </section>
     <section>
       <table class="table table-sm table-dark" style="text-align: center;">
        <thead>
          <tr>
            <th scope="col">DATA</th>
            <th scope="col">PONTUAÇÃO</th>
            <th scope="col">ATIVOS</th>
            <th scope="col">COMERCIAL</th>
            <th scope="col">TECNICO</th>
            <th scope="col">BACKLOG</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $qtdDiaMes = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));
          for ($i = 0; $i <= $qtdDiaMes; $i++){
            $data_incio = mktime(0, 0, 0, date('m') , $i , date('Y'));
            $data_apr = date('d/m/Y',$data_incio);
            $data_sql = date('Y-m-d',$data_incio);
            $hoje = date('Y-m-d');

            $mesPassado = date('m', strtotime('-1 months'));


            if ($i == 0):
              echo "<tr>
                <th scope=\"row\">Backlog</th>";

                $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                   (situacao = 'ATIVO' OR situacao = 'APROVADO' OR situacao = 'COMERCIAL' OR
                     situacao = 'TECNICO' OR situacao = 'BACKLOG') AND MONTH(data_venda) = '$mesPassado'") or print mysql_error();
                $linhaSUM = mysqli_fetch_array($sql);

                $pontuacao = 0;
                do {
                  $ponto = $linhaSUM['ponto'];
                  $pontoM = $linhaSUM['pontoM'];

                  $pontuacao = $pontuacao + $ponto + $pontoM;

                } while ($linhaSUM = mysqli_fetch_array($sql));
                  echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";

                  $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                     situacao = 'ATIVO' AND MONTH(data_venda) = '$mesPassado'") or print mysql_error();
                  $linhaSUM = mysqli_fetch_array($sql);

                  $pontuacao = 0;
                  do {
                    $ponto = $linhaSUM['ponto'];
                    $pontoM = $linhaSUM['pontoM'];

                    $pontuacao = $pontuacao + $ponto + $pontoM;

                  } while ($linhaSUM = mysqli_fetch_array($sql));
                    echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";

                    $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                       situacao = 'COMERCIAL' AND MONTH(data_venda) = '$mesPassado'") or print mysql_error();
                    $linhaSUM = mysqli_fetch_array($sql);

                    $pontuacao = 0;
                    do {
                      $ponto = $linhaSUM['ponto'];
                      $pontoM = $linhaSUM['pontoM'];

                      $pontuacao = $pontuacao + $ponto + $pontoM;

                    } while ($linhaSUM = mysqli_fetch_array($sql));
                      echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";

                      $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                         situacao = 'TECNICO' AND MONTH(data_venda) = '$mesPassado'") or print mysql_error();
                      $linhaSUM = mysqli_fetch_array($sql);

                      $pontuacao = 0;
                      do {
                        $ponto = $linhaSUM['ponto'];
                        $pontoM = $linhaSUM['pontoM'];

                        $pontuacao = $pontuacao + $ponto + $pontoM;

                      } while ($linhaSUM = mysqli_fetch_array($sql));
                        echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";

                        $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                           situacao = 'BACKLOG' AND MONTH(data_venda) = '$mesPassado'") or print mysql_error();
                        $linhaSUM = mysqli_fetch_array($sql);

                        $pontuacao = 0;
                        do {
                          $ponto = $linhaSUM['ponto'];
                          $pontoM = $linhaSUM['pontoM'];

                          $pontuacao = $pontuacao + $ponto + $pontoM;

                        } while ($linhaSUM = mysqli_fetch_array($sql));
                          echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";


                echo "</tr>";

            elseif ($i <= $qtdDiaMes):
              echo "<tr>
                <th scope=\"row\">$data_apr</th>";

              $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                 (situacao = 'ATIVO' OR situacao = 'APROVADO' OR situacao = 'COMERCIAL' OR
                   situacao = 'TECNICO' OR situacao = 'BACKLOG') AND data_venda = '$data_sql'") or print mysql_error();
              $linhaSUM = mysqli_fetch_array($sql);

              $pontuacao = 0;
              do {
                $ponto = $linhaSUM['ponto'];
                $pontoM = $linhaSUM['pontoM'];

                $pontuacao = $pontuacao + $ponto + $pontoM;

              } while ($linhaSUM = mysqli_fetch_array($sql));
                echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";

                $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                   situacao = 'ATIVO' AND data_venda = '$data_sql'") or print mysql_error();
                $linhaSUM = mysqli_fetch_array($sql);

                $pontuacao = 0;
                do {
                  $ponto = $linhaSUM['ponto'];
                  $pontoM = $linhaSUM['pontoM'];

                  $pontuacao = $pontuacao + $ponto + $pontoM;

                } while ($linhaSUM = mysqli_fetch_array($sql));
                  echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";

                  $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                     situacao = 'COMERCIAL' AND data_venda = '$data_sql'") or print mysql_error();
                  $linhaSUM = mysqli_fetch_array($sql);

                  $pontuacao = 0;
                  do {
                    $ponto = $linhaSUM['ponto'];
                    $pontoM = $linhaSUM['pontoM'];

                    $pontuacao = $pontuacao + $ponto + $pontoM;

                  } while ($linhaSUM = mysqli_fetch_array($sql));
                    echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";

                    $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                       situacao = 'TECNICO' AND data_venda = '$data_sql'") or print mysql_error();
                    $linhaSUM = mysqli_fetch_array($sql);

                    $pontuacao = 0;
                    do {
                      $ponto = $linhaSUM['ponto'];
                      $pontoM = $linhaSUM['pontoM'];

                      $pontuacao = $pontuacao + $ponto + $pontoM;

                    } while ($linhaSUM = mysqli_fetch_array($sql));
                      echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";

                      $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                         situacao = 'BACKLOG' AND data_venda = '$data_sql'") or print mysql_error();
                      $linhaSUM = mysqli_fetch_array($sql);

                      $pontuacao = 0;
                      do {
                        $ponto = $linhaSUM['ponto'];
                        $pontoM = $linhaSUM['pontoM'];

                        $pontuacao = $pontuacao + $ponto + $pontoM;

                      } while ($linhaSUM = mysqli_fetch_array($sql));
                        echo "<td class=\"table-light\" style=\"color: black;\">$pontuacao</td>";


              echo "</tr>";
            endif;

              } while($linha = mysqli_fetch_assoc($sql));

           ?>


        </tbody>
      </table>
     </section>
   </main>















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
