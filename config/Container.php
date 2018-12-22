<?php

namespace Config;

use ReflectionClass;
use Exception;

/**
 * Class Container
 */
class Container
{
    protected $instances = [];

    /**
     * Set function
     *
     * @param String $abstract Class name
     * @param String $concrete Class concrete
     *
     * @return void
     */
    public function set($abstract, $concrete = null)
    {
        if ($concrete === null) {
            $concrete = $abstract;
        }
        $this->instances[$abstract] = $concrete;
    }

    /**
     * Get function
     *
     * @param String $abstract   Class name
     * @param array  $parameters Parameters
     *
     * @return mixed|null|object
     * @throws Exception
     */
    public function get($abstract, $parameters = [])
    {
        // if we don't have it, just register it
        if (!isset($this->instances[$abstract])) {
            $this->set($abstract);
        }
        return $this->resolve($this->instances[$abstract], $parameters);
    }
    
    /**
     * Resolve single
     *
     * @param String $concrete   Class name
     * @param array  $parameters Parameters
     *
     * @return mixed|object
     * @throws Exception
     */
    public function resolve($concrete, $parameters)
    {
        if ($concrete instanceof Closure) {
            return $concrete($this, $parameters);
        }
        $reflector = new ReflectionClass($concrete);
        // check if class is instantiable
        if (!$reflector->isInstantiable()) {
            throw new Exception("Class {$concrete} is not instantiable");
        }
        // get class constructor
        $constructor = $reflector->getConstructor();
        if (is_null($constructor)) {
            // get new instance from class
            return $reflector->newInstance();
        }
        // get constructor params
        $parameters   = $constructor->getParameters();
        $dependencies = $this->getDependencies($parameters);
        // get new instance with dependencies resolved
        return $reflector->newInstanceArgs($dependencies);
    }
    
    /**
     * Get all dependencies resolved
     *
     * @param array $parameters Parameters
     *
     * @return array
     * @throws Exception
     */
    public function getDependencies($parameters)
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            // get the type hinted class
            $dependency = $parameter->getClass();
            if ($dependency === null) {
                // check if default value for a parameter is available
                if ($parameter->isDefaultValueAvailable()) {
                    // get default value of parameter
                    $dependencies[] = $parameter->getDefaultValue();
                } else {
                    throw new Exception("Can not resolve class dependency {$parameter->name}");
                }
            } else {
                // get dependency resolved
                $dependencies[] = $this->get($dependency->name);
            }
        }
        return $dependencies;
    }
}
