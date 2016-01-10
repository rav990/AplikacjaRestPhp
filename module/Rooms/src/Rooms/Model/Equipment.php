<?php
namespace Rooms\Model;

use Zend\Form\Annotation;

/**
 * Equipment entity: this class represents data of a single equipment.
 * @Annotation\Name("equipment")
 * @Annotation\Hydrator("Zend\Stdlib\Hydrator\ObjectProperty")
 */
class Equipment
{
    /**
     * Equipment ID
     * @var int
     * @Annotation\Exclude()
     */
    public $id;
    
    /**
     * Room ID to which it belongs.
     * @var int
     */
    public $cid;
    
    /**
     * Name
     * @var string
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":2, "max":20}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z]+$/"}})
     * @Annotation\Options({"label":"Nazwa:"})
     */
    public $name;
    
    /**
     * Quantity
     * @var string
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":1, "max":20}})
     * @Annotation\Options({"label":"Ilość:"})
     */
    public $quantity;
    
    /**
     * Destiny
     * @var string
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":2, "max":20}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z]+$/"}})
     * @Annotation\Options({"label":"Przeznaczenie:"})
     */
    public $destiny;
    
    /**
     * Damaged
     * @var string
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":2, "max":20}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z]+$/"}})
     * @Annotation\Options({"label":"Czy uszkodzony?:"})
     */
    public $damaged;
    
    /**
     * Hire
     * @var string
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Filter({"name":"StringTrim"})
     * @Annotation\Validator({"name":"StringLength", "options":{"min":2, "max":20}})
     * @Annotation\Validator({"name":"Regex", "options":{"pattern":"/^[a-zA-Z]+$/"}})
     * @Annotation\Options({"label":"Czy można wypożyczyć? :"})
     */
    public $hire;
    
    /**
     * Date of addt.
     * @var string format Y-m-d
     * @Annotation\Attributes({"type":"text"})
     * @Annotation\Options({"label":"Data dodania:"})
     * @Annotation\Validator({"name":"Rooms\Model\EquipmentStartdateValidator"})
     */
    
    public $adddate;
    
    // end of equpment database fields.
    
    /**
     * This field is form the form builder.
     * @Annotation\Type("Zend\Form\Element\Submit")
     * @Annotation\Attributes({"value":"Dodaj"})
     */
    public $submit;
    
    
    public function exchangeArray($data)
    {
        $this->id     = (isset($data['id']))     ? $data['id']     : null;
        $this->cid     = (isset($data['cid']))     ? $data['cid']     : null;
        $this->name  = (isset($data['name']))  ? $data['name']  : null;
        $this->quantity = (isset($data['quantity'])) ? $data['quantity'] : null;
        $this->destiny  = (isset($data['destiny']))  ? $data['destiny']  : null;
        $this->damaged  = (isset($data['damaged']))  ? $data['damaged']  : null;
        $this->hire  = (isset($data['hire']))  ? $data['hire']  : null;
        $this->adddate  = (isset($data['adddate']))  ? $data['adddate']  : null;
    }
    /**
     * Returns all valid properties of Equipment.
     * @return array
     */
    public static function propertyNames()
    {
        return array('id', 'cid', 'name','quantity','destiny','damaged','hire','adddate');
    }
    
    /**
     * Populates the Equipment object with data given.
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
    
    /*public function init($id)
    {
        var_dump("AAA");
        $table = new TableGateway('equipment',
            $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter'));
        $rowset = $table->select(array('id' => $id));
        
        if ( $rowset->count() < 1 )
            throw new \Exception("Equipment with id $id not found during initiation.");
        
        $this->addData($rowset->current());
        $this->set_init();
        
        return $this;
    }*/
}
