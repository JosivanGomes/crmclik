<?php
#Verifica se tem um email para pesquisa
if(isset($_POST['cpf'])){

    #Recebe o Email Postado
    $cpfPostado = $_POST['cpf'];

    #Conecta banco de dados
    $con = mysqli_connect("localhost", "root", "", "crmclik");
    $sql = mysqli_query($con, "SELECT * FROM cliente WHERE cpf = '{$cpfPostado}'") or print mysql_error();

    #Se o retorno for maior do que zero, diz que já existe um.
    if(mysqli_num_rows($sql)>0)
        echo json_encode(array('cpf' => 'show'));
    else
        echo json_encode(array('cpf' => 'hide', 'libCadastro' => 'disabled'));
}
?>
