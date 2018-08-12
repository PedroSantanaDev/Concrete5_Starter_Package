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
/**
 * Package controller class
 */
class Controller extends Package
{
  protected $pkgHandle = 'starter';
  protected $appVersionRequired = '8.0';
  protected $pkgVersion = '1.0';
  /**
   * App mode dev/production
   *
   * @var boolean
   */
  protected $is_live = false; //Change to "true" in production

  protected $singlePages = array(
     array('/dashboard/help'),
     array('/dashboard/admin'),
     array('/dashboard/admin/users'),
  );

  /**
   * Blocks array. Add block handles here
   *
   * @var array
   */
  protected $blocks = array(
      //'block_name'
  );
  /**
   * Package discription for the installation page
   *
   * @return void
   */
  public function getPackageDescription()
  {
    return t('Concrete5 Starter  Package');
  }
  /**
   * Package name display on the Concrete5 install page
   *
   * @return void
   */
  public function getPackageName()
  {
    return t('Starter Package');
  }

  /**
   * Unistall the package and drop tables
   *
   * @return void
   */
  public function uninstall() {
    parent::uninstall();
    $db = Database::connection();
    //$db->executeQuery('DROP TABLE IF EXISTS Table_name');
  }
  /**
   * Install the package, theme and blocks
   *
   * @return void
   */
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
  /**
   * Upgrade package
   *
   * @return void
   */
  public function upgrade() {
    $pkg = parent::upgrade();
    $this->checkCreatePages();
    $this->checkCreateBlocks();
  }
  /**
   * Creates pages if needed
   *
   * @return void
   */
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
  /**
   * Creates blocks if needed
   *
   * @return void
   */
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
  /**
   * Registrer assets 
   *
   * @return void
   */
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

    if($is_live){
      $al->register('javascript', 'vue', 'js/vue.min.js', array(), $pkg);
      $al->register('javascript', 'axios', 'js/axios.min.js', array(), $pkg);
    }else{
      $al->register('javascript', 'vue', 'js/vue.js', array(), $pkg);
      $al->register('javascript', 'axios', 'js/axios.js', array(), $pkg);
      
    }

    $al->register('javascript', 'app-main-js', 'js/main.js', array(), $pkg);

  }
  /**
   * Register package routes
   *
   * @return void
   */
  protected function registerRoutes()
  {
    /*Route::register(
    '/dashboard/test',
    '\Concrete\Package\Starter\Controller\Dashboard\Test::view',
    'Test'
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

/**
 * Add page types
 *
 * @param Package $pkg
 * @return void
 */
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
