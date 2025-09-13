<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Check if request is POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metoda nie dozwolona']);
    exit;
}

// Validate required fields
$required_fields = ['name', 'email', 'message'];
$errors = [];

foreach ($required_fields as $field) {
    if (empty($_POST[$field])) {
        $errors[] = "Pole {$field} jest wymagane";
    }
}

// Validate email
if (!empty($_POST['email']) && !filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Nieprawidłowy adres email';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Process form data
$name = htmlspecialchars($_POST['name']);
$email = htmlspecialchars($_POST['email']);
$message = htmlspecialchars($_POST['message']);

// Prepare email content
$to = 'kontakt@butolog.pl';
$subject = 'Nowa wiadomość z formularza kontaktowego - BUTOLOG';

$email_body = "
<html>
<head>
    <meta charset='UTF-8'>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .header { background: #2563eb; color: white; padding: 20px; text-align: center; }
        .content { padding: 20px; }
        .field { margin-bottom: 15px; }
        .label { font-weight: bold; color: #2563eb; }
        .value { margin-top: 5px; }
        .message-content { 
            background: #f8f9fa; 
            padding: 15px; 
            border-left: 4px solid #2563eb; 
            margin: 15px 0; 
            white-space: pre-wrap;
        }
        .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='header'>
        <h2>📧 BUTOLOG - Nowa wiadomość kontaktowa</h2>
    </div>
    
    <div class='content'>
        <div class='field'>
            <div class='label'>Imię:</div>
            <div class='value'>{$name}</div>
        </div>
        
        <div class='field'>
            <div class='label'>Email:</div>
            <div class='value'>{$email}</div>
        </div>
        
        <div class='field'>
            <div class='label'>Data wysłania:</div>
            <div class='value'>" . date('Y-m-d H:i:s') . "</div>
        </div>
        
        <div class='field'>
            <div class='label'>Wiadomość:</div>
            <div class='message-content'>{$message}</div>
        </div>
    </div>
    
    <div class='footer'>
        <p>Ta wiadomość została wysłana z formularza kontaktowego na stronie BUTOLOG.</p>
        <p>Odpowiedz bezpośrednio na adres klienta: {$email}</p>
    </div>
</body>
</html>
";

// Email headers
$headers = [
    'MIME-Version: 1.0',
    'Content-Type: text/html; charset=UTF-8',
    'From: BUTOLOG <system@butolog.pl>',
    'Reply-To: ' . $email,
    'X-Mailer: PHP/' . phpversion(),
    'Content-Transfer-Encoding: 8bit'
];

// Try to send email
$mail_sent = mail($to, $subject, $email_body, implode("\r\n", $headers));

// Save to file as backup
$log_entry = [
    'timestamp' => date('Y-m-d H:i:s'),
    'name' => $name,
    'email' => $email,
    'message' => $message,
    'mail_sent' => $mail_sent
];

file_put_contents('contact_submissions.log', json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);

if ($mail_sent) {
    echo json_encode([
        'success' => true, 
        'message' => 'Wiadomość została wysłana pomyślnie'
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Wystąpił błąd podczas wysyłania wiadomości. Spróbuj ponownie lub skontaktuj się telefonicznie.'
    ]);
}
?>