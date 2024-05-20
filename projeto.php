<?php

session_start();

$usuarios = [
    "Admin" => "admin",
];

$log = [];

$itens = [];

$total_vendas = 0;

function registrar(){
    global $usuarios, $log;
    limpar_tela();
    $nome_usuario = readline("Digite o nome de usuário: ");
    $senha_usuario = readline("Digite uma senha: ");
    $usuarios[$nome_usuario] = $senha_usuario;
    $log[] = date('d/m/Y H:i:s') . " - Usuário " . $nome_usuario ." registrado com sucesso!";
    echo "Usuário " . $nome_usuario ." registrado com sucesso!\n";
    sleep(2);
}

function login(){
    global $usuarios, $log;
    limpar_tela();
    echo "LOGIN\n";
    $nome_usuario = readline("Nome de usuário: ");
    $senha_usuario = readline("Senha: ");
    
    if (isset($usuarios[$nome_usuario]) && $usuarios[$nome_usuario] == $senha_usuario) {
        $_SESSION['nome_usuario'] = $nome_usuario;
        $log[] = date('d/m/Y H:i:s') . " - Usuário " . $nome_usuario . " fez login.";
        return true;
    } else {
        $log[] = date('d/m/Y H:i:s') . " - Falha no login para o usuário " . $nome_usuario . ".";
        return false;
    } 
}

function logout(){
    global $log, $total_vendas;

    $total_vendas = 0;

    $log[] = date('d/m/Y H:i:s') . " - Usuário " . $_SESSION['nome_usuario'] . " fez logout.";
    
    limpar_tela();
    session_unset();
    echo "Logout realizado com sucesso!\n";
    sleep(2);
}

function limpar_tela(){
    system('clear');
}

function visualizar_log(){
    global $log;
    limpar_tela();
    echo "Log do Sistema\n";
    foreach ($log as $entrada) {
        echo $entrada . "\n";
    }
    
    readline("Pressione Enter para continuar...");
}

function adicionar_item(){
    global $itens, $log;
    limpar_tela();
    echo "Nome do Item: ";
    $nome_item = readline();
    echo "Preço: ";
    $preco_item = readline();
    
    $itens[] = [
        'nome' => $nome_item,
        'preco' => $preco_item,
    ];
    $log[] = date('d/m/Y H:i:s') . " - Item " . $nome_item . " adicionado com sucesso!";
    echo "Item " . $nome_item . " adicionado com sucesso!\n";
    sleep(2);
}

function realizar_venda() {
    global $total_vendas, $itens, $log;
    limpar_tela();

    if (count($itens) == 0) {
        echo "Não há itens disponíveis, cadastre um antes de tentar realizar uma venda novamente!\n";
        readline("Pressione Enter para continuar...");
        adicionar_item();
    } 

    echo "--------------------------\n";
    echo "Itens disponíveis:\n";
    foreach ($itens as $item) {
        echo $item['nome'] . " - R$ " . $item['preco'] . "\n";
    }

    $item_escolhido = readline("Digite o nome do item que deseja vender: ");

    $item_encontrado = false;

    foreach ($itens as $key => $item) {
        if ($item['nome'] == $item_escolhido) {
            $total_vendas += $item['preco'];
            $log[] = date('d/m/Y H:i:s') . " - Venda realizada: " . $item['nome'] . " - R$ " . $item['preco'];
            unset($itens[$key]);
            $item_encontrado = true;
            break;
        }
    }

    if ($item_encontrado) {
        echo "Venda realizada com sucesso!\n";
    } else {
        echo "Item não encontrado, tente novamente!\n";
    }
    sleep(2);
}

function menu(){
    global $total_vendas;
    limpar_tela();
    echo "Bem vindo(a) ao mercadinho do dev!\n";
    echo "Total de Vendas: R$ " . $total_vendas . "\n";
    echo "1 - Realizar Venda\n";
    echo "2 - Registrar Novo Usuário\n";
    echo "3 - Visualizar Log\n";
    echo "4 - Adicionar Item\n";
    echo "5 - Logout\n";
    $opcao = readline("Digite o número correspondente da opção que deseja: ");

    switch ($opcao) {
        case 1:
            realizar_venda();
            break;
        case 2: 
            registrar();
            break;
        case 3:
            visualizar_log();
            break;
        case 4:
            adicionar_item();
            break;
        case 5: 
            logout();
            break;
        default:
            echo "Opção inválida!\n";
    }
} 

while (true) {
    if (!isset($_SESSION['nome_usuario'])) {
        if (!login()) {
            echo "Login falhou, tente novamente!\n";
            readline("Pressione Enter para continuar...");
        }
    } else {
        menu();
    }
}
