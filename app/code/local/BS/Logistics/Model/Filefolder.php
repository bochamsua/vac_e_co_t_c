<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * File Folder model
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Model_Filefolder extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_logistics_filefolder';
    const CACHE_TAG = 'bs_logistics_filefolder';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_logistics_filefolder';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'filefolder';
    protected $_productInstance = null;
    protected $_examInstance = null;

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
        $this->_init('bs_logistics/filefolder');
    }

    /**
     * before save file folder
     *
     * @access protected
     * @return BS_Logistics_Model_Filefolder
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
     * save file folder relation
     *
     * @access public
     * @return BS_Logistics_Model_Filefolder
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getProductInstance()->saveFilefolderRelation($this);
        $this->getExamInstance()->saveFilefolderRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return BS_Logistics_Model_Filefolder_Product
     * @author Bui Phong
     */
    public function getProductInstance()
    {
        if (!$this->_productInstance) {
            $this->_productInstance = Mage::getSingleton('bs_logistics/filefolder_product');
        }
        return $this->_productInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedProducts()
    {
        if (!$this->hasSelectedProducts()) {
            $products = array();
            foreach ($this->getSelectedProductsCollection() as $product) {
                $products[] = $product;
            }
            $this->setSelectedProducts($products);
        }
        return $this->getData('selected_products');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return BS_Logistics_Resource_Filefolder_Product_Collection
     * @author Bui Phong
     */
    public function getSelectedProductsCollection()
    {
        $collection = $this->getProductInstance()->getProductCollection($this);
        return $collection;
    }


    public function getExamInstance()
    {
        if (!$this->_examInstance) {
            $this->_examInstance = Mage::getSingleton('bs_docwise/exam_filefolder');
        }
        return $this->_examInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedExams()
    {
        if (!$this->hasSelectedExams()) {
            $exams = array();
            foreach ($this->getSelectedExamsCollection() as $exam) {
                $exams[] = $exam;
            }
            $this->setSelectedExams($exams);
        }
        return $this->getData('selected_exams');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return BS_Logistics_Resource_Filefolder_Product_Collection
     * @author Bui Phong
     */
    public function getSelectedExamsCollection()
    {
        $collection = $this->getExamInstance()->getExamCollection($this);
        return $collection;
    }



    /**
     * Retrieve parent 
     *
     * @access public
     * @return null|BS_Logistics_Model_Filecabinet
     * @author Bui Phong
     */
    public function getParentFilecabinet()
    {
        if (!$this->hasData('_parent_filecabinet')) {
            if (!$this->getFilecabinetId()) {
                return null;
            } else {
                $filecabinet = Mage::getModel('bs_logistics/filecabinet')
                    ->load($this->getFilecabinetId());
                if ($filecabinet->getId()) {
                    $this->setData('_parent_filecabinet', $filecabinet);
                } else {
                    $this->setData('_parent_filecabinet', null);
                }
            }
        }
        return $this->getData('_parent_filecabinet');
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
