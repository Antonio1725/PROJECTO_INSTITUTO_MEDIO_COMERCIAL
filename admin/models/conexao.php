<?php

// Desativa a exibição de erros e avisos
ini_set('display_errors', 0);
error_reporting(0);

date_default_timezone_set("Africa/Luanda");
try {
    //sys-clinica
    $conexao = mysqli_connect('127.0.0.1', 'root', '', 'sergio');
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
if (!$conexao) {
    // Exibe a mensagem personalizada se a conexão falhar
    die("Base de dados não ligada");
}

mysqli_query($conexao, "SET NAMES 'utf8'");
mysqli_query($conexao, "SET character_set_connection=utf8");
mysqli_query($conexao, "SET character_set_client=utf8");
mysqli_query($conexao, "SET character_set_results=utf8");
