<?php
$servername = "localhost";
$dbUsername = "root";
$dbPassword = "";
$dbName = "mygym_app";

// Cria a ligação à BD
$conn = mysqli_connect($servername, $dbUsername, $dbPassword, $dbName);
// Verifica se a ligação falhou
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

$json = file_get_contents('php://input');

$obj = json_decode($json);

$username = $obj->nomeUtilizador;
$password = $obj->pass;

$query = "SELECT * FROM utilizador WHERE nomeUtilizador='$username' AND pass='$password'";
$result = mysqli_query($conn, $query);

$response = "";
$response2 = "";

if ($result) {
    if (mysqli_num_rows($result) > 0) {
        if ($row = mysqli_fetch_assoc($result)) {
            $response = $row;
        }

        $userId = getUserIdByUsername($conn, $username);

        //dados corporais
        $queryDadosCorporais = "SELECT * FROM dadoscorporais WHERE idUtilizador=$userId";
        $resultDadosCoporais = mysqli_query($conn, $queryDadosCorporais);
        if ($resultDadosCoporais) {
            if ($rowDadosCorporais = mysqli_fetch_assoc($resultDadosCoporais)) {
                $responseDadosCorporais = $rowDadosCorporais;

                //exercicios
                $queryExercicios = "SELECT * FROM exercicio WHERE idUtilizador=$userId";
                $resultExercicios = mysqli_query($conn, $queryExercicios);

                $responseExercicios = array();
                if ($resultExercicios) {
                    $i = 0;
                    while ($rowExercicios = mysqli_fetch_assoc($resultExercicios)) {
                        $responseExercicios[$i] = $rowExercicios;
                        $i++;
                    }
                }

                //aulasgrupo
                $queryAulas = "SELECT * from aulaGrupo WHERE idUtilizador=$userId";
                $resultAulas = mysqli_query($conn, $queryAulas);

                $responseAulas = array();

                if ($resultAulas) {
                    $i2 = 0;
                    while ($rowAulas = mysqli_fetch_assoc($resultAulas)) {
                        $responseAulas[$i2] = $rowAulas;
                        $i2++;
                    }
                }

                $finalObj = (object) ['message' => "success", 'user' => $response, 'dadosCorporais' => $responseDadosCorporais, 'exercises' => $responseExercicios, 'aulas' => $responseAulas, 'user_id' => $userId];
            }
        } else {
            $finalObj = (object) ['message' => "login_failed"];
        }
    } else {
        $finalObj = (object) ['message' => "login_failed"];
    }
} else {
    $finalObj = (object) ['message' => "error"];
}

echo json_encode($finalObj, JSON_PRETTY_PRINT);

function getUserIdByUsername($conn, $username)
{
    $userId = 0;

    $query = "SELECT id FROM utilizador WHERE nomeUtilizador='$username'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            if ($row = mysqli_fetch_assoc($result)) {
                $userId = $row["id"];
            }
        }
    }

    return $userId;
}

mysqli_close($conn);
