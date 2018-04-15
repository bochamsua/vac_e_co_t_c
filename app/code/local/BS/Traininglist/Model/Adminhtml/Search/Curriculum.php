<?php
/**
 * BS_Traininglist extension
 * 
 * @category       BS
 * @package        BS_Traininglist
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Traininglist
 * @author      Bui Phong
 */
class BS_Traininglist_Model_Adminhtml_Search_Curriculum extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Traininglist_Model_Adminhtml_Search_Curriculum
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_traininglist/curriculum_collection')
            ->addAttributeToFilter('c_history', 0)
            ->addAttributeToFilter(array(
                array('attribute'=>'c_name', 'like'  => '%'.$this->getQuery().'%'),
                array('attribute'=>'c_code', 'like'  => '%'.$this->getQuery().'%'),
            ))
            ->addAttributeToFilter('status', 1)
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $curriculum) {
            $arr[] = array(
                'id'          => 'curriculum/1/'.$curriculum->getId(),
                'type'        => Mage::helper('bs_traininglist')->__('Curriculum'),
                'name'        => $curriculum->getCName().' - '.$curriculum->getCCode(),
                //'description' => $curriculum->getCurriculumName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/traininglist_curriculum/edit',
                    array('id'=>$curriculum->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
