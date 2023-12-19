<?php

use app\src\model\dataObject\Notes;
use app\src\view\components\ui\Table;

/* @var $notes Notes[] */

Table::createTable($notes, ['idnote', 'etudiant', 'notefinal', "valider", "modifier"], function ($note) {
    Table::cell($note->getIdnote());
    Table::cell($note->getEtudiant());
    Table::cell($note->noteFinal());
    Table::button("/gererNote/valide/" . $note->getIdnote(), "Valider", "green");
    Table::button("/gererNote/modifier/" . $note->getIdnote(), "Modifier", "blue");
});
