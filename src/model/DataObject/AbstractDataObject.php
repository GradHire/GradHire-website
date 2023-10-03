<?php

namespace app\src\model\DataObject;

abstract class AbstractDataObject
{

  protected abstract function getValueColonne(string $nomColonne): string;
}
