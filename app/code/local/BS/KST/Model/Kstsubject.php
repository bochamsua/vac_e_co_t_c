<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject model
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Model_Kstsubject extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_kst_kstsubject';
    const CACHE_TAG = 'bs_kst_kstsubject';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_kst_kstsubject';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'kstsubject';

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
        $this->_init('bs_kst/kstsubject');
    }

    /**
     * before save subject
     *
     * @access protected
     * @return BS_KST_Model_Kstsubject
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
     * get the url to the subject details page
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getKstsubjectUrl()
    {
        if ($this->getUrlKey()) {
            $urlKey = '';
            if ($prefix = Mage::getStoreConfig('bs_kst/kstsubject/url_prefix')) {
                $urlKey .= $prefix.'/';
            }
            $urlKey .= $this->getUrlKey();
            if ($suffix = Mage::getStoreConfig('bs_kst/kstsubject/url_suffix')) {
                $urlKey .= '.'.$suffix;
            }
            return Mage::getUrl('', array('_direct'=>$urlKey));
        }
        return Mage::getUrl('bs_kst/kstsubject/view', array('id'=>$this->getId()));
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
     * save subject relation
     *
     * @access public
     * @return BS_KST_Model_Kstsubject
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
     * @return BS_KST_Model_Kstitem_Collection
     * @author Bui Phong
     */
    public function getSelectedKstitemsCollection()
    {
        if (!$this->hasData('_kstitem_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_kst/kstitem_collection')
                        ->addFieldToFilter('kstsubject_id', $this->getId());
                $this->setData('_kstitem_collection', $collection);
            }
        }
        return $this->getData('_kstitem_collection');
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
                        ->addFieldToFilter('kstsubject_id', $this->getId());
                $this->setData('_kstprogress_collection', $collection);
            }
        }
        return $this->getData('_kstprogress_collection');
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
