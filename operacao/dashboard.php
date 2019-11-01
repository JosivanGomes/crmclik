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
  $sql = "SELECT * FROM operador WHERE id = '$id'";
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

   <script type="text/javascript">
    window.onload = function(){
    mAtual();mAtualV();mAtualVsql(1);
    }
  </script>


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


         <ul class="navbar-nav" style="margin-left: 460px;">

           <li class="nav-item">
             <a class="nav-link" href="operacao.php">Minhas Vendas</a>
           </li>


           <li class="nav-item" style="margin-right: 5px;">
             <div class="btn-group" role="group">
                <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  DashBoard
                </button>
                <div class="dropdown-menu">
                  <button class="dropdown-item" onclick="mAtual();mAtualV();mAtualVsql(1)">Mês Atual</button>
                  <button class="dropdown-item" onclick="passado();mPassadoV();mPassadoVsql(1)">Mês Passado</button>
                  <button class="dropdown-item" onclick="antepassado();mAntepassadoV();mAntepassadoVsql(1)">Antes</button>
                </div>
              </div>
           </li>

           <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ExemploModalCentralizado">
             Nova Venda
           </button>

           <li class="nav-item">
             <a class="nav-link" href="logout.php">Sair</a>
           </li>

         </ul>

     </nav>
   </header>




   <!--TELA PRINCIPAL-->

      <div id="grafico" style="margin-left: 20px; margin-top: 20px; margin-bottom: 20px; float: left;">
        <div id="top_x_div" style="height:300px;"></div>
      </div>

    <div id="grafico2" style="margin-right: 20px; margin-top: 20px; margin-bottom: 20px; float: right;">
      <div id="top_y_div" style="height:300px;"></div>
    </div>


      <div id="vendasDiv" style="float: left;">

      </div>
      <div id="vendasDivp2">

      </div>






   <!-- JavaScript (Opcional) -->



