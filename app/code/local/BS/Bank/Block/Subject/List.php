<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject list block
 *
 * @category    BS
 * @package     BS_Bank
 * @author Bui Phong
 */
class BS_Bank_Block_Subject_List extends Mage_Core_Block_Template
{
    /**
     * initialize
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $subjects = Mage::getResourceModel('bs_bank/subject_collection')
                         ->addFieldToFilter('status', 1);
        $subjects->setOrder('subject_name', 'asc');
        $this->setSubjects($subjects);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_Bank_Block_Subject_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'bs_bank.subject.html.pager'
        )
        ->setCollection($this->getSubjects());
        $this->setChild('pager', $pager);
        $this->getSubjects()->load();
        return $this;
    }

    /**
     * get the pager html
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getPagerHtml()
    {
        return $this->getChildHtml('pager');
    }
}
