<?php
namespace Rooms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Form\Annotation\AnnotationBuilder;
use Rooms\Model\Equipment;
use Rooms\Controller\RoomsController;
use Zend\View\Model\JsonModel;

class EquipmentsController extends AbstractActionController
{
    /**
     * Add a new equipment.
     * Returns a 404 response if no room_id is given in route.
     */
    public function addAction()
    {
        $room_id = $this->params()->fromRoute('room_id');
        if ( ! $room_id )
            return $this->notAvailableAction();
        
        $room = $this->getServiceLocator()->get('room')
                ->init($room_id);
        
        $equipment = new Equipment();
        
        $builder = new AnnotationBuilder();
        $form = $builder->createForm($equipment);
        
        if ( $this->getRequest()->isPost() )
        {
            $form->setData($this->getRequest()->getPost());
            if ( $form->isValid() )
            {
                $equipment->addData($form->getData());
                $this->getServiceLocator()->get('equipments')
                ->init($room)->addNew($equipment);
                
                $this->redirect()->toRoute('rooms_segment',
                        array('action' => 'view', 'id' => $room->id));
            }
        }
        
        // Use RoomsController's subnav because we're still pretty
        // much inside of a room.
        $this->layout()->addChild(
                RoomsController::subnav()->setVariable('room', $room),
                'subnav');
        return array('form' => $form, 'room' => $room);
    }
    
    /**
     * Deletes an equipment. 
     * @return JSON JSON with keys status=ok|error and optional key message.
     */
    public function deleteAction()
    {
        try
        {
            $id = $this->params()->fromPost('id');
            if ( ! $id )
                throw new \Exception('No equipment id.');
            
            $this->getServiceLocator()->get('equipments')
                    ->delete($id);

            return $this->myJsonModel(array('status'=>'ok'));
        }
        catch ( \Exception $e )
        {
            return $this->myJsonModel(array('status'=>'error',
                'message' => $e->getMessage()));
        }
    }
    
    /**
     * For JS AJAX validation - validates a property of Equipment object.
     * @return JSON status=ok|error,message={string}
     */
    public function validateJSONAction()
    {
        try
        {
            $propName = $this->params()->fromPost('propName');
            if ( ! in_array($propName, Equipment::propertyNames()) )
                throw new \Exception('Wrong property provided.');
                    
            $value = $this->params()->fromPost('value');
            if ( ! $propName || ! $value )
                throw new \Exception('No property and/or value given.');
            
            $equipment = new Equipment();
            $builder = new AnnotationBuilder();
            
            $form = $builder->createForm($equipment);
            $form->setData(array($propName => $value))->isValid();
            
            $elements = $form->getElements();
            $element = $elements[$propName];
            
            if ( count($element->getMessages()) )
            {
                return $this->myJsonModel(array('status'=>'error',
                    'message' => $element->getMessages()));
            }
            else
            {
                return $this->myJsonModel(array('status'=>'ok',
                    'message' => ''));
            }
        }
        catch ( \Exception $e )
        {
            // @TODO: set status to syserror so JS doesn't confuse with 
            // validation error
            return $this->myJsonModel(array('status'=>'error',
                'message' => $e->getMessage()));
        }
    }
    
    /**
     * Returns 404 response.
     **/
    public function notAvailableAction()
    {
        return $this->notFoundAction();
    }
   
    protected function myJsonModel($obj)
    {
        $view = new JsonModel($obj);
        $view->setTerminal(true);
        return $view;
    }
}