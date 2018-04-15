<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2016
 */
/**
 * Trainee Feedback admin grid block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Tfeedback_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('tfeedbackGrid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Tfeedback_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_kst/kstprogress')
            ->getCollection();
        $collection->addFieldToFilter('trainee_feedback', array('notnull'=>true));
        //$collection->getSelect()->joinLeft(array('pro'=>'catalog_product_entity'),'course_id = pro.entity_id','sku');
        $collection->getSelect()->joinLeft(array('sub'=>'bs_kst_kstsubject'),'kstsubject_id = sub.entity_id','name');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Tfeedback_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {

        $this->addColumn(
            'sku',
            array(
                'header'    => Mage::helper('bs_kst')->__('Course'),
                'index'     => 'sku',
                'type'      => 'text',

            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('bs_kst')->__('Subject'),
                'index'     => 'name',
                'type'      => 'text',

            )
        );

        $this->addColumn(
            'position',
            array(
                'header' => Mage::helper('bs_kst')->__('No'),
                'index'  => 'position',
                'type'   => 'number',
                'width'  => '20px'
            )
        );

        $this->addColumn(
            'item_name',
            array(
                'header'    => Mage::helper('bs_kst')->__('Item'),
                'index'     => 'item_name',
                'type'      => 'text',

            )
        );


        $this->addColumn(
            'ac_reg',
            array(
                'header'    => Mage::helper('bs_kst')->__('A/C Reg'),
                'align'     => 'left',
                'index'     => 'ac_reg',
            )
        );

        $this->addColumn(
            'complete_date',
            array(
                'header' => Mage::helper('bs_kst')->__('Complete Date'),
                'index'  => 'complete_date',
                'type'=> 'date',

            )
        );

        $this->addColumn(
            'trainee_feedback',
            array(
                'header'    => Mage::helper('bs_kst')->__('Trainee Feedback'),
                'align'     => 'left',
                'index'     => 'trainee_feedback',
            )
        );


        $this->addExportType('*/*/exportCsv', Mage::helper('bs_kst')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_kst')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_kst')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Tfeedback_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('tfeedback');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_kst/tfeedback/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_kst/tfeedback/delete");

        if($isAllowedDelete){
//            $this->getMassactionBlock()->addItem(
//                'delete',
//                array(
//                    'label'=> Mage::helper('bs_kst')->__('Delete'),
//                    'url'  => $this->getUrl('*/*/massDelete'),
//                    'confirm'  => Mage::helper('bs_kst')->__('Are you sure?')
//                )
//            );
        }

        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_KST_Model_Tfeedback
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return false;//s$this->getUrl('*/*/edit', array('id' => $row->getId()));
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
     * @return BS_KST_Block_Adminhtml_Tfeedback_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
