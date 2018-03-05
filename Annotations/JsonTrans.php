<?php

namespace EasternColor\JsonTransBundle\Annotations;

use Doctrine\Common\Annotations\Annotation;
use Doctrine\Common\Annotations\Target;

/**
 * @Annotation
 * @Target({"CLASS", "PROPERTY"})
 */
class JsonTrans
{
    public $fieldsGroup = null;

    public $fields = [];
}
