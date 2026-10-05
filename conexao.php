<?php

$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$dbname = 'abcontrol';

$conn = mysqli_connect($servidor,$usuario,$senha,$dbname);

if (!$conn) {
    die("Erro na conex���o com o banco: " . mysqli_connect_error());
}

?>
