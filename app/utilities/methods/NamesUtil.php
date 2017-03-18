<?php

/**
 * Returns human friendly type name
 *
 * @param $type
 * @return string
 */
function getTypeName($type)
{
    $typeNames = [
        MealTypes::Meat => "Mięsny",
        MealTypes::Vege => "Wega",
        MealTypes::Pizza => "Pizza"
    ];

    return array_get_default($type, $typeNames, "Inne");
}

/**
 * Returns human friendly type color
 *
 * @param $type
 * @return string
 */
function getTypeColor($type)
{
    $typeColors = [
        MealTypes::Meat => "#d9534f",
        MealTypes::Vege => "#5cb85c",
        MealTypes::Pizza => "#f0ad4e"
    ];

    return array_get_default($type, $typeColors, "#337ab7");
}

/**
 * Returns human friendly role name
 *
 * @param $name
 * @return string
 */
function getRoleName($name)
{
    $roleNames = [
        Roles::Admin => "Administrator",
        Roles::Manager => "Globalny",
        Roles::ClassCollector => "Skarbnik",
        Roles::User => "Użytkownik",
        Roles::Suspended => "Zawieszony",
        Roles::Removed => "<s>Usunięty</s>"
    ];

    return array_get_default($name, $roleNames, "???");
}

/**
 * Returns translation of day name
 *
 * @param $name
 * @return string
 */
function getTranslatedDayName($name)
{
    $dayNames = [
        "Monday" => "Poniedziałek",
        "Tuesday" => "Wtorek",
        "Wednesday" => "Środa",
        "Thursday" => "Czwartek",
        "Friday" => "Piątek",
        "Saturday" => "Sobota",
        "Sunday" => "Niedziela"
    ];

    return $dayNames[$name];
}

