<?php
/**
 * BS_Subject extension
 * 
 * @category       BS
 * @package        BS_Subject
 * @copyright      Copyright (c) 2015
 */
/**
 * Subject Content admin grid block
 *
 * @category    BS
 * @package     BS_Subject
 * @author Bui Phong
 */
class BS_Subject_Block_Adminhtml_Subject_Edit_Tab_Subjectcontent extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * constructor
     *
     * @access public
     * @author Bui Phong
     */
    public function __construct()
    {
        parent::__construct();
        $this->setId('subjectcontentGrid');
        //$this->setDefaultSort('subcon_order');
        //$this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareLayout(){
        $this->setChild('add_content_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('New Content'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/subject_subjectcontent/new', array('_current'=>false, 'subject_id'=>$this->getSubject()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        $this->setChild('clear_subject_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Clear All Content'),
                    'onclick'   => 'deleteConfirm(\'*** THIS ACTION MUST BE TAKEN VERY CAREFULLY!!! All subject contents WILL BE REMOVED from this subject! Are you sure?\',\''.$this->getUrl('*/subject_subject/clearSub', array('_current'=>false, 'subject_id'=>$this->getSubject()->getId(),'popup'=>true)).'\')'
                ))
        );

        //window.open(url, '','width=1000,height=700,resizable=1,scrollbars=1');
        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_content_button');
            $html.= $this->getChildHtml('clear_subject_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subjectcontent_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_subject/subjectcontent')
            ->getCollection()->addFieldToFilter('subject_id', $this->getSubject()->getId());
        $collection->getSelect()->order('subcon_order');
        $this->setCollection($collection);
        $this->_prepareTotals('subcon_hour');
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subjectcontent_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'subcon_title',
            array(
                'header'    => Mage::helper('bs_subject')->__('Content Title'),
                'align'     => 'left',
                'index'     => 'subcon_title',
                //'editable'       => true,
                //'type'=> 'input',
                //'renderer'      => 'bs_subject/adminhtml_helper_column_renderer_subconname',
                'totals_label'      => Mage::helper('bs_subject')->__('Total hours')
            )
        );
        $this->addColumn(
            'subcon_code',
            array(
                'header'    => Mage::helper('bs_subject')->__('Code'),
                'align'     => 'left',
                'index'     => 'subcon_code',
            )
        );
        

        $this->addColumn(
            'subcon_level',
            array(
                'header' => Mage::helper('bs_subject')->__('Level'),
                'index'  => 'subcon_level',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'subcon_hour',
            array(
                'header' => Mage::helper('bs_subject')->__('Hour'),
                'index'  => 'subcon_hour',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'subcon_order',
            array(
                'header' => Mage::helper('bs_subject')->__('Sort Order'),
                'index'  => 'subcon_order',
                'editable'       => true,
                'type'=> 'input',
                'renderer'      => 'bs_subject/adminhtml_helper_column_renderer_subconinput',
                'totals_label'      => ''

            )
        );




        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_subject')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_subject')->__('Edit'),
                        'url'     => array('base' => '*/subject_subjectcontent/edit', 'params' => array('popup'=> 1)),
                        'field'   => 'id',
                        'onclick'   => 'window.open(this.href,\'\',\'width=1000,height=700,resizable=1,scrollbars=1\'); return false;'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
                'totals_label'      => ''
            )
        );

        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Subject_Block_Adminhtml_Subjectcontent_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {

        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Subject_Model_Subjectcontent
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return '#';//$this->getUrl('*/subject_subjectcontent/edit', array('id' => $row->getId()));
    }

    /**
     * get the grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl('*/*/subjectcontentsGrid', array('_current'=>true));
    }

    public function getSubject()
    {
        return Mage::registry('current_subject');
    }
    protected function _prepareTotals($columns = 'subcon_hour'){
        $columns=explode(',',$columns);
        if(!$columns){
            return;
        }
        $this->_countTotals = true;
        $totals = new Varien_Object();
        $fields = array();
        foreach($columns as $column){
            $fields[$column]    = 0;
        }

        foreach ($this->getCollection() as $item) {
            foreach($fields as $field=>$value){
                $fields[$field]+=$item->getData($field);
            }
        }

        $totals->setData($fields);
        $this->setTotals($totals);
        return;
    }

    protected function _afterToHtml($html)
    {

        $html1 = "<script>

                </script>";
        return parent::_afterToHtml($html);
    }
}
