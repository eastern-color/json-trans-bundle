<?php

namespace EasternColor\JsonTransBundle\Twig;

use EasternColor\JsonTransBundle\JsonTranslate;
use Twig_Extension;
use Twig_SimpleFunction;

class VarsExtension extends Twig_Extension
{
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('json_trans', [$this, 'jsonTrans']),
        ];
    }

    public function jsonTrans($objectArray, $debug = false)
    {
        if ($objectArray instanceof JsonTranslate) {
            return $objectArray;
        } elseif (is_array($objectArray['jsonTrans'])) {
            $jsonTransArray = $objectArray['jsonTrans'];
        } else {
            $jsonTransArray = json_decode($objectArray['jsonTrans'], true);
        }

        return new JsonTranslate($jsonTransArray, null);
    }

    public function getName()
    {
        return 'json_trans_twig_vars_extension';
    }
}
