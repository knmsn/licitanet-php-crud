<?php

$base_dados  = 'LICITANET';
$usuario_bd  = 'root';
$senha_bd    = 'coco123';
$host_db     = 'localhost';
$charset_db  = 'UTF8';
$conexao_pdo = null;

$detalhes_pdo  = 'mysql:host=' . $host_db . ';';
$detalhes_pdo .= 'dbname='. $base_dados . ';';
$detalhes_pdo .= 'charset=' . $charset_db . ';';

$conexao_pdo = new PDO($detalhes_pdo, $usuario_bd, $senha_bd);


?>