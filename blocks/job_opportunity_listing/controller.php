<?php  namespace Concrete\Package\JobOpportunity\Block\JobOpportunityListing;

defined("C5_EXECUTE") or die("Access Denied.");

use Concrete\Core\Block\BlockController;
use Core;
use Concrete\Core\Editor\LinkAbstractor;
use Database;

class Controller extends BlockController
{
  public $helpers = array('form');
  public $btFieldsRequired = array('displayCategory');
  protected $btExportFileColumns = array();
  protected $btExportPageColumns = array();
  protected $btTable = 'btJobOpportunityListing';
  protected $btInterfaceWidth = 400;
  protected $btInterfaceHeight = 500;
  protected $btIgnorePageThemeGridFrameworkContainer = false;
  protected $btCacheBlockRecord = true;
  protected $btCacheBlockOutput = true;
  protected $btCacheBlockOutputOnPost = true;
  protected $btCacheBlockOutputForRegisteredUsers = true;
  protected $btCacheBlockOutputLifetime = 0;
  protected $pkg = false;

    public function getBlockTypeDescription()
    {
      return t("List active job opportunites by category");
    }

    public function getBlockTypeName()
    {
      return t("Job Opportunity Listing");
    }

    public function view()
    {
      $now = date('Y-m-d H:i:s');
      $db = Database::get();

      if ($this->displayCategory == 0)
      {
        $jobs = $db->GetAll("SELECT jo.*, joc.category_desc
          FROM grand_erie_job_opportunity jo
          LEFT JOIN grand_erie_job_opportunity_category joc
          ON jo.job_category = joc.id
          WHERE jo.job_active = 1
          AND (jo.job_expiry_date > NOW() OR jo.job_expiry_date IS NULL)
          ORDER BY joc.category_desc");
      } else {

        $jobs = $db->GetAll("SELECT jo.*, joc.category_desc
          FROM grand_erie_job_opportunity jo
          LEFT JOIN grand_erie_job_opportunity_category joc
          ON jo.job_category = joc.id
          WHERE jo.job_category = ?
          AND jo.job_active = 1
          AND (jo.job_expiry_date > NOW() OR jo.job_expiry_date IS NULL)", array($this->displayCategory));

      }


  $this->set("jobs", $jobs);
  $this->set("displayCategory", $this->displayCategory);
  $this->set("displayCategory_options", $this->displayCategory_options);
}

public function add()
{
  $this->addEdit();
}

public function edit()
{
  $this->addEdit();
}

protected function addEdit()
{
  $this->set("displayCategory_options", \Concrete\Package\JobOpportunity\Src\Entity\GrandErieJobOpportunityCategory::getCategoryArray('all'));
  $this->set('btFieldsRequired', $this->btFieldsRequired);
  $this->set('identifier_getString', Core::make('helper/validation/identifier')->getString(18));
}

public function validate($args)
{
  $e = Core::make("helper/validation/error");
  if (trim($args["displayCategory"]) == "") {
    $e->add(t("The %s field has an invalid value.", t("Category to Display")));
  }
  return $e;
}

public function composer()
{
  $this->edit();
}
}
