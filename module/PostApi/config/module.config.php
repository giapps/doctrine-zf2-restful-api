<?php

return array(
    'router' => array(
        'routes' => array(
            'post' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/post[/:id]',
                    //'route'    => '/album[/:action][/:id]',
                    'constraints' => array(
                        'id'     => '[0-9]+',
                        //'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'PostApi\Controller\Post',
                    ),
                ),
            ),
        ),
    ),

    'service_manager' => array(
        'invokables' => array(
            'PostApi\Service\BaseServiceInterface' => 'PostApi\Service\PostService',
            'PostApi\Service\ServiceBaseAbstract' => 'PostApi\Service\ServiceBaseAbstract'
        )
    ),

    'controllers' => array(
        'invokables' => array(
            'PostApi\Controller\Post' => 'PostApi\Controller\PostController',
            'PostApi\Controller\Tag' => 'PostApi\Controller\TagController',
        ),

        'factories' => array(
            'PostApi\Controller\Post' => 'PostApi\Factory\PostControllerFactory'
        )

    ),
    'view_manager' => array(
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ),

    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' =>'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/PostApi/Entity')
            ),

            'orm_default' => array(
                'drivers' => array(
                    'PostApi\Entity' => 'application_entities',
                )
            )
        )
    ),
);
