<?php


class InfoPageController extends Controller
{

    public function show()
    {
        echo $this->getTemplates()->render("pages/info");
    }

    public function showHelp()
    {
        echo $this->getTemplates()->render("pages/help");
    }

    public function showHistory()
    {
        echo $this->getTemplates()->render("pages/systemhistory");
    }

    public function error404()
    {
        echo $this->getTemplates()->render("pages/errors/404");
    }

}