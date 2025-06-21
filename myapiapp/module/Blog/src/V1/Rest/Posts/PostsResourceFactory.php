<?php
namespace Blog\V1\Rest\Posts;

class PostsResourceFactory
{
    public function __invoke($services)
    {
        return new PostsResource();
    }
}
