<?php
namespace Rooms;

use Rooms\Model\Room;
use Rooms\Model\RoomTable;
use Rooms\Model\Equipment;
use Rooms\Model\EquipmentTable;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;

class Module
{
    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function getServiceConfig()
    {
        return array(
            'invokables' => array(
                'get_rooms' => 'Rooms\Model\Rooms',
                'room' => 'Rooms\Model\Room',
                'equipments' => 'Rooms\Model\Equipments',
             ),
            
            'factories' => array(
                'Rooms\Model\RoomTable' =>  function($sm) {
                    $tableGateway = $sm->get('RoomTableGateway');
                    $table = new RoomTable($tableGateway);
                    return $table;
                },
                'RoomTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();        
                    $resultSetPrototype->setArrayObjectPrototype(new Room());
                    return new TableGateway('room', $dbAdapter, null, $resultSetPrototype);
                },
                'Rooms\Model\EquipmentTable' =>  function($sm) {
                    $tableGateway = $sm->get('EquipmentTableGateway');
                    $table = new EquipmentTable($tableGateway);
                    return $table;
                },
                'EquipmentTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Equipment());
                     
                    return new TableGateway('equipment', $dbAdapter, null, $resultSetPrototype);
                },
            ),
         );
     }
     
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }
}
