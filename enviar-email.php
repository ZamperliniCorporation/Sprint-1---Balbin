<?php
// CONFIGURAÇÕES
$apiKey = "re_A7PHLxP9_H5GErW6bgdMCh5HYC2UDbesd"; // sua chave da Resend
$destinatario = "rogerio@balbinconcierge.com";

// CAPTURA OS DADOS DO FORMULÁRIO
$nome     = htmlspecialchars($_POST["nome"]);
$plano    = htmlspecialchars($_POST["plano"]);
$email    = htmlspecialchars($_POST["email"]);
$telefone = htmlspecialchars($_POST["telefone"]);
$mensagem = nl2br(htmlspecialchars($_POST["mensagem"]));

// MONTA O HTML DO E-MAIL
$htmlContent = "
  <h2>Nova mensagem recebida via site</h2>
  <p><strong>Nome:</strong> {$nome}</p>
  <p><strong>Plano:</strong> {$plano}</p>
  <p><strong>Email:</strong> {$email}</p>
  <p><strong>Telefone:</strong> {$telefone}</p>
  <p><strong>Mensagem:</strong><br>{$mensagem}</p>
";

// ENVIA PARA A API DA RESEND
$ch = curl_init("https://api.resend.com/emails");

curl_setopt($ch, CURLOPT_HTTPHEADER, [
  "Authorization: Bearer $apiKey",
  "Content-Type: application/json"
]);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$payload = json_encode([
  "from" => "Balbin Concierge <{$destinatario}>",
  "to" => [$destinatario],
  "reply_to" => $email,
  "subject" => "Novo formulário enviado pelo site",
  "html" => $htmlContent
]);


curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

$resposta = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// RESPOSTA
if ($httpCode === 200 || $httpCode === 202) {
    header("Location: index.html?enviado=1");
    exit;
} else {
    echo "<script>
      alert('Ocorreu um erro ao enviar. Tente novamente.');
      window.history.back();
    </script>";
}

?>
