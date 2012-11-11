<?php

class Resource_Comment_List extends Pimcore_Model_List_Abstract
    implements Zend_Paginator_Adapter_Interface, Zend_Paginator_AdapterAggregate, Iterator
{
    /**
     * @var array
     */
    public $comments;

    /**
     *
     * @var Object_Abstract
     */
    public $object;

    /**
     * @var array
     */
    public $validOrderKeys = array(
        "date",
        "user",
        "object",
        "data",
        "metadata"
    );

    /**
     * @param boolean $objectTypeObject
     * @return void
     */
    public function __construct(Element_Interface $target)
    {
        $this->initResource("Resource_Comment_List");
        $type = Commenting_Plugin::getTypeFromTarget($target);
        $this->setCondition(
            "commentingTargetId = ? AND type = ?",
            array($target->getId(), $type)
        );
        $this->setOrderKey('date');
        $this->setOrder('desc');
    }

    /**
     * @param string $key
     * @return boolean
     */
    public function isValidOrderKey($key)
    {
        if (in_array($key, $this->validOrderKeys)) {
            return true;
        }
        return false;
    }

    /**
     * @param integer $offset
     * @param integer $itemCountPerPage
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $this->setOffset($offset);
        $this->setLimit($itemCountPerPage);
        return $this->load();
    }

    /**
     * @return integer
     */
    public function count()
    {
        return $this->getTotalCount();
    }

    /**
     * @return Resource_Comment_List
     */
    public function getPaginatorAdapter()
    {
        return $this;
    }

    /**
     * @return array
     */
    public function getComments()
    {
        if (!$this->comments === null) {
            $this->load();
        }
        return $this->comments;
    }

    /**
     * @param string $objects
     * @return void
     */
    public function setComments($comments)
    {
        $this->comments = $comments;
    }

    /**
     * @return Object_Abstract $object
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * Methods for Iterator
     */
    public function rewind()
    {
        $this->getComments();
        reset($this->comments);
    }

    public function current()
    {
        $this->getComments();
        return current($this->comments);
    }

    public function key()
    {
        $this->getComments();
        return key($this->comments);
    }

    public function next()
    {
        $this->getComments();
        return next($this->comments);
    }

    public function valid()
    {
        $this->getComments();
        return $this->current() !== false;
    }

}
