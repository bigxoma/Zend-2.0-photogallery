<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Alex
 * Date: 25.07.13
 * Time: 15:47
 * To change this template use File | Settings | File Templates.
 */
return array(
    'controllers' => array(
        'invokables' => array(
            'Gallery\Controller\Gallery' => 'Gallery\Controller\GalleryController',
        ),
    ),
    'router' => array(
        'routes' => array(
            'gallery' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/gallery[/:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults' => array(
                        'controller' => 'Gallery\Controller\Gallery',
                        'action'     => 'index',
                    ),
                ),
            ),
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            'gallery' => __DIR__ . '/../view',
        ),
    ),
);