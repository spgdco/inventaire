<?php
// DESTINATAIRE FIXE
$to = "cisco18@gmail.com";

// SECURITE BASIQUE
if (!isset($_FILES['pdf']) || !isset($_POST['nom']) || !isset($_POST['date'])) {
    http_response_code(400);
    exit("Requête invalide");
}

$nom = htmlspecialchars($_POST['nom']);
$date = htmlspecialchars($_POST['date']);

$pdf = $_FILES['pdf'];
$filename = "Inventaire_FPTSR_" . date("Y-m-d_H-i") . ".pdf";

// HEADERS MAIL
$boundary = md5(time());
$headers = "From: inventaire-fptsr@sdis76.fr\r\n";
$headers .= "MIME-Version: 1.0\r\n";
$headers .= "Content-Type: multipart/mixed; boundary=\"$boundary\"";

// CORPS DU MAIL
$message = "--$boundary\r\n";
$message .= "Content-Type: text/plain; charset=UTF-8\r\n\r\n";
$message .= "Inventaire FPTSR\n";
$message .= "Nom : $nom\n";
$message .= "Date : $date\n\n";

// PIECE JOINTE
$fileContent = chunk_split(base64_encode(file_get_contents($pdf['tmp_name'])));

$message .= "--$boundary\r\n";
$message .= "Content-Type: application/pdf; name=\"$filename\"\r\n";
$message .= "Content-Transfer-Encoding: base64\r\n";
$message .= "Content-Disposition: attachment; filename=\"$filename\"\r\n\r\n";
$message .= $fileContent . "\r\n";
$message .= "--$boundary--";

// ENVOI
if (mail($to, "Inventaire FPTSR", $message, $headers)) {
    echo "OK";
} else {
    http_response_code(500);
    echo "Erreur envoi mail";
}
