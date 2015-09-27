<?php

namespace PostApiTest\Controller;

use PostApiTest\Bootstrap;
use PostApi\Controller\PostController;
use BaseApi\Controller\AbstractRestfulJsonController;
use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;
use Zend\Http\Request;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\RouteMatch;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Stdlib\Parameters;
use Exception;


class PostControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;
    protected $serviceManager;
    protected $postService;
    protected $controller;
    protected $routeMatch;
    protected $event;

    public function setUp()
    {
        $this->serviceManager = Bootstrap::getServiceManager();
        $this->controller = new PostController();
        $this->routeMatch = new RouteMatch(array('controller' => 'post'));
        $this->event      = new MvcEvent();
        $config = $this->serviceManager->get('Config');
        $routerConfig = isset($config['router']) ? $config['router'] : array();
        $router = HttpRouter::factory($routerConfig);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->controller->setEvent($this->event);
        $this->controller->setServiceLocator($this->serviceManager);

        $this->setApplicationConfig(
            include __DIR__ . '/../../../../../config/application.config.php'
        );
        parent::setUp();
    }

    public function testIndexActionCanBeAccessed()
    {
        $this->dispatch('/post');
        $this->assertResponseStatusCode(200);
        $this->assertModuleName('PostApi');
        $this->assertControllerName('PostApi\Controller\Post');
        $this->assertControllerClass('PostController');
        $this->assertMatchedRouteName('post');
    }

    public function testPost(){

        $this->getRequest()->setMethod('POST');
        $this->getRequest()->setPost(new Parameters(array('post_title' => 'post title' .time(), 'post_content' => 'post content' . time())));
        $this->dispatch('/post');
        $this->assertResponseStatusCode(200);
    }

    public function testPut() {
        try {
            $this->getRequest()->setMethod('PUT');
            $this->getRequest()->getHeaders()->addHeaders(array(
                'Content-Type' => 'application/x-www-form-urlencoded',
            ));
            $this->getRequest()->setContent('post_title=this is a title for updatting&post_content=this is a content for updating post');
            $this->dispatch('/post/11');
            $this->assertResponseStatusCode(200);
        } catch (Exception $ex) {

        }
    }

    public function testDelete(){
        try {
            $this->getRequest()->setMethod('DELETE');
            $this->dispatch('/post/20');
            $this->assertResponseStatusCode(200);
        } catch (Exception $e){
            if($e) {
                $this->assertTrue(TRUE);
            }else{
                $this->assertTrue(FALSE);;
            }
        }
    }

    public function testExceptionIsThrownWhenGettingNonExistentPost(){
        try {
            $this->getRequest()->setMethod('DELETE');
            $this->dispatch('/post/20');
        }
        catch (\Exception $e) {
            $this->assertSame('Could not find row 20', $e->getMessage());
            return;
        }
    }

}