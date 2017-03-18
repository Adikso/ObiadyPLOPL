<?php

class Email
{

    /**
     * Sends email. Anonymous function
     *
     * @param $function
     * @return bool
     */
    public static function send($function)
    {
        $message = new Message;
        $function($message);

        return self::sendEmail($message);
    }

    /**
     * Sends email
     *
     * @param $message
     * @return bool
     */
    public static function sendEmail($message)
    {
        $mail = new PHPMailer(true);
        $mail->CharSet = 'UTF-8';

        $mail->isSMTP();
        $mail->Host = config('mail.smtp');
        $mail->SMTPAuth = true;
        $mail->Username = config('mail.username');
        $mail->Password = config('mail.password');
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;

        $mail->setFrom($message->from, $message->displayName);
        $mail->addAddress($message->recipients->recipient);

        if (!empty($message->recipients->bcc)) {
            foreach ($message->recipients->bcc as $email) {
                $mail->AddBCC($email);
            }
        }

        $mail->isHTML($message->isHTML);

        $mail->Subject = $message->title;
        $mail->Body = $message->content;

        try {
            $status = $mail->send();
        }catch (Exception $e){
            Debug::$debugBar['exceptions']->addException($e);
            Log::error($e->getMessage());
            return false;
        }

        return $status;
    }

}