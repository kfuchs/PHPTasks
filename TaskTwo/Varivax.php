<?php 

require_once 'Varicella.php';

/**
 * Varivax is an expressive, simple, dependency injection container.
 * 
 * @author kirill <kirill.fuchs@gmail.com>
 * @package ServiceContainer\Varivax
 */
class Varivax
{
    /**
     * Holds all the services in the container.
     * @var array
     */
    protected $service = array();
    
    /**
     * Helper used to put together a service.
     * @var Varicella
     */
    protected $builder;

    /**
     * Constructor.
     */
    public function _construct()
    {
        $this->service = array();
    }
    
    /**
     * Begins adding a service to the container.
     * 
     * @param string $service
     * @throws Exception if the service does not exsist.
     * @return Varivax
     */
    public function add($service)
    {
        if (!class_exists($service)) {
            throw new Exception("The service {$service} could not be created. It does not exsist");
        }
        
        $this->builder = new Varicella();
        $this->builder->setService($service);
        
        return $this;
    }
    
    /**
     * Creates the alias for the service.
     * 
     * @param string $alias a unique name for the service.
     * @throws Exception if alias is not a string || the alias already exsists in the container.
     * @return Varivax
     */
    public function serviceAs($alias)
    {
        if (!is_string($alias)){
            throw new Exception('The alias must be a string');
        }
        if ($this->hasService($alias)) {
            throw new Exception("{$alias} is already being used. You must create a unique alias");
        }
        
        $this->builder->setAlias($alias);
        
        return $this;
    }
    
    /**
     * Sets the params to pass into the service then Builds the service.
     *  
     * @param array $params An array of parameters.
     * @return Varivax
     */
    public function with(array $params)
    {
        $this->builder->setConfigs($params);
        $this->build();
        
        return $this;
    }
    
    /**
     * Gets a service from the container.
     * 
     * @param string $alias The name of the service.
     * @throws Exception if the service cannot be found in the container.
     * @return stdClass
     */
    public function get($alias)
    {
        if ($this->hasService($alias)) {
            return $this->service[$alias];
        }

        throw new Exception("{$alias} is not a valid service.");
    }
    
    /**
     * Checks if the service is in the container.
     * 
     * @param string $alias
     * @return boolean
     */
    public function hasService($alias)
    {
        if (array_key_exists($alias, $this->service)) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Creates and persists a service to the container.
     * 
     * And unsets $this->builder.
     */
    private function build()
    {        
        $service = $this->builder->getService();
        
        $reflector = new ReflectionClass($service);
        
        $this->service[$this->builder->getAlias()] = $reflector->newInstanceArgs(
            $this->builder->getConfigs()
        );
        
        unset($this->builder);
    }
}