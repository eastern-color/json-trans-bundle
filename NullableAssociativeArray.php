<?php

namespace EasternColor\JsonTransBundle;

use ArrayAccess;

/**
 * [NullableAssociativeArray description].
 */
class NullableAssociativeArray implements ArrayAccess
{
    /**
     * Data.
     *
     * @var array
     */
    protected $data;

    protected $entity;

    public function __construct($data, $entity)
    {
        $this->data = $data;
        $this->entity = $entity;
    }

    /**
     * Assigns a value to the specified data.
     *
     * @param string The data key to assign the value to
     * @param mixed  The value to set
     */
    public function __set($key, $value)
    {
        throw new Exception(__CLASS__.' is READONLY');
        $this->data[$key] = $value;
    }

    /**
     * Whether or not an data exists by key.
     *
     * @param string An data key to check for
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * Unsets an data by key.
     *
     * @param string The key to unset
     */
    public function __unset($key)
    {
        throw new Exception(__CLASS__.' is READONLY');
        unset($this->data[$key]);
    }

    public function __call($method, $args)
    {
        // dump(__FUNCTION__);

        return isset($this->data[$method]) ? $this->data[$method] : null;
    }

    /**
     * Get a data by key.
     *
     * @param string The key data to retrieve
     */
    public function &__get($key)
    {
        return $this->data[$key];
    }

    /**
     * {@inheritdoc}
     */
    public function offsetSet($offset, $value)
    {
        throw new Exception(__CLASS__.' is READONLY');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetExists($offset)
    {
        return isset($this->data[$offset]);
    }

    /**
     * {@inheritdoc}
     */
    public function offsetUnset($offset)
    {
        throw new Exception(__CLASS__.' is READONLY');
    }

    /**
     * {@inheritdoc}
     */
    public function offsetGet($offset)
    {
        // dump(__FUNCTION__, $this->data, $this->data[$offset], $this->offsetExists($offset));

        return $this->offsetExists($offset) ? $this->data[$offset] : null;
    }
}
