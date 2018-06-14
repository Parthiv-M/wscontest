<?php

namespace Wikisource\WsContest\Controller;

use Doctrine\DBAL\Connection;
use Psr\Container\ContainerInterface;
use Slim\Collection;
use Slim\Http\Response;
use Slim\Router;
use Slim\Views\Twig;

abstract class Controller
{

    /** @var ContainerInterface */
    protected $container;

    /** @var Twig */
    protected $view;

    /** @var Connection */
    protected $db;

    /** @var Router */
    protected $router;

    /** @var Collection */
    protected $settings;
    
    protected $requireLogin = true;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->view = $container->get('view');
        $this->db = $container->get('db');
        $this->router = $container->get('router');
        $this->settings = $container->get('settings');
    }

    protected function renderView(Response $response, $view, $data)
    {
        return $this->view->render(
            $response,
            $view,
            array_merge($data, [
                'flash' => $this->getFlash(),
                'username' => isset($_SESSION['username']) ? $_SESSION['username'] : false,
            ])
        );
    }

    protected function setFlash($message, $type = 'info', $params = [])
    {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message,
            'params' => $params,
        ];
    }

    protected function getFlash()
    {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return false;
    }
}
