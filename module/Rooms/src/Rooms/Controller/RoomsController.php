<?php
namespace Rooms\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Rooms\Forms\RoomForm;
use Zend\Mvc\InjectApplicationEventInterface;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Rooms\Model\Room;

class RoomsController extends AbstractActionController
implements InjectApplicationEventInterface
{
    /* List all rooms. This is the default action. */
    public function listAction()
    {
        $rooms = $this->getServiceLocator()->get('get_rooms');
        
        $rooms->limit = 100;
        $res = $rooms->fetch();
        
        $this->layout()->addChild($this->subnav(), 'subnav');
        return new ViewModel(array('rooms' => $res,
            'limit' => $rooms->limit));
    }
    
    /* Add a new room. */
    public function addAction()
    {
        $form = new RoomForm();
        
        if ( $this->getRequest()->isPost() )
        {
            $room = $this->getServiceLocator()->get('room');
            $form->setInputFilter($room->getInputFilter());
            
            $form->setData($this->getRequest()->getPost());
            if ( $form->isValid() )
            {
                $room->addData($form->getData());
                $rooms = $this->getServiceLocator()->get('get_rooms');
                $rooms->addNew($room);
                
                $this->flashMessenger()->addMessage('Room created.');
                $this->redirect()->toRoute('rooms_segment', array('action' => 'list'));
            }
        }

        $this->layout()->addChild($this->subnav(), 'subnav');
        return new ViewModel(array('form' => $form));
    }
    
    /**
     * View room.
     */
    public function viewAction()
    {
        $id = $this->params()->fromRoute('id', null);
        if ( ! $id ) {
            throw new \Exception('No room id given.');
        }
        $room = $this->getServiceLocator()->get('room')->init($id);
        $equipments = $room->getEquipments()->fetch();
        
        $this->layout()->addChild(
                $this->subnav()->setVariable('room', $room),
                'subnav'
        );
        return new ViewModel(array('room' => $room,
            'equipments' => $equipments));
    }
    
    /**
     * For JS AJAX validation - validates a property of Room object.
     * @return JSON status=ok|error,message={string}
     */
    public function validateJSONAction()
    {
        try
        {
            $propName = $this->params()->fromPost('propName');
            if ( ! in_array($propName, Room::propertyNames()) )
                    throw new \Exception('Wrong property provided.');
                    
            $value = $this->params()->fromPost('value');
            if ( ! $propName || ! $value )
                throw new \Exception('No property and/or value given.');
         
            $form = new RoomForm();
            $form->setInputFilter(
                    $this->getServiceLocator()->get('room')->getInputFilter());
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
     * Info page
     */
    public function infoAction()
    {
        return array();
    }
    
    /**
     * Returns an action specific navigation ViewModel
     * @param array $assocArray
     * @return ViewModel
     */
    public static function subnav()
    {
        $subnav = new ViewModel();
        return $subnav->setTemplate('rooms_sub_nav');
    }
    
    protected function myJsonModel($obj)
    {
        $view = new JsonModel($obj);
        $view->setTerminal(true);
        return $view;
    }
    
    private $subnav;
}