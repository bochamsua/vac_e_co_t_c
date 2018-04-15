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
class BS_Logistics_Block_Adminhtml_Filefolder_Edit_Tab_Foldercontent extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('foldercontentGrid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    protected function _prepareLayout(){
        $this->setChild('add_content_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('New Content'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/logistics_foldercontent/new', array('_current'=>false, 'filefolder_id'=>$this->getFilefolder()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
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
        $collection = Mage::getModel('bs_logistics/foldercontent')
            ->getCollection()->addFieldToFilter('filefolder_id', $this->getFilefolder()->getId());
        $collection->setOrder('position', 'ASC');
        $this->setCollection($collection);
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

        /*$this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('catalog')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );*/
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('catalog')->__('Title'),
                'align'     => 'left',
                'index'     => 'title',
            )
        );


        $this->addColumn(
            'updated_date',
            array(
                'header' => Mage::helper('catalog')->__('Updated Date'),
                'index'  => 'updated_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'position',
            array(
                'header'         => Mage::helper('catalog')->__('Position'),
                'name'           => 'position',
                'editable'       => true,
                'type'          => 'input',
                'renderer'      => 'bs_logistics/adminhtml_helper_column_renderer_input',
                'filter'    => false,
            )
        );


        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('catalog')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_subject')->__('Edit'),
                        'url'     => array('base' => '*/logistics_foldercontent/edit', 'params' => array('popup'=> 1)),
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
        return $this->getUrl('*/*/foldercontentsGrid', array('_current'=>true));
    }

    public function getFilefolder()
    {
        return Mage::registry('current_filefolder');
    }
}
