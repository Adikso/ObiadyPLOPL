<?php


class OrderingController extends Controller
{

    public function show()
    {
        $from = date('Y-m-d', time());
        $to = date('Y-m-d', strtotime('Next Sunday +1 week', time()));

        $menu = group(Orders::getMenu($from, $to, user()), 'date');
        $pizzas = Orders::getPizzas(user()->classId, $from, $to);

        if (empty($menu)){
            Alerts::show(new Alert(AlertType::Warning, null, 'Pusto, nie ma niczego do zamówienia :C'));
        }

        echo $this->getTemplates()->render("pages/order", ["menu" => $menu, 'pizzas' => $pizzas]);
    }

    public function action()
    {

        if (!checkCSRF()) {
            Alerts::show(new Alert(AlertType::Danger, "Formularz wygasł", "Zamówienie nie zostało złożone"));
            $this->show();
            return;
        }

        if (Input::has('order') && Input::has('dishes')) {

            foreach (Input::get('dishes') as $dish) {

                if (empty($dish['id']) || !is_numeric($dish['id'])) {
                    continue;
                }

                $id = $dish['id'];
                $selected_ingredients = (isset($dish['ingredients']) ? $dish['ingredients'] : null);

                if (!is_null($selected_ingredients) && !Input::has('pizza')) {

                    $ingredients = config("orders.pizza.ingredients");
                    $ingredients[] = "";

                    $array_count = array_count_values($selected_ingredients);
                    if (array_key_exists('', $array_count) && $array_count[''] === count($selected_ingredients)){
                        Alerts::show(new Alert(AlertType::Danger, null, 'Musisz wybrać przynajmniej jeden składnik ;-;'));
                        continue;
                    }

                    foreach ($selected_ingredients as $ing){
                        if (!in_array($ing, $ingredients)){
                            Alerts::show(new Alert(AlertType::Danger, null, 'Podane składniki są nieprawidłowe'));
                            continue;
                        }
                    }

                    // Remove empty ingredients
                    $selected_ingredients = array_filter($selected_ingredients);

                    $pizza = new Pizza($selected_ingredients);

                    if (!Orders::order($id, $pizza)) {
                        Alerts::show(new Alert(AlertType::Danger, null, 'Wystąpił błąd')); // TODO: Make better error description
                    }
                } else if(Input::has('pizza')){
                    if (!Orders::order($id, Input::get('pizza'))) {
                        Alerts::show(new Alert(AlertType::Danger, "Nie udało się dołączyć do połówki", 'Wystąpił błąd'));
                    }
                } else {
                    if (!Orders::order($id)) {
                        Alerts::show(new Alert(AlertType::Danger, null, 'Wystąpił błąd')); // TODO: Make better error description
                    }
                }

            }

        } else if (Input::has('cancel')) {
            if (validateDate(Input::get('cancel'))) {
                Orders::cancel(Input::get('cancel'));
            }

        }

        $this->show();

    }


}