<?php
/**
 * Example application using Varivax
 */

require_once 'Varivax.php';

$container = new Varivax();

/**
 * Wooden doors, Steel Doors, Glass Doors, you name it Door makes it!
 */
class Door
{
    public $stuff;
    
    public function __construct($stuff)
    {
        $this->stuff = $stuff;
    }
}

/**
 * The thing that keeps the rain out.
 */
class Roof
{
    public $stuff;
    
    public function __construct($stuff)
    {
        $this->stuff = $stuff;
    }
}

/**
 * Mi casa es su casa.
 */
class House
{
    public $roof;
    public $doors;
    
    public function __construct($roof, $doors)
    {
        $this->roof = $roof;
        $this->doors = $doors;
    }
}

$container
    ->add('Door')->serviceAs('frontDoor')->with(array('locks'))
    ->add('Door')->serviceAs('backDoor')->with(array(
    	'locks',
        'glass',
        'deadbolt',
    ))
    ->add('Roof')->serviceAs('solarRoof')->with(array('solarPanel'))
    ->add('House')->serviceAs('myHouse')->with(array
    (
        $container->get('solarRoof'),
        array
        (
            $container->get('frontDoor'),
            $container->get('backDoor'),
        )    	
    ));

$container
    ->add('House')->serviceAs('neighborsHouse')->with(array(
    	$container->get('frontDoor'),
        $container->get('solarRoof'),
    ));
    

print_r($container);