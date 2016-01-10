<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'RoomsRest\Controller\RoomsRest' => 'RoomsRest\Controller\RoomsRestController',
            'RoomsRest\Controller\EquipmentsRest' => 'RoomsRest\Controller\EquipmentsRestController',
        ),
    ),

    // The following section is new` and should be added to your file
    'router' => array(
        'routes' => array(
            'rooms-rest' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/rooms[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'RoomsRest\Controller\RoomsRest',
                    ),
                ),
            ),
            'equipments-rest' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/rooms[/:cid]/equipments[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'RoomsRest\Controller\EquipmentsRest',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),
);