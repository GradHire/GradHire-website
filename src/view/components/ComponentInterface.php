<?php

namespace app\src\view\components;

use InvalidArgumentException;

/**
 * L'Interface ComponentInterface est une interface qui définit une méthode pour générer un rendu visuel basé sur le tableau de paramètres fourni.
 */
interface ComponentInterface
{
    /**
     * Cette méthode static permet de générer un rendu visuel en fonction du tableau de paramètres fournis.
     *
     * @param array $params Un tableau associatif de paramètres nécessaires pour le rendu.
     *
     * @return void
     *
     * @throws InvalidArgumentException si les paramètres fournis ne sont pas corrects.
     */
    public static function render(array $params): void;
}