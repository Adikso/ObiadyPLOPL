<?php

class MenuController extends Controller
{

    public function show($from = null, $to = null)
    {
        if (is_null($from)) {
            $from = date('Y-m-d', strtotime('Next Monday', time()));
        }

        if (is_null($to)) {
            $to = date('Y-m-d', strtotime('This Friday', strtotime($from)));
        }

        $menu = Orders::getMenu($from, $to);

        echo $this->getTemplates()->render("pages/manage/menu", [
            "menu" => group($menu, 'date'),
            'from' => $from,
            'to' => $to]);
    }

    public function update()
    {
        $from = date('Y-m-d', strtotime('Next Monday', time()));
        $to = date('Y-m-d', strtotime('This Friday', strtotime($from)));

        if (Input::getDate('from') && Input::getDate('to')) {
            $from = Input::getDate('from');
            $to = Input::getDate('to');
        }

        if (Input::has('disable')) {
            MenuService::disableDay(Input::get('disable'));
        } elseif (Input::has('enable')) {
            MenuService::enableDay(Input::get('enable'));
        }

        if (Input::get('add') == "true") {
            if (user()->getPermission("add_menu") != "ALLOW") {
                Alerts::show(new Alert(AlertType::Danger, "Brak uprawnień", "Nie masz uprawnień żeby dodawać menu"));
                $this->redirect(route('info'), ['alerts' => Alerts::getAlerts()]);
                return;
            }

            if (!checkCSRF()) {
                Alerts::show(new Alert(AlertType::Danger, "Zablokowano niebezpieczną operacje", "Menu nie zostało dodane"));
                $this->redirect(route('info'), ['alerts' => Alerts::getAlerts()]);
                return;
            }

            foreach ($_POST as $key => $value) {
                $parts = explode('#', $key);

                if (empty($value) || sizeof($parts) != 2) {
                    continue;
                }

                filter($value);

                if (validateDate($parts[0])) {
                    $dish = new Dish($parts[0], $value, $parts[1]);
                    $dish->insert();
                }
            }

            Log::info(LogType::AddMenu, json_encode($_POST));
        } else if (Input::has('delete')) {
            MenuService::deleteDish(Input::get('delete'));
            Log::info(LogType::RemoveDay, 'Removed dish '.Input::get('delete'));
        }

        $this->show($from, $to);
    }

}