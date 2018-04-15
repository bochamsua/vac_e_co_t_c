<?php
/**
 * BS_Register extension
 * 
 * @category       BS
 * @package        BS_Register
 * @copyright      Copyright (c) 2015
 */
/**
 * Course Schedule tab on product edit form
 *
 * @category    BS
 * @package     BS_Register
 * @author Bui Phong
 */
class BS_InstructorInfo_Block_Adminhtml_Instructor_Edit_Tab_Otherinfor extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Set grid params
     *
     * @access public
     * @author Bui Phong
     */

    public function __construct()
    {
        parent::__construct();
        $this->setId('otherinfor_grid');
       // $this->setDefaultSort('position');
        //$this->setDefaultDir('DESC');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout(){
        $this->setChild('add_otherinfo_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add Record'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/instructorinfo_otherinfo/new', array('_current'=>false, 'instructor_id'=>$this->getInstructor()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_otherinfo_button');
            $html.= $this->getResetFilterButtonHtml();
            $html.= $this->getSearchButtonHtml();
        }
        return $html;

    }

    /**
     * prepare the schedule collection
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('bs_instructorinfo/otherinfo_collection');
        if ($this->getInstructor()->getId()) {
            $collection->addFieldToFilter('instructor_id', $this->getInstructor()->getId());
        }
        $this->setCollection($collection);
        parent::_prepareCollection();
        return $this;
    }

    /**
     * prepare mass action grid
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        return $this;
    }

    /**
     * prepare the grid columns
     *
     * @access protected
     * @return BS_Register_Block_Adminhtml_Catalog_Product_Edit_Tab_Schedule
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'title',
            array(
                'header'    => Mage::helper('bs_instructorinfo')->__('Training Description'),
                'align'     => 'left',
                'index'     => 'title',
            )
        );



        $this->addColumn(
            'country',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Country'),
                'index'  => 'country',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'start_date',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Start Date'),
                'index'  => 'start_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'end_date',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('End Date'),
                'index'  => 'end_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'cert_info',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Cert.#/Evidence'),
                'index'  => 'cert_info',
                'type'=> 'text',

            )
        );

        $this->addColumn('action',
            array(
                'header'    => Mage::helper('catalog')->__('Action'),
                'width'     => '50px',
                'type'      => 'action',
                'getter'     => 'getId',
                'actions'   => array(
                    array(
                        'caption' => 'Edit',
                        'url'     => array('base' => '*/instructorinfo_otherinfo/edit', 'params' => array('popup'=> 1)),
                        'field'   => 'id',
                        'onclick'   => 'window.open(this.href,\'\',\'width=1000,height=700,resizable=1,scrollbars=1\'); return false;'
                    )
                ),
                'filter'    => false,
                'sortable'  => false
            )
        );

        return parent::_prepareColumns();
    }



    public function getRowUrl($item)
    {
        return '#';//$this->getUrl('*/register_attendance/edit', array('id' => $item->getId()));
    }

    /**
     * get grid url
     *
     * @access public
     * @return string
     * @author Bui Phong
     */
    public function getGridUrl()
    {
        return $this->getUrl(
            '*/*/otherinfosGrid',
            array(
                'id'=>$this->getInstructor()->getId()
            )
        );
    }

    /**
     * get the current product
     *
     * @access public
     * @return Mage_Catalog_Model_Product
     * @author Bui Phong
     */
    public function getInstructor()
    {
        return Mage::registry('current_instructor');
    }
}
