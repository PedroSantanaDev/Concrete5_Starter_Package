<?php 
namespace Concrete\Package\Starter\Controller\SinglePage\Dashboard;
use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Page\PageList as PageList;
use \Concrete\Core\Page\Type\Type as CollectionType;
use \Concrete\Core\Attribute\Key\CollectionKey as CollectionAttributeKey;
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
use Carbon\Carbon;
use UserList;
use Loader;
use Core;
use View;
use Package;
class Help extends DashboardPageController
{
	public function on_start()
    {
        $this->requireAsset('javascript', 'vue');
        $this->requireAsset('javascript', 'axios');

        $this->requireAsset('javascript', 'app-main-js');

        Loader::model('user_list');

        $this->session  = new SymfonySession();

        $html = Loader::helper('html');
        $view = View::getInstance();
    }
    public function view()
    {
        $list = new UserList();
        $list->setItemsPerPage(10);
        $pagination = $list->getPagination();
        $users = $pagination->getCurrentPageResults();
        $pagination = $pagination->renderDefaultView();
        //$this->set('pagination', $pagination);

        return $users;
        //$this->set('users',   $users);
    }

}