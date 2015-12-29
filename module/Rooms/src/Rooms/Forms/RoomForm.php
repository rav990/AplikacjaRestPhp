<?php
namespace Rooms\Forms;

use Zend\Form\Form;

/**
 * Form for Room.
 */
class RoomForm extends Form
{
    public function __construct($name = null)
    {
        parent::__construct('room');
        
        $this->add(array(
            'name' => 'number',
            'type' => 'Text',
            'options' => array(
                'label' => 'Numer: '
            ),
            /* for js validations. */
            'attributes' => array(
                'valid_required' => 'true',
                'valid_minlength' => '3',
                'valid_maxlength' => '50',
            ),
        ));
        
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Nazwa: '
            ),
            'attributes' => array(
                'valid_required' => 'true',
                'valid_minlength' => '10',
                'valid_maxlength' => '100',
            ),
        ));
        
        $this->add(array(
            'name' => 'building',
            'type' => 'Text',
            'options' => array(
                'label' => 'Budynek: '
            ),
            // for js validations
            'attributes' => array(
                'valid_required' => 'true',
                'valid_minlength' => '10',
                'valid_maxlength' => '100',
            ),
        ));
        
        $this->add(array(
            'name' => 'attendant',
            'type' => 'Text',
            'options' => array(
                'label' => 'Opiekun: '
            ),
            // for js validations
            'attributes' => array(
                'valid_required' => 'true',
                'valid_minlength' => '3',
                'valid_maxlength' => '50',
            ),
        ));
        
        $this->add(array(
            'name' => 'posts',
            'type' => 'Text',
            'options' => array(
                'label' => 'Ilość stanowisk: '
            ),
            'attributes' => array(
                'size' => '9',
                'valid_required' => 'true',
                'valid_regexp' => '\d{9}',
            ),
        ));
        
        $this->add(array(
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => array(
                'value' => 'Dodaj'
            ),
        ));
    }
}
