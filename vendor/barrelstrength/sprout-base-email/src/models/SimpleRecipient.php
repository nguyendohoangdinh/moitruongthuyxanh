<?php

namespace barrelstrength\sproutbaseemail\models;

use craft\base\Model;

/**
 * Represents a simple email recipient
 *
 * @property array $customFields
 */
class SimpleRecipient extends Model
{
    /**
     * The name of an email recipient
     *
     * @var string
     */
    public $name;

    /**
     * The email address of an email recipient
     *
     * @var string
     */
    public $email;

    /**
     * @var array Additional custom fields
     */
    private $_customFieldValues = [];

    /**
     * Check if custom properties exist in the $_customFieldValues array if they are not found on the model
     *
     * Attributes can be retrieved via $simpleRecipient->customAttribute
     *
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->_customFieldValues[$name])) {
            return $this->_customFieldValues[$name] ?? null;
        }

        return parent::__get($name);
    }

    /**
     * Add custom properties to the $_customFieldValues array if they are not found on the model
     *
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (!property_exists($this, $name)) {
            $this->_customFieldValues[$name] = $value;

            return;
        }
        parent::__set($name, $value);
    }

    /**
     * Bulk add custom properties to the $_customFieldValues array
     *
     * @param array $fields
     */
    public function setCustomFields(array $fields = [])
    {
        foreach ($fields as $name => $value) {
            $this->_customFieldValues[$name] = $value;
        }
    }
}
