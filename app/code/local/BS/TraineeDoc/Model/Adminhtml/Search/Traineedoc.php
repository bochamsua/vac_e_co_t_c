<?php
/**
 * BS_TraineeDoc extension
 * 
 * @category       BS
 * @package        BS_TraineeDoc
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_TraineeDoc
 * @author      Bui Phong
 */
class BS_TraineeDoc_Model_Adminhtml_Search_Traineedoc extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_TraineeDoc_Model_Adminhtml_Search_Traineedoc
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_traineedoc/traineedoc_collection')
            ->addFieldToFilter('trainee_doc_name', array('like' => '%'.$this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $traineedoc) {
            $arr[] = array(
                'id'          => 'traineedoc/1/'.$traineedoc->getId(),
                'type'        => Mage::helper('bs_traineedoc')->__('Trainee Document'),
                'name'        => $traineedoc->getTraineeDocName(),
                'description' => $traineedoc->getTraineeDocName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/traineedoc_traineedoc/edit',
                    array('id'=>$traineedoc->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
