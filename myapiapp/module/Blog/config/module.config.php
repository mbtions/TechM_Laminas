<?php
return [
    'controllers' => [
        'factories' => [],
    ],
    'router' => [
        'routes' => [
            'blog.rest.posts' => [
                'type' => 'Segment',
                'options' => [
                    'route' => '/posts[/:posts_id]',
                    'defaults' => [
                        'controller' => 'Blog\\V1\\Rest\\Posts\\Controller',
                    ],
                ],
            ],
        ],
    ],
    'api-tools-versioning' => [
        'uri' => [
            0 => 'blog.rest.posts',
        ],
    ],
    'api-tools-rpc' => [],
    'api-tools-content-negotiation' => [
        'controllers' => [
            'Blog\\V1\\Rest\\Posts\\Controller' => 'HalJson',
        ],
        'accept_whitelist' => [
            'Blog\\V1\\Rest\\Posts\\Controller' => [
                0 => 'application/vnd.blog.v1+json',
                1 => 'application/hal+json',
                2 => 'application/json',
            ],
        ],
        'content_type_whitelist' => [
            'Blog\\V1\\Rest\\Posts\\Controller' => [
                0 => 'application/vnd.blog.v1+json',
                1 => 'application/json',
            ],
        ],
    ],
    'service_manager' => [
        'factories' => [
            \Blog\V1\Rest\Posts\PostsResource::class => \Blog\V1\Rest\Posts\PostsResourceFactory::class,
        ],
    ],
    'api-tools-rest' => [
        'Blog\\V1\\Rest\\Posts\\Controller' => [
            'listener' => \Blog\V1\Rest\Posts\PostsResource::class,
            'route_name' => 'blog.rest.posts',
            'route_identifier_name' => 'posts_id',
            'collection_name' => 'posts',
            'entity_http_methods' => [
                0 => 'GET',
                1 => 'PATCH',
                2 => 'PUT',
                3 => 'DELETE',
            ],
            'collection_http_methods' => [
                0 => 'GET',
                1 => 'POST',
            ],
            'collection_query_whitelist' => [],
            'page_size' => 25,
            'page_size_param' => null,
            'entity_class' => \Blog\V1\Rest\Posts\PostsEntity::class,
            'collection_class' => \Blog\V1\Rest\Posts\PostsCollection::class,
            'service_name' => 'posts',
        ],
    ],
    'api-tools-hal' => [
        'metadata_map' => [
            \Blog\V1\Rest\Posts\PostsEntity::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'blog.rest.posts',
                'route_identifier_name' => 'posts_id',
                'hydrator' => \Laminas\Hydrator\ArraySerializableHydrator::class,
            ],
            \Blog\V1\Rest\Posts\PostsCollection::class => [
                'entity_identifier_name' => 'id',
                'route_name' => 'blog.rest.posts',
                'route_identifier_name' => 'posts_id',
                'is_collection' => true,
            ],
        ],
    ],
];
