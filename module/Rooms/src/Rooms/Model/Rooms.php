<?php
namespace Rooms\Model;

use Zend\Db\Sql\Sql;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

use Zend\Db\Adapter\Driver\ResultInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Stdlib\Hydrator\ObjectProperty as hydrator;

use Rooms\Model\Room;

/**
 * This class is used when working with room collections (e.g. retrieval)
 * and for CRUD ops (adding).
 */
class Rooms implements ServiceLocatorAwareInterface
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
     * Fetches rooms.
     * @return Zend\Db\ResultSet\HydratingResultSet collection of Room obj's
     */
    public function fetch()
    {
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        
        $sql = new Sql($adapter);
        
        $select = $sql->select('room')->offset($this->offset)->limit($this->limit);
        $statement = $sql->prepareStatementForSqlObject($select);
        $res = $statement->execute();
        
        $resultSet = new HydratingResultSet(new hydrator, new Room);
        if ( $res instanceof ResultInterface && $res->isQueryResult() )
        {
            $resultSet->initialize($res);
        }
        
        return $resultSet;
    }
    
    /**
     * Adds a new record of the Room object in the database.
     * @param Room $room - a populated object to add.
     */
    public function addNew(Room $room)
    {
        $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
              
        $sql = new Sql($adapter);
        $insert = $sql->insert('room')
            ->columns(array('number', 'name', 'building','attendant','posts'))
            ->values(array(
                'number' => $room->number,
                'name' => $room->name, 
                'building' => $room->building,
                'attendant' => $room->attendant,              
                'posts' => $room->posts));
                
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
            $adapter = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
            $sql = new Sql($adapter);
            $delete = $sql->delete('room')->where(array('id'=>$id));
            $res = $sql->prepareStatementForSqlObject($delete)->execute();
            if ( $res->getAffectedRows() < 1 )
                throw new \Exeption('Room not deleted: DB affected rows count=0.');
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
     * @return ServiceLocator
     */
    public function getServiceLocator()
    {
        return $this->services;
    }
    
    /**
     * @var ServiceLocator
     */
    private $services;
}

