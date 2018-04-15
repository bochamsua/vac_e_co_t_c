<?php
/**
 * BS_Bank extension
 * 
 * @category       BS
 * @package        BS_Bank
 * @copyright      Copyright (c) 2015
 */
/**
 * Answer list block
 *
 * @category    BS
 * @package     BS_Bank
 * @author Bui Phong
 */
class BS_Bank_Block_Answer_List extends Mage_Core_Block_Template
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
        $answers = Mage::getResourceModel('bs_bank/answer_collection')
                         ->addFieldToFilter('status', 1);
        $answers->setOrder('question_id', 'asc');
        $this->setAnswers($answers);
    }

    /**
     * prepare the layout
     *
     * @access protected
     * @return BS_Bank_Block_Answer_List
     * @author Bui Phong
     */
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        $pager = $this->getLayout()->createBlock(
            'page/html_pager',
            'bs_bank.answer.html.pager'
        )
        ->setCollection($this->getAnswers());
        $this->setChild('pager', $pager);
        $this->getAnswers()->load();
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
