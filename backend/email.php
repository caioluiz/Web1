<?php

// Dados de conexão com o banco de dados
$host = "localhost";  // Endereço do servidor MySQL
$user = "seu_usuario";  // Nome de usuário do MySQL
$password = "sua_senha";  // Senha do MySQL
$database = "seu_banco_de_dados";  // Nome do banco de dados

// Estabelece a conexão com o banco de dados
$conn = mysqli_connect($host, $user, $password, $database);

// Verifica se houve erro na conexão
if (!$conn) {
    die("Falha na conexão com o banco de dados: " . mysqli_connect_error());
}

// Função para gerar senha provisória
function gerarSenhaProvisoria($tamanho = 8) {
    $caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    $senha = '';
    
    for ($i = 0; $i < $tamanho; $i++) {
        $senha .= $caracteres[rand(0, strlen($caracteres) - 1)];
    }
    
    return $senha;
}

// Função para enviar e-mail
function enviarEmail($email, $senhaProvisoria, $linkLogin) {
    // Aqui você pode implementar a lógica para enviar o e-mail com a senha provisória e o link de login
    // Pode usar a função mail() do PHP ou bibliotecas externas como PHPMailer
    // Exemplo utilizando a função mail():
    $assunto = "Senha provisória e link de login";
    $mensagem = "Olá,\n\nSua senha provisória é: $senhaProvisoria\n\nPor favor, faça login utilizando o seu CPF ou e-mail e essa senha provisória. Recomendamos que você altere sua senha após o login.\n\nLink de login: $linkLogin";
    $headers = "From: caioluizsouza12@gmail.com";
    
    if (mail($email, $assunto, $mensagem, $headers)) {
        echo "E-mail enviado com sucesso para $email.";
    } else {
        echo "Erro ao enviar o e-mail.";
    }
}

// Recebe o CPF ou e-mail fornecido pelo usuário
$cpfOuEmail = $_POST['cpf_ou_email']; // Supondo que o valor seja enviado por um formulário usando o método POST

// Verifica se o CPF ou e-mail estão cadastrados no banco de dados
$query = "SELECT email, senha_provisoria FROM usuarios WHERE cpf = '$cpfOuEmail' OR email = '$cpfOuEmail'";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    $row = mysqli_fetch_assoc($result);
    $emailUsuario = $row['email'];
    $senhaProvisoria = $row['senha_provisoria'];
    
    // Gera um link de login (pode ser a página de login do seu sistema)
    $linkLogin = "./index.html";
    
    // Verifica se a senha provisória está definida no banco de dados
    if (!empty($senhaProvisoria)) {
        // Envia o e-mail com a senha provisória e o link de login
        enviarEmail($emailUsuario, $senhaProvisoria, $linkLogin);
    } else {
        // Verifica se foi enviado um formulário de alteração de senha
        if (isset($_POST['nova_senha'])) {
            // Recebe a nova senha fornecida pelo usuário
            $novaSenha = $_POST['nova_senha'];
            
            // Atualiza a senha no banco de dados
            $queryUpdateSenha = "UPDATE usuarios SET senha = '$novaSenha' WHERE email = '$emailUsuario'";
            mysqli_query($conn, $queryUpdateSenha);
            
            // Redireciona para a tela principal após a alteração da senha
            header("Location: tela_principal.php");
            exit();
        } else {
            // Exibe o formulário para o usuário alterar a senha
            echo "<form method='POST' action=''>
                    <label for='nova_senha'>Nova Senha:</label>
                    <input type='password' id='nova_senha' name='nova_senha' required>
                    <button type='submit'>Alterar Senha</button>
                </form>";
        }
    }
} else {
    echo "O e-mail ou CPF não estão cadastrados no sistema.";
}

// Fecha a conexão com o banco de dados
mysqli_close($conn);

?>