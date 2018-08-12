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

        $this->session  = new SymfonySession();

        $html = Loader::helper('html');
        $view = View::getInstance();
    }
    public function view()
    {
        
    }

}