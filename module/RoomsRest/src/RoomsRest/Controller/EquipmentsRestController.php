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
        $data['cid'] = $cid;
        $form = new EquipmentForm();
        $equipment = new Equipment();
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
        $cid = $this->params()->fromRoute('cid');
        $data['id'] = $id;
        $equipment = $this->getEquipmentTable()->getEquipment($id, $cid);
        $form  = new EquipmentForm();
        $form->setHydrator(new \Zend\Stdlib\Hydrator\ObjectProperty());
        $form->bind($equipment);        
        //$form->setInputFilter($equipment->getInputFilter());
        $form->setData($data);
        if ($form->isValid()) {
            $id = $this->getEquipmentTable()->saveEquipment($form->getData(),$cid);
        }

        return $this->get($id);
    }

    public function delete($id)
    {
        $cid = $this->params()->fromRoute('cid');
        $this->getEquipmentTable()->deleteEquipment($id,$cid);

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
