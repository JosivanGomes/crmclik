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
  $sql = "SELECT * FROM gerencia WHERE id = '$id'";
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


       <a href="#" data-toggle="modal" data-target="#agenda">
         <img src="img/agenda.png" alt="Agenda" title="Agenda" class="rounded-circle" style="width: 40px; background-color: #f0f0f0;">
       </a>

       <a data-toggle="modal" data-target="#previsao" href="#">
         <img src="img/previsao.jpg" alt="Previsão" title="Previsão" class="rounded-circle" style="width: 40px; background-color: #f0f0f0;">
       </a>

       <a data-toggle="modal" data-target="#diarioVendedor" href="#">
         <img src="img/diario.jpg" alt="Diário Vendedor" title="Diário Vendedor" class="rounded-circle" style="width: 45px; background-color: #f0f0f0;">
       </a>

       <a data-toggle="modal" data-target="#rankingMes" href="#">
         <img src="img/trofeu.jpg" alt="Ranking" title="Ranking" class="rounded-circle" style="width: 45px; background-color: #f0f0f0;">
       </a>

       <a data-toggle="modal" data-target="#DashB" href="#">
         <img src="img/dashB.png" alt="Dash Board Diário" title="Dash Board Diário" class="rounded-circle" style="width: 45px; background-color: #f0f0f0;">
       </a>

         <ul class="navbar-nav" style="margin-left: 300px">


           <li class="nav-item dropdown">
             <a class="nav-link" href="gerencia.php">Home</a>
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
                  <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#novoOperador">Cadastrar novo supervisor</button>
                </a>
              </div>
           </li>



           <li class="nav-item">
             <a class="nav-link" href="logout.php">Sair</a>
           </li>

         </ul>

     </nav>
   </header>



    <div id="DashB" class="modal fade bd-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-sm">
        <h4 style="color: #f0f0f0; font-size: 30px;">Dash Board Diário:</h4>
        <div class="modal-content" style="padding: 20px;">
          <table class="table" style="text-align: center;">
            <tr>
                <th scope="col">Pontos Aprovados</th>
                <?php
                $hoje = date('Y-m-d');
                $super = $dados['id'];

                $sql = mysqli_query($connect,"SELECT * FROM proposta WHERE id_super = $super AND data_venda = '$hoje' AND sitctrt = 'OK' AND (situacao = 'APROVADO' OR situacao = 'APROVADO DIVERGENTE')");
                $linha = mysqli_fetch_array($sql);
                $ponto = 0;
                $pontoM = 0;
                if ($linha > 0):
                  do{
                    $ponto = $linha['ponto'];
                    $pontoM = $linha['pontoM'];
                    $total = $pontoM + $ponto;

                  } while($linha = mysqli_fetch_assoc($sql));
                endif;

                echo "<td>$ponto</td>";
                echo "<td>$pontoM</td>";


                 ?>

            </tr>
            <tr>
                <th scope="col">Quantidade Tv</th>

                <?php
                $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                   (situacao = 'APROVADO' OR situacao = 'APROVADO DIVERGENTE') AND data_venda = '$hoje' AND tv != 'NULL'") or print mysql_error();
                $linhaSUM = mysqli_num_rows($sql);

                echo "<td colspan=\"2\">$linhaSUM</td>";
                 ?>

            </tr>
            <tr>
                <th scope="col">Quantidade Móvel</th>

                <?php
                $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                   (situacao = 'APROVADO' OR situacao = 'APROVADO DIVERGENTE') AND data_venda = '$hoje' AND movel != 'NULL'") or print mysql_error();
                $linhaSUM = mysqli_num_rows($sql);

                echo "<td colspan=\"2\">$linhaSUM</td>";
                 ?>
            </tr>
            <tr>
                <th scope="col">Quantidade Geral</th>
                <?php
                $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                   (situacao = 'APROVADO' OR situacao = 'APROVADO DIVERGENTE') AND data_venda = '$hoje'") or print mysql_error();
                $linhaSUM = mysqli_num_rows($sql);

                echo "<td colspan=\"2\">$linhaSUM</td>";
                 ?>
            </tr>
            <tr>
                <th scope="col">Pendências</th>
                <?php
                $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE id_super = '$super' AND
                   situacao != 'APROVADO' AND situacao != 'APROVADO DIVERGENTE' AND situacao != 'NEGADO' AND data_venda = '$hoje'") or print mysql_error();
                $linhaSUM = mysqli_num_rows($sql);

                echo "<td colspan=\"2\">$linhaSUM</td>";
                 ?>
            </tr>
          </table>
        </div>
      </div>
    </div>

   <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="agenda">
    <div class="modal-dialog modal-xl" style="margin-left: 200px;">
      <h4 style="color: #f0f0f0; font-size: 30px;">Proximas instalações:</h4>
        <div class="modal-content" style="padding: 20px; width: 800px;">
            <?php

            $hoje = date('Y-m-d');



            $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE situacao = 'APROVADO' AND data_instalacao > '$hoje'") or print mysql_error();
            $linha = mysqli_fetch_array($sql);



            $contrato= $linha["contrato"];
            $listaInstalacao = array();
            if(mysqli_num_rows($sql)>0):
              do {
                $dataInst = $linha['data_instalacao'];
                if (!in_array("$dataInst", $listaInstalacao)):
                  $listaInstalacao[] = $linha['data_instalacao'];
                endif;
              } while ($linha = mysqli_fetch_assoc($sql));

            endif;



            function sortFunction( $a, $b ) {
                return strtotime($a) - strtotime($b);
            }
            usort($listaInstalacao, "sortFunction");


            $contador = count($listaInstalacao);

            for ($i=0; $i < $contador; $i++) {
              $diaInst = $listaInstalacao[$i];

              $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE data_instalacao = '$diaInst'") or print mysql_error();
              $linha = mysqli_fetch_array($sql);



              $contrato= $linha["contrato"];
              $diaInst = date('d/m/Y', strtotime($diaInst));
              echo "Data: $diaInst";
              if(mysqli_num_rows($sql)>0):
                echo "<div class=\"table-responsive-xl\">";
                echo "<table class=\"table table-hover text-center text-truncate\">";
                echo "<thead class=\"thead-dark\">";
                echo "<tr>";

                echo   "<th scope=\"col\">Vendedor</th>";
                echo   "<th scope=\"col\">Supervisor</th>";
                echo   "<th scope=\"col\">Status</th>";
                echo   "<th scope=\"col\">Data Instalação</th>";
                echo   "<th scope=\"col\">Turno</th>";
                echo   "<th scope=\"col\">Inst Chamado</th>";
                echo   "<th scope=\"col\">Cidade</th>";
                echo   "<th scope=\"col\">Contrato</th>";
                echo   "<th scope=\"col\">Data Venda</th>";
                echo   "<th scope=\"col\">CPF Cliente</th>";
                echo   "<th scope=\"col\">Cliente</th>";
                echo   "<th scope=\"col\">Fone1</th>";
                echo   "<th scope=\"col\">Fone2</th>";
                echo   "<th scope=\"col\">Tv</th>";
                echo   "<th scope=\"col\">Pt Adc Tv</th>";
                echo   "<th scope=\"col\">Internet</th>";
                echo   "<th scope=\"col\">Fone</th>";
                echo   "<th scope=\"col\">Móvel</th>";
                echo   "<th scope=\"col\">Valor</th>";
                echo   "<th scope=\"col\">Pontuação</th>";
                echo   "<th scope=\"col\">Pontuação - Móvel</th>";
                echo   "<th scope=\"col\">BackOffice</th>";
                echo "</tr>";
                echo "</thead>";

                echo "<tbody>";
                //Lembrete = tentar fazer uma nova tabela a cada ciclo
                do {

                  $clienteCpf = $linha["cpf_cliente"];
                  $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
                  $linhaC = mysqli_fetch_array($sqlC);

                  $vendedor = $linha["id_vendedor"];
                  $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$vendedor}'") or print mysql_error();
                  $linhaV = mysqli_fetch_array($sqlV);

                   $nmVendedor = $linhaV['login'];
                    echo "<td>$nmVendedor</td>";



                    $status = $linha["situacao"];
                    echo   "<td>$status</td>";

                    $dtInstala = $linha["data_instalacao"];
                    if(empty($dtInstala)):
                      echo   "<td>$dtInstala</td>";
                    else:
                      $newDate = date("d/m/Y", strtotime($dtInstala));
                      echo   "<td>$newDate</td>";
                    endif;

                      $turnoInst = $linha["turnoinst"];
                      if ($turnoInst == "NULL"):
                        echo "<td></td>";
                      else:
                        echo   "<td>$turnoInst</td>";
                      endif;

                      $chamadoinst = $linha["chamadoinst"];
                      if ($chamadoinst == "NULL"):
                        echo "<td></td>";
                      else:
                        echo   "<td>$chamadoinst</td>";
                      endif;



                      $localCidade = $linhaC["cidade"];
                      echo   "<td>$localCidade</td>";

                      $contratoC = $linha["contrato"];
                      echo   "<td>$contratoC</td>";



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

                    $F1Cliente = $linhaC["telfixo"];
                    echo   "<td>$F1Cliente</td>";

                    $F2Cliente = $linhaC["telmovel"];
                    echo   "<td>$F2Cliente</td>";

                    $planoTv = $linha["tv"];
                    if ($planoTv == "NULL"):
                      echo   "<td></td>";
                    else:
                      echo "<td>$planoTv</td>";
                    endif;

                    $pontoTv = $linha["pt_adc_tv"];
                    if ($pontoTv == 0):
                      echo "<td></td>";
                    else:
                      echo   "<td>$pontoTv</td>";
                    endif;

                    $net = $linha["internet"];
                    if ($net == "NULL"):
                      echo "<td></td>";
                    else:
                      echo   "<td>$net</td>";
                    endif;

                    $fone = $linha["telefone"];
                    if ($fone == "NULL"):
                      echo "<td></td>";
                    else:
                      echo   "<td>$fone</td>";
                    endif;

                    $movel = $linha["movel"];
                    if ($movel == "NULL"):
                      echo "<td></td>";
                    else:
                      echo   "<td>$movel</td>";
                    endif;

                    $precoP = number_format($linha["preco"], 2, '.', '');
                      echo   "<td>R$ $precoP</td>";





                    $pontuacao = $linha["ponto"];
                    echo   "<td>$pontuacao</td>";

                    $pontuacaoMovel = $linha["pontoM"];
                    echo   "<td>$pontuacaoMovel</td>";

                    $idBko = $linha["id_bko"];
                    $sqlB = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idBko}'") or print mysql_error();
                    $linhaB = mysqli_fetch_array($sqlB);
                    $nmBko = $linhaB["login"];
                    echo   "<td>$nmBko</td>";

                    echo "</tr>";







                } while ($linha = mysqli_fetch_assoc($sql));
                echo "</tbody>";

                echo "</table>";
                echo "</div>";
              else:
                echo "<h2>Sem dados</h2>";
              endif;

            }



              ?>
        </div>
    </div>
  </div>

   <div class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true" id="previsao">
    <div class="modal-dialog modal-xl" style="margin-left: 200px;">
      <h4 style="color: #f0f0f0; font-size: 30px;">Previsão:</h4>
        <div class="modal-content" style="padding: 20px; width: 800px;">

          <?php

            $qtdDiaMes = cal_days_in_month(CAL_GREGORIAN,date('m'),date('Y'));


            echo "<div class=\"table-responsive-xl\">";
            echo "<table class=\"table table-hover text-center text-truncate\">";
            echo "<thead class=\"thead-dark\">";
            echo "<tr>";

            echo   "<th scope=\"col\">DATA</th>";
            echo   "<th scope=\"col\">PONTUAÇÃO</th>";
            echo   "<th scope=\"col\">ATIVOS</th>";
            echo   "<th scope=\"col\">COMERCIAL</th>";
            echo   "<th scope=\"col\">TECNICO</th>";
            echo   "<th scope=\"col\">BACKLOG</th>";
            echo "</tr>";
            echo "</thead>";

            echo "<tbody>";

            for ($i = 1; $i <= $qtdDiaMes; $i++){
              $data_incio = mktime(0, 0, 0, date('m') , $i , date('Y'));
              $data_apr = date('d/m/Y',$data_incio);
              $data_sql = date('Y-m-d',$data_incio);
              $hoje = date('Y-m-d');

              echo "<td>$data_apr</td>";
              $super = $dados['id'];

              $sql = mysqli_query($connect,"SELECT * FROM proposta");
              $linha = mysqli_fetch_array($sql);
              $total = 0;
              if ($linha >0):
                do{
                  $dtInst = $linha['data_instalacao'];
                  if ($dtInst == $data_sql):
                    $ponto = $linha['ponto'];
                    $pontoM = $linha['pontoM'];
                    $total += $ponto + $pontoM;
                  endif;
                } while($linha = mysqli_fetch_assoc($sql));
              endif;

              if ($total != 0):
                echo "<td>$total</td>";
              else:
                echo "<td></td>";
              endif;


              $sql = mysqli_query($connect,"SELECT * FROM proposta WHERE situacao ='ATIVO'");
              $linha = mysqli_fetch_array($sql);
              $total = 0;
              if ($linha >0):
                do{
                  $dtInst = $linha['data_instalacao'];
                  if ($dtInst == $data_sql):
                    $ponto = $linha['ponto'];
                    $pontoM = $linha['pontoM'];
                    $total += $ponto + $pontoM;
                  endif;
                } while($linha = mysqli_fetch_assoc($sql));
              endif;

              if ($total != 0):
                echo "<td>$total</td>";
              else:
                echo "<td></td>";
              endif;

              $sql = mysqli_query($connect,"SELECT * FROM proposta WHERE situacao ='COMERCIAL'");
              $linha = mysqli_fetch_array($sql);
              $total = 0;
              if ($linha >0):
                do{
                  $dtInst = $linha['data_instalacao'];
                  if ($dtInst == $data_sql):
                    $ponto = $linha['ponto'];
                    $pontoM = $linha['pontoM'];
                    $total += $ponto + $pontoM;
                  endif;
                } while($linha = mysqli_fetch_assoc($sql));
              endif;

              if ($total != 0):
                echo "<td>$total</td>";
              else:
                echo "<td></td>";
              endif;

              $sql = mysqli_query($connect,"SELECT * FROM proposta WHERE situacao ='TECNICO'");
              $linha = mysqli_fetch_array($sql);
              $total = 0;
              if ($linha >0):
                do{
                  $dtInst = $linha['data_instalacao'];
                  if ($dtInst == $data_sql):
                    $ponto = $linha['ponto'];
                    $pontoM = $linha['pontoM'];
                    $total += $ponto + $pontoM;
                  endif;
                } while($linha = mysqli_fetch_assoc($sql));
              endif;

              if ($total != 0):
                echo "<td>$total</td>";
              else:
                echo "<td></td>";
              endif;

              $sql = mysqli_query($connect,"SELECT * FROM proposta WHERE situacao ='BACKLOG'");
              $linha = mysqli_fetch_array($sql);
              $total = 0;
              if ($linha >0):
                do{
                  $dtInst = $linha['data_instalacao'];
                  if ($dtInst == $data_sql):
                    $ponto = $linha['ponto'];
                    $pontoM = $linha['pontoM'];
                    $total += $ponto + $pontoM;
                  endif;
                } while($linha = mysqli_fetch_assoc($sql));
              endif;

              if ($total != 0):
                echo "<td>$total</td>";
              else:
                echo "<td></td>";
              endif;

              echo "</tr>";
            }

            echo "</tbody>

                </table>
                </div>";

           ?>
        </div>
      </div>
    </div>

    <div id="diarioVendedor" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
     <div class="modal-dialog modal-xl" style="margin-left: 200px;">
       <h4 style="color: #f0f0f0; font-size: 30px;">Diário Vendedor:</h4>
         <div class="modal-content" style="padding: 20px; width: 800px;">


           <?php
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
            echo "<th></th>";

            for ($i = 1; $i <= $qtdDiaMes; $i++){
              $data_incio = mktime(0, 0, 0, date('m') , $i , date('Y'));
              $data_apr = date('d/m/Y',$data_incio);
              echo   "<th scope=\"col\">$data_apr</th>";
            };
            echo "</tr>";
            echo "</thead>";
            echo "<tbody>";

            for ($i=0; $i < $controle ; $i++) {
              echo "<tr>";
              $id_OP = $operadores[$i]['idOperador'];
              $nome_OP = $operadores[$i]['nomeOperador'];
              echo "<th scope=\"row\">$nome_OP</th>";

              for ($iB = 1; $iB <= $qtdDiaMes; $iB++){
                $data_incio = mktime(0, 0, 0, date('m') , $iB , date('Y'));
                $data_apr = date('d/m/Y',$data_incio);
                $data_sql = date('Y-m-d',$data_incio);

                $sqlPontos = mysqli_query($connect, "SELECT * FROM proposta WHERE id_vendedor = $id_OP AND sitctrt = 'OK'");
                $linhaPontos = mysqli_fetch_array($sqlPontos);

                $total = 0;

                if ($linhaPontos >0):
                  do{
                    $dtInst = $linhaPontos['data_instalacao'];
                    if ($dtInst == $data_sql):
                      $ponto = $linhaPontos['ponto'];
                      $pontoM = $linhaPontos['pontoM'];
                      $total += $ponto + $pontoM;
                    endif;
                  } while($linhaPontos = mysqli_fetch_assoc($sqlPontos));
                endif;

                if ($total != 0):
                  echo "<td>$total</td>";
                else:
                  echo "<td></td>";
                endif;

              };
              echo "</tr>";

            };

            echo "</tbody>";


            echo "</table>";
            echo "</div>";

            ?>

         </div>
       </div>
     </div>

     <div id="rankingMes" class="modal fade bd-example-modal-xl" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-xl" style="margin-left: 200px;">
        <h5 style="color: #f0f0f0; font-size: 30px;">Ranking:</h5>
          <div class="modal-content" style="padding: 20px; width: 800px;" id="areaTeste">
            <style media="screen">
              #areaTeste{
                display: flex;
                flex-direction: row;
              }

              .atvMov{
                margin-right: 20px;
                padding: 20px;
              }
            </style>

            <div class="atvMov" style="background-color: #98FB98;">
              <?php
                echo "<h6>Ativo: </h6>";
                //Fazer lista de Id de Operadores do supervisor
                $supervisor = $dados['id'];
                $sql = mysqli_query($connect, "SELECT * FROM operador WHERE id_super = $supervisor AND cargo = 'VENDEDOR'");
                $linha = mysqli_fetch_array($sql);

                do{
                  $idOperador = $linha['id'];
                  $listaA[] = array('id' => $idOperador);
                }while ($linha = mysqli_fetch_assoc($sql));
                $contador = count($listaA);

                $mes = date('m');


                //Fazer Lista com Id e pontos do vendedor no dia
                for ($i = 0; $i < $contador; $i++){
                  $ponto = 0;
                  $pontoM = 0;
                  $idOperador2 = $listaA[$i]['id'];
                  $sqlVendas = mysqli_query($connect,"SELECT * FROM proposta
                    WHERE id_vendedor = '$idOperador2' AND situacao = 'ATIVO' AND MONTH(data_venda) = '$mes'");

                  $linhaVendas = mysqli_fetch_array($sqlVendas);
                  do {
                    $ponto += $linhaVendas['ponto'];


                  } while ($linhaVendas = mysqli_fetch_assoc($sqlVendas));


                    $dataA[] = array('id' => $idOperador2, 'ponto' => $ponto);

                    // Colocar lista 2 em ordem
                    foreach ($dataA as $key => $row) {
                        $idV[$key]  = $row['id'];
                        $pontoGeralA[$key] = $row['ponto'];
                    }

                    array_multisort($pontoGeralA, SORT_DESC, $dataA);

                };

                $contador = count($dataA);
                for ($i = 0; $i < $contador; $i++){
                    $controle = $i + 1;
                    $teste1 = $dataA[$i]['id'];

                    $teste3 = mysqli_query($connect, "SELECT * FROM operador WHERE id = $teste1");
                    $linhaTeste3 = mysqli_fetch_array($teste3);

                    $nome = $linhaTeste3['login'];


                    $teste2 = $dataA[$i]['ponto'];
                    echo "$controle ª - $nome: $teste2<br>";
                };



               ?>
            </div>

            <div class="atvMov" style="background-color: #B0E0E6;">
              <?php
                echo "<h6>Ativo + Móvel: </h6>";
                //Fazer lista de Id de Operadores do supervisor
                $supervisor = $dados['id'];
                $sql = mysqli_query($connect, "SELECT * FROM operador WHERE id_super = $supervisor AND cargo = 'VENDEDOR'");
                $linha = mysqli_fetch_array($sql);

                do{
                  $idOperador = $linha['id'];
                  $lista1[] = array('id' => $idOperador);
                }while ($linha = mysqli_fetch_assoc($sql));
                $contador = count($lista1);

                $mes = date('m');


                //Fazer Lista com Id e pontos do vendedor no dia
                for ($i = 0; $i < $contador; $i++){
                  $ponto = 0;
                  $pontoM = 0;
                  $idOperador2 = $lista1[$i]['id'];
                  $sqlVendas = mysqli_query($connect,"SELECT * FROM proposta
                    WHERE id_vendedor = '$idOperador2' AND situacao = 'ATIVO' AND MONTH(data_venda) = '$mes'");

                  $linhaVendas = mysqli_fetch_array($sqlVendas);
                  do {
                    $ponto += $linhaVendas['ponto'];
                    $pontoM += $linhaVendas['pontoM'];

                  } while ($linhaVendas = mysqli_fetch_assoc($sqlVendas));
                    $ponto += $pontoM;

                    $data[] = array('id' => $idOperador2, 'ponto' => $ponto);

                    // Colocar lista 2 em ordem
                    foreach ($data as $key => $row) {
                        $idV[$key]  = $row['id'];
                        $pontoGeral[$key] = $row['ponto'];
                    }

                    array_multisort($pontoGeral, SORT_DESC, $data);

                };

                $contador = count($data);
                for ($i = 0; $i < $contador; $i++){
                    $controle = $i + 1;
                    $teste1 = $data[$i]['id'];

                    $teste3 = mysqli_query($connect, "SELECT * FROM operador WHERE id = $teste1");
                    $linhaTeste3 = mysqli_fetch_array($teste3);

                    $nome = $linhaTeste3['login'];


                    $teste2 = $data[$i]['ponto'];
                    echo "$controle ª - $nome: $teste2<br>";
                };



               ?>
            </div>


        </div>
      </div>
    </div>

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

          <button type="button" class="btn btn-primary" data-toggle="modal" onclick="incluiSupervisor()">Enviar</button>

          <div id="resultado">

          </div>

        </form>
      </div>
    </div>
   </div>





   <!--TELA PRINCIPAL-->

   <div style="width: 600px; padding: 10px; margin: 0;float: left; background-color: #DCDCDC;">
     <h4>Vendas do dia: </h4>
   <?php
   $sql = $sql = mysqli_query($connect, "SELECT * FROM supervisor") or print mysql_error();
   $linha = mysqli_fetch_array($sql);

   do {
     $idSupervisor = $linha['id'];
     $nomeSupervisor = $linha['login'];
     $dadosSupervisor[]= array('idSuper' => $idSupervisor, 'nomeSuper' => $nomeSupervisor);
   } while ($linha = mysqli_fetch_assoc($sql));
   $controleGerencia = count($dadosSupervisor);

   for ($i=0; $i < $controleGerencia; $i++) {

     $nomeSupervisor = $dadosSupervisor[$i]['nomeSuper'];
     $supervisor = $dadosSupervisor[$i]['idSuper'];

     $hoje = date('Y-m-d');

     $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE data_venda = '$hoje' AND id_super = $supervisor") or print mysql_error();
     $linha = mysqli_fetch_array($sql);



     $contrato= $linha["contrato"];
     echo "<h4>$nomeSupervisor:</h4>";
     if(mysqli_num_rows($sql)>0):

       echo "<div class=\"table-responsive-xl\">";
       echo "<table class=\"table table-hover text-center text-truncate\">";
       echo "<thead class=\"thead-dark\">";
       echo "<tr>";

       echo   "<th scope=\"col\">Vendedor</th>";
       echo   "<th scope=\"col\">Status</th>";
       echo   "<th scope=\"col\">Data Instalação</th>";
       echo   "<th scope=\"col\">Turno</th>";
       echo   "<th scope=\"col\">Inst Chamado</th>";
       echo   "<th scope=\"col\">Cidade</th>";
       echo   "<th scope=\"col\">Contrato</th>";
       echo   "<th scope=\"col\">Data Venda</th>";
       echo   "<th scope=\"col\">CPF Cliente</th>";
       echo   "<th scope=\"col\">Cliente</th>";
       echo   "<th scope=\"col\">Fone1</th>";
       echo   "<th scope=\"col\">Fone2</th>";
       echo   "<th scope=\"col\">Tv</th>";
       echo   "<th scope=\"col\">Pt Adc Tv</th>";
       echo   "<th scope=\"col\">Internet</th>";
       echo   "<th scope=\"col\">Fone</th>";
       echo   "<th scope=\"col\">Móvel</th>";
       echo   "<th scope=\"col\">Valor</th>";
       echo   "<th scope=\"col\">Pontuação</th>";
       echo   "<th scope=\"col\">Pontuação - Móvel</th>";
       echo   "<th scope=\"col\">BackOffice</th>";
       echo "</tr>";
       echo "</thead>";

       echo "<tbody>";
       //Lembrete = tentar fazer uma nova tabela a cada ciclo
       do {

         $clienteCpf = $linha["cpf_cliente"];
         $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
         $linhaC = mysqli_fetch_array($sqlC);

         $vendedor = $linha["id_vendedor"];
         $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$vendedor}'") or print mysql_error();
         $linhaV = mysqli_fetch_array($sqlV);

          $nmVendedor = $linhaV['login'];
           echo "<td>$nmVendedor</td>";

           $status = $linha["situacao"];
           echo   "<td>$status</td>";

           $dtInstala = $linha["data_instalacao"];
           if(empty($dtInstala)):
             echo   "<td>$dtInstala</td>";
           else:
             $newDate = date("d/m/Y", strtotime($dtInstala));
             echo   "<td>$newDate</td>";
           endif;

             $turnoInst = $linha["turnoinst"];
             if ($turnoInst == "NULL"):
               echo "<td></td>";
             else:
               echo   "<td>$turnoInst</td>";
             endif;

             $chamadoinst = $linha["chamadoinst"];
             if ($chamadoinst == "NULL"):
               echo "<td></td>";
             else:
               echo   "<td>$chamadoinst</td>";
             endif;



             $localCidade = $linhaC["cidade"];
             echo   "<td>$localCidade</td>";

             $contratoC = $linha["contrato"];
             echo   "<td>$contratoC</td>";



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

           $F1Cliente = $linhaC["telfixo"];
           echo   "<td>$F1Cliente</td>";

           $F2Cliente = $linhaC["telmovel"];
           echo   "<td>$F2Cliente</td>";

           $planoTv = $linha["tv"];
           if ($planoTv == "NULL"):
             echo   "<td></td>";
           else:
             echo "<td>$planoTv</td>";
           endif;

           $pontoTv = $linha["pt_adc_tv"];
           if ($pontoTv == 0):
             echo "<td></td>";
           else:
             echo   "<td>$pontoTv</td>";
           endif;

           $net = $linha["internet"];
           if ($net == "NULL"):
             echo "<td></td>";
           else:
             echo   "<td>$net</td>";
           endif;

           $fone = $linha["telefone"];
           if ($fone == "NULL"):
             echo "<td></td>";
           else:
             echo   "<td>$fone</td>";
           endif;

           $movel = $linha["movel"];
           if ($movel == "NULL"):
             echo "<td></td>";
           else:
             echo   "<td>$movel</td>";
           endif;

           $precoP = number_format($linha["preco"], 2, '.', '');
             echo   "<td>R$ $precoP</td>";





           $pontuacao = $linha["ponto"];
           echo   "<td>$pontuacao</td>";

           $pontuacaoMovel = $linha["pontoM"];
           echo   "<td>$pontuacaoMovel</td>";

           $idBko = $linha["id_bko"];
           $sqlB = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idBko}'") or print mysql_error();
           $linhaB = mysqli_fetch_array($sqlB);
           $nmBko = $linhaB["login"];
           echo   "<td>$nmBko</td>";

           echo "</tr>";







       } while ($linha = mysqli_fetch_assoc($sql));
       echo "</tbody>";

       echo "</table>";
       echo "</div>";
     else:
       echo "<h5>Sem dados</h5>";
     endif;
   }





     ?>
  </div>

 <div style="margin: 0;">
  <div id="ranking" style="width: 400px; height: 130px; padding: 10px; float: right; background-color: #DCDCDC; margin-bottom: 17px; overflow:auto;">
    <h5>Ranking:</h5>
    <?php
      //Fazer lista de Id de Operadores do supervisor

      $sql = mysqli_query($connect, "SELECT * FROM operador WHERE cargo = 'VENDEDOR'");
      $linha = mysqli_fetch_array($sql);

      do{
        $idOperador = $linha['id'];
        $idSuper = $linha['id_super'];
        $listaB1[] = array('idVendedor' => $idOperador, 'idSupervisor' => $idSuper);
      }while ($linha = mysqli_fetch_assoc($sql));
      $contador1 = count($listaB1);


      //Fazer Lista com Id e pontos do vendedor no dia
      for ($i = 0; $i < $contador1; $i++){
        $ponto = 0;
        $pontoM = 0;
        $idOperador2 = $listaB1[$i]['idVendedor'];
        $idSuper2 = $listaB1[$i]['idSupervisor'];
        $sqlVendas = mysqli_query($connect,"SELECT * FROM proposta
          WHERE id_vendedor = '$idOperador2' AND situacao = 'APROVADO' AND data_venda = '$hoje'
          OR id_vendedor = '$idOperador2' AND situacao = 'APROVADO DIVERGENTE' AND data_venda = '$hoje'");

        $linhaVendas = mysqli_fetch_array($sqlVendas);
        do {
          $ponto += $linhaVendas['ponto'];
          $pontoM += $linhaVendas['pontoM'];

        } while ($linhaVendas = mysqli_fetch_assoc($sqlVendas));
          $ponto += $pontoM;

          $data2[] = array('idVendedor' => $idOperador2, 'ponto' => $ponto, 'idSupervisor' => $idSuper2);
      };

      // Colocar lista 2 em ordem
      foreach ($data2 as $key => $row) {
          $pontoGeral[$key] = $row['ponto'];
      }

      array_multisort($pontoGeral, SORT_DESC, $data2);



      $contador2 = count($data2);

      echo "<div class=\"table-responsive-xl\">";
      echo "<table class=\"table table-sm table-hover text-center text-truncate\">";
      echo "<thead class=\"thead-dark\">";
      echo "<tr>";
      echo   "<th scope=\"col\">#</th>";
      echo   "<th scope=\"col\">Vendedor</th>";
      echo   "<th scope=\"col\">Pontos</th>";
      echo   "<th scope=\"col\">Supervisor</th>";
      echo "</tr>";
      echo "</thead>";
      echo "<tbody>";
      for ($i = 0; $i < $contador2; $i++){
          $controle = $i + 1;
          $teste1 = $data2[$i]['idVendedor'];
          $teste4 = $data2[$i]['idSupervisor'];

          $teste3 = mysqli_query($connect, "SELECT * FROM operador WHERE id = $teste1");
          $linhaTeste3 = mysqli_fetch_array($teste3);

          $nome = $linhaTeste3['login'];

          $teste3 = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = $teste4");
          $linhaTeste3 = mysqli_fetch_array($teste3);

          $nomeSuper = $linhaTeste3['login'];


          $teste2 = $data2[$i]['ponto'];

          echo "<tr>";
          echo "<td>$controle ª</td>";
          echo "<td>$nome</td>";
          echo "<td>$teste2</td>";
          echo "<td>$nomeSuper</td>";
          echo "</tr>";
      };

      echo "</tbody>";

      echo "</table>";
      echo "</div>";
     ?>

  </div>

  <div style="margin: 0;">
   <div id="ranking" style="width: 400px; height: 130px; padding: 10px; float: right; background-color: #DCDCDC; margin-bottom: 17px; overflow:auto;">
     <h5>Ranking Supervisor:</h5>
     <?php
       //Fazer lista de Id de Operadores do supervisor

       $sql = mysqli_query($connect, "SELECT * FROM supervisor");
       $linha = mysqli_fetch_array($sql);

       do{

         $idSuper = $linha['id'];
         $nomeSuper = $linha['login'];
         $listaB2[] = array('idSupervisor' => $idSuper, 'nomeSupervisor' => $nomeSuper);
       }while ($linha = mysqli_fetch_assoc($sql));
       $contador1 = count($listaB2);


       //Fazer Lista com Id e pontos do vendedor no dia
       for ($i = 0; $i < $contador1; $i++){
         $ponto = 0;
         $pontoM = 0;
         $nomeSuper2 = $listaB2[$i]['nomeSupervisor'];
         $idSuper2 = $listaB2[$i]['idSupervisor'];
         $sqlVendas = mysqli_query($connect,"SELECT * FROM proposta
           WHERE id_super = $idSuper2 AND situacao = 'APROVADO' AND data_venda = '$hoje'
           OR id_super = $idSuper2 AND situacao = 'APROVADO DIVERGENTE' AND data_venda = '$hoje'");

         $linhaVendas = mysqli_fetch_array($sqlVendas);
         do {
           $ponto += $linhaVendas['ponto'];
           $pontoM += $linhaVendas['pontoM'];

         } while ($linhaVendas = mysqli_fetch_assoc($sqlVendas));
           $ponto += $pontoM;

           $dataB[] = array('ponto' => $ponto, 'nomeSupervisor' => $nomeSuper2,'idSupervisor' => $idSuper2);
       };

       // Colocar lista 2 em ordem
       foreach ($dataB as $key => $row) {
           $pontoSupervisor[$key] = $row['ponto'];
           $nomeSupervisor[$key] = $row['nomeSupervisor'];
           $idSupervisor[$key] = $row['idSupervisor'];
       }

       array_multisort($pontoSupervisor, SORT_DESC, $dataB);



       $contador2 = count($dataB);

       echo "<div class=\"table-responsive-xl\">";
       echo "<table class=\"table table-sm table-hover text-center text-truncate\">";
       echo "<thead class=\"thead-dark\">";
       echo "<tr>";
       echo   "<th scope=\"col\">#</th>";
       echo   "<th scope=\"col\">Supervisor</th>";
       echo   "<th scope=\"col\">Pontos</th>";
       echo "</tr>";
       echo "</thead>";
       echo "<tbody>";
       for ($i = 0; $i < $contador2; $i++){
           $controle = $i + 1;
           $teste4 = $dataB[$i]['idSupervisor'];


           $teste3 = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = $teste4");
           $linhaTeste3 = mysqli_fetch_array($teste3);

           $nomeSuper = $linhaTeste3['login'];


           $teste2 = $data2[$i]['ponto'];

           echo "<tr>";
           echo "<td>$controle ª</td>";
           echo "<td>$nomeSuper</td>";
           echo "<td>$teste2</td>";
           echo "</tr>";
       };

       echo "</tbody>";

       echo "</table>";
       echo "</div>";
      ?>

   </div>

    <div id="pendencias" style="width: 400px; padding: 10px; background-color: #DCDCDC; float: right; clear: right;;">
      <h5>Pendências:</h5>

      <?php
      $hoje = date('Y-m-d');
      $supervisor = $dados["id"];

      $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE situacao != 'APROVADO' AND situacao != 'APROVADO DIVERGENTE' AND situacao != 'CADASTRO OK' AND data_venda = '$hoje'") or print mysql_error();
      $linha = mysqli_fetch_array($sql);



      $contrato= $linha["contrato"];

      if(mysqli_num_rows($sql)>0):
        echo "<div class=\"table-responsive-xl\">";
        echo "<table class=\"table table-hover text-center text-truncate\">";
        echo "<thead class=\"thead-dark\">";
        echo "<tr>";

        echo   "<th scope=\"col\">Vendedor</th>";
        echo   "<th scope=\"col\">Supervisor</th>";
        echo   "<th scope=\"col\">Status</th>";
        echo   "<th scope=\"col\">Data Instalação</th>";
        echo   "<th scope=\"col\">Turno</th>";
        echo   "<th scope=\"col\">Inst Chamado</th>";
        echo   "<th scope=\"col\">Cidade</th>";
        echo   "<th scope=\"col\">Contrato</th>";
        echo   "<th scope=\"col\">Data Venda</th>";
        echo   "<th scope=\"col\">CPF Cliente</th>";
        echo   "<th scope=\"col\">Cliente</th>";
        echo   "<th scope=\"col\">Fone1</th>";
        echo   "<th scope=\"col\">Fone2</th>";
        echo   "<th scope=\"col\">Tv</th>";
        echo   "<th scope=\"col\">Pt Adc Tv</th>";
        echo   "<th scope=\"col\">Internet</th>";
        echo   "<th scope=\"col\">Fone</th>";
        echo   "<th scope=\"col\">Móvel</th>";
        echo   "<th scope=\"col\">Valor</th>";
        echo   "<th scope=\"col\">Pontuação</th>";
        echo   "<th scope=\"col\">Pontuação - Móvel</th>";
        echo   "<th scope=\"col\">BackOffice</th>";
        echo "</tr>";
        echo "</thead>";

        echo "<tbody>";
        //Lembrete = tentar fazer uma nova tabela a cada ciclo
        do {

          $clienteCpf = $linha["cpf_cliente"];
          $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
          $linhaC = mysqli_fetch_array($sqlC);

          $vendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$vendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);

           $nmVendedor = $linhaV['login'];
            echo "<td>$nmVendedor</td>";

           $idSupervisor = $linha["id_super"];
           $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSupervisor}'") or print mysql_error();
           $linhaS = mysqli_fetch_array($sqlS);

           $nmSupervisor = $linhaS['login'];
            echo "<td>$nmSupervisor</td>";

            $status = $linha["situacao"];
            echo   "<td>$status</td>";

            $dtInstala = $linha["data_instalacao"];
            if(empty($dtInstala)):
              echo   "<td>$dtInstala</td>";
            else:
              $newDate = date("d/m/Y", strtotime($dtInstala));
              echo   "<td>$newDate</td>";
            endif;

              $turnoInst = $linha["turnoinst"];
              if ($turnoInst == "NULL"):
                echo "<td></td>";
              else:
                echo   "<td>$turnoInst</td>";
              endif;

              $chamadoinst = $linha["chamadoinst"];
              if ($chamadoinst == "NULL"):
                echo "<td></td>";
              else:
                echo   "<td>$chamadoinst</td>";
              endif;



              $localCidade = $linhaC["cidade"];
              echo   "<td>$localCidade</td>";

              $contratoC = $linha["contrato"];
              echo   "<td>$contratoC</td>";



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

            $F1Cliente = $linhaC["telfixo"];
            echo   "<td>$F1Cliente</td>";

            $F2Cliente = $linhaC["telmovel"];
            echo   "<td>$F2Cliente</td>";

            $planoTv = $linha["tv"];
            if ($planoTv == "NULL"):
              echo   "<td></td>";
            else:
              echo "<td>$planoTv</td>";
            endif;

            $pontoTv = $linha["pt_adc_tv"];
            if ($pontoTv == 0):
              echo "<td></td>";
            else:
              echo   "<td>$pontoTv</td>";
            endif;

            $net = $linha["internet"];
            if ($net == "NULL"):
              echo "<td></td>";
            else:
              echo   "<td>$net</td>";
            endif;

            $fone = $linha["telefone"];
            if ($fone == "NULL"):
              echo "<td></td>";
            else:
              echo   "<td>$fone</td>";
            endif;

            $movel = $linha["movel"];
            if ($movel == "NULL"):
              echo "<td></td>";
            else:
              echo   "<td>$movel</td>";
            endif;

            $precoP = number_format($linha["preco"], 2, '.', '');
              echo   "<td>R$ $precoP</td>";





            $pontuacao = $linha["ponto"];
            echo   "<td>$pontuacao</td>";

            $pontuacaoMovel = $linha["pontoM"];
            echo   "<td>$pontuacaoMovel</td>";

            $idBko = $linha["id_bko"];
            $sqlB = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idBko}'") or print mysql_error();
            $linhaB = mysqli_fetch_array($sqlB);
            $nmBko = $linhaB["login"];
            echo   "<td>$nmBko</td>";

            echo "</tr>";







        } while ($linha = mysqli_fetch_assoc($sql));
        echo "</tbody>";

        echo "</table>";
        echo "</div>";
      else:
        echo "<h6>Sem dados</h6>";
      endif;

        ?>

    </div>

    <div id="instalacoes" style="width: 400px; padding: 10px; background-color: #DCDCDC; float: right; clear: right; margin-top: 17px;">
      <h5>Instalações:</h5>

      <?php
      $hoje = date('Y-m-d');
      $supervisor = $dados["id"];

      $sql = mysqli_query($connect, "SELECT * FROM proposta WHERE data_instalacao = '$hoje'") or print mysql_error();
      $linha = mysqli_fetch_array($sql);



      $contrato= $linha["contrato"];

      if(mysqli_num_rows($sql)>0):
        echo "<div class=\"table-responsive-xl\">";
        echo "<table class=\"table table-hover text-center text-truncate\">";
        echo "<thead class=\"thead-dark\">";
        echo "<tr>";

        echo   "<th scope=\"col\">Vendedor</th>";
        echo   "<th scope=\"col\">Supervisor</th>";
        echo   "<th scope=\"col\">Status</th>";
        echo   "<th scope=\"col\">Data Instalação</th>";
        echo   "<th scope=\"col\">Turno</th>";
        echo   "<th scope=\"col\">Inst Chamado</th>";
        echo   "<th scope=\"col\">Cidade</th>";
        echo   "<th scope=\"col\">Contrato</th>";
        echo   "<th scope=\"col\">Data Venda</th>";
        echo   "<th scope=\"col\">CPF Cliente</th>";
        echo   "<th scope=\"col\">Cliente</th>";
        echo   "<th scope=\"col\">Fone1</th>";
        echo   "<th scope=\"col\">Fone2</th>";
        echo   "<th scope=\"col\">Tv</th>";
        echo   "<th scope=\"col\">Pt Adc Tv</th>";
        echo   "<th scope=\"col\">Internet</th>";
        echo   "<th scope=\"col\">Fone</th>";
        echo   "<th scope=\"col\">Móvel</th>";
        echo   "<th scope=\"col\">Valor</th>";
        echo   "<th scope=\"col\">Pontuação</th>";
        echo   "<th scope=\"col\">Pontuação - Móvel</th>";
        echo   "<th scope=\"col\">BackOffice</th>";
        echo "</tr>";
        echo "</thead>";

        echo "<tbody>";
        //Lembrete = tentar fazer uma nova tabela a cada ciclo
        do {

          $clienteCpf = $linha["cpf_cliente"];
          $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
          $linhaC = mysqli_fetch_array($sqlC);

          $vendedor = $linha["id_vendedor"];
          $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$vendedor}'") or print mysql_error();
          $linhaV = mysqli_fetch_array($sqlV);

           $nmVendedor = $linhaV['login'];
            echo "<td>$nmVendedor</td>";

            $idSupervisor = $linha["id_super"];
            $sqlS = mysqli_query($connect, "SELECT * FROM supervisor WHERE id = '{$idSupervisor}'") or print mysql_error();
            $linhaS = mysqli_fetch_array($sqlS);

            $nmSupervisor = $linhaS['login'];
             echo "<td>$nmSupervisor</td>";

            $status = $linha["situacao"];
            echo   "<td>$status</td>";

            $dtInstala = $linha["data_instalacao"];
            if(empty($dtInstala)):
              echo   "<td>$dtInstala</td>";
            else:
              $newDate = date("d/m/Y", strtotime($dtInstala));
              echo   "<td>$newDate</td>";
            endif;

              $turnoInst = $linha["turnoinst"];
              if ($turnoInst == "NULL"):
                echo "<td></td>";
              else:
                echo   "<td>$turnoInst</td>";
              endif;

              $chamadoinst = $linha["chamadoinst"];
              if ($chamadoinst == "NULL"):
                echo "<td></td>";
              else:
                echo   "<td>$chamadoinst</td>";
              endif;



              $localCidade = $linhaC["cidade"];
              echo   "<td>$localCidade</td>";

              $contratoC = $linha["contrato"];
              echo   "<td>$contratoC</td>";



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

            $F1Cliente = $linhaC["telfixo"];
            echo   "<td>$F1Cliente</td>";

            $F2Cliente = $linhaC["telmovel"];
            echo   "<td>$F2Cliente</td>";

            $planoTv = $linha["tv"];
            if ($planoTv == "NULL"):
              echo   "<td></td>";
            else:
              echo "<td>$planoTv</td>";
            endif;

            $pontoTv = $linha["pt_adc_tv"];
            if ($pontoTv == 0):
              echo "<td></td>";
            else:
              echo   "<td>$pontoTv</td>";
            endif;

            $net = $linha["internet"];
            if ($net == "NULL"):
              echo "<td></td>";
            else:
              echo   "<td>$net</td>";
            endif;

            $fone = $linha["telefone"];
            if ($fone == "NULL"):
              echo "<td></td>";
            else:
              echo   "<td>$fone</td>";
            endif;

            $movel = $linha["movel"];
            if ($movel == "NULL"):
              echo "<td></td>";
            else:
              echo   "<td>$movel</td>";
            endif;

            $precoP = number_format($linha["preco"], 2, '.', '');
              echo   "<td>R$ $precoP</td>";





            $pontuacao = $linha["ponto"];
            echo   "<td>$pontuacao</td>";

            $pontuacaoMovel = $linha["pontoM"];
            echo   "<td>$pontuacaoMovel</td>";

            $idBko = $linha["id_bko"];
            $sqlB = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idBko}'") or print mysql_error();
            $linhaB = mysqli_fetch_array($sqlB);
            $nmBko = $linhaB["login"];
            echo   "<td>$nmBko</td>";

            echo "</tr>";







        } while ($linha = mysqli_fetch_assoc($sql));
        echo "</tbody>";

        echo "</table>";
        echo "</div>";
      else:
        echo "<h6>Sem dados</h6>";
      endif;

        ?>

    </div>

  </div>

   <script language="javascript">

             var nomeO = $("#nomeOperador");
             var loginO = $("#loginOperador");
             var senhaO = $("#senhaOperador");
             function incluiSupervisor(){
               $.ajax({
                  url : "incluirSupervisor.php",
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
