<?php


class LogsController extends Controller
{

    public function display()
    {
        $files = [];
        $handler = opendir(ROOT_DIR . 'logs');

        while ($f = readdir($handler)) {
            if ($f != "." && $f != "..") {
                $files[] = $f;
            }
        }

        closedir($handler);
        rsort($files);

        if (Input::has('file')) {

            $file = Input::get('file');
            if (validateDate($file)) {
                $file = $file . ".log";
            }

            if (in_array($file, $files)) {
                if ($file !== "mysql_errors.log") {
                    $parsed = [];
                    $handle = fopen(ROOT_DIR . 'logs/' . $file, "r");
                    if ($handle) {
                        while (($line = fgets($handle)) !== false) {

                            $parts = explode(" ", $line);
                            $date = $parts[0] . ' ' . $parts[1];

                            $type = $parts[2];
                            $data = substr($line, strpos($line, " - ") + 3);

                            switch ($type) {
                                case "ADD-MENU":
                                    $type = "Dodawanie menu";
                                    break;

                                case "PASSWORD_CHANGE_REQUEST":
                                    $data = json_decode($data);
                                    $type = "Żądanie zmiany emaila";
                                    $data = "Użytkownik: " . $data->{'ident'};
                                    break;

                                case "ORDER":
                                    $data = json_decode($data);
                                    $type = "Zamówienie";
                                    $data = "ID dania: " . $data->{'dish'} . "<br>
                                    ID użytkownika: " . $data->{'id'} . "<br>
                                    ID pizzy: " . $data->{'pizza'};
                                    break;

                                case "ORDER-PIZZA":
                                    $data = json_decode($data);
                                    $type = "Zamówienie pizzy";
                                    $data = "Składniki: " . $data->{'ingredients'};
                                    break;

                                case LogType::Activation:
                                    $data = json_decode($data);
                                    $type = "Aktywacja konta";
                                    $data = "ID użytkownika: " . $data->{'id'};
                                    break;

                                case "CLEARED_EXPIRED_KEYS":
                                    $type = "Sprzątanie tokenów";
                                    break;
                            }


                            $parsed[] = ["date" => $date, "type" => $type, "data" => $data];

                        }

                        fclose($handle);
                    } else {

                    }
                } else {
                    $content = file_get_contents(ROOT_DIR . 'logs/' . $file);
                    $content = str_replace(PHP_EOL, "<br>", $content);
                }
            } else {
                $content = "Brak logów z tego dnia";
            }

        }

        echo $this->getTemplates()->render("pages/admin/logs", [
            "parsed" => isset($parsed) ? $parsed : [],
            "content" => isset($content) ? $content : null,
            "files" => isset($files) ? $files : []
        ]);
    }

}