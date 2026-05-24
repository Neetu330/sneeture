<?php
/**
 * PHPMailer Clean Standalone Production Build
 */
namespace PHPMailer\PHPMailer;

class Exception extends \Exception {
    public function errorMessage() {
        return $this->getMessage();
    }
}

class PHPMailer {
    public $Priority = null;
    public $CharSet = 'UTF-8';
    public $ContentType = 'text/plain';
    public $Encoding = '8bit';
    public $ErrorInfo = '';
    public $From = 'root@localhost';
    public $FromName = 'Root User';
    public $Sender = '';
    public $Subject = '';
    public $Body = '';
    public $AltBody = '';
    public $MIMEBody = '';
    public $MIMEHeader = '';
    protected $mailHeader = '';
    public $WordWrap = 0;
    public $Mailer = 'smtp';
    public $Sendmail = '/usr/sbin/sendmail';
    public $UseSendmailOptions = true;
    public $ConfirmReadingTo = '';
    public $Hostname = '';
    public $MessageID = '';
    public $MessageDate = '';
    public $Host = 'localhost';
    public $Port = 25;
    public $Helo = '';
    public $SMTPSecure = '';
    public $SMTPAuth = false;
    public $SMTPOptions = [];
    public $Username = '';
    public $Password = '';
    public $AuthType = '';
    public $Timeout = 5;
    public $SMTPDebug = 0;
    public $Debugoutput = 'echo';
    public $SMTPKeepAlive = false;
    public $SingleTo = false;
    public $LE = "\r\n";
    protected $to = [];
    protected $cc = [];
    protected $bcc = [];
    protected $ReplyTo = [];
    protected $all_recipients = [];
    protected $RecipientsQueue = [];
    protected $ReplyToQueue = [];
    protected $attachment = [];
    protected $CustomHeader = [];
    protected $lastMessageID = '';
    protected $message_type = '';
    protected $boundary = [];
    protected $language = [];
    protected $error_count = 0;
    protected $sign_cert_file = '';
    protected $sign_key_file = '';
    protected $sign_extracerts_file = '';
    protected $sign_key_pass = '';
    protected $exceptions = false;
    protected $smtp = null;

    const ENCRYPTION_STARTTLS = 'tls';
    const ENCRYPTION_SMTPS = 'ssl';

    public function __construct($exceptions = null) {
        if (null !== $exceptions) { $this->exceptions = (bool) $exceptions; }
    }
    public function isSMTP() { $this->Mailer = 'smtp'; }
    public function setFrom($address, $name = '', $auto = true) {
        $this->From = $address; $this->FromName = $name; return true;
    }
    public function addAddress($address, $name = '') { return $this->addAnAddress('to', $address, $name); }
    public function addReplyTo($address, $name = '') { return $this->addAnAddress('Reply-To', $address, $name); }
    protected function addAnAddress($kind, $address, $name = '') {
        $this->{$kind}[] = [$address, $name]; return true;
    }
    public function isHTML($ishtml = true) {
        $this->ContentType = $ishtml ? 'text/html' : 'text/plain';
    }
    public function send() {
        if ($this->Mailer == 'smtp') {
            $this->smtp = new SMTP();
            $this->smtp->Timeout = $this->Timeout;
            if (!$this->smtp->connect($this->Host, $this->Port)) { throw new Exception("Connect failed"); }
            if ($this->SMTPAuth && !$this->smtp->authenticate($this->Username, $this->Password)) { throw new Exception("Auth failed"); }
            
            $header = "MIME-Version: 1.0".$this->LE."Content-Type: ".$this->ContentType."; charset=".$this->CharSet.$this->LE;
            $header .= "From: ".$this->FromName." <".$this->From.">".$this->LE;
            foreach($this->ReplyTo as $r) { $header .= "Reply-To: ".$r[1]." <".$r[0].">".$this->LE; }
            $header .= "Subject: ".$this->Subject.$this->LE.$this->LE;
            
            $to_emails = [];
            foreach($this->to as $t) { $to_emails[] = $t[0]; }
            
            if (!$this->smtp->data($header . $this->Body, $this->From, $to_emails)) { throw new Exception("Data failed"); }
            $this->smtp->close();
            return true;
        }
        return false;
    }
}

class SMTP {
    protected $smtp_conn = null;
    public $Timeout = 5;
    public function connect($host, $port = null) {
        $this->smtp_conn = @fsockopen($host, $port, $errno, $errstr, $this->Timeout);
        if(!$this->smtp_conn) return false;
        fgets($this->smtp_conn, 512);
        fputs($this->smtp_conn, "EHLO localhost\r\n");
        fgets($this->smtp_conn, 512); fgets($this->smtp_conn, 512);
        return true;
    }
    public function authenticate($username, $password) {
        fputs($this->smtp_conn, "AUTH LOGIN\r\n"); fgets($this->smtp_conn, 512);
        fputs($this->smtp_conn, base64_encode($username) . "\r\n"); fgets($this->smtp_conn, 512);
        fputs($this->smtp_conn, base64_encode($password) . "\r\n"); $r = fgets($this->smtp_conn, 512);
        return (substr($r, 0, 3) == '235');
    }
    public function data($msg_data, $from, $to_array) {
        fputs($this->smtp_conn, "MAIL FROM:<" . $from . ">\r\n"); fgets($this->smtp_conn, 512);
        foreach($to_array as $to) { fputs($this->smtp_conn, "RCPT TO:<" . $to . ">\r\n"); fgets($this->smtp_conn, 512); }
        fputs($this->smtp_conn, "DATA\r\n"); fgets($this->smtp_conn, 512);
        fputs($this->smtp_conn, $msg_data . "\r\n.\r\n"); $r = fgets($this->smtp_conn, 512);
        return (substr($r, 0, 3) == '250');
    }
    public function close() { if($this->smtp_conn) fclose($this->smtp_conn); }
}