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

use Concrete\Package\JobOpportunity\Src\Entity\GrandErieJobOpportunityCategory as CategoryEntity;
use Concrete\Package\JobOpportunity\Src\Entity\GrandErieJobOpportunity as DataEntity;



class JobOpportunityAdmin extends DashboardPageController {

  //Database connection
  private $db;

  //Entity manager object
  private $em;

  private $repository = 'Concrete\Package\JobOpportunity\Src\Entity\GrandErieJobOpportunity';

  public function on_start()
  {
    $html = \Core::make('helper/html');
    $this->set('form', Core::make('helper/form'));

    $this->db = Database::get();
    $this->em = $this->db->getEntityManager();

    $r = ResponseAssetGroup::get();
    $r->requireAsset('job_opportunity/datetime_picker');
    $r->requireAsset('job_opportunity');    
  }

  public function view() {
    $list = new DataEntity();
    $list->setEntity("FROM ".$this->repository);
    $list->createQuery();
    if ($_GET['qtitle']) {
      $list->filterBy('job_title', $_GET['qtitle'], 'like');
    }

    if ($_GET['qcategory']) {
      $list->filterBy('job_category', $_GET['qcategory']);
    }

    $list->setItemsPerPage(20);

    if ($_GET['sort'])
    {
      $sort_direction = 'asc';

      if ($_GET['sort'] == 'job_posted_date' || $_GET['sort'] == 'job_expiry_date') {
        $sort_direction = 'desc';
      }
      $list->sortBy($_GET['sort'], $sort_direction);
    } else {
      $list->sortBy('job_posted_date', 'desc');
    }

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
      $job = $orm->findOneBy(array('id' => $id));
      if (!$job) {
        $session->getFlashBag()->add('error', 'Job not found');
        $this->redirect('dashboard/job_opportunity/job_opportunity_admin');
      }
    } else {
      $job = new DataEntity;
    }

    if ($this->post() && $job) {

      // assign params to entity model
			$job->setParams($_POST);

      // setparams won't work on these as we need a datetime object and we need the html in the description
      $job->job_description = $_POST['job_description'];
      $job->job_posted_date = $job->getDateTime($_POST['job_posted_date']);
      $job->job_expiry_date = $job->getDateTime($_POST['job_expiry_date']);

      // validation
			$valid = $job->Validate();

			if ($valid)
			{
        /*echo "<pre>"; var_dump($job); echo "</pre>";
        die();*/
        // save
        $this->em->persist($job);
        $this->em->flush();

        $session->getFlashBag()->add('success', 'Job Saved');
        // redirect back to edit screen after successful save
        $this->redirect('dashboard/job_opportunity/job_opportunity_admin/edit/'.$job->id);
      }
    }

    if (!$job) {
      $session->getFlashBag()->add('error', 'Job not found');
      $this->redirect('dashboard/job_opportunity/job_opportunity_admin/view');
    }

    $this->set('job',$job);
    $this->render('dashboard/job_opportunity/job_opportunity_admin/edit'); // Renders update view
  }

  public function delete($id='')
  {
    $session = new SymfonySession();
    $orm = $this->em->getRepository($this->repository);

    if ($id) {
      // delete
      $job = $orm->find($id);
      if (!$job) {
        $session->getFlashBag()->add('error', 'Job not found');
      } else {

        // this should work but throws error don't have time to investigate so just use normal query
        //$this->$em->remove($job);
        //$this->$em->flush();

        $db = Database::get();
    		$db->Execute("DELETE FROM grand_erie_job_opportunity WHERE id = ?", array($id));
        $session->getFlashBag()->add('success', 'Job deleted');
      }
    }

    $this->redirect('dashboard/job_opportunity/job_opportunity_admin');
  }
}
