<?php

class User {
    public $username;
    public $password;
    
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }
}

class Sale {
    public $itemName;
    public $value;
    public $time;

    public function __construct($itemName, $value) {
        $this->itemName = $itemName;
        $this->value = $value;
        $this->time = date('d/m/Y H:i:s');
    }
}

$users = [
    new User("admin", "admin123")
];
$currentUsername = null;
$sales = [];

function addUser(&$users, $username, $password) {
    $users[] = new User($username, $password);
    logMessage("User $username cadastrado");
}

function login(&$currentUsername, $username, $password) {
    global $users;
    foreach ($users as $user) {
        if ($user->username === $username && $user->password === $password) {
            $currentUsername = $username;
            logMessage("Login realizado pelo usuário $username");
            return true;
        }
    }
    return false;
}

function logout(&$currentUsername) {
    if ($currentUsername !== null) {
        logMessage("Usuário $currentUsername deslogou");
        $currentUsername = null;
    }
}

function sellItem($itemName, $value) {
    global $currentUsername, $sales;
    if ($currentUsername !== null) {
        $sales[] = new Sale($itemName, $value);
        logMessage("Usuário $currentUsername realizou uma venda do item $itemName no valor de $value");
    }
}

function showLog() {
    global $sales;
    foreach ($sales as $sale) {
        echo "Usuário realizou uma venda do item {$sale->itemName} no valor de {$sale->value} às {$sale->time}" . PHP_EOL;
    }
}

function getTotalSales() {
    global $sales;
    $total = 0;
    foreach ($sales as $sale) {
        $total += $sale->value;
    }
    return $total;
}

function logMessage($message) {
    echo date('d/m/Y H:i:s') . " - $message" . PHP_EOL;
}

while (true) {
    if ($currentUsername === null) {
        echo "Realize o login:\n";
        $username = readline("Usuário: ");
        $password = readline("Senha: ");
        if (login($currentUsername, $username, $password)) {
            echo "Login realizado com sucesso!\n";
        } else {
            echo "Usuário ou senha incorretos. Tente novamente.\n";
        }
    } else {
        echo "Bem-vindo, $currentUsername!\n";
        echo "Opções disponíveis:\n";
        echo "1. Vender\n";
        echo "2. Cadastrar novo usuário\n";
        echo "3. Verificar log\n";
        echo "4. Deslogar\n";
        echo "Valor total das vendas: " . getTotalSales() . "\n";
        $option = readline("Escolha uma opção: ");
        switch ($option) {
            case '1':
                $itemName = readline("Nome do item vendido: ");
                $value = floatval(readline("Valor da venda: "));
                sellItem($itemName, $value);
                break;
            case '2':
                $newUsername = readline("Novo usuário: ");
                $newPassword = readline("Nova senha: ");
                addUser($users, $newUsername, $newPassword);
                break;
            case '3':
                echo "Histórico de log:\n";
                showLog();
                break;
            case '4':
                logout($currentUsername);
                break;
            default:
                echo "Opção inválida. Tente novamente.\n";
                break;
        }
    }
}
