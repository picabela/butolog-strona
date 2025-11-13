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
$required_fields = ['quantity', 'description', 'email', 'privacy_accepted'];
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

// Validate privacy acceptance
if (empty($_POST['privacy_accepted'])) {
    $errors[] = 'Musisz zaakceptować politykę prywatności';
}

// Check for image upload
if (!isset($_FILES['images']) || empty($_FILES['images']['name'][0])) {
    $errors[] = 'Przynajmniej jedno zdjęcie jest wymagane';
}

if (!empty($errors)) {
    echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
    exit;
}

// Process form data
$user_id = $_POST['user_id'] ?? 'BT-' . time() . '-' . strtoupper(substr(md5(uniqid()), 0, 4));
$quantity = htmlspecialchars($_POST['quantity']);
$description = htmlspecialchars($_POST['description']);
$email = htmlspecialchars($_POST['email']);
$marketing_accepted = isset($_POST['marketing_accepted']) ? 'TAK' : 'NIE';

// Handle file upload
$upload_dir = 'uploads/';
if (!file_exists($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

$uploaded_images = [];
$image_attachments = [];

if (isset($_FILES['images'])) {
    $file_count = count($_FILES['images']['name']);
    
    // Limit to 5 files
    if ($file_count > 5) {
        echo json_encode(['success' => false, 'message' => 'Maksymalnie 5 zdjęć']);
        exit;
    }
    
    for ($i = 0; $i < $file_count; $i++) {
        if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
            $file_extension = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
            $image_name = $user_id . '_' . time() . '_' . ($i + 1) . '.' . $file_extension;
            $image_path = $upload_dir . $image_name;
            
            // Validate file type
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
            if (!in_array(strtolower($file_extension), $allowed_types)) {
                echo json_encode(['success' => false, 'message' => 'Nieprawidłowy typ pliku: ' . $_FILES['images']['name'][$i]]);
                exit;
            }
            
            // Validate file size (10MB max)
            if ($_FILES['images']['size'][$i] > 10 * 1024 * 1024) {
                echo json_encode(['success' => false, 'message' => 'Plik jest za duży (max 10MB): ' . $_FILES['images']['name'][$i]]);
                exit;
            }
            
            if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $image_path)) {
                $uploaded_images[] = $image_name;
                $image_attachments[] = $image_path;
            }
        }
    }
}

// Prepare email content
$to = 'kontakt@butolog.pl';
$subject = 'Nowe zgłoszenie wyceny butów - ' . $user_id;

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
        .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class='header'>
        <h2>🔧 BUTOLOG - Nowe zgłoszenie wyceny</h2>
    </div>
    
    <div class='content'>
        <div class='field'>
            <div class='label'>ID użytkownika:</div>
            <div class='value'>{$user_id}</div>
        </div>
        
        <div class='field'>
            <div class='label'>Email klienta:</div>
            <div class='value'>{$email}</div>
        </div>

        <div class='field'>
            <div class='label'>Ilość butów do naprawy:</div>
            <div class='value'>{$quantity}</div>
        </div>

        <div class='field'>
            <div class='label'>Opis naprawy:</div>
            <div class='value'>{$description}</div>
        </div>
        
        <div class='field'>
            <div class='label'>Zgoda na marketing:</div>
            <div class='value'>{$marketing_accepted}</div>
        </div>
        
        <div class='field'>
            <div class='label'>Data zgłoszenia:</div>
            <div class='value'>" . date('Y-m-d H:i:s') . "</div>
        </div>
        
        <div class='field'>
            <div class='label'>Zdjęcie:</div>
            <div class='value'>Zobacz załączniki (" . count($uploaded_images) . " zdjęć)</div>
        </div>
    </div>
    
    <div class='footer'>
        <p>To zgłoszenie zostało wysłane automatycznie z formularza na stronie BUTOLOG.</p>
        <p>Szacowany czas odpowiedzi: 1 minuta - 12 godzin</p>
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
$mail_sent = false;

// Method 1: Try with attachments if images exist
if (!empty($image_attachments)) {
    $boundary = md5(time());
    
    $headers_with_attachment = [
        'MIME-Version: 1.0',
        'Content-Type: multipart/mixed; boundary="' . $boundary . '"',
        'From: BUTOLOG <system@butolog.pl>',
        'Reply-To: ' . $email,
        'X-Mailer: PHP/' . phpversion(),
        'Content-Transfer-Encoding: 8bit'
    ];
    
    $attachments_content = '';
    foreach ($image_attachments as $index => $image_path) {
        if (file_exists($image_path)) {
            $filename = basename($image_path);
            $attachments_content .= "
--{$boundary}
Content-Type: application/octet-stream; name=\"{$filename}\"
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename=\"{$filename}\"

" . chunk_split(base64_encode(file_get_contents($image_path))) . "
";
        }
    }
    
    $email_body_with_attachment = "
--{$boundary}
Content-Type: text/html; charset=UTF-8
Content-Transfer-Encoding: 7bit

{$email_body}

{$attachments_content}--{$boundary}--
";
    
    $mail_sent = mail($to, $subject, $email_body_with_attachment, implode("\r\n", $headers_with_attachment));
}

// Method 2: Fallback without attachment
if (!$mail_sent) {
    $mail_sent = mail($to, $subject, $email_body, implode("\r\n", $headers));
}

// Save to file as backup
$log_entry = [
    'timestamp' => date('Y-m-d H:i:s'),
    'user_id' => $user_id,
    'email' => $email,
    'quantity' => $quantity,
    'description' => $description,
    'marketing_accepted' => $marketing_accepted,
    'image_files' => $uploaded_images,
    'mail_sent' => $mail_sent
];

file_put_contents('quote_submissions.log', json_encode($log_entry) . "\n", FILE_APPEND | LOCK_EX);

if ($mail_sent) {
    echo json_encode([
        'success' => true, 
        'message' => 'Zgłoszenie zostało wysłane pomyślnie',
        'user_id' => $user_id
    ]);
} else {
    echo json_encode([
        'success' => false, 
        'message' => 'Wystąpił błąd podczas wysyłania email. Spróbuj ponownie lub skontaktuj się telefonicznie.'
    ]);
}
?>