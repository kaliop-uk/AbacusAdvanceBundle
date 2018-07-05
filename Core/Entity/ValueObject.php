<?php

namespace Abacus\AdvanceBundle\Core\Entity;

use Abacus\AdvanceBundle\Core\Exception\PropertyNotFoundException;
use Abacus\AdvanceBundle\Core\Exception\PropertyReadOnlyException;

/**
 * The base class for all value objects and structs.
 *
 * Supports readonly properties by marking them as protected.
 * In this case they will only be writable using constructor, and need to be documented
 * using @property-read <type> <$var> in class doc in addition to inline property doc.
 * Writable properties must be public and must be documented inline.
 */
abstract class ValueObject
{
    const REJECT_UNKNOWN_PROPERTIES = 0;
    const DROP_UNKNOWN_PROPERTIES = 1;
    const ACCEPT_UNKNOWN_PROPERTIES = 2;

    private $forcePropertyCreation = false;

    /**
     * Construct object optionally with a set of properties.
     *
     * Readonly properties values must be set using $properties as they are not writable anymore
     * after object has been created.
     *
     * @param array $properties
     * @param int $unknownPropertiesHandling decides what to do with unexpected elements in the $properties array
     * @throws \Abacus\AdvanceBundle\Core\Exception\PropertyNotFoundException
     */
    public function __construct(array $properties = array(), $unknownPropertiesHandling = self::ACCEPT_UNKNOWN_PROPERTIES)
    {
        foreach ($properties as $property => $value) {
            $property = lcfirst($property);
            try {
                $this->$property = $value;
            } catch (PropertyNotFoundException $e) {
                switch ($unknownPropertiesHandling) {
                    case self::REJECT_UNKNOWN_PROPERTIES:
                        throw $e;
                    case self::ACCEPT_UNKNOWN_PROPERTIES:
                        $this->forcePropertyCreation = true;
                        // go out of our way to be tolerant in case we get funky characters that do not translate well
                        // into easily-accessible property names.
                        // Btw: wanna have some fun ? see https://stackoverflow.com/questions/17973357/what-are-the-valid-characters-in-php-variable-method-class-etc-names
                        $property = preg_replace('/[^a-zA-Z0-9_\x7f-\xff]/', '_', $property);
                        $this->$property = $value;
                        $this->forcePropertyCreation = false;
                        break;
                    case self::DROP_UNKNOWN_PROPERTIES:
                        break;

                }
            }
        }
    }

    /**
     * Magic set function handling writes to non public properties.
     *
     * @ignore This method is for internal use
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\PropertyNotFoundException When property does not exist
     * @throws \Abacus\AdvanceBundle\Core\Exception\PropertyReadOnlyException When property is readonly (protected)
     *
     * @param string $property Name of the property
     * @param string $value
     */
    public function __set($property, $value)
    {
        if ( $this->forcePropertyCreation) {
            return;
        }
        if (property_exists($this, $property)) {
            throw new PropertyReadOnlyException($property, get_class($this));
        }
        throw new PropertyNotFoundException($property, get_class($this));
    }

    /**
     * Magic get function handling read to non public properties.
     *
     * Returns value for all readonly (protected) properties.
     *
     * @ignore This method is for internal use
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\PropertyNotFoundException exception on all reads to undefined properties so typos are not silently accepted.
     *
     * @param string $property Name of the property
     *
     * @return mixed
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }
        throw new PropertyNotFoundException($property, get_class($this));
    }

    /**
     * Magic isset function handling isset() to non public properties.
     *
     * Returns true for all (public/)protected/private properties.
     *
     * @ignore This method is for internal use
     *
     * @param string $property Name of the property
     *
     * @return bool
     */
    public function __isset($property)
    {
        return property_exists($this, $property);
    }

    /**
     * Magic unset function handling unset() to non public properties.
     *
     * @ignore This method is for internal use
     *
     * @throws \Abacus\AdvanceBundle\Core\Exception\PropertyNotFoundException exception on all writes to undefined properties so typos are not silently accepted and
     * @throws \Abacus\AdvanceBundle\Core\Exception\PropertyReadOnlyException exception on readonly (protected) properties.
     *
     * @uses __set()
     * @param string $property Name of the property
     *
     * @return bool
     */
    public function __unset($property)
    {
        $this->__set($property, null);
    }

    /**
     * Returns a new instance of this class with the data specified by $array.
     *
     * $array contains all the data members of this class in the form:
     * array('member_name'=>value).
     *
     * __set_state makes this class exportable with var_export.
     * var_export() generates code, that calls this method when it
     * is parsed with PHP.
     *
     * @ignore This method is for internal use
     *
     * @param mixed[] $array
     *
     * @return ValueObject
     */
    public static function __set_state(array $array)
    {
        return new static($array);
    }
}
