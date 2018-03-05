<?php

namespace EasternColor\JsonTransBundle;

use ArrayAccess;
use EasternColor\CoreBundle\Service\StaticHelpers\StaticRequest;

/**
 * [JsonTranslate description].
 */
class JsonTranslate extends NullableAssociativeArray implements ArrayAccess
{
    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        if (isset($this->data[$offset])) {
            return true;
        } elseif (isset($this->data[StaticRequest::getLocale()][$offset])) {
            return true;
        } elseif (isset($this->data['en'][$offset])) {
            return true;
        } else {
            // dump($offset, $this->data, $this->entity);

            return false;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        return static::get($this->data, $offset);
    }

    public static function get($data, $offset)
    {
        if (isset($data[$offset])) {
            return $data[$offset];
        } elseif (isset($data[StaticRequest::getLocale()][$offset])) {
            return $data[StaticRequest::getLocale()][$offset];
        } elseif (isset($data['en'][$offset])) {
            return $data['en'][$offset];
        } else {
            return null;
        }
    }
}
