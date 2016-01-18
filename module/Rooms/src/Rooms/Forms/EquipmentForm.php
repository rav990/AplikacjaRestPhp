<?php
namespace Rooms\Forms;

use Zend\Form\Form;

/**
 * Form for Equipment.
 */
class EquipmentForm extends Form
{
    public function __construct($name = null)
    {
                
        parent::__construct('equipment');
        $this->setAttribute('method', 'post');
        $this->add(array(
            'name' => 'name',
            'type' => 'Text',
            'options' => array(
                'label' => 'Nazwa: '
            ),

            'attributes' => array(
                'valid_required' => 'true',
                'valid_minlength' => '1',
                'valid_maxlength' => '50',
            ),
        ));
        
        $this->add(array(
            'name' => 'quantity',
            'type' => 'Text',
            'options' => array(
                'label' => 'Ilość: '
            ),
            'attributes' => array(
                'valid_required' => 'true',
                'valid_minlength' => '1',
                'valid_maxlength' => '100',
            ),
        ));
        
        $this->add(array(
            'name' => 'destiny',
            'type' => 'Text',
            'options' => array(
                'label' => 'Przeznaczenie: '
            ),
            // for js validations
            'attributes' => array(
                'valid_required' => 'true',
                'valid_minlength' => '10',
                'valid_maxlength' => '100',
            ),
        ));
        
        $this->add(array(
            'name' => 'damaged',
            'type' => 'Text',
            'options' => array(
                'label' => 'Czy zniszczony: '
            ),
            // for js validations
            'attributes' => array(
                'valid_required' => 'true',
                'valid_minlength' => '3',
                'valid_maxlength' => '50',
            ),
        ));
        
        $this->add(array(
            'name' => 'hire',
            'type' => 'Text',
            'options' => array(
                'label' => 'Możliwość wypożyczenia: '
            ),
            'attributes' => array(
                'size' => '9',
                'valid_required' => 'true',
                'valid_minlength' => '3',
                'valid_maxlength' => '50',
            ),
        ));
        
        $this->add(array(
            'name' => 'adddate',
            'type' => 'Text',
            'options' => array(
                'label' => 'Data dodania: '
            ),
            'attributes' => array(
                'valid_required' => 'false',
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
