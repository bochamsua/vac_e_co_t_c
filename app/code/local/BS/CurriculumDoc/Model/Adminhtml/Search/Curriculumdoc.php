<?php
/**
 * BS_CurriculumDoc extension
 * 
 * 
 * @category       BS
 * @package        BS_CurriculumDoc
 * @copyright      Copyright (c) 2015
 * @license        http://opensource.org/licenses/mit-license.php MIT License
 */
/**
 * Admin search model
 *
 * @category    BS
 * @package     BS_CurriculumDoc
 * @author      Bui Phong
 */
class BS_CurriculumDoc_Model_Adminhtml_Search_Curriculumdoc extends Varien_Object
{
    /**
     * Load search results
     *
     * @access public
     * @return BS_CurriculumDoc_Model_Adminhtml_Search_Curriculumdoc
     * @author Bui Phong
     */
    public function load()
    {
        $arr = array();
        if (!$this->hasStart() || !$this->hasLimit() || !$this->hasQuery()) {
            $this->setResults($arr);
            return $this;
        }
        $collection = Mage::getResourceModel('bs_curriculumdoc/curriculumdoc_collection')
            ->addFieldToFilter('cdoc_name', array('like' => $this->getQuery().'%'))
            ->setCurPage($this->getStart())
            ->setPageSize($this->getLimit())
            ->load();
        foreach ($collection->getItems() as $curriculumdoc) {
            $arr[] = array(
                'id'          => 'curriculumdoc/1/'.$curriculumdoc->getId(),
                'type'        => Mage::helper('bs_curriculumdoc')->__('Curriculum Document'),
                'name'        => $curriculumdoc->getCdocName(),
                'description' => $curriculumdoc->getCdocName(),
                'url' => Mage::helper('adminhtml')->getUrl(
                    '*/curriculumdoc_curriculumdoc/edit',
                    array('id'=>$curriculumdoc->getId())
                ),
            );
        }
        $this->setResults($arr);
        return $this;
    }
}
