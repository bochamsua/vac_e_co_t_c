<?php
/**
 * BS_Logistics extension
 * 
 * @category       BS
 * @package        BS_Logistics
 * @copyright      Copyright (c) 2015
 */
/**
 * Classroom/Examroom admin grid block
 *
 * @category    BS
 * @package     BS_Logistics
 * @author Bui Phong
 */
class BS_Logistics_Block_Adminhtml_Classroom_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('classroomGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Classroom_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_logistics/classroom')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Classroom_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        /*$this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_logistics')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );*/
        $this->addColumn(
            'classroom_name',
            array(
                'header'    => Mage::helper('bs_logistics')->__('Name'),
                'align'     => 'left',
                'index'     => 'classroom_name',
            )
        );
        

        $this->addColumn(
            'classroom_code',
            array(
                'header' => Mage::helper('bs_logistics')->__('Code'),
                'index'  => 'classroom_code',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'classroom_location',
            array(
                'header' => Mage::helper('bs_logistics')->__('Location'),
                'index'  => 'classroom_location',
                'type'  => 'options',
                'options' => Mage::helper('bs_logistics')->convertOptions(
                    Mage::getModel('bs_logistics/classroom_attribute_source_classroomlocation')->getAllOptions(false)
                )

            )
        );
       /* $this->addColumn(
            'classroom_area',
            array(
                'header' => Mage::helper('bs_logistics')->__('Area'),
                'index'  => 'classroom_area',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_logistics')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_logistics')->__('Enabled'),
                    '0' => Mage::helper('bs_logistics')->__('Disabled'),
                )
            )
        );*/

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_logistics')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_logistics')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_logistics')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_logistics')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_logistics')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Classroom_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('classroom');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/classroom/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_logistics/classroom/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_logistics')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_logistics')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_logistics')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_logistics')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_logistics')->__('Enabled'),
                                '0' => Mage::helper('bs_logistics')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'classroom_location',
            array(
                'label'      => Mage::helper('bs_logistics')->__('Change Location'),
                'url'        => $this->getUrl('*/*/massClassroomLocation', array('_current'=>true)),
                'additional' => array(
                    'flag_classroom_location' => array(
                        'name'   => 'flag_classroom_location',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_logistics')->__('Location'),
                        'values' => Mage::getModel('bs_logistics/classroom_attribute_source_classroomlocation')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_Logistics_Model_Classroom
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/edit', array('id' => $row->getId()));
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
        return $this->getUrl('*/*/grid', array('_current'=>true));
    }

    /**
     * after collection load
     *
     * @access protected
     * @return BS_Logistics_Block_Adminhtml_Classroom_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
