<?php
/**
 * BS_ImportInstructor extension
 * 
 * @category       BS
 * @package        BS_ImportInstructor
 * @copyright      Copyright (c) 2015
 */
/**
 * Import Instructor admin grid block
 *
 * @category    BS
 * @package     BS_ImportInstructor
 * @author Bui Phong
 */
class BS_ImportInstructor_Block_Adminhtml_Importinstructor_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('importinstructorGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_ImportInstructor_Block_Adminhtml_Importinstructor_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_importinstructor/importinstructor')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_ImportInstructor_Block_Adminhtml_Importinstructor_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_importinstructor')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'curriculum_id',
            array(
                'header'    => Mage::helper('bs_importinstructor')->__('Curriculum'),
                'align'     => 'left',
                'index'     => 'curriculum_id',
            )
        );
        

        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_importinstructor')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_importinstructor')->__('Enabled'),
                    '0' => Mage::helper('bs_importinstructor')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_importinstructor')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_importinstructor')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_importinstructor')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_importinstructor')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_importinstructor')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_ImportInstructor_Block_Adminhtml_Importinstructor_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('importinstructor');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/importinstructor/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_traininglist/importinstructor/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_importinstructor')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_importinstructor')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_importinstructor')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_importinstructor')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_importinstructor')->__('Enabled'),
                                '0' => Mage::helper('bs_importinstructor')->__('Disabled'),
                            )
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
     * @param BS_ImportInstructor_Model_Importinstructor
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
     * @return BS_ImportInstructor_Block_Adminhtml_Importinstructor_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
