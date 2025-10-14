<?php

namespace App\Service;

use App\Repository\PostRepository;

class PostService
{

    public function __construct(private PostRepository $postRepository)
    {

    }

    public function createPost($post){
        $lastPosition = $this->postRepository->getLastPostPosition() ;
        $newPosition = $lastPosition + 1;
        $post->setPosition($newPosition);
        $this->postRepository->save($post);

    }

    public function getPosts(): array
    {
        $posts = $this->postRepository->getPosts();
        return $posts;
    }


}
