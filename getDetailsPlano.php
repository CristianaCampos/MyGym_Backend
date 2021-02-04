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

$userId = $obj->userId;
$planoId = $obj->planoId;

$query = "SELECT * FROM planotreino WHERE idUtilizador=$userId AND id=$planoId";
$result = mysqli_query($conn, $query);

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            $idEx1 = $row["idEx1"];
            $idEx2 = $row["idEx2"];
            $idEx3 = $row["idEx3"];
            $idAula1 = $row["idAula1"];
            $idAula2 = $row["idAula2"];

            $nomeEx1 = getExercicioNameById($conn, $idEx1);
            $nomeEx2 = getExercicioNameById($conn, $idEx2);
            $nomeEx3 = getExercicioNameById($conn, $idEx3);

            $nomeAula1 = getAulaNameById($conn, $idAula1);
            $nomeAula2 = getAulaNameById($conn, $idAula2);

            $row["exercicio1"] = $nomeEx1;
            $row["exercicio2"] = $nomeEx2;
            $row["exercicio3"] = $nomeEx3;

            $row["aula1"] = $nomeAula1;
            $row["aula2"] = $nomeAula2;
            $response = $row;
        }
        $finalObj = (object) ['message' => "success",  'plano' => $response];
    } else {
        $finalObj = (object) ['message' => "no_plano_assigned", 'plano' => $response];
    }
} else {
    $finalObj = (object) ['message' => "error", 'plano' => $response];
}

function getExercicioNameById($conn, $idExercicio)
{
    $nome = "";

    if ($idExercicio != 0) {

        $query = "SELECT nome FROM exercicio WHERE id=$idExercicio";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $nome = $row["nome"];
                }
            }
        }
    } else {
        $nome = "---";
    }

    return $nome;
}

function getAulaNameById($conn, $idAula)
{
    $nome = "";

    if ($idAula != 0) {

        $query = "SELECT nome FROM aulaGrupo WHERE id=$idAula";
        $result = mysqli_query($conn, $query);

        if ($result) {
            if (mysqli_num_rows($result) > 0) {
                if ($row = mysqli_fetch_assoc($result)) {
                    $nome = $row["nome"];
                }
            }
        }
    } else {
        $nome = "---";
    }

    return $nome;
}

echo json_encode($finalObj, JSON_PRETTY_PRINT);

mysqli_close($conn);
