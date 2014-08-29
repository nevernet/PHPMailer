<?php
/**
 * the mail wrapper for PHPMailer
 * https://github.com/nevernet/Watermark
 *
 * Copyright 2013, Daniel Qin <xin.qin@qinx.org>
 *
 * Licensed under the MIT license
 * Redistributions of part of code must retain the above copyright notice.
 *
 * @author Daniel Qin <xin.qin@qinx.org>
 * @version 1.0.0
 * @copyright Copyright 2010, Daniel Qin <xin.qin@qinx.org>
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 */

/*
yii main.php 配置

'mailer'=>array(
        'host'=>'',
        'port'=>25,
        'username'=>'',
        'password'=>'',
        'isSMTP'=>true,
        'SMTPAuth'=>true,
        'SMTPSecure'=>'',
        'from'=>'notify@example.com',
        'fromName'=>'notify',
    ),

*/

require __DIR__.'/../extensions/phpmailer/PHPMailerAutoload.php';

class CMail extends CApplicationComponent{

    public $isSMTP = true;
    public $host = ''; //the smtp server
    public $port = 25;
    public $SMTPAuth = true;
    public $username = '';
    public $password = '';
    public $SMTPSecure = ''; //can be tls, ssl
    public $isDebug = false;

    public $from = 'notify@example.com';
    public $fromName = 'notify';

    public $errors = '';

    public function init(){
        parent::init();

    }

    public function setup($host, $port, $username, $password,
            $isSMTP=true, $SMTPAuth=true, $SMTPSecure=''){
        $this->host = $host;
        $this->username = $username;
        $this->password = $password;
        $this->isSMTP = $isSMTP;
        $this->SMTPAuth = $SMTPAuth;
        $this->SMTPSecure = $SMTPSecure;
    }

    public function changeFrom($from, $fromName){
        $this->from = $from;
        $this->fromName = $fromName;
    }


    public function send($subject, $body, $to, $isHTML=true, $attachments=array()){
        $mail = new PHPMailer;
        $mail->setLanguage('zh_cn');

        if($this->isDebug){
            $mail->SMTPDebug = 1;
        }

        if($this->isSMTP)
            $mail->isSMTP();

        $mail->Host = $this->host;
        $mail->Port = $this->port;
        $mail->SMTPAuth = $this->SMTPAuth;
        $mail->Username = $this->username;
        $mail->Password = $this->password;
        $mail->SMTPSecure = $this->SMTPSecure;

        $mail->From = $this->from;
        $mail->FromName = $this->fromName;

        $mail->addAddress($to);

        $mail->WordWrap = 100;

        foreach ($attachments as $att){
            $mail->addAttachment($att);
        }

        if($isHTML)
            $mail->isHTML(true);

        $mail->Subject = $subject;
        $mail->Body    = $body;
        $mail->AltBody = $body;

        if(!$mail->send()) {
            $this->errors = $mail->ErrorInfo;

            return false;
        } else {
            return true;
        }
    }

    public function getErrors(){
        return $this->errors;
    }
}
?>
