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


    <link href="https://fonts.googleapis.com/css?family=Anton&display=swap" rel="stylesheet">
    <style media="screen">
      #areaSuper h2{
        font-family: 'Anton', sans-serif;
        color: #4169E1;
      }
    </style>
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

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Alterna navegação">
          <span class="navbar-toggler-icon"></span>
        </button>
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="operacao.php">Minhas Vendas</a>
            </li>
            <li class="nav-item dropdown" id="drop">
              <a class="nav-link dropdown-toggle" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">Dashboard</a>
              <div class="dropdown-menu">
                <a class="dropdown-item" href="#">Em Aberto</a>
                <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="#">Finalizado: Mês passado</a>
                  <a class="dropdown-item" href="#">Finalizado: Mês retrasado</a>
              </div>
            </li>
            <li class="nav-item">

              <!-- Botão para acionar modal -->
              <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#ExemploModalCentralizado">
                Nova Venda
              </button>

            </li>

            <li class="nav-item">
              <a class="nav-link" href="logout.php">Sair</a>
            </li>

          </ul>

      </nav>
    </header>
    <!-- Modal -->
    <div class="modal fade bd-example-modal-lg" id="ExemploModalCentralizado" tabindex="-1" role="dialog" aria-labelledby="TituloModalCentralizado" aria-hidden="true">
      <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
          <form>

            <div class="modal-body">


              <div class="form-row">

                <div class="form-group col-md-5">
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

              </div>

              <div class="form-row">

                  <div class="form-group col-md-8">
                      <label>Nome:</label>
                      <input type="text" class="form-control" id="nomeCliente" required>
                  </div>



                  <div class="form-group col-md-4">
                    <label>Cpf:</label>
                    <input type="text" class="form-control" id="cpfCliente" required>

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
                    <select class="custom-select mr-sm-2" id="tvplano" onblur="somaTV()">
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
                    <input type="number" class="form-control" id="ptvplano" min="0" max="10">
                  </div>

                  <div class="form-group col-md-5">
                    <label>Internet:</label>
                    <select class="custom-select mr-sm-2" id="netplano" onblur="somaNET()">
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
                    <select class="custom-select mr-sm-2" id="fixoplano" onblur="somaFIXO()">
                      <option> </option>
                      <option>MUNDO TOTAL</option>
                      <option>BRASIL TOTAL</option>
                      <option>BRASIL</option>
                    </select>
                  </div>


                <div class="form-group col-md-3">
                  <label>Movel:</label>
                  <select class="custom-select mr-sm-2" id="movelplano" onblur="somaMOVEL()">
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
                    <input class="form-control" id="precoPlano" placeholder="R$" onclick="mostraPonto()" required>
                  </div>
                </div>

                <div class="form-group row">
                  <label class="col-sm-4 col-form-label">Pontos: </label>
                  <div class="col-sm-8">
                    <input class="form-control" id="pontoPlano" disabled>
                  </div>
                </div>

              </div>

              <div class="form-group col-md-2">
              <div class="col-sm-4">
                <button type="submit" class="btn btn-primary" onclick="propCliente()">Enviar</button>
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


    <!--TELA PRINCIPAL-->


      <?php
        $vendedor = $dados["id"];
        $nmvendedor = $dados["login"];
        $con = mysqli_connect("localhost", "root", "", "crmclik");
        $sql = mysqli_query($con, "SELECT * FROM proposta WHERE id_vendedor = '{$vendedor}'") or print mysql_error();
        $linha = mysqli_fetch_array($sql);



        $contrato= $linha["contrato"];
        if(mysqli_num_rows($sql)>0):
          echo "<div class=\"table-responsive-xl\">";
          echo "<table class=\"table table-hover text-center text-truncate\">";
          echo "<thead class=\"thead-dark\">";
          echo "<tr>";

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
          echo   "<th scope=\"col\">BackOffice</th>";
          echo "</tr>";
          echo "</thead>";

          echo "<tbody>";
          //Lembrete = tentar fazer uma nova tabela a cada ciclo
          do {

            $clienteCpf = $linha["cpf_cliente"];
            $sqlC = mysqli_query($con, "SELECT * FROM cliente WHERE cpf = '{$clienteCpf}'") or print mysql_error();
            $linhaC = mysqli_fetch_array($sqlC);


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

              $idBko = $linha["id_bko"];
              $sqlB = mysqli_query($con, "SELECT * FROM operador WHERE id = '{$idBko}'") or print mysql_error();
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
       ?>




    <!--Valida cpf -->

    <!-- JavaScript (Opcional) -->


    <script language="javascript">


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

        function mostraPonto(){

          document.getElementById("pontoPlano").setAttribute('placeholder',(pontotv + pontonet + pontofixo + pontomovel));
        }


        function  propCliente(){
          var ponto = (pontotv + pontonet + pontofixo + pontomovel);
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

    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
  </body>
</html>
