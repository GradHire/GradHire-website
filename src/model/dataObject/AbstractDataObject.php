<?php

namespace app\src\model\dataObject;

abstract class AbstractDataObject
{

    protected abstract function getValueColonne(string $nomColonne): string;

}
