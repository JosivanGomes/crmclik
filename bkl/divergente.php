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

           <li class="nav-item">
             <a class="nav-link" href="bkl.php">Home</a>
           </li>

           <li>
             <a href="dm1.php" class="btn btn-primary" role="button" aria-pressed="true">D -1 <span class="badge badge-danger">
                <?php
                  $dm1 = date('Y-m-d', strtotime('+1 days'));
                  $con = mysqli_connect("localhost", "root", "", "crmclik");
                  $sql = mysqli_query($con, "SELECT * FROM proposta WHERE situacao = 'APROVADO' AND data_instalacao = '$dm1'") or print mysql_error();
                  echo mysqli_num_rows($sql);
                ?>
              </span>
            </a>
           </li>

           <li>
             <a href="divergente.php" class="btn btn-danger" role="button" aria-pressed="true" style="margin-left: 10px;">Divergente <span class="badge badge-primary">
                <?php
                  $d = date('m');
                  $con = mysqli_connect("localhost", "root", "", "crmclik");
                  $sql = mysqli_query($con, "SELECT * FROM proposta WHERE  MONTH(data_instalacao) = '$dm1' AND situacao = 'APROVADO DIVERGENTE' OR situacao = 'BACKLOG'") or print mysql_error();
                  echo mysqli_num_rows($sql);
                ?>
              </span>
            </a>
           </li>

           <li>
             <a href="cancelados.php" class="btn btn-primary" role="button" aria-pressed="true" style="margin-left: 10px;">Cancelados <span class="badge badge-danger">
                <?php
                  $d = date('m');
                  $con = mysqli_connect("localhost", "root", "", "crmclik");
                  $sql = mysqli_query($con, "SELECT * FROM proposta WHERE situacao = 'CANCELADO' AND MONTH(data_instalacao) = '$d'") or print mysql_error();
                  echo mysqli_num_rows($sql);
                ?>
              </span>
            </a>
           </li>




           <li class="nav-item">
             <a class="nav-link" href="logout.php">Sair</a>
           </li>

         </ul>

     </nav>
   </header>



   <!--TELA PRINCIPAL-->


     <?php
     $vendedor = $dados["id"];
     $nmvendedor = $dados["login"];
     $con = mysqli_connect("localhost", "root", "", "crmclik");
     $sql = mysqli_query($con, "SELECT * FROM proposta WHERE id_bkoPend = '{$vendedor}' AND situacao = 'TRATAMENTO DIVERGENTE'") or print mysql_error();
     $linha = mysqli_fetch_array($sql);

     echo "<h4>Pessoal:</h4>";


     $contrato = $linha["contrato"];
     if(mysqli_num_rows($sql)>0):
       echo "<h6>Aprovado Divergente: </h6>";
       echo "<div class=\"table-responsive-xl\">";
       echo "<table class=\"table table-hover text-center text-truncate\">";
       echo "<thead class=\"thead-dark\">";
       echo "<tr>";

       echo   "<th scope=\"col\"></th>";
       echo   "<th scope=\"col\">Contrato</th>";
       echo   "<th scope=\"col\">Sit Contrato</th>";
       echo   "<th scope=\"col\">Status</th>";
       echo   "<th scope=\"col\">Turno</th>";
       echo   "<th scope=\"col\">Data Instalação</th>";
       echo   "<th scope=\"col\">Vendedor</th>";
       echo   "<th scope=\"col\">Supervisor</th>";
       echo   "<th scope=\"col\">Cidade</th>";
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

       echo "</tr>";
       echo "</thead>";

       echo "<tbody>";
       //Lembrete = tentar fazer uma nova tabela a cada ciclo
       do {



         $clienteCpf = $linha["cpf_cliente"];
         $sqlC = mysqli_query($con, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
         $linhaC = mysqli_fetch_array($sqlC);
         $idVenda = $linha["id"];



         $clienteCpf = $linha["cpf_cliente"];
         $sqlC = mysqli_query($con, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
         $linhaC = mysqli_fetch_array($sqlC);

         $idVenda = $linha["id"];

           echo   "<td>

                      <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"finaliza$idVenda()\">Concluir</button>

                  </td>";

           $contrato = $linha["contrato"];
           echo   "<td>$contrato</td>";

           $contratoSit = $linha["sitctrt"];
           echo   "<td>$contratoSit</td>";

           $status = $linha["situacao"];
           $obs = $linha["observacao"];
           echo   "<td title=\"Obs: $obs\">$status</td>";

           echo "<td>
                 <select id=\"turno$idVenda\">
                   <option></option>
                   <option>Manha</option>
                   <option>Tarde</option>
                 </td>";

           echo "<td>
                  <input id=\"dtInstala$idVenda\" type=\"date\" style=\"font-size:13px\">
                 </td>";

           $idVendedor = $linha["id_vendedor"];
           $sqlV = mysqli_query($con, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
           $linhaV = mysqli_fetch_array($sqlV);
           $nmVend = $linhaV["login"];
           echo   "<td>$nmVend</td>";

           $idSuper = $linhaV["id_super"];
           $sqlS = mysqli_query($con, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
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





             $chamado = $linha["chamadoinst"];


           echo "</tr>";

           echo "<!-- Modal -->


                <script>

                var statusVenda$idVenda = document.getElementById('status$idVenda');

                  function finaliza$idVenda(){


                    $.ajax({
                      url: 'atualizabklDiv.php',
                      type: 'POST',
                      data:{\"turnoVenda\" : turno$idVenda.value,
                            \"instalacaoVenda\" : dtInstala$idVenda.value,
                            \"idVenda\" : $idVenda
                            },

                      success: function(data) {
                        console.log(data);
                        data = $.parseJSON(data);
                          alert(data.retorno);

                          window.location.reload()
                        }


                      });


                  }

                </script>

                ";






       } while ($linha = mysqli_fetch_assoc($sql));
       echo "</tbody>";

       echo "</table>";
       echo "</div>";
     else:
       echo "Sem dados";
     endif;


     $sql = mysqli_query($con, "SELECT * FROM proposta WHERE id_bkoPend = '{$vendedor}' AND situacao = 'TRATAMENTO BACKLOG'") or print mysql_error();
     $linha = mysqli_fetch_array($sql);



     $contrato = $linha["contrato"];
     if(mysqli_num_rows($sql)>0):
       echo "<h6>BACKLOG: </h6>";
       echo "<div class=\"table-responsive-xl\">";
       echo "<table class=\"table table-hover text-center text-truncate\">";
       echo "<thead class=\"thead-dark\">";
       echo "<tr>";

       echo   "<th scope=\"col\"></th>";
       echo   "<th scope=\"col\">Contrato</th>";
       echo   "<th scope=\"col\">Sit Contrato</th>";
       echo   "<th scope=\"col\">Status</th>";
       echo   "<th scope=\"col\">Turno</th>";
       echo   "<th scope=\"col\">Data Instalação</th>";
       echo   "<th scope=\"col\">Vendedor</th>";
       echo   "<th scope=\"col\">Supervisor</th>";
       echo   "<th scope=\"col\">Cidade</th>";
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

       echo "</tr>";
       echo "</thead>";

       echo "<tbody>";
       //Lembrete = tentar fazer uma nova tabela a cada ciclo
       do {



         $clienteCpf = $linha["cpf_cliente"];
         $sqlC = mysqli_query($con, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
         $linhaC = mysqli_fetch_array($sqlC);
         $idVenda = $linha["id"];



         $clienteCpf = $linha["cpf_cliente"];
         $sqlC = mysqli_query($con, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
         $linhaC = mysqli_fetch_array($sqlC);

         $idVenda = $linha["id"];

           echo   "<td>

                      <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"finaliza$idVenda()\">Concluir</button>

                  </td>";

           $contrato = $linha["contrato"];
           echo   "<td>$contrato</td>";

           $contratoSit = $linha["sitctrt"];
           echo   "<td>$contratoSit</td>";

           $status = $linha["situacao"];
           $obs = $linha["observacao"];
           echo   "<td title=\"Obs: $obs\">$status</td>";

           echo "<td>
                 <select id=\"turno$idVenda\">
                   <option></option>
                   <option>Manha</option>
                   <option>Tarde</option>
                 </td>";

           echo "<td>
                  <input id=\"dtInstala$idVenda\" type=\"date\" style=\"font-size:13px\">
                 </td>";

           $idVendedor = $linha["id_vendedor"];
           $sqlV = mysqli_query($con, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
           $linhaV = mysqli_fetch_array($sqlV);
           $nmVend = $linhaV["login"];
           echo   "<td>$nmVend</td>";

           $idSuper = $linhaV["id_super"];
           $sqlS = mysqli_query($con, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
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





             $chamado = $linha["chamadoinst"];


           echo "</tr>";

           echo "<!-- Modal -->


                <script>

                var statusVenda$idVenda = document.getElementById('status$idVenda');

                  function finaliza$idVenda(){


                    $.ajax({
                      url: 'atualizabklDiv.php',
                      type: 'POST',
                      data:{\"turnoVenda\" : turno$idVenda.value,
                            \"instalacaoVenda\" : dtInstala$idVenda.value,
                            \"idVenda\" : $idVenda
                            },

                      success: function(data) {
                        console.log(data);
                        data = $.parseJSON(data);
                          alert(data.retorno);

                          window.location.reload()
                        }


                      });


                  }

                </script>

                ";






       } while ($linha = mysqli_fetch_assoc($sql));
       echo "</tbody>";

       echo "</table>";
       echo "</div>";
     else:
       echo "Sem dados";
     endif;



       //Dados Gerais
       echo "<h4>Geral:</h4>";
       $mes = date('m');
       $sql = mysqli_query($con, "SELECT * FROM proposta WHERE MONTH(data_instalacao) = '$mes' and situacao = 'APROVADO DIVERGENTE' OR situacao = 'BACKLOG'") or print mysql_error();
       $linha = mysqli_fetch_array($sql);


       $contrato= $linha["contrato"];
       if(mysqli_num_rows($sql)>0):

         echo "<div class=\"table-responsive-xl\">";
         echo "<table class=\"table table-hover text-center text-truncate\">";
         echo "<thead class=\"thead-dark\">";
         echo "<tr>";

         echo   "<th scope=\"col\"></th>";
         echo   "<th scope=\"col\">Contrato</th>";
         echo   "<th scope=\"col\">Sit Contrato</th>";
         echo   "<th scope=\"col\">Status</th>";
         echo   "<th scope=\"col\">Turno</th>";
         echo   "<th scope=\"col\">Data Instalação</th>";
         echo   "<th scope=\"col\">Vendedor</th>";
         echo   "<th scope=\"col\">Supervisor</th>";
         echo   "<th scope=\"col\">Cidade</th>";
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
         echo "</tr>";
         echo "</thead>";

         echo "<tbody>";
         //Lembrete = tentar fazer uma nova tabela a cada ciclo
         do {

           $clienteCpf = $linha["cpf_cliente"];
           $sqlC = mysqli_query($con, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
           $linhaC = mysqli_fetch_array($sqlC);

           $idVenda = $linha["id"];

             echo   "<td>

                        <button type=\"button\" class=\"btn btn-outline-primary\" onclick=\"pgProposta($idVenda)\">Tratar</button>

                    </td>";

             $contrato = $linha["contrato"];
             echo   "<td>$contrato</td>";

             $contratoSit = $linha["sitctrt"];
             echo   "<td>$contratoSit</td>";

             $status = $linha["situacao"];
             $obs = $linha["observacao"];
             echo   "<td title=\"Obs: $obs\">$status</td>";

             $turno = $linha["turnoinst"];
             echo   "<td>$turno</td>";

             $dtVendaIns = $linha["data_instalacao"];
             if(empty($dtVendaIns)):
               echo   "<td>$dtVendaIns</td>";
             else:
               $newDateIns = date("d/m/Y", strtotime($dtVendaIns));
               echo   "<td>$newDateIns</td>";
             endif;

             $idVendedor = $linha["id_vendedor"];
             $sqlV = mysqli_query($con, "SELECT * FROM operador WHERE id = '{$idVendedor}'") or print mysql_error();
             $linhaV = mysqli_fetch_array($sqlV);
             $nmVend = $linhaV["login"];
             echo   "<td>$nmVend</td>";

             $idSuper = $linhaV["id_super"];
             $sqlS = mysqli_query($con, "SELECT * FROM supervisor WHERE id = '{$idSuper}'") or print mysql_error();
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




             echo "</tr>";







         } while ($linha = mysqli_fetch_assoc($sql));
         echo "</tbody>";

         echo "</table>";
         echo "</div>";
       else:
         echo "Sem dados";
       endif;




      ?>

  <script>


  function pgProposta(x){
    $.ajax({
      url: 'enviarbkldiv.php',
      type: 'POST',
      data:{"proposta" : x},

      success: function(data) {
        console.log(data);
        data = $.parseJSON(data);
          alert(data.retorno);

          window.location.reload()
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
