<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Email extends BaseConfig {
    public string $fromEmail = '';
    public string $fromName = '';
    public string $recipients = '';

    /**
     * The "user agent"
     */
    public string $userAgent = 'FcCM CRM';

    /**
     * The mail sending protocol: mail, sendmail, smtp
     */
    public string $protocol = 'smtp';

    /**
     * The server path to Sendmail.
     */
    public string $mailPath = '';

    /**
     * SMTP Server Hostname
     */
    public string $SMTPHost = 'mail.adm.tools';

    /**
     * SMTP Username
     */
    public string $SMTPUser = 'fbccm@web-dev-project.com';

    /**
     * SMTP Password
     */
    public string $SMTPPass = '6AnSnC3v52';

    /**
     * SMTP Port
     */
    public int $SMTPPort = 465;

    /**
     * SMTP Timeout (in seconds)
     */
    public int $SMTPTimeout = 60;

    /**
     * Enable persistent SMTP connections
     */
    public bool $SMTPKeepAlive = false;

    /**
     * SMTP Encryption.
     *
     * @var string '', 'tls' or 'ssl'. 'tls' will issue a STARTTLS command
     *             to the server. 'ssl' means implicit SSL. Connection on port
     *             465 should set this to ''.
     */
    public string $SMTPCrypto = 'ssl';

    /**
     * Enable word-wrap
     */
    public bool $wordWrap = true;

    /**
     * Character count to wrap at
     */
    public int $wrapChars = 76;

    /**
     * Type of mail, either 'text' or 'html'
     */
    public string $mailType = 'html';

    /**
     * Character set (utf-8, iso-8859-1, etc.)
     */
    public string $charset = 'UTF-8';

    /**
     * Whether to validate the email address
     */
    public bool $validate = true;

    /**
     * Email Priority. 1 = highest. 5 = lowest. 3 = normal
     */
    public int $priority = 3;

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $CRLF = "\r\n";

    /**
     * Newline character. (Use “\r\n” to comply with RFC 822)
     */
    public string $newline = "\r\n";

    /**
     * Enable BCC Batch Mode.
     */
    public bool $BCCBatchMode = false;

    /**
     * Number of emails in each BCC batch
     */
    public int $BCCBatchSize = 200;

    /**
     * Enable notify message from server
     */
    public bool $DSN = false;

    public function __construct() {
        parent::__construct();

        $this->fromEmail = env('email.fromEmail', 'default-email@example.com');
        $this->fromName = env('email.fromName', 'Default Name');
        $this->userAgent = env('email.userAgent', 'CodeIgniter');
        $this->protocol = env('email.protocol', 'smtp');
        $this->SMTPHost = env('email.SMTPHost', 'smtp.example.com');
        $this->SMTPUser = env('email.SMTPUser', 'user@example.com');
        $this->SMTPPass = env('email.SMTPPass', 'default_password');
        $this->SMTPPort = env('email.SMTPPort', 587);
        $this->SMTPCrypto = env('email.SMTPCrypto', 'tls');
    }
}
