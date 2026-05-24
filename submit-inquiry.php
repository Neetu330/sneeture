<?php
// 1. Establish secure API cross-origin response streams
header("Access-Control-Allow-Origin: *");
header("Content-Type: text/plain; charset=UTF-8");

// 2. Import the physical files manually from your local directory
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';

// 3. Declare the namespaces (Must be written exactly like this)
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// 4. Begin your form request interceptor logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Extract and sanitize inputs from the jQuery FormData package
    $name    = isset($_POST["name"]) ? strip_tags(trim($_POST["name"])) : '';
    $email   = isset($_POST["email"]) ? filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL) : '';
    $phone   = isset($_POST["phone"]) ? strip_tags(trim($_POST["phone"])) : ''; // <-- Added Phone Extraction
    $service = isset($_POST["service"]) ? strip_tags(trim($_POST["service"])) : '';
    $message = isset($_POST["message"]) ? trim($_POST["message"]) : '';

    // Check validation (allowing phone to be optional or filled)
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        http_response_code(400);
        echo "Validation Error: Please complete all form inputs correctly.";
        exit;
    }

    // Initialize the engine
    $mail = new PHPMailer(true);

    try {
        // --- GOOGLE OUTGOING SERVER CONFIGURATION ---
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'sneeture@gmail.com';             
        $mail->Password   = 'pflh uylf kyjf rxdw'; // Your Google App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;    
        $mail->Port       = 587;                               

        // --- ENVELOPE ROUTING STRUCTURE ---
        $mail->setFrom('sneeture@gmail.com', 'Sneeture Contact Form');
        $mail->addAddress('sneeture@gmail.com');               
        $mail->addReplyTo($email, $name);                      

        // --- MESSAGE CONTENT ---
        $mail->isHTML(true);
        $mail->Subject = "New Inquiry from " . $name . " [Sneeture Form]";
        
        // Formatted Email Body including the new Phone row
        $mail->Body    = "
        <html>
        <body style='font-family: Arial, sans-serif; line-height: 1.6; color: #222;'>
            <div style='max-width: 600px; background: #ffffff; padding: 20px; border: 1px solid #eee;'>
                <h3 style='border-bottom: 2px solid #000; padding-bottom: 8px;'>New Website Submission</h3>
                <p><strong>Name:</strong> {$name}</p>
                <p><strong>Email:</strong> {$email}</p>
                <p><strong>Phone Number:</strong> " . (!empty($phone) ? $phone : 'Not Provided') . "</p>
                <p><strong>Service Requested:</strong> {$service}</p>
                <p><strong>Message:</strong><br>" . nl2br(htmlspecialchars($message)) . "</p>
            </div>
        </body>
        </html>
        ";

        $mail->send();
        
        http_response_code(200);
        echo "Success";

    } catch (Exception $e) {
        http_response_code(500);
        echo "Mailer Error details: {$mail->ErrorInfo}";
    }
}
?>