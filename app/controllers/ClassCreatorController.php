<?php


class ClassCreatorController extends Controller
{

    public function show()
    {
        $possible_owners = Database::query("SELECT COLUMN_TYPE FROM INFORMATION_SCHEMA.COLUMNS
                          WHERE TABLE_NAME = 'classes' AND COLUMN_NAME = 'owner' LIMIT 1;")->fetch()['COLUMN_TYPE'];

        $possible_owners = explode(',', str_replace("'", '', substr($possible_owners, 5, -1)));

        echo $this->getTemplates()->render("pages/class/create",
            ['created' => false,
                'possible_owners' => $possible_owners]);
    }

    public function create()
    {
        $users = [];
        if (Input::has('create')) {

            if (strlen(Input::get('inputYear')) != 4
                || !Input::has('inputClass')
                || strlen(Input::get('inputClass')) > 2
                || !Input::has('inputOwner')
            ) {
                return;
            }

            $class = new SchoolClass();
            $class->year = Input::get('inputYear');
            $class->class = Input::get('inputClass');
            $class->email = Input::get('inputEmail');
            $class->owner = Input::get('inputOwner');

            $classId = $class->insert();
            $users = [];

            for ($i = 0; $i < 26; ++$i) {
                $user = new User();
                $user->login = Input::get('inputLogin')['val'][$i];
                $user->firstname = Input::get('inputFirstname')['val'][$i];
                $user->secondname = Input::get('inputSecondname')['val'][$i];
                $user->role = Input::get('inputRole')['val'][$i];
                $user->classId = $classId;
                $user->balance = 0;

                if (!empty($user->login)) {
                    $userId = $user->insert();
                    $token = new Token();
                    $token->type = TokenType::PasswordReminder;
                    $token->devicename = "Tworzenie klasy";
                    $token->additional = "HIDDEN";
                    $token->authKey = generateRandomString(10, '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ');
                    $token->userId = $userId;

                    $authKey = Auth::addAuthKey($token);

                    $users[$authKey] = $user;
                }
            }
        }

        echo $this->getTemplates()->render("pages/class/create",
            ['created' => true,
                'users' => $users]);
    }

}