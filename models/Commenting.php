<?php

/**
 * ModernWeb
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.modernweb.pl/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to kontakt@modernweb.pl so we can send you a copy immediately.
 *
 * @category    Pimcore
 * @package     Plugin_Commenting
 * @author      Rafał Gałka <rafal@modernweb.pl>
 * @copyright   Copyright (c) 2007-2012 ModernWeb (http://www.modernweb.pl)
 * @license     http://www.modernweb.pl/license/new-bsd     New BSD License
 */

/**
 * Model facade.
 *
 * @category    Pimcore
 * @package     Plugin_Commenting
 * @author      Rafał Gałka <rafal@modernweb.pl>
 * @copyright   Copyright (c) 2007-2012 ModernWeb (http://www.modernweb.pl)
 */
class Commenting
{
    /**
     * @var Zend_Config
     */
    protected $_options;

    /**
     * @var Commenting_Form
     */
    protected $_form;

    public function __construct($options = null)
    {
        if(null !== $options) {
            $this->setOptions($options);
        }
    }

    public function setOptions($options)
    {
        if(is_array($options)) {
            $options = new Zend_Config($options);
        }

        if(!$options instanceof Zend_Config) {
            throw new Exception("Options must be array or Zend_Config instance");
        }

        $this->_options = $options;

        return $this;
    }

    /**
     * @return Zend_Config
     */
    public function getOptions()
    {
        return $this->_options;
    }

    /**
     * @return Commenting_Form
     */
    public function getForm()
    {
        if(!$this->_form) {
            $this->_form = new Commenting_Form();
        }

        return $this->_form;
    }

    /**
     * Save the comment for the specified target.
     *
     * @param array $data
     * @param Element_Interface $target
     * @return boolean
     */
    public function saveComment(array $data, Element_Interface $target)
    {
        if($this->getForm()->isValid($data)) {
            Commenting_Plugin::postComment(
                $this->getForm()->getValue("message"),
                time(),
                $target,
                null,
                array(
                    "name" => $this->getForm()->getValue("name"),
                    "email" => $this->getForm()->getValue("email"),
                )
            );
            return true;
        }

        return false;
    }

    /**
     * @param Element_Interface $target
     * @param integer $page
     * @param integer $perPage
     * @return Zend_Paginator
     */
    public function getComments(Element_Interface $target, $page = 1, $perPage = 10)
    {
        $paginator = new Zend_Paginator(new Resource_Comment_List($target));
        $paginator->setCurrentPageNumber((int)$page);
        $paginator->setItemCountPerPage((int)$perPage);
        $paginator->setPageRange(5);

        return $paginator;
    }

}
