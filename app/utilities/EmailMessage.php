<?php


class EmailMessage
{
    public $from = null;
    public $displayName = null;

    public $isHTML = false;
    public $content = null;

    public $title = null;

    public $recipients = null;

    public function __construct()
    {
        $this->recipients = new EmailRecipients;
    }

    /**
     * Sets sender name and source address
     *
     * @param $email
     * @param $displayName
     */
    public function from($email, $displayName)
    {
        $this->from = $email;
        $this->displayName = $displayName;
    }

    /**
     * Sets addressed
     *
     * @param $email
     * @return EmailRecipients|null
     */
    public function to($email)
    {
        $this->recipients->setRecipient($email);
        return $this->recipients;
    }

    /**
     * Sets email title
     *
     * @param $title
     */
    public function title($title)
    {
        $this->title = $title;
    }

    /**
     * Sets email content as plain text
     *
     * @param $content
     */
    public function text($content)
    {
        $this->content = $content;
    }

    /**
     * Sets email content as HTML
     *
     * @param $content
     */
    public function html($content)
    {
        $this->isHTML = true;
        $this->content = $content;
    }

}