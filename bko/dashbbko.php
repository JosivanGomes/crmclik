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


         <ul class="navbar-nav" style="margin-left: 500px">

           <li class="nav-item">
             <a class="nav-link" href="bko.php">Home</a>
           </li>

           <li>
             <a href="pendenciabko.php" class="btn btn-primary" role="button" aria-pressed="true">Pendências <span class="badge badge-danger">
                <?php
                  $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE sitctrt = 'N-OK' AND situacao = 'APROVADO' OR situacao = 'CHAMADO' OR situacao = 'CHECK OK'") or print mysql_error();
                  echo mysqli_num_rows($sql);
                ?>
              </span>
            </a>
           </li>

           <li class="nav-item" style="margin-left: 10px;">
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


           <li class="nav-item">
             <a class="nav-link" href="logout.php">Sair</a>
           </li>

         </ul>

     </nav>
   </header>

   <!--TELA PRINCIPAL-->

      <div id="grafico" style="margin-left: 110px; margin-top: 20px; margin-bottom: 20px;">
        <div id="top_x_div" style="height:320px;"></div>
      </div>



      <div id="vendasDiv">

      </div>
      <div id="vendasDivp2">

      </div>






   <!-- JavaScript (Opcional) -->



<!--MÊS ATUAL-->
   <?php

      $myAtual = date("m/Y");
      $mAtual = date("m");
      $operador = $dados["id"];
      $ativo = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $ttAtivo = mysqli_num_rows($ativo);
      $aprovado = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $ttAprovado = mysqli_num_rows($aprovado);
      $erE = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'ERRO ENDERECO' AND MONTH(data_instalacao) = $mAtual");
      $ttErE = mysqli_num_rows($erE);
      $erP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'ERRO PROPOSTA' AND MONTH(data_instalacao) = $mAtual");
      $ttErP = mysqli_num_rows($erP);

      echo "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
       <script type=\"text/javascript\">

         google.charts.load('current', {'packages':['bar']});
         google.charts.setOnLoadCallback(mAtual());

         function mAtual() {
          var data = new google.visualization.arrayToDataTable([
             ['Move', 'Quantidade'],
             [\"Ativo\", $ttAtivo],
             [\"Aprovado\", $ttAprovado],
             [\"Erro Endereço\", $ttErE],
             [\"Erro Proposta\", $ttErP]
           ]);

           var options = {
             width: 800,
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


         };
       </script>";
    ?>

    <script>
    function mAtualV(){
      $("#vendasDiv").html("");

      $("#vendasDiv").html('<?php
      echo "<h4>Proposta:</h4>";
      echo "<div class=\"btn-group\" role=\"group\">";
      echo "<button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"mAtualVsql(1)\">Geral</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-secondary\" onclick=\"mAtualVsql(2)\")>Ativos</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-success\" onclick=\"mAtualVsql(3)\">Aprovados</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-danger\" onclick=\"mAtualVsql(4)\">Erro Endereço</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-warning\" onclick=\"mAtualVsql(5)\">Erro Proposta</button>";
      echo "</div>"
        ?>');
      }

      function mAtualVsql(x) {

        if (x == 1) {
        $("#vendasDivp2").html("");
        $("#vendasDivp2").html('<?php   $mAtual = date("m");
          $vendedor = $dados["id"];
          $nmvendedor = $dados["login"];
          

          $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual";
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


                echo "</tr>";

            } while ($linha = mysqli_fetch_assoc($sqlFim));
            echo "</tbody>";

            echo "</table>";
            echo "</div>";
          else:
            echo "Sem dados";
          endif;
?>')
}else if (x == 2) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m");
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ATIVO'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}else if (x == 3) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m");
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'APROVADO'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}else if (x == 4) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m");
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ERRO ENDERECO'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}else if (x == 5) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m");
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ERRO PROPOSTA'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}
      }

      </script>

<!--FIM MÊS ATUAL-->




<!--MÊS PASSADO-->
   <?php

      $myAtual = date("m/Y", strtotime('-1 months', strtotime(date('Y-m'))));
      $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
      $operador = $dados["id"];
      $ativo = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $ttAtivo = mysqli_num_rows($ativo);
      $aprovado = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $ttAprovado = mysqli_num_rows($aprovado);
      $erE = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'ERRO ENDERECO' AND MONTH(data_instalacao) = $mAtual");
      $ttErE = mysqli_num_rows($erE);
      $erP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'ERRO PROPOSTA' AND MONTH(data_instalacao) = $mAtual");
      $ttErP = mysqli_num_rows($erP);

      echo "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
       <script type=\"text/javascript\">

         google.charts.load('current', {'packages':['bar']});
         google.charts.setOnLoadCallback(passado());

         function passado() {
          var data = new google.visualization.arrayToDataTable([
             ['Move', 'Quantidade'],
             [\"Ativo\", $ttAtivo],
             [\"Aprovado\", $ttAprovado],
             [\"Erro Endereço\", $ttErE],
             [\"Erro Proposta\", $ttErP]
           ]);

           var options = {
             width: 800,
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


         };
       </script>";
    ?>

    <script>
    function mPassadoV(){
      $("#vendasDiv").html("");

      $("#vendasDiv").html('<?php
      echo "<h4>Proposta:</h4>";
      echo "<div class=\"btn-group\" role=\"group\">";
      echo "<button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"mPassadoVsql(1)\">Geral</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-secondary\" onclick=\"mPassadoVsql(2)\")>Ativos</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-success\" onclick=\"mPassadoVsql(3)\">Aprovados</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-danger\" onclick=\"mPassadoVsql(4)\">Erro Endereço</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-warning\" onclick=\"mPassadoVsql(5)\">Erro Proposta</button>";
      echo "</div>"
        ?>');
      }

      function mPassadoVsql(x) {

        if (x == 1) {
        $("#vendasDivp2").html("");
        $("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
          $vendedor = $dados["id"];
          $nmvendedor = $dados["login"];
          

          $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual";
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


                echo "</tr>";

            } while ($linha = mysqli_fetch_assoc($sqlFim));
            echo "</tbody>";

            echo "</table>";
            echo "</div>";
          else:
            echo "Sem dados";
          endif;
?>')
}else if (x == 2) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ATIVO'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}else if (x == 3) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'APROVADO'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}else if (x == 4) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ERRO ENDERECO'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}else if (x == 5) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-1 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ERRO PROPOSTA'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}
      }

      </script>

<!--FIM MÊS PASSADO-->


<!--MÊS ANTEPASSADO-->
   <?php

      $myAtual = date("m/Y", strtotime('-2 months', strtotime(date('Y-m'))));
      $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
      $operador = $dados["id"];
      $ativo = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'ATIVO' AND MONTH(data_instalacao) = $mAtual");
      $ttAtivo = mysqli_num_rows($ativo);
      $aprovado = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'APROVADO' AND MONTH(data_instalacao) = $mAtual");
      $ttAprovado = mysqli_num_rows($aprovado);
      $erE = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'ERRO ENDERECO' AND MONTH(data_instalacao) = $mAtual");
      $ttErE = mysqli_num_rows($erE);
      $erP = mysqli_query($connect, "SELECT * FROM proposta WHERE id_bko = $operador AND situacao = 'ERRO PROPOSTA' AND MONTH(data_instalacao) = $mAtual");
      $ttErP = mysqli_num_rows($erP);

      echo "<script type=\"text/javascript\" src=\"https://www.gstatic.com/charts/loader.js\"></script>
       <script type=\"text/javascript\">

         google.charts.load('current', {'packages':['bar']});
         google.charts.setOnLoadCallback(antepassado());

         function antepassado() {
          var data = new google.visualization.arrayToDataTable([
             ['Move', 'Quantidade'],
             [\"Ativo\", $ttAtivo],
             [\"Aprovado\", $ttAprovado],
             [\"Erro Endereço\", $ttErE],
             [\"Erro Proposta\", $ttErP]
           ]);

           var options = {
             width: 800,
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


         };
       </script>";
    ?>

    <script>
    function mAntepassadoV(){
      $("#vendasDiv").html("");

      $("#vendasDiv").html('<?php
      echo "<h4>Proposta:</h4>";
      echo "<div class=\"btn-group\" role=\"group\">";
      echo "<button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"mAntepassadoVsql(1)\">Geral</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-secondary\" onclick=\"mAntepassadoVsql(2)\")>Ativos</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-success\" onclick=\"mAntepassadoVsql(3)\">Aprovados</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-danger\" onclick=\"mAntepassadoVsql(4)\">Erro Endereço</button>";
      echo "<button type=\"button\" class=\"btn btn-outline-warning\" onclick=\"mAntepassadoVsql(5)\">Erro Proposta</button>";
      echo "</div>"
        ?>');
      }

      function mAntepassadoVsql(x) {

        if (x == 1) {
        $("#vendasDivp2").html("");
        $("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
          $vendedor = $dados["id"];
          $nmvendedor = $dados["login"];
          

          $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual";
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


                echo "</tr>";

            } while ($linha = mysqli_fetch_assoc($sqlFim));
            echo "</tbody>";

            echo "</table>";
            echo "</div>";
          else:
            echo "Sem dados";
          endif;
?>')
}else if (x == 2) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ATIVO'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}else if (x == 3) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'APROVADO'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}else if (x == 4) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ERRO ENDERECO'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}else if (x == 5) {
$("#vendasDivp2").html("");
$("#vendasDivp2").html('<?php   $mAtual = date("m", strtotime('-2 months', strtotime(date('m'))));
  $vendedor = $dados["id"];
  $nmvendedor = $dados["login"];
  

  $sqlDsz = "SELECT * FROM proposta WHERE id_bko = '{$vendedor}' AND MONTH(data_instalacao) = $mAtual AND situacao = 'ERRO PROPOSTA'";
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


        echo "</tr>";

    } while ($linha = mysqli_fetch_assoc($sqlFim));
    echo "</tbody>";

    echo "</table>";
    echo "</div>";
  else:
    echo "Sem dados";
  endif;
?>')
}
      }

      </script>

<!--FIM MÊS ANTEPASSADO-->



   <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.1.1.min.js">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 </body>
</html>
