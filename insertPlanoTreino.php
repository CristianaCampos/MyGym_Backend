<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "mygym_app";

// Cria a ligação à BD
$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
// Verifica se a ligação falhou (ou teve sucesso)
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$json = file_get_contents('php://input');

$obj = json_decode($json);

$nome = $obj->nome;
$diaSemana = $obj->diaSemana;
$exercicio1 = $obj->exercicio1;
$exercicio2 = $obj->exercicio2;
$exercicio3 = $obj->exercicio3;
$aula1 = $obj->aula1;
$aula2 = $obj->aula2;

$idUtilizador = $obj->userId;

$idEx1 = getIdExercicioByName($conn, $exercicio1);
$idEx2 = getIdExercicioByName($conn, $exercicio2);
$idEx3 = getIdExercicioByName($conn, $exercicio3);

$idAula1 = getIdAulaByName($conn, $aula1);
$idAula2 = getIdAulaByName($conn, $aula2);

$query = "INSERT INTO planotreino(nome, diaSemana, idEx1, idEx2, idEx3, idAula1, idAula2, idUtilizador) VALUES ('$nome', '$diaSemana', $idEx1, $idEx2, $idEx3, $idAula1, $idAula2, $idUtilizador)";
$result = mysqli_query($conn, $query);

if ($result) {
    $finalObj = (object) ['message' => "success"];
} else {
    $finalObj = (object) ['message' => "errorInsertPlano"];
}

function getIdExercicioByName($conn, $nomeExercicio)
{
    $id = 0;

    if ($nomeExercicio != "---") {
        $query = "SELECT id FROM exercicio WHERE nome='$nomeExercicio'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $id = $row["id"];
                }
            }
        }
    }

    return $id;
}

function getIdAulaByName($conn, $nomeAula)
{
    $id = 0;

    if ($nomeAula != "---") {
        $query = "SELECT id FROM aulaGrupo WHERE nome='$nomeAula'";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $id = $row["id"];
                }
            }
        }
    }

    return $id;
}


echo json_encode($finalObj, JSON_PRETTY_PRINT);

mysqli_close($conn);
