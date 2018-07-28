<?php
namespace Concrete\Package\JobOpportunity\Src\Entity;
use Concrete\Package\JobOpportunity\Src\Entity as Entity;
use Doctrine\Common\Collections\ArrayCollection;
use Concrete\Core\Support\Facade\Database;
use \Symfony\Component\HttpFoundation\Session\Session as SymfonySession;
/**
* @Entity
* @Table(name="grand_erie_job_opportunity_category")
*/
class GrandErieJobOpportunityCategory extends Entity
{

  /**
  * @ID @Column(type="integer")
  * @GeneratedValue
  */
  protected $id;

  /** @Column(type="string",length=255, nullable=false) */
  protected $category_desc;

  public function getId()
  {
    return $this->id;
  }

  static function getCategoryArray($option = '')
  {
    if ($option == 'all') {
      $cat_array = array('0'=>'All Categories');
    } else {
      $cat_array = array(''=>'');
    }

    $db = Database::get();
		$categories = $db->GetAll("SELECT * FROM grand_erie_job_opportunity_category");

    if ($categories) {
      foreach ($categories as $key => $cat) {
        $cat_array[$cat['id']] = $cat['category_desc'];
      }
    }

    return $cat_array;
  }

  public function Validate ()
  {
    $session = new SymfonySession();

    $valid = 1;

    if (empty($this->category_desc)) {
      $session->getFlashBag()->add('error', 'Category is required');
      $valid = 0;
    };

    return $valid;
  }
}
