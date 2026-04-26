<?php
// api/send-mail.php

// 1. Set Headers for JSON & CORS (Cross-Origin Resource Sharing)
header("Content-Type: application/json; charset=utf-8");
header("Access-Control-Allow-Origin: *"); // Allows access from your domain
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

// 2. Check Request Method
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405); // Method Not Allowed
    echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
    exit;
}

// 3. Get Input Data
// Check content type to handle both JSON (Fetch API) and Form Data
 $contentType = isset($_SERVER["CONTENT_TYPE"]) ? $_SERVER["CONTENT_TYPE"] : '';

if (strpos($contentType, 'application/json') !== false) {
    // Case A: Input is JSON (from fetch() in HTML files)
    $jsonInput = file_get_contents("php://input");
    $data = json_decode($jsonInput, true);
} else {
    // Case B: Input is Standard Form Data (Fallback)
    $data = $_POST;
}

// 4. Clean and Validate Data
// strip_tags prevents XSS attacks
 $name = strip_tags(trim($data['name'] ?? ''));
 $phone = strip_tags(trim($data['phone'] ?? ''));
 $service = strip_tags(trim($data['service'] ?? ''));
 $address = strip_tags(trim($data['address'] ?? ''));
 $message = strip_tags(trim($data['message'] ?? ''));

// Check Required Fields
if (empty($name) || empty($phone) || empty($service)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "الاسم، الهاتف، ونوع الخدمة مطلوبة"]);
    exit;
}

// Basic Phone Validation (Optional but recommended for Saudi numbers)
// Checks for 05... or +966...
if (!preg_match('/^(05|00966|966|009705)[0-9]{8,15}$/', $phone)) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "رقم الهاتف غير صحيح"]);
    exit;
}

// 5. Email Configuration
 $recipient = "qazisultan121jm@gmail.com"; // <--- CHANGE THIS TO YOUR EMAIL
 $subject = "طلب جديد من الموقع: " . $service; 

// 6. Email Body Formatting (Text format is safer and more reliable)
 $emailBody = "لديك طلب خدمة جديد من الموقع:\n\n";
 $emailBody .= "---------------------------------\n";
 $emailBody .= "الاسم: " . $name . "\n";
 $emailBody .= "رقم الهاتف: " . $phone . "\n";
 $emailBody .= "الخدمة: " . $service . "\n";
 $emailBody .= "العنوان: " . $address . "\n";
 $emailBody .= "---------------------------------\n";
 $emailBody .= "تفاصيل الرسالة:\n";
 $emailBody .= $message . "\n";
 $emailBody .= "---------------------------------\n";
 $emailBody .= "يرجى التواصل مع العميل في أسرع وقت ممكن.\n\n";
 $emailBody .= "شركة Swift Home Fix\n";
 $emailBody .= "https://technicalstars.online";

// Email Headers
 $headers = "From: Swift Home Fix <noreply@technicalstars.online>\r\n";
 $headers .= "Reply-To: $recipient\r\n";
 $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
 $headers .= "X-Mailer: PHP/" . phpversion();

// 7. Send Email
// The 'mail' function is standard on Hostinger and Linux servers
if (mail($recipient, $subject, $emailBody, $headers)) {
    // 8. Success Response
    echo json_encode(["success" => true, "message" => "تم استلام طلبك بنجاح"]);
} else {
    // 9. Failure Response
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "فشل في إرسال البريد الإلكتروني"]);
}
?>