<?php

namespace Zikula\Bundle\DynamicFormPropertyBundle\Entity;

trait EntityArrayAccessTrait
{
    public function offsetExists($key): bool
    {
        try {
            $this->getGetterForProperty($key);

            return true;
        } catch (\RuntimeException $exception) {
            return false;
        }
    }

    /**
     * @param mixed $key
     * @return mixed
     */
    #[\ReturnTypeWillChange]
    public function offsetGet($key)
    {
        $method = $this->getGetterForProperty($key);

        if (empty($method)) {
            return null;
        }

        return $this->{$method}();
    }

    public function offsetSet($key, $value): void
    {
        $method = $this->getSetterForProperty($key);
        $this->{$method}($value);
    }

    public function offsetUnset($key): void
    {
        $this->offsetSet($key, null);
    }

    /**
     * Returns the accessor's method name for retrieving a certain property.
     */
    private function getGetterForProperty(string $name): string
    {
        $getMethod = 'get' . ucfirst($name);
        if (method_exists($this, $getMethod)) {
            return $getMethod;
        }

        $isMethod = 'is' . ucfirst($name);
        if (method_exists($this, $isMethod)) {
            return $isMethod;
        }

        // see #1863
        return '';
    }

    private function getSetterForProperty(string $name): string
    {
        $setMethod = 'set' . ucfirst($name);
        if (method_exists($this, $setMethod)) {
            return $setMethod;
        }

        $class = static::class;
        throw new \RuntimeException("Entity \"${class}\" does not have a setter for property \"${name}\". Please add ${setMethod}().");
    }
}