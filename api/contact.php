<?php
// api/contact.php

// Allow JSON to be sent from your HTML pages
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// Check if the request is POST
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
    exit;
}

// Get the raw POST data (JSON)
 $input = json_decode(file_get_contents('php://input'), true);

// Simple validation
if (!$input) {
    echo json_encode(["success" => false, "message" => "No Data Received"]);
    exit;
}

// Clean the data (Security)
 $name = strip_tags(trim($input['name'] ?? ''));
 $phone = strip_tags(trim($input['phone'] ?? ''));
 $service = strip_tags(trim($input['service'] ?? ''));
 $address = strip_tags(trim($input['address'] ?? ''));
 $message = strip_tags(trim($input['message'] ?? ''));

// Required fields check
if (empty($name) || empty($phone)) {
    echo json_encode(["success" => false, "message" => "Name and Phone are required"]);
    exit;
}

// Email Configuration
 $recipient = "qazisultan121jm@gmail.com"; // <--- CHANGE THIS TO YOUR EMAIL
 $subject = "New Service Request: $service";

// Email Body
 $email_content = "Name: $name\n";
 $email_content .= "Phone: $phone\n";
 $email_content .= "Service Type: $service\n";
 $email_content .= "Address: $address\n";
 $email_content .= "Message:\n$message\n";

// Email Headers
 $headers = "From: Swift Home Fix <noreply@technicalstars.online>\r\n";
 $headers .= "Reply-To: $recipient\r\n";
 $headers .= "X-Mailer: PHP/" . phpversion();

// Send the email
if (mail($recipient, $subject, $email_content, $headers)) {
    // If email sent successfully
    echo json_encode(["success" => true]);
} else {
    // If email failed
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to send email"]);
}
?>