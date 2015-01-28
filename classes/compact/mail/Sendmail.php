<?php
namespace compact\mail;

use compact\validation\ValidationException;

/**
 * PHP mailer
 *
 * @example $mail = new Sendmail();<br />
 *          $mail->setCharSet("UTF-8");<br />
 *          $mail->from("test@domain.com", "Me");<br />
 *          $mail->to("test@domain.com");<br />
 *          $mail->cc("CC@domain.com");<br />
 *          $mail->bcc("BCC@domain.com");<br />
 *          $mail->subject("Subject");<br />
 *          $mail->text("Text");<br />
 *          $mail->attachment("mail.php");<br />
 *          $mail->attachment("mail2.php");<br />
 *          $mail->send();<br />
 *         
 * @package mail
 *         
 */
class Sendmail
{

    /**
     * The mail header
     *
     * @var string
     */
    private $header;

    private $textheader;

    private $textboundary;

    private $emailboundary;

    private $charset = "UTF-8";

    private $subject;

    private $to;

    private $addAttachment = array();

    private $cc = array();

    private $bcc = array();

    /**
     * Creates a new Sendmail
     */
    public function __construct()
    {
        $this->textboundary = uniqid(time());
        $this->emailboundary = uniqid(time());
    }

    /**
     * Adds an attachment
     *
     * @param
     *            $aFilename
     *            
     * @throws Exception when file could not be found
     */
    public function attachment($aFilename)
    {
        if (is_file($aFilename)) {
            
            $attachment_header = '--' . $this->emailboundary . "\r\n";
            $attachment_header .= 'Content-Type: application/octet-stream; name="' . basename($aFilename) . '"' . "\r\n";
            $attachment_header .= 'Content-Transfer-Encoding: base64' . "\r\n";
            $attachment_header .= 'Content-Disposition: attachment; filename="' . basename($aFilename) . '"' . "\r\n\r\n";
            
            $file['content'] = fread(fopen($aFilename, "rb"), filesize($aFilename));
            $file['content'] = base64_encode($file['content']);
            $file['content'] = chunk_split($file['content'], 72);
            
            $this->addAttachment[] = $attachment_header . $file['content'] . "\r\n";
        } else {
            throw new \Exception("Could not find file: " . $aFilename);
        }
    }

    /**
     * Sets a bcc address
     *
     * @param $aAddress string            
     *
     * @throws ValidationException when validation of the email address fails
     */
    public function bcc($aAddress)
    {
        $this->validateEmail($aAddress);
        $this->bcc[] = $aAddress;
    }

    /**
     * Sets a cc address
     *
     * @param $aAddress string            
     *
     * @throws ValidationException when validation of the email address fails
     */
    public function cc($aAddress)
    {
        $this->validateEmail($aAddress);
        $this->cc[] = $aAddress;
    }

    /**
     *
     * @param $email string            
     * @param $name string            
     *
     * @throws ValidationException when validation of the email address fails
     */
    public function from($email, $name = NULL)
    {
        if ($name == null)
            $this->header .= 'From: ' . $email . "\r\n";
        else
            $this->header .= 'From: ' . $name . '<' . $email . '>' . "\r\n";
    }

    private function makeMimeMail()
    {
        if (count($this->cc) > 0) {
            $this->header .= 'Cc: ';
            
            for ($i = 0; $i < count($this->cc); $i ++) {
                if ($i > 0)
                    $this->header .= ',';
                
                $this->header .= $this->cc[$i];
            }
            $this->header .= "\r\n";
        }
        
        if (count($this->bcc) > 0) {
            $this->header .= 'Bcc: ';
            
            for ($j = 0; $j < count($this->bcc); $j ++) {
                if ($j > 0)
                    $this->header .= ',';
                
                $this->header .= $this->bcc[$j];
            }
            
            $this->header .= "\r\n";
        }
        
        $this->header .= 'MIME-Version: 1.0' . "\r\n";
    }

    /**
     * Sends an email
     *
     * @return void
     *
     * @throws \Exception When sending mail failed
     */
    // TODO throw a more distinct exception
    public function send()
    {
        $this->makeMimeMail();
        
        $header = $this->header;
        
        if (count($this->addAttachment) > 0) {
            $header .= 'Content-Type: multipart/mixed; boundary="' . $this->emailboundary . '"' . "\r\n\r\n";
            $header .= '--' . $this->emailboundary . "\r\n";
            $header .= $this->textheader;
            if (count($this->addAttachment) > 0)
                $header .= implode("", $this->addAttachment);
            $header .= '--' . $this->emailboundary . '--';
        } else {
            $header .= $this->textheader;
        }
        
        if (! @mail($this->to, $this->subject, "", $header)) {
            throw new \Exception("Unable to send mail.");
        }
    }

    public function setCharSet($aCharSet)
    {
        $this->charset = $aCharSet;
    }

    public function subject($subject)
    {
        $this->subject = $subject;
    }

    public function text($aText)
    {
        $this->textheader .= 'Content-Type: multipart/alternative; boundary="' . $this->textboundary . '"' . "\r\n\r\n";
        $this->textheader .= '--' . $this->textboundary . "\r\n";
        $this->textheader .= 'Content-Type: text/plain; charset="' . $this->charset . '"' . "\r\n";
        $this->textheader .= 'Content-Transfer-Encoding: quoted-printable' . "\r\n\r\n";
        $this->textheader .= strip_tags($aText) . "\r\n\r\n";
        
        $this->textheader .= '--' . $this->textboundary . "\r\n";
        $this->textheader .= 'Content-Type: text/html; charset="' . $this->charset . '"' . "\r\n";
        $this->textheader .= 'Content-Transfer-Encoding: 7bit' . "\r\n\r\n";
        
        // check for an opening html tag
        if (! preg_match("/\<html/", $aText)) {
            $aText = '<html><body>' . $aText . '</body></html>';
        }
        $this->textheader .= $aText . "\r\n\r\n";
        $this->textheader .= '--' . $this->textboundary . '--' . "\r\n\r\n";
    }

    /**
     * Sets the to email address
     *
     * @param $to string            
     *
     * @throws ValidationException when validation of the email address fails
     */
    public function to($aAddress)
    {
        $this->validateEmail($aAddress);
        $this->to = $aAddress;
    }

    /**
     * Validates an emailaddress
     *
     * @param $aMailaddress string            
     *
     * @throws ValidationException when validation of the email address fails
     */
    private function validateEmail($aMailaddress)
    {
        if (! preg_match("/^[[:alnum:]][a-z0-9_.-]*@[a-z0-9.-]+\.[a-z]{2,6}$/i", $aMailaddress)) {
            throw new ValidationException('E-mailaddress ' . $aMailaddress . ' is not correct.');
        }
    }
}