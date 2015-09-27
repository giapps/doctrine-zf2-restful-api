<?php
namespace PostApi\Controller;

use BaseApi\Controller\AbstractRestfulJsonController;
use Zend\Serializer\Adapter\Json;
use Zend\View\Model\JsonModel;
use PostApi\Entity;
use PostApi\Service\ServiceBaseAbstract;
use PostApi\Entity\PostServiceInterface;
use PostApi\Service\PostService;
use PostApi\Entity\PostRepository;
use Exception;


class PostController extends AbstractRestfulJsonController
{

    private  $_entityManager;

    public function getList()
    {
        $this->_entityManager = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $_posts = $this->_entityManager->getRepository('PostApi\Entity\Post')->findAll();

        foreach($_posts as $post){
            $data[] = array(
                'id' => $post->getId(),
                'post_title' => $post->getPostTitle(),
                'post_content' => $post->getPostContent(),
            );
        }

        return new JsonModel( array('data' => $data) );
    }

    public function get($id)
    {
        // Action used for GET requests with resource Id
        $this->_entityManager = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $_post = $this->_entityManager->getRepository('PostApi\Entity\Post')->find($id);

        if(isset($_post)) {
            return new JsonModel(array("data" => array('id'=>$_post->getId(),'post_title'=>$_post->getPostTitle(), 'post_content' => $_post->getPostContent())));
        } else {
            return new JsonModel(array('data'=> array()));
        }
    }

    public function create($data)
    {
        if(isset($data['post_title']) && isset($data['post_content'])) {
            // Action used for POST requests
            $this->_entityManager = $this
                ->getServiceLocator()
                ->get('Doctrine\ORM\EntityManager');
            $_post = new \PostApi\Entity\Post();
            $_post->setPostTitle($data['post_title']);
            $_post->setPostContent($data['post_content']);
            $this->_entityManager->persist($_post);
            $this->_entityManager->flush();
            return new JsonModel(array('data' => array('id'=> $_post->getId(), 'post_title' => $_post->getPostTitle(), 'post_content' => $_post->getPostContent())));
        } else {
            return new JsonModel(array('data' => array()));
        }
    }

    public function update($id, $data)
    {
        // Action used for PUT requests
        if(isset($data['post_title']) && isset($data['post_content'])) {
            $this->_entityManager = $this
                ->getServiceLocator()
                ->get('Doctrine\ORM\EntityManager');
            $_post = $this->_entityManager->getRepository('PostApi\Entity\Post')->find($id);
            if(isset($_post)){
                $_post->setPostTitle($data['post_title']);
                $_post->setPostContent($data['post_content']);
                $this->_entityManager->merge($_post);
                $this->_entityManager->flush();
                return new JsonModel(array('data' => array('id'=> $_post->getId(), 'post_title' => $_post->getPostTitle(), 'post_content' => $_post->getPostContent())));
            }
        } else {
            return new JsonModel(array('data' => array()));
        }
    }

    public function delete($id)
    {
        // Action used for DELETE requests
        // Action used for GET requests with resource Id
        $this->_entityManager = $this
            ->getServiceLocator()
            ->get('Doctrine\ORM\EntityManager');

        $_post = $this->_entityManager->getRepository('PostApi\Entity\Post')->find($id);

        if(isset($_post)) {
            $this->_entityManager->remove($_post);
            $this->_entityManager->flush();
            return new JsonModel(array('data' => "post id $id deleted"));
        } else {
            throw new Exception("post does not exist");
            //return new JsonModel(array('data'=> array()));
        }
    }


}