<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Item model
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Model_Kstitem extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_kst_kstitem';
    const CACHE_TAG = 'bs_kst_kstitem';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_kst_kstitem';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'kstitem';

    /**
     * constructor
     *
     * @access public
     * @return void
     * @author Bui Phong
     */
    public function _construct()
    {
        parent::_construct();
        $this->_init('bs_kst/kstitem');
    }

    /**
     * before save item
     *
     * @access protected
     * @return BS_KST_Model_Kstitem
     * @author Bui Phong
     */
    protected function _beforeSave()
    {
        parent::_beforeSave();
        $now = Mage::getSingleton('core/date')->gmtDate();
        if ($this->isObjectNew()) {
            $this->setCreatedAt($now);
        }
        $this->setUpdatedAt($now);
        return $this;
    }

    /**
     * get the url to the item details page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getKstitemUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('bs_kst/kstitem/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('bs_kst/kstitem/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('bs_kst/kstitem/view', array('id'=>$this->getId()));
    }

    /**
     * check URL key
     *
     * @access public
     * @param string $urlKey
     * @param bool $active
     * @return mixed
     * @author Bui Phong
     */
    public function checkUrlKey($urlKey, $active = true)
    {
        return $this->_getResource()->checkUrlKey($urlKey, $active);
    }

    /**
     * save item relation
     *
     * @access public
     * @return BS_KST_Model_Kstitem
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        return parent::_afterSave();
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_KST_Model_Kstprogress_Collection
     * @author Bui Phong
     */
    public function getSelectedKstprogressesCollection()
    {
        if (!$this->hasData('_kstprogress_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_kst/kstprogress_collection')
                        ->addFieldToFilter('kstitem_id', $this->getId());
                $this->setData('_kstprogress_collection', $collection);
            }
        }
        return $this->getData('_kstprogress_collection');
    }

    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_KST_Model_Kstsubject
     * @author Bui Phong
     */
    public function getParentKstsubject()
    {
        if (!$this->hasData('_parent_kstsubject')) {
            if (!$this->getKstsubjectId()) {
                return null;
            } else {
                $kstsubject = Mage::getModel('bs_kst/kstsubject')
                    ->load($this->getKstsubjectId());
                if ($kstsubject->getId()) {
                    $this->setData('_parent_kstsubject', $kstsubject);
                } else {
                    $this->setData('_parent_kstsubject', null);
                }
            }
        }
        return $this->getData('_parent_kstsubject');
    }

    /**
     * get default values
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getDefaultValues()
    {
        $values = array();
        $values['status'] = 1;
        return $values;
    }
    
}
