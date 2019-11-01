<?php

  require_once 'db_connect.php';

//Sessão
  session_start();

//Verificação
  if(!isset($_SESSION['logado'])):
    header('Location: index.php');
  endif;

//Dados
  $mHoje = date("m");

  $id = $_SESSION['id_usuario'];

  $sitVenda = $_POST['situacao'];

  $venda = $_POST['vendedor'];
  $dataI = $_POST['data_i'];
  $dataF = $_POST['data_f'];

  $cpfPost = $_POST['cpf_cliente'];
  $contrato = $_POST['contrato'];


  $sql = "SELECT * FROM proposta WHERE ";

  if ($sitVenda != "GERAL"):
    if ($sitVenda == "PENDENCIAS"):
      $sql = $sql."situacao != 'BACKLOG' AND situacao != 'TECNICO' AND situacao != 'COMERCIAL' AND situacao != 'CANCELADO' AND situacao != 'APROVADO' AND situacao != 'ATIVO'";
    else:
      $sql = $sql."situacao = '$sitVenda'";
    endif;
endif;

  if ($venda != 'TODOS'):
    if ($sitVenda != "GERAL"):
       $sql = $sql." AND id_vendedor = '$venda'";
    else:
      $sql = $sql." id_vendedor = '$venda'";
    endif;
  endif;

  if (!empty($cpfPost)):
    if (($venda != 'TODOS')||($sitVenda != "GERAL")):
      $sql = $sql." AND cpf_cliente = '$cpfPost'";
    else:
      $sql = $sql." cpf_cliente = '$cpfPost'";
    endif;
  endif;

  if (!empty($contrato)):
    if ((($venda != 'TODOS')||($sitVenda != "GERAL")||(!empty($cpfPost)))):
      $sql = $sql." AND contrato = '$contrato'";
    else:
      $sql = $sql." contrato = '$contrato'";
    endif;
  endif;


  if (empty($dataI)):
    if ((($venda != 'TODOS')||($sitVenda != "GERAL")||(!empty($cpfPost))||(!empty($contrato)))):
      $sql = $sql." AND MONTH(data_venda) = '$mHoje'";
    else:
      $sql = $sql." MONTH(data_venda) = '$mHoje'";
    endif;
  else:
    if (($venda != 'TODOS')||(($sitVenda != "GERAL"))):
      $sql = $sql." AND data_venda >= '$dataI' AND data_venda <= '$dataI'";
    else:
      $sql = $sql." data_venda >= '$dataI' AND data_venda <= '$dataI'";
    endif;
  endif;



  $sql = $sql." AND id_super = '$id'";
  $resultado = mysqli_query($connect, $sql);
  $linha = mysqli_fetch_array($resultado);

  $contrato = $linha["contrato"];
  $impresso = "";

  if (mysqli_num_rows($resultado) > 0):
    $impresso =  "<div class=\"table-responsive-xl\">
                    <table class=\"table table-hover text-center text-truncate\">
                      <thead class=\"thead-dark\">
                        <tr>

                          <th scope=\"col\">Vendedor</th>
                          <th scope=\"col\">Status</th>
                          <th scope=\"col\">Data Instalação</th>
                          <th scope=\"col\">Turno</th>
                          <th scope=\"col\">Inst Chamado</th>
                          <th scope=\"col\">Cidade</th>
                          <th scope=\"col\">Contrato</th>
                          <th scope=\"col\">Data Venda</th>
                          <th scope=\"col\">CPF Cliente</th>
                          <th scope=\"col\">Cliente</th>
                          <th scope=\"col\">Fone1</th>
                          <th scope=\"col\">Fone2</th>
                          <th scope=\"col\">Tv</th>
                          <th scope=\"col\">Pt Adc Tv</th>
                          <th scope=\"col\">Internet</th>
                          <th scope=\"col\">Fone</th>
                          <th scope=\"col\">Móvel</th>
                          <th scope=\"col\">Valor</th>
                          <th scope=\"col\">Pontuação</th>
                          <th scope=\"col\">Pontuação - Móvel</th>
                          <th scope=\"col\">BackOffice</th>
                        </tr>
                      </thead>

                      <tbody>";
    do {

      $clienteCpf = $linha["cpf_cliente"];
      $sqlC = mysqli_query($connect, "SELECT * FROM cliente WHERE cpf = '$clienteCpf'") or print mysql_error();
      $linhaC = mysqli_fetch_array($sqlC);

      $vendedor = $linha["id_vendedor"];
      $sqlV = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$vendedor}'") or print mysql_error();
      $linhaV = mysqli_fetch_array($sqlV);
      $nmVendedor = $linhaV['login'];
      $impresso = $impresso."<td>$nmVendedor</td>";

      $status = $linha["situacao"];
      $impresso = $impresso."<td>$status</td>";

      $dtInstala = $linha["data_instalacao"];
      if(empty($dtInstala)):
        $impresso = $impresso."<td>$dtInstala</td>";
      else:
        $newDate = date("d/m/Y", strtotime($dtInstala));
        $impresso = $impresso."<td>$newDate</td>";
      endif;

      $turnoInst = $linha["turnoinst"];
      if ($turnoInst == "NULL"):
        $impresso = $impresso."<td></td>";
      else:
        $impresso = $impresso."<td>$turnoInst</td>";
      endif;

      $chamadoinst = $linha["chamadoinst"];
      if ($chamadoinst == "NULL"):
        $impresso = $impresso."<td></td>";
      else:
        $impresso = $impresso."<td>$chamadoinst</td>";
      endif;

      $localCidade = $linhaC["cidade"];
      $impresso = $impresso."<td>$localCidade</td>";

      $contratoC = $linha["contrato"];
      $impresso = $impresso."<td>$contratoC</td>";

      $dtVenda = $linha["data_venda"];
      if(empty($dtVenda)):
        $impresso = $impresso."<td>$dtVenda</td>";
      else:
        $newDate = date("d/m/Y", strtotime($dtVenda));
        $impresso = $impresso."<td>$newDate</td>";
      endif;

      $impresso = $impresso."<td>$clienteCpf</td>";

      $nmcliente = $linhaC["nome"];
      $impresso = $impresso."<td>$nmcliente</td>";

      $F1Cliente = $linhaC["telfixo"];
      $impresso = $impresso."<td>$F1Cliente</td>";

      $F2Cliente = $linhaC["telmovel"];
      $impresso = $impresso."<td>$F2Cliente</td>";

      $planoTv = $linha["tv"];
      if ($planoTv == "NULL"):
        $impresso = $impresso."<td></td>";
      else:
        $impresso = $impresso."<td>$planoTv</td>";
      endif;

      $pontoTv = $linha["pt_adc_tv"];
      if ($pontoTv == 0):
        $impresso = $impresso."<td></td>";
      else:
        $impresso = $impresso."<td>$pontoTv</td>";
      endif;

      $net = $linha["internet"];
      if ($net == "NULL"):
        $impresso = $impresso."<td></td>";
      else:
        $impresso = $impresso."<td>$net</td>";
      endif;

      $fone = $linha["telefone"];
      if ($fone == "NULL"):
        $impresso = $impresso."<td></td>";
      else:
       $impresso = $impresso."<td>$fone</td>";
      endif;

      $movel = $linha["movel"];
      if ($movel == "NULL"):
        $impresso = $impresso."<td></td>";
      else:
        $impresso = $impresso."<td>$movel</td>";
      endif;

      $precoP = number_format($linha["preco"], 2, '.', '');
      $impresso = $impresso."<td>R$ $precoP</td>";

      $pontuacao = $linha["ponto"];
      $impresso = $impresso."<td>$pontuacao</td>";

      $pontuacaoMovel = $linha["pontoM"];
      $impresso = $impresso."<td>$pontuacaoMovel</td>";

      $idBko = $linha["id_bko"];
      $sqlB = mysqli_query($connect, "SELECT * FROM operador WHERE id = '{$idBko}'") or print mysql_error();
      $linhaB = mysqli_fetch_array($sqlB);
      $nmBko = $linhaB["login"];
      $impresso = $impresso."<td>$nmBko</td>";

      $impresso = $impresso."</tr>";


    } while ($linha = mysqli_fetch_assoc($resultado));

    $impresso = $impresso."</tbody>
                          </table>
                          </div>";
    else:
      $impresso = $impresso."<h2>Sem dados</h2>";
    endif;





  echo json_encode(array('retorno' => "$impresso"));



 ?>
