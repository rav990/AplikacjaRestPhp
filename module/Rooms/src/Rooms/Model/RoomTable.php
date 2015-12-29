<?php

namespace Rooms\Model;

use Zend\Db\TableGateway\TableGateway;

class RoomTable
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

    public function getRoom($id)
    {
        $id  = (int) $id;
        $rowset = $this->tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function saveRoom(Room $room)
    {
        $data = array(
            'number' => $room->number,
            'name'  => $room->name,
            'building'  => $room->building,
            'attendant'  => $room->attendant,
            'posts'  => $room->posts,
        );

        $id = (int)$room->id;
        if ($id == 0) {
            $this->tableGateway->insert($data);
            $id = $this->tableGateway->getLastInsertValue();
        } else {
            if ($this->getRoom($id)) {
                $this->tableGateway->update($data, array('id' => $id));
            } else {
                throw new \Exception('Form id does not exist');
            }
        }
        return $id; // Add Return
    }

    public function deleteRoom($id)
    {
        $this->tableGateway->delete(array('id' => $id));
    }
}