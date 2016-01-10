<?php
namespace Rooms\Model;

use Rooms\Model\Room;
use Rooms\Model\Equipment;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ObjectProperty as Hydrator;

Use Zend\Db\Sql\Sql;

use Rooms\Code\CheckInitialized;

/**
 * Equipments collection. This class contains all the functionality needed
 * to work with equipment lists.
 */
class Equipments implements ServiceLocatorAwareInterface, CheckInitialized
{
    /**
     * Max num. of records to return in fetch().
     * @var int 
     */
    public $limit = 10;
    
    /**
     * Set offset (starting record) for fetch().
     * @var int
     */
    public $offset = 0;
    
    /**
     * This app only cares about equipments as belonging to a certain room,
     * not for example, all of them globally, so Equipments instances are tied
     * to one certain Room.
     * @param \Rooms\Model\Room $room
     * @return $this
     */
    public function init(Room $room)
    {
        $this->room = $room;
        $this->setServiceLocator($room->getServiceLocator());
        $this->set_init();
        return $this;
    }
    
    /**
     * Fetches equipments of the room.
     * @see init()
     * @return \Zend\Db\ResultSet\HydratingResultSet of Equipment objects
     */
    public function fetch()
    {
        if ( ! $this->is_init() )
            throw new \Exception("Equipments collection uninitiated.");
        
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $sql = new Sql($adapter);
        
        $select = $sql->select('equipment')->where(array('cid' => $this->room->id))
                ->offset($this->offset)->limit($this->limit);
        $statement = $sql->prepareStatementForSqlObject($select);
        $res = $statement->execute();
        
        $hydratedRes = new HydratingResultSet(new Hydrator, new Equipment);
        if ($res instanceof ResultInterface && $res->isQueryResult()) {
            $hydratedRes->initialize($res);
        }
        return $hydratedRes;
    }
    
    /**
     * Adds a new record of the Equipment object in the database.
     * @param Equipment $equipment - a populated object to add.
     */
    public function addNew(Equipment $equipment)
    {
        if ( ! $this->is_init() )
            throw new \Exception("Equipments collection uninitiated.");
                
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $sql = new Sql($adapter);
        $insert = $sql->insert('equipment')
            ->columns(array('cid','name', 'quantity', 'destiny', 'damaged', 'hire', 'adddate'))
            ->values(array(
                'cid' => $equipment->cid,
                'name' => $equipment->name,
                'quantity' => $equipment->quantity, 
                'destiny' => $equipment->destiny,
                'damaged' => $equipment->damaged,
                'hire' => $equipment->hire,
                'adddate' => $equipment->adddate));
                
        $sql->prepareStatementForSqlObject($insert)->execute();
    }
    
    /**
     * Deletes a record.
     * @param $id
     * @throws Exception if no records are deleted
     * @return $this
     */
    public function delete($id)
    {
        /*$adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $sql = new Sql($adapter);
        $delete = $sql->delete('equipment')->where(array('id'=>$id));
        $res = $sql->prepareStatementForSqlObject($delete)->execute();
        if ( $res->getAffectedRows() < 1 )
            throw new \Exeption('Equipment not deleted: DB affected rows count=0.');*/
    }
    
    /**
     * Set service locator
     * @param \Zend\ServiceManager\ServiceLocatorInterface $serviceLocator
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->services = $serviceLocator;
    }

    /**
     * Get service locator
     * @return ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->services;
    }
    
    /**
     * Set object as initialized or not.
     * @param bool $bool
     * @return $this
     */
    public function set_init($bool = true)
    {
        $this->initialized = (bool) $bool;
        return $this;
    }
    
    /**
     * Is object initiated?
     * @return bool 
     */
    public function is_init()
    {
        return $this->initialized;
    }
    
    /**
     * Room.
     * @var Rooms\Model\Room 
     */
    private $room;
    
    
    /**
     * @var bool 
     */
    private $initialized = false;
}
