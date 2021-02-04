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
$nomeUtilizador = $obj->nomeUtilizador;
$password = $obj->pass;
$contacto = $obj->contacto;
$email = $obj->email;

if (!userExists($conn, $nomeUtilizador)) {
    $query = "INSERT INTO utilizador (nome, nomeUtilizador, email, contacto, pass) VALUES ('$nome', '$nomeUtilizador', '$email', '$contacto', '$password');";
    $result = mysqli_query($conn, $query);

    $userId = getUserId($conn);

    if ($result) {
        if (insertDadosCorporais($conn, $userId)) {
            $finalObj = (object) ['message' => "success", 'user_id' => $userId];
        } else {
            $finalObj = (object) ['message' => "error_inserting_dadosCorporais", 'user_id' => $userId];
        }
    } else {
        $finalObj = (object) ['message' => "error", 'user_id' => -1];
    }
} else {
    $finalObj = (object) ['message' => "user_already_exists", 'user_id' => -1];
}

$response = json_encode($finalObj, JSON_PRETTY_PRINT);
echo $response;

function userExists($conn, $nomeUtilizador)
{
    $exists = false;

    $query = "SELECT id FROM utilizador WHERE nomeUtilizador='$nomeUtilizador'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        if (mysqli_num_rows($result) > 0) {
            $exists = true;
        }
    }

    return $exists;
}

function getUserId($conn)
{
    $userId = mysqli_insert_id($conn);

    $query = "SELECT id FROM utilizador WHERE id=$userId";
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

function insertDadosCorporais($conn, $idUser)
{
    $inserted = false;

    $sql = "INSERT INTO dadoscorporais (idUtilizador) VALUES ($idUser)";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        if (mysqli_affected_rows($conn) > 0) {
            $inserted = true;
        }
    }

    return $inserted;
}

mysqli_close($conn);
