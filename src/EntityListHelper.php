<?php
namespace Concrete\Package\Starter\Src;
/*
*This is a simple helper class that provides the methods under
*Concrete\Core\Search\ItemList\EntityList
*For class documentation: *https://documentation.concrete5.org/api/8.0/Concrete/Core/Search/ItemList/EntityItemList.html
*
*/

use Concrete\Core\Search\ItemList\EntityItemList;
use Concrete\Core\Search\Pagination\Pagination;
use Package;
use Concrete\Core\Search\Result;
use Pagerfanta\Adapter\DoctrineORMAdapter;
/**
* EntityListHelper Entity helper to implement the EntityItemList methods
*/
class EntityListHelper extends EntityItemList
{
  //Concrete5 Query Builder
  protected $qb;

  public function createQuery() {
    $this->query = $this->qb;
  }
  /**
   * [setEntity Load the entity into the query builder object]
   * @param string $entity ORM entity to use in the query
   */
  public function setEntity($entity)
  {
      $this->qb = $this->getEntityManager()->createQueryBuilder();
      $this->qb->addSelect("e ".$entity  ." e");
  }
  public function getEntityManager()
  {
    return Package::getByHandle("base")->getEntityManager();
  }

  protected function executeSortBy($field, $direction = "asc")
  {
    $this->query->addOrderBy("e." . $field, $direction);
  }

  public function executeGetResults( )
  {
    return $this->query->getQuery()->getResult();
  }

  public function getResult( $mixed )
  {
    return $mixed;
  }

  public function filterBy($column, $value, $expression = '')
  {
    switch ($expression) {
      case '=':
        $this->query->andWhere($this->query->expr()->eq('e.'.$column, ':'.$column));
        break;
      case 'like':
        $this->query->andWhere($this->query->expr()->like('e.'.$column, ':'.$column));
        $value = '%'.$value.'%';
        break;
      case '>':
        $this->query->andWhere($this->query->expr()->gt('e.'.$column, ':'.$column));
        break;
      case '<':
        $this->query->andWhere($this->query->expr()->lt('e.'.$column, ':'.$column));
        break;
      default:
        $this->query->andWhere($this->query->expr()->eq('e.'.$column, ':'.$column));
        break;
    }
    $this->query->setParameter($column, $value);
  }

  public function debugStart()
  {

  }
  public function debugStop()
  {

  }

  protected function createPaginationObject()
  {
    return new Pagination($this, new DoctrineORMAdapter($this->query) );
  }

  public function getTotalResults()
  {
    return $this->query->getQuery()->getMaxResults();
  }
}
?>
