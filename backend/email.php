<?php
  //Variáveis
  $nome = $_POST['nome'];
  $email = $_POST['email'];

  //Compo E-mail
  $arquivo = "
    <html>
      <p><b>Nome: </b>Caio</p>
      <p><b>E-mail: </b>$email</p>
    </html>
  ";
  
  //Emails para quem será enviado o formulário
  $destino = "caioluizsouza12@gmail.com";
  $assunto = "Contato pelo Site";

  //Este sempre deverá existir para garantir a exibição correta dos caracteres
  $headers  = "MIME-Version: 1.0\n";
  $headers .= "Content-type: text/html; charset=iso-8859-1\n";
  $headers .= "From: $nome <$email>";

  //Enviar
  mail($destino, $assunto, $arquivo, $headers);
  
  echo "<meta http-equiv='refresh' content='10;URL=../contato.html'>";
?>