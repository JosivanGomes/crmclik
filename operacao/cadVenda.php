<?php
$nmCliente = filter_input(INPUT_POST, 'nmCliente', FILTER_SANITIZE_STRING);
$cpfCliente = filter_input(INPUT_POST, 'cpfCliente', FILTER_SANITIZE_STRING);

echo "nome: $nmCliente<br>";
echo "cpf: $cpfCliente";

 ?>