<!--MÊS ATUAL-->
   <?php

      $myAtual = date("m/Y");
      $mAtual = date("m");
      $operador = $dados["id"];

      $ativo = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $ativoTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $ativoMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttAtivo = mysqli_num_rows($ativo);
      $ttAtivoTv = mysqli_num_rows($ativoTv);
      $ttAtivoMov = mysqli_num_rows($ativoMov);


      $somaAtivo = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Ativo FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmAtv = mysqli_fetch_assoc($somaAtivo);
      $somaAtv = $linhasmAtv['ponto_Ativo'];
      if (empty($somaAtv)) {
          $somaAtv = 0;
      };

      $somaAtivoM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_AtivoM FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmAtvM = mysqli_fetch_assoc($somaAtivoM);
      $somaAtvM = $linhasmAtvM['ponto_AtivoM'];
      if (empty($somaAtvM)) {
          $somaAtvM = 0;
      };


      $aprovado = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $aprovadoTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $aprovadoMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttAprovado = mysqli_num_rows($aprovado);
      $ttAprovadoTv = mysqli_num_rows($aprovadoTv);
      $ttAprovadoMov = mysqli_num_rows($aprovadoMov);


      $somaAprovado = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Aprovado FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmApvd = mysqli_fetch_assoc($somaAprovado);
      $somaApvd = $linhasmApvd['ponto_Aprovado'];
      if (empty($somaApvd)) {
          $somaApvd = 0;
      };

      $somaAprovadoM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_AprovadoM FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmApvdM = mysqli_fetch_assoc($somaAprovadoM);
      $somaApvdM = $linhasmApvdM['ponto_AprovadoM'];
      if (empty($somaApvdM)) {
          $somaApvdM = 0;
      };


      $cancelado = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual");
      $canceladoTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $canceladoMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttCancelado = mysqli_num_rows($cancelado);
      $ttCanceladoTv = mysqli_num_rows($canceladoTv);
      $ttCanceladoMov = mysqli_num_rows($canceladoMov);


      $somaCancelado = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Cancel FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual");
      $linhasmCanc = mysqli_fetch_assoc($somaCancelado);
      $somaCancel = $linhasmCanc['ponto_Cancel'];
      if (empty($somaCancel)) {
          $somaCancel = 0;
      };

      $somaCanceladoM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_CancelM FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual");
      $linhasmCancM = mysqli_fetch_assoc($somaCanceladoM);
      $somaCancelM = $linhasmCancM['ponto_CancelM'];
      if (empty($somaCancelM)) {
          $somaCancelM = 0;
      };

      $bklg = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual");
      $bklgTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $bklgMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttbklg = mysqli_num_rows($bklg);
      $ttbklgTv = mysqli_num_rows($bklgTv);
      $ttbklgMov = mysqli_num_rows($bklgMov);


      $somabklg = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Bko FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual");
      $linhasmbklg = mysqli_fetch_assoc($somabklg);
      $somabk = $linhasmbklg['ponto_Bko'];
      if (empty($somabk)) {
          $somabk = 0;
      };

      $somabklgM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_BkoM FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual");
      $linhasmbklgM = mysqli_fetch_assoc($somabklgM);
      $somabkM = $linhasmbklgM['ponto_BkoM'];
      if (empty($somabkM)) {
          $somabkM = 0;
      };

      $geral = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND MONTH(data_instalacao) = $mAtual");
      $geralTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $geralMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttGeral = mysqli_num_rows($geral);
      $ttGeralTv = mysqli_num_rows($geralTv);
      $ttGeralMov = mysqli_num_rows($geralMov);


      echo "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
       <script type=\"text/javascript\">

         google.charts.load('current', {'packages':['bar']});
         google.charts.setOnLoadCallback(mAtual());

         function mAtual() {
          var data = new google.visualization.arrayToDataTable([
             ['Move', 'Pontos'],
             [\"Ativo\", $somaAtv],

             [\"Aprovado\", $somaApvd],
             [\"Canc Comércial\", $somaCancel],
             [\"BackLog\", $somabk]

           ]);

           var options = {
             width: 450,
             legend: { position: 'none' },
             chart: {
               title: 'Acompanhamento',
               subtitle: 'Mês: $myAtual' },
             axes: {
               x: {
                 0: { side: 'top', label: 'Propostas'} // Top x-axis.
               }
             },
             bar: { groupWidth: \"90%\" }
           };

           var chart = new google.charts.Bar(document.getElementById('top_x_div'));
           // Convert the Classic options to Material options.
           chart.draw(data, google.charts.Bar.convertOptions(options));


           var data = new google.visualization.arrayToDataTable([
              ['Move', 'Pontos'],
              [\"Ativo\", $somaAtvM],

              [\"Aprovado\", $somaApvdM],
              [\"Canc Comércial\", $somaCancelM],
              [\"BackLog\", $somabkM]

            ]);

            var options = {
              width: 450,
              legend: { position: 'none' },
              chart: {
                title: 'Acompanhamento + Móvel',
                subtitle: 'Mês: $myAtual' },
              axes: {
                x: {
                  0: { side: 'top', label: 'Propostas'} // Top x-axis.
                }
              },
              bar: { groupWidth: \"90%\" }
            };

            var chart = new google.charts.Bar(document.getElementById('top_y_div'));
            // Convert the Classic options to Material options.
            chart.draw(data, google.charts.Bar.convertOptions(options));


         };
       </script>";
    ?>

    <script>
    function mAtualV(){
      $("#vendasDiv").html("");

      $("#vendasDiv").html('<?php

      echo "<h4>Proposta:</h4>";


      echo "<div class=\"btn-group\" role=\"group\">";
      echo "<button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"mAtualVsql(1)\">Geral <span class=\"badge badge-primary\">$ttGeral</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-primary\">$ttGeralTv</span> M <span class=\"badge badge-primary\">$ttGeralMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-secondary\" onclick=\"mAtualVsql(2)\")>Ativos <span class=\"badge badge-secondary\">$ttAtivo</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-secondary\">$ttAtivoTv</span> M <span class=\"badge badge-secondary\">$ttAtivoMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-success\" onclick=\"mAtualVsql(3)\">Aprovados <span class=\"badge badge-success\">$ttAprovado</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-success\">$ttAprovadoTv</span> M <span class=\"badge badge-success\">$ttAprovadoMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-danger\" onclick=\"mAtualVsql(4)\">Comercial <span class=\"badge badge-danger\">$ttCancelado</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-danger\">$ttCanceladoTv</span> M <span class=\"badge badge-danger\">$ttCanceladoMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-dark\" onclick=\"mAtualVsql(5)\">BackLog <span class=\"badge badge-dark\">$ttbklg</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-dark\">$ttbklgTv</span> M <span class=\"badge badge-dark\">$ttbklgMov</span></span></button>";


      echo "<div style=\"margin-left: 30px; \" id=\"extraF\">";

      echo "</div>";

      echo "</div>";
        ?>');
      }




      function mAtualVsql(x) {



        if (x == 1) {

        $("#vendasDivp2").html("");
        $("#vendasDivp2").html('<?php   $mAtual = date("m");
          $vendedor = $dados["id"];
          $nmvendedor = $dados["login"];



          $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual";
          $sqlFim = mysqli_query($connect, $sqlDsz);
          $linha = mysqli_fetch_array($sqlFim);




          $contrato= $linha["contrato"];
          if(mysqli_num_rows($sqlFim)>0):
            echo "<div class=\"table-responsive-xl\">";
            echo "<table class=\"table table-hover text-center text-truncate\">";
            echo "<thead class=\"thead-dark\">";
            echo "<tr>";
            //FILTAR POR STATUS
            echo   "<th scope=\"col\">Status</th>";
            echo   "<th scope=\"col\">Data Instalação</th>";
            echo   "<th scope=\"col\">Inst Chamado</th>";
            echo   "<th scope=\"col\">Contrato</th>";
            echo   "<th scope=\"col\">Vendedor</th>";
            echo   "<th scope=\"col\">Supervisor</th>";
            echo   "<th scope=\"col\">Cidade</th>";
            echo   "<th scope=\"col\">Data Venda</th>";
            echo   "<th scope=\"col\">CPF Cliente</th>";
            echo   "<th scope=\"col\">Cliente</th>";
            echo   "<th scope=\"col\">Pontos</th>";
            echo   "<th scope=\"col\">Pontos Móvel</th>";




            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";
            //Lembrete = tentar fazer uma nova tabela a cada ciclo
            do {

              $clienteCpf = $linha["cpf_cliente"];
              $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
              $linhaC = mysqli_fetch_array($sqlC);
              $idVenda = $linha["id"];


              $status = $linha["situacao"];
              $obs = $linha["observacao"];
              echo   "<td title=\"Obs: $obs\">$status</td>";

              $dtInstala = $linha["data_instalacao"];
              echo "<td>$dtInstala</td>";

              $chamadoinst = $linha["chamadoinst"];
              echo "<td>$chamadoinst</td>";

              $contratoC = $linha["contrato"];
              $contratoS = $linha["sitctrt"];
              echo "<td>$contratoC - $contratoS</td>";

                  $idVendedor = $linha["id_vendedor"];
                  $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
                  $linhaV = mysqli_fetch_array($sqlV);
                  $nmVend = $linhaV["login"];
                  echo   "<td>$nmVend</td>";

                  $idSuper = $linhaV["id_super"];
                  $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
                  $linhaS = mysqli_fetch_array($sqlS);
                  $nmSup = $linhaS["login"];
                  echo   "<td>$nmSup</td>";

                  $localCidade = $linhaC["cidade"];
                  echo   "<td>$localCidade</td>";


                $dtVenda = $linha["data_venda"];
                if(empty($dtVenda)):
                  echo   "<td>$dtVenda</td>";
                else:
                  $newDate = date("d/m/Y", strtotime($dtVenda));
                  echo   "<td>$newDate</td>";
                endif;

                echo   "<td>$clienteCpf</td>";


                $nmcliente = $linhaC["nome"];
                echo   "<td>$nmcliente</td>";

                $ptcliente = $linha["ponto"];
                echo   "<td>$ptcliente</td>";

                $ptMcliente = $linha["pontoM"];
                echo   "<td>$ptMcliente</td>";


                echo "</tr>";

            } while ($linha = mysqli_fetch_assoc($sqlFim));
            echo "</tbody>";

            echo "</table>";
            echo "</div>";

          endif;





?>')



}else if (x == 2) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m");
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ATIVO'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}else if (x == 3) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m");
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'APROVADO'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}else if (x == 4) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m");
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'COMERCIAL'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}else if (x == 5) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m");
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'BACKLOG'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}
      }

      </script>

<!--FIM MÊS ATUAL-->

<!--MÊS PASSADO-->
   <?php

      $myAtual = date("m/Y",strtotime('-1 months', strtotime(date('Y-m'))));
      $mAtual = date("m",strtotime('-1 months', strtotime(date('m'))));
      $operador = $dados["id"];

      $ativoP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $ativoPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $ativoPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttAtivoP = mysqli_num_rows($ativoP);
      $ttAtivoPTv = mysqli_num_rows($ativoPTv);
      $ttAtivoPMov = mysqli_num_rows($ativoPMov);


      $somaAtivo = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Ativo FROM proposta WHERE situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmAtv = mysqli_fetch_assoc($somaAtivo);
      $somaAtv = $linhasmAtv['ponto_Ativo'];
      if (empty($somaAtv)) {
          $somaAtv = 0;
      };

      $somaAtivoM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_AtivoM FROM proposta WHERE situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmAtvM = mysqli_fetch_assoc($somaAtivoM);
      $somaAtvM = $linhasmAtvM['ponto_AtivoM'];
      if (empty($somaAtvM)) {
          $somaAtvM = 0;
      };


      $aprovadoP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $aprovadoPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $aprovadoPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttAprovadoP = mysqli_num_rows($aprovadoP);
      $ttAprovadoPTv = mysqli_num_rows($aprovadoPTv);
      $ttAprovadoPMov = mysqli_num_rows($aprovadoPMov);


      $somaAprovado = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Aprovado FROM proposta WHERE situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmApvd = mysqli_fetch_assoc($somaAprovado);
      $somaApvd = $linhasmApvd['ponto_Aprovado'];
      if (empty($somaApvd)) {
          $somaApvd = 0;
      };

      $somaAprovadoM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_AprovadoM FROM proposta WHERE situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmApvdM = mysqli_fetch_assoc($somaAprovadoM);
      $somaApvdM = $linhasmApvdM['ponto_AprovadoM'];
      if (empty($somaApvdM)) {
          $somaApvdM = 0;
      };


      $canceladoP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual");
      $canceladoPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $canceladoPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttCanceladoP = mysqli_num_rows($canceladoP);
      $ttCanceladoPTv = mysqli_num_rows($canceladoPTv);
      $ttCanceladoPMov = mysqli_num_rows($canceladoPMov);


      $somaCancelado = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Cancel FROM proposta WHERE situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual");
      $linhasmCanc = mysqli_fetch_assoc($somaCancelado);
      $somaCancel = $linhasmCanc['ponto_Cancel'];
      if (empty($somaCancel)) {
          $somaCancel = 0;
      };

      $somaCanceladoM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_CancelM FROM proposta WHERE situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual");
      $linhasmCancM = mysqli_fetch_assoc($somaCanceladoM);
      $somaCancelM = $linhasmCancM['ponto_CancelM'];
      if (empty($somaCancelM)) {
          $somaCancelM = 0;
      };

      $bklgP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual");
      $bklgPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $bklgPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttbklgP = mysqli_num_rows($bklgP);
      $ttbklgPTv = mysqli_num_rows($bklgPTv);
      $ttbklgPMov = mysqli_num_rows($bklgPMov);


      $somabklg = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Bko FROM proposta WHERE situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual");
      $linhasmbklg = mysqli_fetch_assoc($somabklg);
      $somabk = $linhasmbklg['ponto_Bko'];
      if (empty($somabk)) {
          $somabk = 0;
      };

      $somabklgM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_BkoM FROM proposta WHERE situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual");
      $linhasmbklgM = mysqli_fetch_assoc($somabklgM);
      $somabkM = $linhasmbklgM['ponto_BkoM'];
      if (empty($somabkM)) {
          $somabkM = 0;
      };

      $geralP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND MONTH(data_instalacao) = $mAtual");
      $geralPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $geralPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttGeralP = mysqli_num_rows($geralP);
      $ttGeralPTv = mysqli_num_rows($geralPTv);
      $ttGeralPMov = mysqli_num_rows($geralPMov);


      echo "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
       <script type=\"text/javascript\">

         google.charts.load('current', {'packages':['bar']});
         google.charts.setOnLoadCallback(passado());

         function passado() {
          var data = new google.visualization.arrayToDataTable([
             ['Move', 'Pontos'],
             [\"Ativo\", $somaAtv],

             [\"Aprovado\", $somaApvd],
             [\"Canc Comércial\", $somaCancel],
             [\"BackLog\", $somabk]

           ]);

           var options = {
             width: 450,
             legend: { position: 'none' },
             chart: {
               title: 'Pontos',
               subtitle: 'Mês: $myAtual' },
             axes: {
               x: {
                 0: { side: 'top', label: 'Propostas'} // Top x-axis.
               }
             },
             bar: { groupWidth: \"90%\" }
           };

           var chart = new google.charts.Bar(document.getElementById('top_x_div'));
           // Convert the Classic options to Material options.
           chart.draw(data, google.charts.Bar.convertOptions(options));


           var data = new google.visualization.arrayToDataTable([
              ['Move', 'Quantidade'],
              [\"Ativo\", $somaAtvM],

              [\"Aprovado\", $somaApvdM],
              [\"Canc Comércial\", $somaCancelM],
              [\"BackLog\", $somabkM]

            ]);

            var options = {
              width: 450,
              legend: { position: 'none' },
              chart: {
                title: 'Acompanhamento + Móvel',
                subtitle: 'Mês: $myAtual' },
              axes: {
                x: {
                  0: { side: 'top', label: 'Propostas'} // Top x-axis.
                }
              },
              bar: { groupWidth: \"90%\" }
            };

            var chart = new google.charts.Bar(document.getElementById('top_y_div'));
            // Convert the Classic options to Material options.
            chart.draw(data, google.charts.Bar.convertOptions(options));


         };
       </script>";
    ?>

    <script>
    function mPassadoV(){
      $("#vendasDiv").html("");

      $("#vendasDiv").html('<?php

      echo "<h4>Proposta:</h4>";


      echo "<div class=\"btn-group\" role=\"group\">";
      echo "<button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"mPassadoVsql(1)\">Geral <span class=\"badge badge-primary\">$ttGeralP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-primary\">$ttGeralPTv</span> M <span class=\"badge badge-primary\">$ttGeralPMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-secondary\" onclick=\"mPassadoVsql(2)\")>Ativos <span class=\"badge badge-secondary\">$ttAtivoP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-secondary\">$ttAtivoPTv</span> M <span class=\"badge badge-secondary\">$ttAtivoPMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-success\" onclick=\"mPassadoVsql(3)\">Aprovados <span class=\"badge badge-success\">$ttAprovadoP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-success\">$ttAprovadoPTv</span> M <span class=\"badge badge-success\">$ttAprovadoPMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-danger\" onclick=\"mPassadoVsql(4)\">Comercial <span class=\"badge badge-danger\">$ttCanceladoP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-danger\">$ttCanceladoPTv</span> M <span class=\"badge badge-danger\">$ttCanceladoPMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-dark\" onclick=\"mPassadoVsql(5)\">BackLog <span class=\"badge badge-dark\">$ttbklgP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-dark\">$ttbklgPTv</span> M <span class=\"badge badge-dark\">$ttbklgPMov</span></span></button>";



      echo "<div style=\"margin-left: 30px; \" id=\"extraF\">";

      echo "</div>";

      echo "</div>";
        ?>');
      }

      function mPassadoVsql(x) {

        if (x == 1) {

        $("#vendasDivp2").html("");
        $("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
          $vendedor = $dados["id"];
          $nmvendedor = $dados["login"];


          $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual";
          $sqlFim = mysqli_query($connect, $sqlDsz);
          $linha = mysqli_fetch_array($sqlFim);




          $contrato= $linha["contrato"];
          if(mysqli_num_rows($sqlFim)>0):
            echo "<div class=\"table-responsive-xl\">";
            echo "<table class=\"table table-hover text-center text-truncate\">";
            echo "<thead class=\"thead-dark\">";
            echo "<tr>";
            //FILTAR POR STATUS
            echo   "<th scope=\"col\">Status</th>";
            echo   "<th scope=\"col\">Data Instalação</th>";
            echo   "<th scope=\"col\">Inst Chamado</th>";
            echo   "<th scope=\"col\">Contrato</th>";
            echo   "<th scope=\"col\">Vendedor</th>";
            echo   "<th scope=\"col\">Supervisor</th>";
            echo   "<th scope=\"col\">Cidade</th>";
            echo   "<th scope=\"col\">Data Venda</th>";
            echo   "<th scope=\"col\">CPF Cliente</th>";
            echo   "<th scope=\"col\">Cliente</th>";
            echo   "<th scope=\"col\">Pontos</th>";
            echo   "<th scope=\"col\">Pontos Móvel</th>";




            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";
            //Lembrete = tentar fazer uma nova tabela a cada ciclo
            do {

              $clienteCpf = $linha["cpf_cliente"];
              $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
              $linhaC = mysqli_fetch_array($sqlC);
              $idVenda = $linha["id"];


              $status = $linha["situacao"];
              $obs = $linha["observacao"];
              echo   "<td title=\"Obs: $obs\">$status</td>";

              $dtInstala = $linha["data_instalacao"];
              echo "<td>$dtInstala</td>";

              $chamadoinst = $linha["chamadoinst"];
              echo "<td>$chamadoinst</td>";

              $contratoC = $linha["contrato"];
              $contratoS = $linha["sitctrt"];
              echo "<td>$contratoC - $contratoS</td>";

                  $idVendedor = $linha["id_vendedor"];
                  $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
                  $linhaV = mysqli_fetch_array($sqlV);
                  $nmVend = $linhaV["login"];
                  echo   "<td>$nmVend</td>";

                  $idSuper = $linhaV["id_super"];
                  $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
                  $linhaS = mysqli_fetch_array($sqlS);
                  $nmSup = $linhaS["login"];
                  echo   "<td>$nmSup</td>";

                  $localCidade = $linhaC["cidade"];
                  echo   "<td>$localCidade</td>";


                $dtVenda = $linha["data_venda"];
                if(empty($dtVenda)):
                  echo   "<td>$dtVenda</td>";
                else:
                  $newDate = date("d/m/Y", strtotime($dtVenda));
                  echo   "<td>$newDate</td>";
                endif;

                echo   "<td>$clienteCpf</td>";


                $nmcliente = $linhaC["nome"];
                echo   "<td>$nmcliente</td>";

                $ptcliente = $linha["ponto"];
                echo   "<td>$ptcliente</td>";

                $ptMcliente = $linha["pontoM"];
                echo   "<td>$ptMcliente</td>";


                echo "</tr>";

            } while ($linha = mysqli_fetch_assoc($sqlFim));
            echo "</tbody>";

            echo "</table>";
            echo "</div>";

          endif;


?>')
}else if (x == 2) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ATIVO'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}else if (x == 3) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'APROVADO'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}else if (x == 4) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'COMERCIAL'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}else if (x == 5) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'BACKLOG'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}
      }

      </script>

<!--FIM MÊS PASSADO-->

<!--MÊS RETRASADO-->
   <?php

      $myAtual = date("m/Y",strtotime('-2 months', strtotime(date('Y-m'))));
      $mAtual = date("m",strtotime('-2 months', strtotime(date('m'))));
      $operador = $dados["id"];

      $ativoAP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $ativoAPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $ativoAPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttAtivoAP = mysqli_num_rows($ativoAP);
      $ttAtivoAPTv = mysqli_num_rows($ativoAPTv);
      $ttAtivoAPMov = mysqli_num_rows($ativoAPMov);


      $somaAtivo = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Ativo FROM proposta WHERE situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmAtv = mysqli_fetch_assoc($somaAtivo);
      $somaAtv = $linhasmAtv['ponto_Ativo'];
      if (empty($somaAtv)) {
          $somaAtv = 0;
      };

      $somaAtivoM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_AtivoM FROM proposta WHERE situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmAtvM = mysqli_fetch_assoc($somaAtivoM);
      $somaAtvM = $linhasmAtvM['ponto_AtivoM'];
      if (empty($somaAtvM)) {
          $somaAtvM = 0;
      };


      $aprovadoAP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $aprovadoAPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $aprovadoAPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttAprovadoAP = mysqli_num_rows($aprovadoAP);
      $ttAprovadoAPTv = mysqli_num_rows($aprovadoAPTv);
      $ttAprovadoAPMov = mysqli_num_rows($aprovadoAPMov);


      $somaAprovado = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Aprovado FROM proposta WHERE situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmApvd = mysqli_fetch_assoc($somaAprovado);
      $somaApvd = $linhasmApvd['ponto_Aprovado'];
      if (empty($somaApvd)) {
          $somaApvd = 0;
      };

      $somaAprovadoM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_AprovadoM FROM proposta WHERE situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $linhasmApvdM = mysqli_fetch_assoc($somaAprovadoM);
      $somaApvdM = $linhasmApvdM['ponto_AprovadoM'];
      if (empty($somaApvdM)) {
          $somaApvdM = 0;
      };


      $canceladoAP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual");
      $canceladoAPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $canceladoAPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttCanceladoAP = mysqli_num_rows($canceladoAP);
      $ttCanceladoAPTv = mysqli_num_rows($canceladoAPTv);
      $ttCanceladoAPMov = mysqli_num_rows($canceladoAPMov);


      $somaCancelado = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Cancel FROM proposta WHERE situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual");
      $linhasmCanc = mysqli_fetch_assoc($somaCancelado);
      $somaCancel = $linhasmCanc['ponto_Cancel'];
      if (empty($somaCancel)) {
          $somaCancel = 0;
      };

      $somaCanceladoM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_CancelM FROM proposta WHERE situacao = 'COMERCIAL' AND MONTH(data_instalacao) = $mAtual");
      $linhasmCancM = mysqli_fetch_assoc($somaCanceladoM);
      $somaCancelM = $linhasmCancM['ponto_CancelM'];
      if (empty($somaCancelM)) {
          $somaCancelM = 0;
      };

      $bklgAP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual");
      $bklgAPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $bklgAPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttbklgAP = mysqli_num_rows($bklgAP);
      $ttbklgAPTv = mysqli_num_rows($bklgAPTv);
      $ttbklgAPMov = mysqli_num_rows($bklgAPMov);


      $somabklg = mysqli_query($connect, "SELECT SUM(ponto) AS ponto_Bko FROM proposta WHERE situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual");
      $linhasmbklg = mysqli_fetch_assoc($somabklg);
      $somabk = $linhasmbklg['ponto_Bko'];
      if (empty($somabk)) {
          $somabk = 0;
      };

      $somabklgM = mysqli_query($connect, "SELECT SUM(ponto) + SUM(pontoM) AS ponto_BkoM FROM proposta WHERE situacao = 'BACKLOG' AND MONTH(data_instalacao) = $mAtual");
      $linhasmbklgM = mysqli_fetch_assoc($somabklgM);
      $somabkM = $linhasmbklgM['ponto_BkoM'];
      if (empty($somabkM)) {
          $somabkM = 0;
      };

      $geralAP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND MONTH(data_instalacao) = $mAtual");
      $geralAPTv = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND MONTH(data_instalacao) = $mAtual AND tv != 'NULL'");
      $geralAPMov = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $operador AND MONTH(data_instalacao) = $mAtual AND movel != 'NULL'");
      $ttGeralAP = mysqli_num_rows($geralAP);
      $ttGeralAPTv = mysqli_num_rows($geralAPTv);
      $ttGeralAPMov = mysqli_num_rows($geralAPMov);


      echo "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
       <script type=\"text/javascript\">

         google.charts.load('current', {'packages':['bar']});
         google.charts.setOnLoadCallback(antepassado());

         function antepassado() {
          var data = new google.visualization.arrayToDataTable([
             ['Move', 'Pontos'],
             [\"Ativo\", $somaAtv],

             [\"Aprovado\", $somaApvd],
             [\"Canc Comércial\", $somaCancel],
             [\"BackLog\", $somabk]

           ]);

           var options = {
             width: 450,
             legend: { position: 'none' },
             chart: {
               title: 'Acompanhamento',
               subtitle: 'Mês: $myAtual' },
             axes: {
               x: {
                 0: { side: 'top', label: 'Propostas'} // Top x-axis.
               }
             },
             bar: { groupWidth: \"90%\" }
           };

           var chart = new google.charts.Bar(document.getElementById('top_x_div'));
           // Convert the Classic options to Material options.
           chart.draw(data, google.charts.Bar.convertOptions(options));


           var data = new google.visualization.arrayToDataTable([
              ['Move', 'Pontos'],
              [\"Ativo\", $somaAtvM],

              [\"Aprovado\", $somaApvdM],
              [\"Canc Comércial\", $somaCancelM],
              [\"BackLog\", $somabkM]

            ]);

            var options = {
              width: 450,
              legend: { position: 'none' },
              chart: {
                title: 'Acompanhamento + Móvel',
                subtitle: 'Mês: $myAtual' },
              axes: {
                x: {
                  0: { side: 'top', label: 'Propostas'} // Top x-axis.
                }
              },
              bar: { groupWidth: \"90%\" }
            };

            var chart = new google.charts.Bar(document.getElementById('top_y_div'));
            // Convert the Classic options to Material options.
            chart.draw(data, google.charts.Bar.convertOptions(options));


         };
       </script>";
    ?>

    <script>
    function mAntepassadoV(){
      $("#vendasDiv").html("");

      $("#vendasDiv").html('<?php

      echo "<h4>Proposta:</h4>";


      echo "<div class=\"btn-group\" role=\"group\">";
      echo "<button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"mAntepassadoVsql(1)\">Geral <span class=\"badge badge-primary\">$ttGeralAP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-primary\">$ttGeralAPTv</span> M <span class=\"badge badge-primary\">$ttGeralAPMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-secondary\" onclick=\"mAntepassadoVsql(2)\")>Ativos <span class=\"badge badge-secondary\">$ttAtivoAP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-secondary\">$ttAtivoAPTv</span> M <span class=\"badge badge-secondary\">$ttAtivoAPMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-success\" onclick=\"mAntepassadoVsql(3)\">Aprovados <span class=\"badge badge-success\">$ttAprovadoAP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-success\">$ttAprovadoAPTv</span> M <span class=\"badge badge-success\">$ttAprovadoAPMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-danger\" onclick=\"mAntepassadoVsql(4)\">Comercial <span class=\"badge badge-danger\">$ttCanceladoAP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-danger\">$ttCanceladoAPTv</span> M <span class=\"badge badge-danger\">$ttCanceladoAPMov</span></span></button>";
      echo "<button type=\"button\" class=\"btn btn-outline-dark\" onclick=\"mAntepassadoVsql(5)\">BackLog <span class=\"badge badge-dark\">$ttbklgAP</span><br><span style=\"font-size: 12px;\">T <span class=\"badge badge-dark\">$ttbklgAPTv</span> M <span class=\"badge badge-dark\">$ttbklgAPMov</span></span></button>";



      echo "<div style=\"margin-left: 30px; \" id=\"extraF\">";

      echo "</div>";

      echo "</div>";
        ?>');
      }

      function mAntepassadoVsql(x) {

        if (x == 1) {

        $("#vendasDivp2").html("");
        $("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
          $vendedor = $dados["id"];
          $nmvendedor = $dados["login"];


          $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual";
          $sqlFim = mysqli_query($connect, $sqlDsz);
          $linha = mysqli_fetch_array($sqlFim);




          $contrato= $linha["contrato"];
          if(mysqli_num_rows($sqlFim)>0):
            echo "<div class=\"table-responsive-xl\">";
            echo "<table class=\"table table-hover text-center text-truncate\">";
            echo "<thead class=\"thead-dark\">";
            echo "<tr>";
            //FILTAR POR STATUS
            echo   "<th scope=\"col\">Status</th>";
            echo   "<th scope=\"col\">Data Instalação</th>";
            echo   "<th scope=\"col\">Inst Chamado</th>";
            echo   "<th scope=\"col\">Contrato</th>";
            echo   "<th scope=\"col\">Vendedor</th>";
            echo   "<th scope=\"col\">Supervisor</th>";
            echo   "<th scope=\"col\">Cidade</th>";
            echo   "<th scope=\"col\">Data Venda</th>";
            echo   "<th scope=\"col\">CPF Cliente</th>";
            echo   "<th scope=\"col\">Cliente</th>";
            echo   "<th scope=\"col\">Pontos</th>";
            echo   "<th scope=\"col\">Pontos Móvel</th>";




            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";
            //Lembrete = tentar fazer uma nova tabela a cada ciclo
            do {

              $clienteCpf = $linha["cpf_cliente"];
              $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
              $linhaC = mysqli_fetch_array($sqlC);
              $idVenda = $linha["id"];


              $status = $linha["situacao"];
              $obs = $linha["observacao"];
              echo   "<td title=\"Obs: $obs\">$status</td>";

              $dtInstala = $linha["data_instalacao"];
              echo "<td>$dtInstala</td>";

              $chamadoinst = $linha["chamadoinst"];
              echo "<td>$chamadoinst</td>";

              $contratoC = $linha["contrato"];
              $contratoS = $linha["sitctrt"];
              echo "<td>$contratoC - $contratoS</td>";

                  $idVendedor = $linha["id_vendedor"];
                  $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
                  $linhaV = mysqli_fetch_array($sqlV);
                  $nmVend = $linhaV["login"];
                  echo   "<td>$nmVend</td>";

                  $idSuper = $linhaV["id_super"];
                  $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
                  $linhaS = mysqli_fetch_array($sqlS);
                  $nmSup = $linhaS["login"];
                  echo   "<td>$nmSup</td>";

                  $localCidade = $linhaC["cidade"];
                  echo   "<td>$localCidade</td>";


                $dtVenda = $linha["data_venda"];
                if(empty($dtVenda)):
                  echo   "<td>$dtVenda</td>";
                else:
                  $newDate = date("d/m/Y", strtotime($dtVenda));
                  echo   "<td>$newDate</td>";
                endif;

                echo   "<td>$clienteCpf</td>";


                $nmcliente = $linhaC["nome"];
                echo   "<td>$nmcliente</td>";

                $ptcliente = $linha["ponto"];
                echo   "<td>$ptcliente</td>";

                $ptMcliente = $linha["pontoM"];
                echo   "<td>$ptMcliente</td>";


                echo "</tr>";

            } while ($linha = mysqli_fetch_assoc($sqlFim));
            echo "</tbody>";

            echo "</table>";
            echo "</div>";

          endif;


?>')
}else if (x == 2) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ATIVO'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}else if (x == 3) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'APROVADO'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}else if (x == 4) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'COMERCIAL'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}else if (x == 5) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];


  $sqlDsz = "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'BACKLOG'";
  $sqlFim = mysqli_query($connect, $sqlDsz);
  $linha = mysqli_fetch_array($sqlFim);




  $contrato= $linha["contrato"];
  if(mysqli_num_rows($sqlFim)>0):
    echo "<div class=\"table-responsive-xl\">";
    echo "<table class=\"table table-hover text-center text-truncate\">";
    echo "<thead class=\"thead-dark\">";
    echo "<tr>";
    //FILTAR POR STATUS
    echo   "<th scope=\"col\">Status</th>";
    echo   "<th scope=\"col\">Data Instalação</th>";
    echo   "<th scope=\"col\">Inst Chamado</th>";
    echo   "<th scope=\"col\">Contrato</th>";
    echo   "<th scope=\"col\">Vendedor</th>";
    echo   "<th scope=\"col\">Supervisor</th>";
    echo   "<th scope=\"col\">Cidade</th>";
    echo   "<th scope=\"col\">Data Venda</th>";
    echo   "<th scope=\"col\">CPF Cliente</th>";
    echo   "<th scope=\"col\">Cliente</th>";
    echo   "<th scope=\"col\">Pontos</th>";
    echo   "<th scope=\"col\">Pontos Móvel</th>";




    echo "</tr>";
    echo "</thead>";

    echo "<tbody>";
    //Lembrete = tentar fazer uma nova tabela a cada ciclo
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);
      $idVenda = $linha["id"];


      $status = $linha["situacao"];
      $obs = $linha["observacao"];
      echo   "<td title=\"Obs: $obs\">$status</td>";

      $dtInstala = $linha["data_instalacao"];
      echo "<td>$dtInstala</td>";

      $chamadoinst = $linha["chamadoinst"];
      echo "<td>$chamadoinst</td>";

      $contratoC = $linha["contrato"];
      $contratoS = $linha["sitctrt"];
      echo "<td>$contratoC - $contratoS</td>";

          $idVendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);
          $nmVend = $linhaV["login"];
          echo   "<td>$nmVend</td>";

          $idSuper = $linhaV["id_super"];
          $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
          $linhaS = mysqli_fetch_array($sqlS);
          $nmSup = $linhaS["login"];
          echo   "<td>$nmSup</td>";

          $localCidade = $linhaC["cidade"];
          echo   "<td>$localCidade</td>";


        $dtVenda = $linha["data_venda"];
        if(empty($dtVenda)):
          echo   "<td>$dtVenda</td>";
        else:
          $newDate = date("d/m/Y", strtotime($dtVenda));
          echo   "<td>$newDate</td>";
        endif;

        echo   "<td>$clienteCpf</td>";


        $nmcliente = $linhaC["nome"];
        echo   "<td>$nmcliente</td>";

        $ptcliente = $linha["ponto"];
        echo   "<td>$ptcliente</td>";

        $ptMcliente = $linha["pontoM"];
        echo   "<td>$ptMcliente</td>";


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";

  endif;
?>')
}
      }

      </script>

<!--FIM MÊS RETRASADO-->


<!-- Modal -->
<div class="modal fade bd-example-modal-lg" id="ExemploModalCentralizado" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <form>

        <div class="modal-body">


          <div class="form-row">

            <div class="form-group col-md-5" style="margin-right: 30px;" onblur="apgpreco()">
              <label>Cidade - UF:</label>
              <select class="custom-select mr-sm-2" id="cidadeCliente" required>
                <option selected> </option>
                <option>APUCARANA - PR</option>
                <option>ARACAJU - SE</option>
                <option>CAMBE - PR</option>
                <option>CAMPOS DOS GOYTACAZES - RJ</option>
                <option>CARUARU - PE</option>
                <option>CORONEL FABRICIANO - MG</option>
                <option>DIVINOPOLIS - MG</option>
                <option>FLORIANOPOLIS - SC</option>
                <option>FORTALEZA - CE</option>
                <option>GOIANIA - GO</option>
                <option>JABOATAO DOS GUARARAPES - PE</option>
                <option>JARAGUA DO SUL - SC</option>
                <option>JOAO PESSOA - PB</option>
                <option>MACEIO - AL</option>
                <option>NATAL - RN</option>
                <option>NITEROI - RJ</option>
                <option>OLINDA - PE</option>
                <option>NOVA IGUACU - RJ</option>
                <option>OSASCO - SP</option>
                <option>PAULISTA - PE</option>
                <option>POCOS DE CALDAS - MG</option>
                <option>POUSO ALEGRE - MG</option>
                <option>RECIFE - PE</option>
                <option>RIO DE JANEIRO - RJ</option>
                <option>RIO VERDE - GO</option>
                <option>ROLANDIA - PR</option>
                <option>SALVADOR - BA</option>
                <option>SAO GONCALO - RJ</option>
                <option>SAO PAULO - SP</option>
                <option>SERRA - ES</option>
                <option>TERESINA - PI</option>
                <option>VITORIA - ES</option>
                <option>VOLTA REDONDA - RJ</option>
                <option>CURITIBA - PR</option>
                <option>BELO HORIZONTE - MG</option>
              </select>
            </div>



            <div class="form row">

              <div class="form col-sm-6" style="margin-right: 0px;">
                <label>Pontos: </label>
                <input class="form-control" id="pontoPlano" disabled>
              </div>

              <div class="form col-sm-6" style="margin-right: 0px;">
                <label>Pontos Móvel: </label>
                <input class="form-control" id="pontoPlanoM" disabled>
              </div>
            </div>

          </div>

          <div class="form-row">

              <div class="form-group col-md-8">
                  <label>Nome:</label>
                  <input type="text" class="form-control" id="nomeCliente" onblur="apgpreco()" required>
              </div>



              <div class="form-group col-md-4">
                <label>Cpf:</label>
                <input type="text" class="form-control" id="cpfCliente" onblur="apgpreco()" required>

                <div id="resposta">
                </div>
              </div>

          </div>

          <div class="form-row">
            <div class="form-group col-md-4">
                <label>Fixo:</label>
                <input type="text" class="form-control" id="fixoCliente" placeholder="(DDD) 0000-0000" required>
            </div>

            <div class="form-group col-md-4">
                <label>Movel:</label>
                <input type="text" class="form-control" id="movelCliente" placeholder="(DDD) 0 0000-0000" required>
            </div>

            <div class="form-group col-md-4">
                <label>Comercial:</label>
                <input type="text" class="form-control" id="fone2Cliente" placeholder="(DDD) 0 0000-0000" required>
            </div>
          </div>

          <fieldset id="bloqCadastro" disabled>


            <div class="form-row">
              <div class="form-group col-md-4">
                <label>Tv:</label>
                <select class="custom-select mr-sm-2" id="tvplano" onblur="somaTV(); apgpreco()">
                  <option> </option>
                  <option>TOP 4K COM PO</option>
                  <option>TOP 4K SEM PO</option>
                  <option>MIX HD COM PO</option>
                  <option>MIX HD SEM PO</option>
                  <option>FACIL HD</option>
                  <option>TV INICIAL</option>
                </select>
              </div>

              <div class="form-group col-md-3">
                <label>Pontos adicionais:</label>
                <input type="number" class="form-control" id="ptvplano"  onblur="apgpreco()" min="0" max="10">
              </div>

              <div class="form-group col-md-5">
                <label>Internet:</label>
                <select class="custom-select mr-sm-2" id="netplano" onblur="somaNET(); apgpreco()">
                  <option> </option>
                  <option>500 MB</option>
                  <option>240 MB</option>
                  <option>120 MB</option>
                  <option>60 MB</option>
                  <option>35 MB</option>
                </select>
              </div>

            </div>


            <div class="form-row">

              <div class="form-group col-md-4">
                <label>Fixo:</label>
                <select class="custom-select mr-sm-2" id="fixoplano" onblur="somaFIXO(); apgpreco()">
                  <option> </option>
                  <option>MUNDO TOTAL</option>
                  <option>BRASIL TOTAL</option>
                  <option>BRASIL</option>
                </select>
              </div>


            <div class="form-group col-md-3">
              <label>Movel:</label>
              <select class="custom-select mr-sm-2" id="movelplano" onblur="somaMOVEL(); apgpreco()">
                <option> </option>
                <option>CLARO GIGA 60GB</option>
                <option>CLARO GIGA 30GB</option>
                <option>CLARO GIGA 15GB</option>
                <option>CLARO GIGA 10GB</option>
                <option>CLARO GIGA 7GB</option>
                <option>CONTROLE 5GB</option>
                <option>CONTROLE 4GB</option>
                <option>CONTROLE 3GB</option>
              </select>
            </div>

            <div class="form-group col-md-5">
              <label>Dependentes:</label>
              <input type="number" class="form-control" id="depenmov" min="0" max="5">
            </div>

          </div>

          <div class="form-row">

          <div class="form-group col-md-7">
            <label>Obs:</label>
            <textarea class="form-control" id="obsvenda" rows="3"></textarea>
          </div>

          <div class="form-group col-md-3">
            <div class="form-group row">
              <label class="col-sm-4 col-form-label">Preço: </label>
              <div class="col-sm-8">
                <input class="form-control" id="precoPlano" placeholder="R$" onblur="mostraPonto()"  required>
              </div>

            </div>



          </div>

          <div class="form-group col-md-2">
          <div class="col-sm-4">
            <button type="submit" class="btn btn-primary" onclick="propCliente()" id = "btnEnv">Enviar</button>
          </div>
          </div>


          </div>

          </fieldset>

          </div>
        </form>

  </div>
</div>
</div>


<div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" id="areaSuper">
  <div class="modal-dialog modal-xl" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h2 class="modal-title">Cliente já cadastrado</h2>
        <p>Solicite a liberação para novo cadastro a um supervisor!</p>
        <button type="button" class="close" data-dismiss="modal" aria-label="Fechar">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <form>
          <div class="form-group">
            <label>Login:</label>
            <input type="text" class="form-control" id="loginSuper" aria-describedby="emailHelp" placeholder="Login">
          </div>
          <div class="form-group">
            <label for="exampleInputPassword1">Senha:</label>
            <input type="password" class="form-control" id="senhaSuper" placeholder="Senha">
          </div>


      </div>

          <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
              <button type="button" class="btn btn-primary" onclick="libSuper()">Liberar</button>
          </div>
        </form>

    </div>
  </div>
</div>
<script language="javascript">

    //apagar preço
    function apgpreco(){
      document.getElementById("precoPlano").value = '';
      document.getElementById("btnEnv").setAttribute("disabled", "disabled");
    };

    function mostraPonto(){

      document.getElementById("pontoPlano").setAttribute('placeholder',(pontotv + pontonet + pontofixo));
      document.getElementById("pontoPlanoM").setAttribute('placeholder',(pontomovel));
      document.getElementById("btnEnv").removeAttribute('disabled');
    };
    //dados proposta
    var tv = $("#tvplano");
    var pttv = $("#ptvplano");
    var net = $("#netplano");
    var fixo = $("#fixoplano");
    var movel = $("#movelplano");
    var depMvl = $("#depenmov");
    var obs = $("#obsvenda");
    var prec = $("#precoPlano");

    //dados cliente
    var cidade = $("#cidadeCliente");
    var cpfC = $("#cpfCliente");
    var nomeC = $("#nomeCliente");
    var telfixo = $("#fixoCliente");
    var telmovel = $("#movelCliente");
    var telfone = $("#fone2Cliente");

    //somando pontos
    var pontotv = 0;
    function somaTV(){
      var tvpontuacao = document.getElementById('tvplano');
      var cidadepontuacao = document.getElementById('cidadeCliente');
      if ((tvpontuacao.value == "TOP 4K COM PO") || (tvpontuacao.value == "TOP 4K SEM PO")){
        if (
          (cidadepontuacao.value == ("RIO VERDE - GO")) ||
          (cidadepontuacao.value == ("CAMBE - PR")) ||
          (cidadepontuacao.value == ("APUCARANA - PR")) ||
          (cidadepontuacao.value == ("ROLANDIA - PR")) ||
          (cidadepontuacao.value == ("CORONEL FABRICIANO - MG")) ||
          (cidadepontuacao.value == ("DIVINOPOLIS - MG")) ||
          (cidadepontuacao.value == ("POCOS DE CALDAS - MG")) ||
          (cidadepontuacao.value == ("POUSO ALEGRE - MG"))
       ){
            pontotv = 150;
        }else {
            pontotv = 270;
        }

      }else if ((tvpontuacao.value == "MIX HD COM PO") || (tvpontuacao.value == "MIX HD SEM PO")) {
        if (
          (cidadepontuacao.value == ("RIO VERDE - GO")) ||
          (cidadepontuacao.value == ("CAMBE - PR")) ||
          (cidadepontuacao.value == ("APUCARANA - PR")) ||
          (cidadepontuacao.value == ("ROLANDIA - PR")) ||
          (cidadepontuacao.value == ("CORONEL FABRICIANO - MG")) ||
          (cidadepontuacao.value == ("DIVINOPOLIS - MG")) ||
          (cidadepontuacao.value == ("POCOS DE CALDAS - MG")) ||
          (cidadepontuacao.value == ("POUSO ALEGRE - MG"))
       ){
            pontotv = 150;
        }else {
            pontotv = 170;
        }
      }else if (tvpontuacao.value == "FACIL HD") {
        if (
          (cidadepontuacao.value == ("RIO VERDE - GO")) ||
          (cidadepontuacao.value == ("CAMBE - PR")) ||
          (cidadepontuacao.value == ("APUCARANA - PR")) ||
          (cidadepontuacao.value == ("ROLANDIA - PR")) ||
          (cidadepontuacao.value == ("CORONEL FABRICIANO - MG")) ||
          (cidadepontuacao.value == ("DIVINOPOLIS - MG")) ||
          (cidadepontuacao.value == ("POCOS DE CALDAS - MG")) ||
          (cidadepontuacao.value == ("POUSO ALEGRE - MG"))
       ){
            pontotv = 150;
        }else {
            pontotv = 80;
        }

      }
    }

    var pontonet = 0;
    function somaNET(){
      var netpontuacao = document.getElementById('netplano');
      var cidadepontuacao = document.getElementById('cidadeCliente');
      if (
        (cidadepontuacao.value == ("RIO VERDE - GO")) ||
        (cidadepontuacao.value == ("CAMBE - PR")) ||
        (cidadepontuacao.value == ("APUCARANA - PR")) ||
        (cidadepontuacao.value == ("ROLANDIA - PR")) ||
        (cidadepontuacao.value == ("CORONEL FABRICIANO - MG")) ||
        (cidadepontuacao.value == ("DIVINOPOLIS - MG")) ||
        (cidadepontuacao.value == ("POCOS DE CALDAS - MG")) ||
        (cidadepontuacao.value == ("POUSO ALEGRE - MG"))
     ){
          pontonet = 110;
    }else {
      if (netpontuacao.value == "500 MB"){
        pontonet = 250;
      }else if (netpontuacao.value == "240 MB") {
        pontonet = 160;
      }else if (netpontuacao.value == "120 MB") {
        pontonet = 140;
      }else if (netpontuacao.value == "60 MB") {
        pontonet = 100;
      }else if (netpontuacao.value == "35 MB") {
        pontonet = 70;
      }
    }
  }



    var pontofixo = 0;
    function somaFIXO(){
      var fixopontuacao = document.getElementById('fixoplano')
      var cidadepontuacao = document.getElementById('cidadeCliente');

      if (
        (cidadepontuacao.value == ("RIO VERDE - GO")) ||
        (cidadepontuacao.value == ("CAMBE - PR")) ||
        (cidadepontuacao.value == ("APUCARANA - PR")) ||
        (cidadepontuacao.value == ("ROLANDIA - PR")) ||
        (cidadepontuacao.value == ("CORONEL FABRICIANO - MG")) ||
        (cidadepontuacao.value == ("DIVINOPOLIS - MG")) ||
        (cidadepontuacao.value == ("POCOS DE CALDAS - MG")) ||
        (cidadepontuacao.value == ("POUSO ALEGRE - MG"))
     ){

      pontofixo = 35;
    }else {

      if (fixopontuacao.value == "MUNDO TOTAL"){
        pontofixo = 120;
      }else if (fixopontuacao.value == "BRASIL TOTAL") {
        pontofixo = 90;
      }else if (fixopontuacao.value == "BRASIL") {
        pontofixo = 40;
      }
    }
  }

    var pontomovel = 0;
    function somaMOVEL(){
      var movelpontuacao = document.getElementById('movelplano')
      if (movelpontuacao.value == "CLARO GIGA 60GB"){
        pontomovel = 500;
      }else if (movelpontuacao.value == "CLARO GIGA 30GB") {
        pontomovel = 300;
      }else if (movelpontuacao.value == "CLARO GIGA 15GB") {
        pontomovel = 230;
      }else if (movelpontuacao.value == "CLARO GIGA 10GB") {
        pontomovel = 180;
      }else if (movelpontuacao.value == "CLARO GIGA 7GB") {
        pontomovel = 150;
      }else if (movelpontuacao.value == "CONTROLE 5GB") {
        pontomovel = 100;
      }else if (movelpontuacao.value == "CONTROLE 4GB") {
        pontomovel = 50;
      }
    }



    function  propCliente(){
      var ponto = (pontotv + pontonet + pontofixo);
      var pontoMov = pontomovel;
      var controlBtn = 0;

      if(document.getElementById('nomeCliente').value != ""){
        controlBtn += 1;
      }

      if(document.getElementById('cpfCliente').value != ""){
        controlBtn += 1;
      }

      if(document.getElementById('cidadeCliente').value != ""){
        controlBtn += 1;
      }

      if((document.getElementById('fixoCliente').value != "") || (document.getElementById('movelCliente').value != "") || (document.getElementById('fone2Cliente').value != "")){
        controlBtn += 1;
      }

      if ((document.getElementById('tvplano').value != "") || (document.getElementById('netplano').value != "") || (document.getElementById('fixoplano').value != "") || (document.getElementById('movelplano').value != "")){
        controlBtn += 1;
      }


      if (controlBtn > 4){
        $.ajax({
          url: 'enviar.php',
          type: 'POST',
          data:{"tvplano" : tv.val(),
          "ptvplano": pttv.val(),
          "netplano": net.val(),
          "fixoplano": fixo.val(),
          "movelplano": movel.val(),
          "depenmov": depMvl.val(),
          "obsvenda": obs.val(),
          "precoPlano": prec.val(),
          "pontoPlano": ponto,
          "pontoPlanoM": pontoMov,
          "cidadeCliente": cidade.val(),
          "cpfCliente": cpfC.val(),
          "nomeCliente": nomeC.val(),
          "fixoCliente": telfixo.val(),
          "movelCliente": telmovel.val(),
          "fone2Cliente": telfone.val()},

          success: function(data) {
            console.log(data);
            data = $.parseJSON(data);
              alert(data.retorno);

              window.location.reload()
            }


          });
      }else{
        alert("Alguns dados precisam ser preenchidos!");
      }

      }


</script>


<script language="javascript">


      var loginS = $("#loginSuper");
      var senhaS = $("#senhaSuper");
      function libSuper(){
        $.ajax({
            url: 'verificSuper.php',
            type: 'POST',
            data:{"login" : loginS.val(), "senha": senhaS.val()},

            success: function(data) {
              console.log(data);
              data = $.parseJSON(data);
              if (data.retorno == 'Acesso ok'){
                $("#loginSuper").val("");
                $("#senhaSuper").val("");
                $("#areaSuper").modal("hide");
                document.getElementById("bloqCadastro").removeAttribute("disabled");
              }else{
                alert(data.retorno);
              };


            }

        });

      }


        var cpf = $("#cpfCliente");
        var valorCpf = document.getElementById("cpfCliente");
        cpf.blur(
          function() {


            if (TestaCPF(valorCpf.value)){


            $.ajax({
                url: 'verificaCpfCliente.php',
                type: 'POST',
                data:{"cpf" : cpf.val()},

                success: function(data) {
                  console.log(data);
                  data = $.parseJSON(data);
                  var retorno = data.cpf;
                  var retorno2 = data.libCadastro;
                  $('#areaSuper').modal(retorno);
                  document.getElementById("bloqCadastro").removeAttribute(retorno2);
                }

            });
          }else {
            alert("- Cpf inválido!\n- Retire pontos(.) e traços(-)!");
          }


        });


function TestaCPF(strCPF) {
    var Soma;
    var Resto;
    Soma = 0;
  if (strCPF == "00000000000") return false;

  for (i=1; i<=9; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (11 - i);
  Resto = (Soma * 10) % 11;

    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(9, 10)) ) return false;

  Soma = 0;
    for (i = 1; i <= 10; i++) Soma = Soma + parseInt(strCPF.substring(i-1, i)) * (12 - i);
    Resto = (Soma * 10) % 11;

    if ((Resto == 10) || (Resto == 11))  Resto = 0;
    if (Resto != parseInt(strCPF.substring(10, 11) ) ) return false;
    return true;
};



// Exemplo de JavaScript inicial para desativar envios de formulário, se houver campos inválidos.
(function() {
  'use strict';
  window.addEventListener('load', function() {
    // Pega todos os formulários que nós queremos aplicar estilos de validação Bootstrap personalizados.
    var forms = document.getElementsByClassName('needs-validation');
    // Faz um loop neles e evita o envio
    var validation = Array.prototype.filter.call(forms, function(form) {
      form.addEventListener('submit', function(event) {
        if (form.checkValidity() === false) {
          event.preventDefault();
          event.stopPropagation();
        }
        form.classList.add('was-validated');
      }, false);
    });
  }, false);
})();
</script>





   <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.1.1.min.js">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 </body>
</html>
