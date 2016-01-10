<?php

namespace RoomsRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

//use Rooms\Model\Room;        
//use Rooms\Forms\RoomForm;      
use Zend\View\Model\JsonModel;

class RoomsRestController extends AbstractRestfulController
{
    protected $roomTable;

    public function getList()
    {
        $results = $this->getRoomTable()->fetchAll();
        $data = array();
        foreach($results as $result) {
            $data[] = $result;
        }

        return new JsonModel(array(
            'data' => $data,
        ));
    }

    public function get($id)
    {
        $room = $this->getRoomTable()->getRoom($id);

        return new JsonModel(array(
            'data' => $room,
        ));
    }

    public function create($data)
    {
        if (empty($data['id'])) {
            $data['id'] = 0;
        }
        $form = new RoomForm();
        $room = new Room();
        $form->setInputFilter($room->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $room->exchangeArray($form->getData());
            $id = $this->getRoomTable()->saveRoom($room);
        }
        
        return $this->get($id);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        var_dump($data['id'] );
        $room = $this->getRoomTable()->getRoom($id);
        $form  = new RoomForm();
        $form->bind($room);
        $form->setInputFilter($room->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $id = $this->getRoomTable()->saveRoom($form->getData());
        }

        return $this->get($id);
    }

    public function delete($id)
    {
        $this->getRoomTable()->deleteRoom($id);

        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }

    public function getRoomTable()
    {
        if (!$this->roomTable) {
            $sm = $this->getServiceLocator();
            $this->roomTable = $sm->get('Rooms\Model\RoomTable');
        }
        return $this->roomTable;
    }
}
