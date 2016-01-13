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

    public function fetchAll($cid)
    {
        $resultSet = $this->tableGateway->select(array('cid' => $cid));
        return $resultSet;
    }

    public function getEquipment($id,$cid)
    {
        $id  = (int) $id;
        $cid = (int) $cid;
        $rowset = $this->tableGateway->select(array('id' => $id,'cid'=>$cid));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveEquipment(Equipment $equipment,$cid)
    {
        $cid = (int) $cid;
        $data = array(
            'name' => $equipment->name,
            'quantity'  => $equipment->quantity,
            'destiny'  => $equipment->destiny,
            'damaged'  => $equipment->damaged,
            'hire'  => $equipment->hire,
            'adddate' => $equipment->adddate,
        );

        $id = (int)$equipment->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getEquipment($id, $cid)) {
                $this->tableGateway->update($data, array('id' => $id,'cid'=>$cid));
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
