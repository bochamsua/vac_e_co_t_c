<?php
/**
 * BS_Docwise extension
 * 
 * @category       BS
 * @package        BS_Docwise
 * @copyright      Copyright (c) 2015
 */
/**
 * Exam model
 *
 * @category    BS
 * @package     BS_Docwise
 * @author Bui Phong
 */
class BS_Docwise_Model_Exam extends Mage_Core_Model_Abstract
{
    /**
     * Entity code.
     * Can be used as part of method name for entity processing
     */
    const ENTITY    = 'bs_docwise_exam';
    const CACHE_TAG = 'bs_docwise_exam';

    /**
     * Prefix of model events names
     *
     * @var string
     */
    protected $_eventPrefix = 'bs_docwise_exam';

    /**
     * Parameter name in event
     *
     * @var string
     */
    protected $_eventObject = 'exam';
    protected $_docwisementInstance = null;
    protected $_filefolderInstance = null;


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
        $this->_init('bs_docwise/exam');
    }

    /**
     * before save exam
     *
     * @access protected
     * @return BS_Docwise_Model_Exam
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
     * save exam relation
     *
     * @access public
     * @return BS_Docwise_Model_Exam
     * @author Bui Phong
     */
    protected function _afterSave()
    {
        $this->getDocwisementInstance()->saveExamRelation($this);
        $this->getFilefolderInstance()->saveExamRelation($this);
        return parent::_afterSave();
    }

    /**
     * get product relation model
     *
     * @access public
     * @return BS_Docwise_Model_Exam_Product
     * @author Bui Phong
     */
    public function getDocwisementInstance()
    {
        if (!$this->_docwisementInstance) {
            $this->_docwisementInstance = Mage::getSingleton('bs_docwise/exam_docwisement');
        }
        return $this->_docwisementInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedDocwisements()
    {
        if (!$this->hasSelectedDocwisements()) {
            $docwisements = array();
            foreach ($this->getSelectedDocwisementsCollection() as $docwisement) {
                $docwisements[] = $docwisement;
            }
            $this->setSelectedDocwisements($docwisements);
        }
        return $this->getData('selected_docwisements');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return BS_Docwise_Resource_Exam_Product_Collection
     * @author Bui Phong
     */
    public function getSelectedDocwisementsCollection()
    {
        $collection = $this->getDocwisementInstance()->getDocwisementCollection($this);
        return $collection;
    }



    /**
     * get product relation model
     *
     * @access public
     * @return BS_Docwise_Model_Exam_Product
     * @author Bui Phong
     */
    public function getFilefolderInstance()
    {
        if (!$this->_filefolderInstance) {
            $this->_filefolderInstance = Mage::getSingleton('bs_docwise/exam_filefolder');
        }
        return $this->_filefolderInstance;
    }

    /**
     * get selected products array
     *
     * @access public
     * @return array
     * @author Bui Phong
     */
    public function getSelectedFilefolders()
    {
        if (!$this->hasSelectedFilefolders()) {
            $filefolders = array();
            foreach ($this->getSelectedFilefoldersCollection() as $filefolder) {
                $filefolders[] = $filefolder;
            }
            $this->setSelectedFilefolders($filefolders);
        }
        return $this->getData('selected_filefolders');
    }

    /**
     * Retrieve collection selected products
     *
     * @access public
     * @return BS_Docwise_Resource_Exam_Product_Collection
     * @author Bui Phong
     */
    public function getSelectedFilefoldersCollection()
    {
        $collection = $this->getFilefolderInstance()->getFilefolderCollection($this);
        return $collection;
    }

    /**
     * Retrieve  collection
     *
     * @access public
     * @return BS_Docwise_Model_Score_Collection
     * @author Bui Phong
     */
    public function getSelectedScoresCollection()
    {
        if (!$this->hasData('_score_collection')) {
            if (!$this->getId()) {
                return new Varien_Data_Collection();
            } else {
                $collection = Mage::getResourceModel('bs_docwise/score_collection')
                        ->addFieldToFilter('exam_id', $this->getId());
                $this->setData('_score_collection', $collection);
            }
        }
        return $this->getData('_score_collection');
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
