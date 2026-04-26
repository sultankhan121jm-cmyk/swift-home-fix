<?php
// api/contact.php

// 1. Set Headers to allow JSON and CORS (Cross-Origin Resource Sharing)
header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type");

// 2. Check Request Method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
    exit;
}

// 3. Get the raw POST data (JSON) and decode it
 $jsonInput = file_get_contents("php://input");
 $data = json_decode($jsonInput, true);

// Check if JSON decoding failed
if ($data === null) {
    http_response_code(400); // Bad Request
    echo json_encode(["success" => false, "message" => "Invalid JSON Data"]);
    exit;
}

// 4. Clean and Validate Data
// Using strip_tags to prevent XSS (Cross-Site Scripting)
 $name  = strip_tags(trim($data['name'] ?? ''));
 $phone = strip_tags(trim($data['phone'] ?? ''));
 $service = strip_tags(trim($data['service'] ?? ''));
 $address = strip_tags(trim($data['address'] ?? ''));
 $message = strip_tags(trim($data['message'] ?? ''));

// Check required fields
if (empty($name) || empty($phone) || empty($service)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Name, Phone, and Service are required"]);
    exit;
}

// 5. Email Configuration
 $recipient = "qazisultan121jm@gmail.com"; // <--- REPLACE WITH YOUR EMAIL
 $subject = "New Service Request: " . ucfirst($service); // e.g., "New Service Request: Ac"

// Email Body Formatting
 $email_content = "Name: $name\n";
 $email_content .= "Phone: $phone\n";
 $email_content .= "Service Type: $service\n";
 $email_content .= "Address: $address\n";
 $email_content .= "Message:\n$message\n";

// Email Headers
 $headers = "From: Swift Home Fix <noreply@technicalstars.online>\r\n";
 $headers .= "Reply-To: $recipient\r\n";
 $headers .= "X-Mailer: PHP/" . phpversion();

// 6. Send Email
if (mail($recipient, $subject, $email_content, $headers)) {
    // 7. Success Response
    echo json_encode(["success" => true]);
} else {
    // 8. Failure Response
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Failed to send email"]);
}
?>