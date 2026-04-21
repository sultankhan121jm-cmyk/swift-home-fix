<?php
header('Content-Type: application/json');
// Basic validation
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = strip_tags(trim($_POST["fname"]));
    $phone = strip_tags(trim($_POST["phone"]));
    $service = strip_tags(trim($_POST["service"]));
    $message = strip_tags(trim($_POST["message"]));
    
    $recipient = "qazisultan121jm@gmail.com"; // REPLACE WITH YOUR EMAIL
    $subject = "New Service Request from: $name";
    
    $email_content = "Name: $name\n";
    $email_content .= "Phone: $phone\n";
    $email_content .= "Service: $service\n\n";
    $email_content .= "Message:\n$message\n";
    
    $email_headers = "From: Swift Home Fix <noreply@yourdomain.com>";
    
    if (mail($recipient, $subject, $email_content, $email_headers)) {
        echo json_encode(["success" => true]);
    } else {
        http_response_code(500);
        echo json_encode(["success" => false]);
    }
} else {
    http_response_code(403);
    echo json_encode(["success" => false]);
}
?>