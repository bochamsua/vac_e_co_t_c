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
class BS_InstructorInfo_Block_Adminhtml_Instructor_Edit_Tab_Infor extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('infor_grid');
       // $this->setDefaultSort('position');
        //$this->setDefaultDir('DESC');
        $this->setUseAjax(true);

    }

    protected function _prepareLayout(){
        $this->setChild('add_info_button',
            $this->getLayout()->createBlock('adminhtml/widget_button')
                ->setData(array(
                    'label'     => Mage::helper('adminhtml')->__('Add Record'),
                    'onclick'   => 'window.open(\''.$this->getUrl('*/instructorinfo_info/new', array('_current'=>false, 'instructor_id'=>$this->getInstructor()->getId(),'popup'=>true)).'\',\'\',\'width=1000,height=700,resizable=1,scrollbars=1\')'
                ))
        );

        parent::_prepareLayout();
    }


    public function getMainButtonsHtml()
    {
        $html = '';
        if($this->getFilterVisibility()){
            $html.= $this->getChildHtml('add_info_button');
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
        $collection = Mage::getResourceModel('bs_instructorinfo/info_collection');
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
            'compliance_with',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Compliance With'),
                'index'  => 'compliance_with',
                'type'  => 'options',
                'options' => Mage::helper('bs_instructorinfo')->convertOptions(
                    Mage::getModel('bs_instructorinfo/info_attribute_source_compliancewith')->getAllOptions(false)
                )

            )
        );

        $this->addColumn(
            'approved_course',
            array(
                'header'    => Mage::helper('bs_instructorinfo')->__('Approved Course'),
                'align'     => 'left',
                'index'     => 'approved_course',
            )
        );



        $this->addColumn(
            'approved_function',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Approved Function'),
                'index'  => 'approved_function',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'approved_doc',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Approved Doc'),
                'index'  => 'approved_doc',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'approved_date',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Approved Date'),
                'index'  => 'approved_date',
                'type'=> 'date',

            )
        );
        $this->addColumn(
            'expire_date',
            array(
                'header' => Mage::helper('bs_instructorinfo')->__('Expire Date'),
                'index'  => 'expire_date',
                'type'=> 'date',

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
                        'url'     => array('base' => '*/instructorinfo_info/edit', 'params' => array('popup'=> 1)),
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
            '*/*/infosGrid',
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
