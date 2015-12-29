<?php
return array(
    'controllers' => array(
        'invokables' => array(
            'RoomsRest\Controller\RoomsRest' => 'RoomsRest\Controller\RoomsRestController',
        ),
    ),

    // The following section is new` and should be added to your file
    'router' => array(
        'routes' => array(
            'rooms-rest' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'    => '/rooms-rest[/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'RoomsRest\Controller\RoomsRest',
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