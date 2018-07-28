<?php
namespace Concrete\Package\Starter;

defined('C5_EXECUTE') or die('Access Denied.');

use Core;
use Package;
use PageTheme;
use Concrete\Core\Page\Single as SinglePage;
use Concrete\Core\Page\Page;
use Request;
use Database;
use Loader; /** @see \Concrete\Core\Legacy\Loader */
use Route; /** @see \Concrete\Core\Routing\Router */
use PageTemplate; /** @see \Concrete\Core\Page\Template */
use CollectionAttributeKey; /** @see \Concrete\Core\Attribute\Key\CollectionKey */
use Concrete\Core\Http\ResponseAssetGroup;
use BlockType;

use Concrete\Core\Page\Type\Composer\Control\CorePageProperty\NameCorePageProperty as NameControl;


use Concrete\Core\Page\Type\Composer\FormLayoutSet as PageTypeComposerFormLayoutSet;
use Concrete\Core\Page\Type\Composer\FormLayoutSetControl as PageTypeComposerFormLayoutSetControl;
use Concrete\Core\Page\Type\Composer\Control\Type\Type as PageTypeComposerControlType;
use Concrete\Core\Page\Type\PublishTarget\Type\Type as PublishTargetType;
use Concrete\Core\Page\Type\Type as CollectionType;
use PageType;
use URL;

class Controller extends Package
{
  protected $pkgHandle = 'starter';
  protected $appVersionRequired = '8.0';
  protected $pkgVersion = '1.0';

  protected $singlePages = array(
    // array('/dashboard/page_name'),
  );

  /**
   * Blocks array. Add block handles here
   *
   * @var array
   */
  protected $blocks = array(
      //'block_name'
  );

  public function getPackageDescription()
  {
    return t('Concrete5 Starter  Package');
  }

  public function getPackageName()
  {
    return t('Starter Package');
  }

  public function uninstall() {
    parent::uninstall();
    $db = Database::connection();
    //$db->executeQuery('DROP TABLE IF EXISTS Table_name');
  }

  public function install() {
    $pkg = parent::install();
    PageTheme::add('starter', $pkg);

    //Install Concrete5 Page types
    $this->addCollectionTypes($pkg);
    //Create pages if we have to.
    $this->checkCreatePages();
    //Create blocks if we need to
    $this->checkCreateBlocks();
  }

  public function upgrade() {
    $pkg = parent::upgrade();
    $this->checkCreatePages();
    $this->checkCreateBlocks();
  }

  private function checkCreatePages() {
    if(count($this->singlePages)) {
      $pkg = Package::getByHandle($this->pkgHandle);
      foreach($this->singlePages as $sp) {
        $page = Page::getByPath($sp[0]);
        if ($page->getCollectionID() <= 0) {
          SinglePage::add($sp[0], $pkg);
          $page = Page::getByPath($sp[0]);
        }
        if ($sp[1] === true) {
          $page->setAttribute('exclude_nav', $sp[1]);
        }
      }
    }
  }

  private function checkCreateBlocks() {
    if(count($this->blocks)) {
      $pkg = Package::getByHandle($this->pkgHandle);
      foreach($this->blocks as $block) {
        $blockType = BlockType::getByHandle($block, $pkg);
        if(!is_object($blockType)) {
          BlockType::installBlockType($block, $pkg);
        }
      }
    }
  }

  public function on_start()
  {
    $pkg = Package::getByHandle($this->pkgHandle);
    $al = \AssetList::getInstance();
    $ra = ResponseAssetGroup::get();

    $al->register('css', 'datetimepicker', 'css/bootstrap-datetimepicker.min.css', array(), $pkg);
    $al->register('css', 'bootstrapfonts', 'css/bootstrap.fonts.css', array(), $pkg);
    $al->register('javascript', 'moment', 'js/moment.min.js', array(), $pkg);
    $al->register('javascript', 'bootstrap', 'js/bootstrap.min.js', array(), $pkg);
    $al->register('javascript', 'datetimepicker',
    'js/bootstrap-datetimepicker.min.js', array(), $pkg);
    $al->register('css', 'bootstrap', 'css/bootstrap.min.css', array(), $pkg);
  }

  protected function registerRoutes()
  {
    /*Route::register(
    '/dashboard/job_opportunity',
    '\Concrete\Package\Starter\Controller\Dashboard\JobOpportunity::view',
    'JobOpportunity'
  );*/
 }


 /**
     * Format:
     *      array(
     *          'collection_handle' => "Collection Name"
     *      );
     */
    private $collectionTypes = array(
      'home' => 'Home',
      'left_sidebar' => 'Left Sidebar',
  'right_sidebar' => 'Right Sidebar',
  'subpage' => 'Subpage',
  );


private function addCollectionTypes($pkg)
{
   // Loader::model('collection_types');
    if (is_array($this->collectionTypes) && !empty($this->collectionTypes)) {
        foreach ($this->collectionTypes as $handle => $name) {
            $ct = CollectionType::getByHandle($handle);										
            if (!is_object($ct)) {
                CollectionType::add(array(
                    'name' => $name,
                    'handle' => $handle,
        'defaultTemplate'       => PageTemplate::getByHandle($handle),
        'ptIsFrequentlyAdded'   => 1,
        'ptLaunchInComposer'    => 1
                ), $pkg);
            }
    
  $pt = PageType::getByHandle($handle);
     /** @var $layoutSet \Concrete\Core\Page\Type\Composer\FormLayoutSet */
        $layoutSet = $pt->addPageTypeComposerFormLayoutSet('Basics', 'Basics');
  
        /** @var $controlTypeCorePageProperty \Concrete\Core\Page\Type\Composer\Control\Type\CorePagePropertyType */
        $controlTypeCorePageProperty = \Concrete\Core\Page\Type\Composer\Control\Type\Type::getByHandle('core_page_property');
  
   /** @var $controlTypeName \Concrete\Core\Page\Type\Composer\Control\CorePageProperty\NameCorePageProperty */
        $controlTypeName = $controlTypeCorePageProperty->getPageTypeComposerControlByIdentifier('name');
        $controlTypeName->addToPageTypeComposerFormLayoutSet($layoutSet)
                        ->updateFormLayoutSetControlRequired(true);
          
          $controlTypeName = $controlTypeCorePageProperty->getPageTypeComposerControlByIdentifier('description');
        $controlTypeName->addToPageTypeComposerFormLayoutSet($layoutSet)
                        ->updateFormLayoutSetControlRequired(false);
          
          $controlTypeName = $controlTypeCorePageProperty->getPageTypeComposerControlByIdentifier('page_template');
        $controlTypeName->addToPageTypeComposerFormLayoutSet($layoutSet)
                        ->updateFormLayoutSetControlRequired(true);
          
        
          
          $controlTypeName = $controlTypeCorePageProperty->getPageTypeComposerControlByIdentifier('url_slug');
        $controlTypeName->addToPageTypeComposerFormLayoutSet($layoutSet)
                        ->updateFormLayoutSetControlRequired(false);
          
          /** @var $controlTypePublishTarget \Concrete\Core\Page\Type\Composer\Control\CorePageProperty\PublishTargetCorePageProperty */
        $controlTypePublishTarget = $controlTypeCorePageProperty->getPageTypeComposerControlByIdentifier('publish_target');
        $controlTypePublishTarget->addToPageTypeComposerFormLayoutSet($layoutSet)
                                 ->updateFormLayoutSetControlRequired(true);
            
        }
    }
}
}
