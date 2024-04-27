<?php

// Verifica se o formulário foi submetido
if (isset($_POST['submit'])) {

    // Recupera os dados do formulário
    $nome = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $assunto = filter_input(INPUT_POST, 'subject', FILTER_SANITIZE_STRING);
    $mensagem = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_STRING);

    // Validação básica (opcional)
    if (!$nome || !$email || !$assunto || !$mensagem) {
        $erro = "Preencha todos os campos.";
    } else {

        // Configura o email de destino e remetente
        $destinatario = "bailo_14@hotmail.com"; // Substitua pelo seu email real
        $remetente = "$nome <$email>";

        // Monta a mensagem do email
        $corpo = "
Nome: $nome\n
Email: $email\n
Assunto: $assunto\n
Mensagem: $mensagem\n
        ";

        // Cabeçalhos da mensagem (com boundary para separar texto e HTML opcional)
        $boundary = md5(uniqid());
        $headers = "From: $remetente\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: multipart/alternative; boundary=" . $boundary . "\r\n";

        // Cria a versão em texto da mensagem (opcional para compatibilidade)
        $texto = "
Nome: $nome
Email: $email
Assunto: $assunto
Mensagem: $mensagem
        ";

        // Cria a versão HTML da mensagem (opcional)
        $html = "
        <!DOCTYPE html>
        <html lang='pt-br'>
        <head>
            <meta charset='UTF-8'>
            <title>Encomenda Loja</title>
        </head>
        <body>
            <p><b>Nome:</b> $nome</p>
            <p><b>Email:</b> $email</p>
            <p><b>Assunto:</b> $assunto</p>
            <p><b>Mensagem:</b> $mensagem</p>
        </body>
        </html>
        ";

        // Prepara a mensagem final com as partes texto e HTML (opcional)
        $mensagem_final = "--" . $boundary . "\r\n";
        $mensagem_final .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
        $mensagem_final .= $texto . "\r\n";
        $mensagem_final .= "--" . $boundary . "\r\n";
        $mensagem_final .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $mensagem_final .= $html . "\r\n";
        $mensagem_final .= "--" . $boundary . "--\r\n";

        // Envia o email
        if (mail($destinatario, $assunto, $mensagem_final, $headers)) {
            $sucesso = "Encomenda enviada com sucesso!";
        } else {
            $erro = "Erro ao enviar a encomenda. Tente novamente mais tarde.";
        }
    }
}

?>

<!DOCTYPE html>
