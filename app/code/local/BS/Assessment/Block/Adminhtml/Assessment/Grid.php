<?php
/**
 * BS_Assessment extension
 * 
 * @category       BS
 * @package        BS_Assessment
 * @copyright      Copyright (c) 2015
 */
/**
 * Assessment admin grid block
 *
 * @category    BS
 * @package     BS_Assessment
 * @author Bui Phong
 */
class BS_Assessment_Block_Adminhtml_Assessment_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('assessmentGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Assessment_Block_Adminhtml_Assessment_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_assessment/assessment')
            ->getCollection();
        $collection->getSelect()->joinLeft(array('pro'=>'catalog_product_entity'),'course_id = pro.entity_id','sku');
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Assessment_Block_Adminhtml_Assessment_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_assessment')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'content',
            array(
                'header'    => Mage::helper('bs_assessment')->__('Content'),
                'align'     => 'left',
                'index'     => 'content',
            )
        );
        

        $this->addColumn(
            'sku',
            array(
                'header' => Mage::helper('bs_assessment')->__('Course'),
                'index'  => 'sku',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'suffix',
            array(
                'header'    => Mage::helper('bs_assessment')->__('Code Suffix'),
                'align'     => 'left',
                'index'     => 'suffix',
            )
        );
        $this->addColumn(
            'duration',
            array(
                'header' => Mage::helper('bs_assessment')->__('Duration'),
                'index'  => 'duration',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'article',
            array(
                'header' => Mage::helper('bs_assessment')->__('On article'),
                'index'  => 'article',
                'type'  => 'options',
                'options' => Mage::helper('bs_assessment')->convertOptions(
                    Mage::getModel('bs_assessment/assessment_attribute_source_article')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'app_type',
            array(
                'header' => Mage::helper('bs_assessment')->__('App. Type'),
                'index'  => 'app_type',
                'type'  => 'options',
                'options' => Mage::helper('bs_assessment')->convertOptions(
                    Mage::getModel('bs_assessment/assessment_attribute_source_apptype')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'app_cat',
            array(
                'header' => Mage::helper('bs_assessment')->__('App Cat'),
                'index'  => 'app_cat',
                'type'  => 'options',
                'options' => Mage::helper('bs_assessment')->convertOptions(
                    Mage::getModel('bs_assessment/assessment_attribute_source_appcat')->getAllOptions(false)
                )

            )
        );
        $this->addColumn(
            'prepared_date',
            array(
                'header' => Mage::helper('bs_assessment')->__('Prepared Date'),
                'index'  => 'prepared_date',
                'type'=> 'date',

            )
        );


        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_assessment')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_assessment')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_assessment')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_assessment')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_assessment')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Assessment_Block_Adminhtml_Assessment_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('assessment');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/assessment/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/assessment/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_assessment')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_assessment')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_assessment')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_assessment')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_assessment')->__('Enabled'),
                                '0' => Mage::helper('bs_assessment')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'article',
            array(
                'label'      => Mage::helper('bs_assessment')->__('Change On article'),
                'url'        => $this->getUrl('*/*/massArticle', array('_current'=>true)),
                'additional' => array(
                    'flag_article' => array(
                        'name'   => 'flag_article',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_assessment')->__('On article'),
                        'values' => Mage::getModel('bs_assessment/assessment_attribute_source_article')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'app_type',
            array(
                'label'      => Mage::helper('bs_assessment')->__('Change App. Type'),
                'url'        => $this->getUrl('*/*/massAppType', array('_current'=>true)),
                'additional' => array(
                    'flag_app_type' => array(
                        'name'   => 'flag_app_type',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_assessment')->__('App. Type'),
                        'values' => Mage::getModel('bs_assessment/assessment_attribute_source_apptype')
                            ->getAllOptions(true),

                    )
                )
            )
        );
        $this->getMassactionBlock()->addItem(
            'app_cat',
            array(
                'label'      => Mage::helper('bs_assessment')->__('Change App Cat'),
                'url'        => $this->getUrl('*/*/massAppCat', array('_current'=>true)),
                'additional' => array(
                    'flag_app_cat' => array(
                        'name'   => 'flag_app_cat',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_assessment')->__('App Cat'),
                        'values' => Mage::getModel('bs_assessment/assessment_attribute_source_appcat')
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
     * @param BS_Assessment_Model_Assessment
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
     * @return BS_Assessment_Block_Adminhtml_Assessment_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
