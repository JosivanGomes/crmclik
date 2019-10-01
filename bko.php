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
         <img src="imagens/LOGO.png" width="30" height="30" class="rounded-circle">
         <p>Cli-K</p>
       </a>


         <ul class="navbar-nav" style="margin-left: 230px">


           <li>
             <button type="button" class="btn btn-primary">
              Pendências <span class="badge badge-danger">
                <?php
                  $con = mysqli_connect("localhost", "root", "", "crmclik");
                  $sql = mysqli_query($con, "SELECT * FROM proposta WHERE situacao = 'CHECK OK' OR sitctrt = 'OK'") or print mysql_error();
                  echo mysqli_num_rows($sql);
                ?>
              </span>
              </button>
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
       $sql = mysqli_query($con, "SELECT * FROM proposta WHERE id_bko = '{$vendedor}'") or print mysql_error();
       $linha = mysqli_fetch_array($sql);

       echo "<h4>Pessoal:</h4>";

       $contrato= $linha["contrato"];
       if(mysqli_num_rows($sql)>0):
         echo "<div class=\"table-responsive-xl\">";
         echo "<table class=\"table table-hover text-center text-truncate\">";
         echo "<thead class=\"thead-dark\">";
         echo "<tr>";

         echo "<th scope=\"col\"></th>";

         echo   "<th scope=\"col\">Status</th>";
         echo   "<th scope=\"col\">Data Instalação</th>";
         echo   "<th scope=\"col\">Turno</th>";
         echo   "<th scope=\"col\">Inst Chamado</th>";
         echo   "<th scope=\"col\">Contrato</th>";
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

                          <button type=\"button\" class=\"btn btn-success\">Finalizar</button>

             </td>";


             echo"<td>
                    <select id=\"status$idVenda \">
                      <option>Selecione ...</option>
                      <option>APROVADO</option>
                      <option>CHAMADO</option>
                      <option>NEGADO</option>
                      <option>CHECK OK</option>
                      <option>RETORNO VENDEDOR</option>
                      <option>BLOQUEADO</option>
                      <option>APROVADO DIVERGENTE</option>
                      <option>SEM CONTATO</option>
                    </select>
                  </td>";

             echo "<td>
                    <input id=\"dtInstala$idVenda\" type=\"date\" style=\"font-size:13px\">
                   </td>";

             echo "<td>
                   <select id=\"turno$idVenda \">
                     <option>Selecione ...</option>
                     <option>Manhã</option>
                     <option>Tarde</option>
                   </td>";

            echo "<td><button type=\"button\" class=\"btn btn-outline-primary\" data-toggle=\"modal\" data-target=\"#modal$idVenda\">Ver/Atualizar</button></td>";




               echo "<td>
                      <input type=\"text\">
                      <input type=\"checkbox\"  id=\"exampleCheck1\">
                      <label>OK</label>

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
                  <div class=\"modal fade\" id=\"modal$idVenda\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"TituloModalCentralizado\" aria-hidden=\"true\">
                    <div class=\"modal-dialog modal-dialog-centered\" role=\"document\">
                      <div class=\"modal-content\">
                        <div class=\"modal-header\">
                            $chamado
                          <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Fechar\">
                            <span aria-hidden=\"true\">&times;</span>
                          </button>
                        </div>

                        <div class=\"modal-body\">
                          <textarea id=\"chamadoinst$idVenda\" rows=\"5\" cols=\"63\">

                          </textarea>
                        </div>
                        <div class=\"modal-footer\">
                          <button type=\"button\" class=\"btn btn-secondary\" data-dismiss=\"modal\">Fechar</button>

                        </div>
                      </div>
                    </div>
                  </div>";




         } while ($linha = mysqli_fetch_assoc($sql));
         echo "</tbody>";

         echo "</table>";
         echo "</div>";
       else:
         echo "Sem dados";
       endif;





       //Dados Gerais
       echo "<h4>Geral:</h4>";

       $sql = mysqli_query($con, "SELECT * FROM proposta WHERE situacao = 'Cadastro - ok' OR situacao = 'SEM CONTATO'") or print mysql_error();
       $linha = mysqli_fetch_array($sql);


       $contrato= $linha["contrato"];
       if(mysqli_num_rows($sql)>0):

         echo "<div class=\"table-responsive-xl\">";
         echo "<table class=\"table table-hover text-center text-truncate\">";
         echo "<thead class=\"thead-dark\">";
         echo "<tr>";

         echo   "<th scope=\"col\"></th>";
         echo   "<th scope=\"col\">Status</th>";
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

             $status = $linha["situacao"];
             echo   "<td title=\"Obs: Lorem ipsum et at vitae posuere ullamcorper etiam nulla, inceptos phasellus id dapibus ullamcorper ornare pretium eu, placerat semper etiam feugiat justo porttitor semper. lacus ipsum eu dictum ultricies lacus himenaeos risus ut, consequat metus mollis sem tristique ultrices est fringilla et, inceptos pellentesque facilisis quisque sit proin convallis. proin molestie quisque dictum feugiat iaculis suscipit ornare orci augue, elementum scelerisque bibendum primis duis lacinia et tempor morbi scelerisque, sagittis quam in consequat consectetur pretium molestie fames. at viverra nibh aenean cubilia suscipit class purus, velit porttitor mattis laoreet integer.\">$status</td>";


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
      url: 'enviarbko.php',
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



   <!-- JavaScript (Opcional) -->

   <!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS -->
   <script src="https://code.jquery.com/jquery-3.1.1.min.js">
   <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
   <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
 </body>
</html>
