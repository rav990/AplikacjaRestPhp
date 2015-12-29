<?php

namespace Rooms\Model;

use Zend\Db\TableGateway\TableGateway;

class EquipmentTable
{
    protected $tableGateway;

    public function __construct(TableGateway $tableGateway)
    {
        $this->tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->tableGateway->select();
        return $resultSet;
    }

    public function getEquipment($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveEquipment(Equipment $equipment)
    {
        $data = array(
            'name' => $eqipment->name,
            'quantity'  => $eqipment->quantity,
            'destiny'  => $eqipment->destiny,
            'damaged'  => $eqipment->damaged,
            'hire'  => $eqipment->hire,
            'adddate' => $eqipment->adddate
        );

        $id = (int)$equipment->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getEquipment($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
        return $id; // Add Return
    }

    public function deleteEquipment($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}
