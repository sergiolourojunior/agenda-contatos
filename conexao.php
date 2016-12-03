<?php

date_default_timezone_set('America/Sao_Paulo');

try
{
    $PDO = new PDO( 'mysql:host=localhost; dbname=agenda_contatos', 'root', '');
}
catch ( PDOException $e )
{
    echo utf8_encode('Erro ao conectar com o banco: ' . $e->getMessage());
}

?>