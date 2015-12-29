<?php

namespace Equipments\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Equipments\Model\Equipment;        
use Rooms\Forms\RoomForm;      
use Zend\View\Model\JsonModel;

class EquipmentsRestController extends AbstractRestfulController
{
    protected $equipmentTable;

    public function getList()
    {
        $results = $this->getEquipmentTable()->fetchAll();
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
        $equipment = $this->getEquipmentTable()->getEquipment($id);

        return new JsonModel(array(
            'data' => $equipment,
        ));
    }

    public function create($data)
    {
        if (empty($data['id'])) {
            $data['id'] = 0;
        }
        $form = new EquipmentForm();
        $equipment = new Equipment();
        $form->setInputFilter($equipment->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $equipment->exchangeArray($form->getData());
            $id = $this->getEquipmentTable()->saveEquipment($equipment);
        }
        
        return $this->get($id);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $equipment = $this->getEquipmentTable()->getEquipment($id);
        $form  = new EquipmentForm();
        $form->bind($equipment);
        $form->setInputFilter($equipment->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $id = $this->getEquipmentTable()->saveEquipment($form->getData());
        }

        return $this->get($id);
    }

    public function delete($id)
    {
        $this->getEquipmentTable()->deleteEquipment($id);

        return new JsonModel(array(
            'data' => 'deleted',
        ));
    }

    public function getEquipmentTable()
    {
        if (!$this->equipmentTable) {
            $sm = $this->getServiceLocator();
            $this->equipmentTable = $sm->get('Equipments\Model\EquipmentTable');
        }
        return $this->equipmentTable;
    }
}
