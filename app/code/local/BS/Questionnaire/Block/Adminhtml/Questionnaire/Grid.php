<?php
/**
 * BS_Questionnaire extension
 * 
 * @category       BS
 * @package        BS_Questionnaire
 * @copyright      Copyright (c) 2015
 */
/**
 * Questionnaire admin grid block
 *
 * @category    BS
 * @package     BS_Questionnaire
 * @author Bui Phong
 */
class BS_Questionnaire_Block_Adminhtml_Questionnaire_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('questionnaireGrid');
        $this->setDefaultSort('entity_id');
        $this->setDefaultDir('DESC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_Questionnaire_Block_Adminhtml_Questionnaire_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_questionnaire/questionnaire')
            ->getCollection();
        
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_Questionnaire_Block_Adminhtml_Questionnaire_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $this->addColumn(
            'entity_id',
            array(
                'header' => Mage::helper('bs_questionnaire')->__('Id'),
                'index'  => 'entity_id',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'name',
            array(
                'header'    => Mage::helper('bs_questionnaire')->__('Content'),
                'align'     => 'left',
                'index'     => 'name',
            )
        );
        

        $this->addColumn(
            'course_id',
            array(
                'header' => Mage::helper('bs_questionnaire')->__('Course'),
                'index'  => 'course_id',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'subject_id',
            array(
                'header' => Mage::helper('bs_questionnaire')->__('Subject'),
                'index'  => 'subject_id',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'instructor_id',
            array(
                'header' => Mage::helper('bs_questionnaire')->__('Instructor'),
                'index'  => 'instructor_id',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'number_of_questions',
            array(
                'header' => Mage::helper('bs_questionnaire')->__('Number of Questions'),
                'index'  => 'number_of_questions',
                'type'=> 'number',

            )
        );
        $this->addColumn(
            'number_of_times',
            array(
                'header' => Mage::helper('bs_questionnaire')->__('Number of Questionnaires'),
                'index'  => 'number_of_times',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'import_bank',
            array(
                'header' => Mage::helper('bs_questionnaire')->__('Import to Question Bank?'),
                'index'  => 'import_bank',
                'type'    => 'options',
                    'options'    => array(
                    '1' => Mage::helper('bs_questionnaire')->__('Yes'),
                    '0' => Mage::helper('bs_questionnaire')->__('No'),
                )

            )
        );
        $this->addColumn(
            'note',
            array(
                'header' => Mage::helper('bs_questionnaire')->__('Note'),
                'index'  => 'note',
                'type'=> 'text',

            )
        );
        $this->addColumn(
            'status',
            array(
                'header'  => Mage::helper('bs_questionnaire')->__('Status'),
                'index'   => 'status',
                'type'    => 'options',
                'options' => array(
                    '1' => Mage::helper('bs_questionnaire')->__('Enabled'),
                    '0' => Mage::helper('bs_questionnaire')->__('Disabled'),
                )
            )
        );

        $this->addColumn(
            'action',
            array(
                'header'  =>  Mage::helper('bs_questionnaire')->__('Action'),
                'width'   => '100',
                'type'    => 'action',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('bs_questionnaire')->__('Edit'),
                        'url'     => array('base'=> '*/*/edit'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'is_system' => true,
                'sortable'  => false,
            )
        );
        $this->addExportType('*/*/exportCsv', Mage::helper('bs_questionnaire')->__('CSV'));
        $this->addExportType('*/*/exportExcel', Mage::helper('bs_questionnaire')->__('Excel'));
        $this->addExportType('*/*/exportXml', Mage::helper('bs_questionnaire')->__('XML'));
        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_Questionnaire_Block_Adminhtml_Questionnaire_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('questionnaire');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_exam/questionnaire/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_exam/questionnaire/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_questionnaire')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_questionnaire')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){
            $this->getMassactionBlock()->addItem(
                'status',
                array(
                    'label'      => Mage::helper('bs_questionnaire')->__('Change status'),
                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
                    'additional' => array(
                        'status' => array(
                            'name'   => 'status',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_questionnaire')->__('Status'),
                            'values' => array(
                                '1' => Mage::helper('bs_questionnaire')->__('Enabled'),
                                '0' => Mage::helper('bs_questionnaire')->__('Disabled'),
                            )
                        )
                    )
                )
            );




        $this->getMassactionBlock()->addItem(
            'import_bank',
            array(
                'label'      => Mage::helper('bs_questionnaire')->__('Change Import to Question Bank?'),
                'url'        => $this->getUrl('*/*/massImportBank', array('_current'=>true)),
                'additional' => array(
                    'flag_import_bank' => array(
                        'name'   => 'flag_import_bank',
                        'type'   => 'select',
                        'class'  => 'required-entry',
                        'label'  => Mage::helper('bs_questionnaire')->__('Import to Question Bank?'),
                        'values' => array(
                                '1' => Mage::helper('bs_questionnaire')->__('Yes'),
                                '0' => Mage::helper('bs_questionnaire')->__('No'),
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
     * @param BS_Questionnaire_Model_Questionnaire
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
     * @return BS_Questionnaire_Block_Adminhtml_Questionnaire_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }
}
