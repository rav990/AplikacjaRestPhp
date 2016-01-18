<?php

return array(
    'controllers' => array(
        'invokables' => array(
            'Rooms\Controller\Rooms' => 'Rooms\Controller\RoomsController',
            'Rooms\Controller\Equipments' => 'Rooms\Controller\EquipmentsController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'add_equipment' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/add_equipment/[:room_id]',
                    'constraints' => array(
                        'room_id' => '\d+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rooms\Controller\Equipments',
                        'action' => 'add'
                    )
                ),
            ),
            'delete_equipment' => array(
                'type' => 'literal',
                'options' => array(
                    'route' => '/delete_equipment',
                    'defaults' => array(
                        'controller' => 'Rooms\Controller\Equipments',
                        'action' => 'delete'
                    )
                ),
            ),
            'del_room' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/del_room/[:room_id]',
                    'constraints' => array(
                        'room_id' => '\d+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rooms\Controller\Rooms',
                        'action' => 'delete'
                    )
                ),
            ),
            'rooms_segment' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/rooms[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z]+',
                        'id' => '\d+',
                    ),
                    'defaults' => array(
                        'controller' => 'Rooms\Controller\Rooms',
                        'action' => 'list',
                    ),
                ),
            ),
            'equipment_segment' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/equipments[/][:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Rooms\Controller\Equipments',
                        'action' => 'notAvailable',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_map' => array(
            'equipment_partial' => __DIR__ . '/../view/rooms/equipments/equipmentPartial.phtml',
            'rooms_sub_nav' => __DIR__ . '/../view/rooms/rooms_sub_nav.phtml',
        ),
        'template_path_stack' => array(
            'rooms' => __DIR__ . '/../view',
        ),
        'strategies' => array(
           'ViewJsonStrategy',
        ),
    ),
);
