<?php
namespace Rooms\Model;

use Zend\InputFilter\InputFilter;
use Zend\InputFilter\InputFilterAwareInterface;
use Zend\InputFilter\InputFilterInterface;

use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;

use Zend\Db\TableGateway\TableGateway;
Use Zend\Db\Sql;

/**
 * Room entity: this class represents data of a single room.
 */
class Room implements InputFilterAwareInterface, ServiceLocatorAwareInterface
{
    /**
     * @var int
     */
    public $id;
    
    /**
     * Number
     * @var string 
     */
    public $number;
    
    /**
     * Name
     * @var string 
     */
    public $name;
    
    /**
     * Building
     * @var string
     */
    public $building;
    
    /**
     * Attendant
     * @var string
     */
    public $attendant;
    /**
     * Returns all valid properties of Room.
     * @return array
     */
    
    public $posts;
    /**
     * Returns all valid properties of Room.
     * @return array
     */
    
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->number  = (isset($data['number']))  ? $data['number']  : null;
        $this->name = (isset($data['name'])) ? $data['name'] : null;
        $this->building  = (isset($data['building']))  ? $data['building']  : null;
        $this->attendant  = (isset($data['attendant']))  ? $data['attendant']  : null;
        $this->posts  = (isset($data['posts']))  ? $data['posts']  : null;
    }
    
    public static function propertyNames()
    {
        return array('id','number','name','building','attendant','posts');
    }
    
    /**
     * Populates the Room object with data given.
     * @param mixed $data an indexed key-value collection.
     * @return $this
     */
    public function addData($data)
    {
        foreach ( $this::propertyNames() as $field )
        {
            $this->$field = !empty($data[$field]) ? $data[$field] : null;
        }
        return $this;
    }
   
    public function getArrayCopy()
    {
        return get_object_vars($this);
    }
    /**
     * Initialize Room object by id.
     * This initializes the direct properties of the rooms table,
     * without any foreign relations like the num. of equipments. The latter
     * one is loaded on request.
     * @param int $id
     * @return $this
     * @throws Exception when no room found in DB
     */
    public function init($id)
    {
        $table = new TableGateway('room',
            $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
        $rowset = $table->select(array('id' => $id));
        
        if ( $rowset->count() < 1 )
            throw new \Exception("Room with id $id not found during initiation.");
        
        $this->addData($rowset->current());
        $this->set_init();
        
        return $this;
    }
    
    /**
     * Returns the number of equipments belonging to this room.
     * @return int
     * @throws Exception if room not initialized
     */
    public function getEquipmentCount()
    {
        if ( ! $this->is_init() )
            throw new \Exception('Room is not initialized.');

        if ( ! $this->equipmentCount )
        {
            $equipmentTable = new TableGateway('equipment',
                $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
            
            $select = new Sql\Select('equipment');
            $select->columns(
                    array('n' => new Sql\Expression('COUNT(*)')
            ));
            $where = new Sql\Where();
            $where->equalTo('cid', $this->id);
            $select->where($where);
            
            $row = $equipmentTable->selectWith($select)->current();
            $this->equipmentCount = $row->n;
        }
        return $this->equipmentCount;
    }
    
    /**
     * Gets room equipments
     * @return Rooms\Model\Equipments
     * @throws Exception if room not initialized
     */
    public function getEquipments()
    {
        if ( ! $this->is_init() )
            throw new \Exception('Room is not initialized.');
        
        if ( ! $this->equipments )
        {
            $this->equipments = new Equipments();
            $this->equipments->init($this);
        }
        return $this->equipments;
    }
    
    /**
     * Not used - out input filter is lazily loaded in call to getInputFilter.
     * @param \Zend\InputFilter\InputFilterInterface $inputFilter
     * @throws \Exception
     */
    public function setInputFilter(InputFilterInterface $inputFilter)
    {
        throw new \Exception('Not used');
    }

    /**
     * @return Zend\InputFilter\InputFilter
     */
    public function getInputFilter()
    {
        if ( ! $this->inputFilter )
        {
            $this->inputFilter = new InputFilter();
            $this->inputFilter->add(array(
                'name'     => 'number',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 50,
                        ),
                    ),
                ),
            ));
             $this->inputFilter->add(array(
                'name'     => 'name',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 3,
                            'max' => 50,
                        ),
                    ),
                    array(
                        'name' => 'Rooms\Model\RoomNameValidator',
                        'options' => array(
                            'serviceLocator' => $this->getServiceLocator(),
                        )
                    )
                ),
            ));
              $this->inputFilter->add(array(
                'name'     => 'building',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 10,
                        ),
                    ),
                ),
            ));
            $this->inputFilter->add(array(
                'name'     => 'attendant',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 1,
                            'max' => 100,
                        ),
                    ),
                ),
            ));
            $this->inputFilter->add(array(
                'name'     => 'posts',
                'required' => true,
                'filters'  => array(
                    array('name' => 'StripTags'),
                    array('name' => 'StringTrim'),
                ),
                'validators' => array(
                    array(
                        'name'    => 'StringLength',
                        'options' => array(
                            'min' => 0, // format: 869821191
                            'max' => 9,
                        ),
                    ),

                ),
            ));
        }
        return $this->inputFilter;
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
    
    /**
     * @var Equipments Equipments object.
     */
    private $equipments;
    
    /**
     * @var int 
     */
    private $equipmentCount;
    
    /**
     * @var Zend\InputFilter\InputFilter
     */
    private $inputFilter;
    
    /**
     * @var bool 
     */
    private $initialized = false;
}
