<?php
/**
 * BS_Instructor extension
 * 
 * @category       BS
 * @package        BS_Instructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_Instructor
 * @author      Bui Phong
 */
class BS_Instructor_Model_Adminhtml_Search_Instructor extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_Instructor_Model_Adminhtml_Search_Instructor
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_instructor/instructor_collection')
            ->addAttributeToFilter(array(
                array('attribute'=>'iname', 'like'  => '%'.$this->getQuery().'%'),
                array('attribute'=>'iusername', 'like'  => '%'.$this->getQuery().'%'),
                array('attribute'=>'ivaecoid', 'like'  => '%'.$this->getQuery().'%'),
            ))
            ->addAttributeToSelect('iphone')
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $instructor) {
            $arr[] = array(
                'id'          => 'instructor/1/'.$instructor->getId(),
                'type'        => Mage::helper('bs_instructor')->__('Instructor'),
                'name'        => $instructor->getIname(),
                'description' => $instructor->getIvaecoid().' - '. $instructor->getIphone(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/instructor_instructor/edit',
                    array('id'=>$instructor->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
