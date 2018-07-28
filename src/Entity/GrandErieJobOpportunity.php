<?php
namespace Concrete\Package\JobOpportunity\Src\Entity;
use Concrete\Package\JobOpportunity\Src\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Concrete\Core\Support\Facade\Database;
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
/**
* @Entity
* @Table(name="grand_erie_job_opportunity")
*/
class GrandErieJobOpportunity extends Entity
{

  /**
  * @ID @Column(type="integer")
  * @GeneratedValue
  */
  protected $id;

  /** @Column(type="string",length=255, nullable=false) */
  protected $job_title;

  /** @Column(type="integer",nullable=true) */
  protected $job_category=null;

  /** @Column(type="text",nullable=true) */
  protected $job_description=null;

  /** @Column(type="integer",nullable=true) */
  protected $job_active=null;

  /** @Column(type="datetime",nullable=true) */
  protected $job_posted_date=null;

  /** @Column(type="datetime",nullable=true) */
  protected $job_expiry_date=null;

  public function getId()
  {
    return $this->id;
  }

  public function getCategory()
  {
    $db = Database::get();
		$category = $db->GetOne("SELECT category_desc FROM grand_erie_job_opportunity_category WHERE id = ?",
    array($this->job_category));

    return $category;
  }

  public function Validate ()
	{
		$session = new SymfonySession();

		$valid = 1;

		if (empty($this->job_title)) {
			$session->getFlashBag()->add('error', 'Job Title is Required');
			$valid = 0;
		};

    if (empty($this->job_description)) {
			$session->getFlashBag()->add('error', 'Job Description is Required');
			$valid = 0;
		};

		return $valid;
	}
}
