<?php

namespace RoomsRest\Controller;

use Zend\Mvc\Controller\AbstractRestfulController;

use Rooms\Model\Equipment;        
use Rooms\Forms\EquipmentForm;      
use Zend\View\Model\JsonModel;

class EquipmentsRestController extends AbstractRestfulController
{
    protected $equipmentTable;

    public function getList()
    {
        $cid = $this->params()->fromRoute('cid');
        //var_dump($cid);
        $results = $this->getEquipmentTable()->fetchAll($cid);
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
        $cid = $this->params()->fromRoute('cid');
        $equipment = $this->getEquipmentTable()->getEquipment($id,$cid);

        return new JsonModel(array(
            'data' => $equipment,
        ));
    }

    public function create($data)
    {
        $cid = $this->params()->fromRoute('cid');
        if (empty($data['id'])) {
            $data['id'] = 0;
        }
        $form = new EquipmentForm();
        $equipment = new Equipment($cid);
        //$form->setInputFilter($equipment->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $equipment->exchangeArray($form->getData());
            $id = $this->getEquipmentTable()->saveEquipment($equipment,$cid);
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
            $this->equipmentTable = $sm->get('Rooms\Model\EquipmentTable');
        }
        return $this->equipmentTable;
    }
}
