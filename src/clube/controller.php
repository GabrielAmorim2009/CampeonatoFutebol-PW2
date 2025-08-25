<?php
$host = 'db'; // Nome do serviço do banco de dados no docker-compose
$db   = 'campeonato_futebol'; // Nome do banco de dados
$user = 'root'; // Nome do usuário do banco de dados
$pass = 'root'; // Senha do usuário do banco de dados
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

function conectar(): PDO {
    global $dsn, $user, $pass, $options;
    try {
        $pdo = new PDO($dsn, $user, $pass, $options);
        return $pdo;
    } catch (\PDOException $e) {
        echo 'Erro na conexão: ' . $e->getMessage();
        exit;
    }
}

function listarClubes($search=""): array {
    $pdo = conectar();
    // Exemplo de consulta
    $stmt = $pdo->prepare(query: 'SELECT * FROM clube WHERE nome LIKE :search ORDER BY nome');
    $search = "%$search%";
    $stmt->bindValue(':search', $search, PDO::PARAM_STR);
    $stmt->execute();
    if($stmt->rowCount() > 0) {
        return $stmt->fetchAll();
    } else {
        return [];
    }     
}

function listarClubesJson($search="") {
    header('Content-Type: application/json');
    $lista = listarClubes($search);
    if($lista) {
        $resposta = [
            'status' => 'success',
            'data' => $lista
        ];
        http_response_code(200);
        echo json_encode($resposta);
    } else {
        $resposta = [
            'status' => 'error',
            'message' => 'Nenhum clube encontrado.'
        ];
        http_response_code(404);
        echo json_encode($resposta);
    }
}

// Estamos fazendo a verificação dos tipos de metodos que o usuário está utilizando
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    listarClubesJson(search: $_GET['search'] ?? '');
}
elseif($_SERVER['REQUEST_METHOD'] === 'POST'){

}
elseif($_SERVER['REQUEST_METHOD'] === 'PUT'){
    
}
elseif($_SERVER['REQUEST_METHOD'] === 'DELETE'){
    
}
else {
    http_response_code(405);
    echo json_encode(['status' => 'error', 'message' => 'Método não permitido.']);
}