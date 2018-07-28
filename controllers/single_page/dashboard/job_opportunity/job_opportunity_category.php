<?php
namespace Concrete\Package\JobOpportunity\Controller\SinglePage\Dashboard\JobOpportunity;

defined('C5_EXECUTE') or die(_("Access Denied."));

use \Concrete\Core\Page\Controller\DashboardPageController;
use \Concrete\Core\Http\Service\Json as Json;
use \Concrete\Core\Http\Service\Ajax as Ajax;
use \Concrete\Core\Search\StickyRequest;
use \Concrete\Core\Form\Service\Widget\UserSelector;
use \Concrete\Core\Form\Service\Widget\PageSelector;
use \Concrete\Core\Form\Service\Widget\DateTime;
use \Concrete\Core\Utility\Service\Validation\Numbers;
use Concrete\Core\Support\Facade\Database;
use Doctrine\ORM\Tools\Pagination\Paginator;
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
use Concrete\Core\Http\ResponseAssetGroup;
use Core;
use URL;
use stdClass;
use Loader;
use View;

use \Concrete\Package\JobOpportunity\Src\Entity\GrandErieJobOpportunityCategory as DataEntity;



class JobOpportunityCategory extends DashboardPageController {

  //Database connection
  private $db;

  //Entity manager object
  private $em;

  private $repository = 'Concrete\Package\JobOpportunity\Src\Entity\GrandErieJobOpportunityCategory';

  public function on_start()
  {
    $html = \Core::make('helper/html');
    $this->set('form', Core::make('helper/form'));

    $this->db = Database::get();
    $this->em = $this->db->getEntityManager();

    $r = ResponseAssetGroup::get();
    $r->requireAsset('job_opportunity');  
  }

  public function view() {
    $list = new DataEntity();
    $list->setEntity("FROM ".$this->repository);
    $list->createQuery();
    $list->setItemsPerPage(20);

    $list->sortBy('category_desc');

    //Get pagination object
    $pagination = $list->getPagination();
    //Gets page lists
    $lists = $pagination->getCurrentPageResults();

    //Pass lists to view
    $this->set('lists', $lists);
    $this->set('pagination', $pagination);

  }

  /**
  * [update description]
  * @param  string $id list id
  * @return void
  */
  public function edit($id='')
  {
    $session = new SymfonySession();
    $orm = $this->em->getRepository($this->repository);

    if (!empty(trim($this->post('entityId')))) {
      // we are editing existing
      $id = trim($this->post('entityId'));
    }

    if ($id) {
      // we are editing so get existing entity
      $category = $orm->findOneBy(array('id' => $id));
      if (!$category) {
        $session->getFlashBag()->add('error', 'Category not found');
        $this->redirect('dashboard/job_opportunity/job_opportunity_category');
      }
    } else {
      $category = new DataEntity;
    }

    if ($this->post() && $category) {
      // assign params to entity model
			$category->setParams($_POST);

      // validation
			$valid = $category->Validate();

			if ($valid)
			{
        // save
        $this->em->persist($category);
        $this->em->flush();

        $session->getFlashBag()->add('success', 'Category Saved');
        // redirect back to view categories screen after successful save
        $this->redirect('dashboard/job_opportunity/job_opportunity_category');
      }
    }

    $this->set('category',$category);
    $this->render('dashboard/job_opportunity/job_opportunity_category/edit'); // Renders update view
  }

}
