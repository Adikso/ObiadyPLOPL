<?php


class EmailRecipients
{
    public $recipient = null;
    public $bcc = [];

    /**
     * Sets email recipient
     *
     * @param $email
     */
    public function setRecipient($email)
    {
        $this->recipient = $email;
    }

    /**
     * Sets Blind Carbon Copy
     * Email will be also sent to this address
     *
     * @param $email
     * @return $this
     */
    public function bcc($email)
    {
        $this->bcc[] = $email;
        return $this;
    }
}