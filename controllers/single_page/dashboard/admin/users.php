<?php namespace Concrete\Package\Starter\Controller\SinglePage\Dashboard\Admin;

use Loader;
use Package;
use Response;
use UserList;
use View;
use \Concrete\Core\Page\Controller\DashboardPageController;
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;

class Users extends DashboardPageController
{
    public function on_start()
    {
        $this->requireAsset('javascript', 'vue');
        $this->requireAsset('javascript', 'axios');

        $this->requireAsset('javascript', 'app-main-js');

        Loader::model('user_list');

        $this->session = new SymfonySession();

        $html = Loader::helper('html');
        $view = View::getInstance();
    }

    public function view()
    {

    }

    public function index()
    {
        $list = new UserList();
        $list->setItemsPerPage(10);
        $pagination = $list->getPagination();
        $users = $pagination->getCurrentPageResults();
        $pagination = $pagination->renderDefaultView();
        //$this->set('pagination', $pagination);

        return $users; 
    }
    
}
