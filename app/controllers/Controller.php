<?php


abstract class Controller
{

    public function getTemplates()
    {
        $templates = new League\Plates\Engine(ROOT_DIR . "app/templates");

        $messages = Messenger::get(user());
        $templates->addData(["messages" => $messages]);

        return $templates;
    }

    /**
     * Shortcut for redirect()
     *
     * @param $url
     * @param null $data
     */
    public function redirect($url, $data = null)
    {
        redirect($url, $data);
    }

    public function error($title, $description){
        echo $this->getTemplates()->render("pages/errors/universal",
            ["title" => $title, "description" => $description]);
    }

}