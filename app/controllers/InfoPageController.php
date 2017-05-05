<?php


class InfoPageController extends Controller
{

    public function show()
    {
        echo $this->getTemplates()->render("pages/info");
    }

    public function showHelp()
    {
        if (isEnabled('shell_exec')){
            $last_version_details = shell_exec("git log -1 --pretty=format:'%h - %s (%ci)' --abbrev-commit");
        }else{
            $last_version_details = null;
        }

        echo $this->getTemplates()->render("pages/help", ['last_version_details' => $last_version_details]);
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