<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress admin grid block
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstprogress_Grid extends Mage_Adminhtml_Block_Widget_Grid
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
        $this->setId('kstprogressGrid');
        $this->setDefaultSort('position');
        $this->setDefaultDir('ASC');
        $this->setSaveParametersInSession(true);
        $this->setUseAjax(true);

        $courseId = $this->getRequest()->getParam('course_id', false);
        if($courseId){

            $this->setDefaultFilter( array('course_id'=>$courseId) );
        }
    }

    /**
     * prepare collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstprogress_Grid
     * @author Bui Phong
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('bs_kst/kstprogress')
            ->getCollection();

        if($this->getRequest()->getParam('course_id', false)){

            $courseId = $this->getRequest()->getParam('course_id');
            $collection->addFieldToFilter('course_id', $courseId);
        }

        $currentUser = Mage::getSingleton('admin/session')->getUser();
        $userName = $currentUser->getUsername();

        $resource = Mage::getSingleton('core/resource');
        $readConnection = $resource->getConnection('core_read');

        $tableProgress = $resource->getTableName('bs_kst/kstprogress');

        $pUsername = $readConnection->fetchCol("SELECT DISTINCT username FROM {$tableProgress} WHERE username = '{$userName}'");

        if(count($pUsername)){//we found that this is trainee username, process permision

            $checkUsername = $pUsername[0];

            $collection->addFieldToFilter('username', array('eq'=>$checkUsername));

        }



        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * prepare grid collection
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstprogress_Grid
     * @author Bui Phong
     */
    protected function _prepareColumns()
    {
        $requestData = Mage::helper('adminhtml')->prepareFilterString($this->getRequest()->getParam('filter'));
        $curriculumId = 0;
        $courseId = 0;
        if($this->getRequest()->getParam('course_id')){
            $courseId = $this->getRequest()->getParam('course_id');

            $currentUser = Mage::getSingleton('admin/session')->getUser();
            $userId = $currentUser->getId();
            $userName = $currentUser->getUsername();
            //get Group Id
            $groupId = 0;
            $member = Mage::getModel('bs_kst/kstmember')->getCollection()->addFieldToFilter('username', $userName)->getFirstItem();
            if($member->getId()){
                $groupId = $member->getKstgroupId();
            }

            $courses = Mage::getResourceModel('catalog/product_collection')->addAttributeToFilter('entity_id', $courseId);

            $instructors = Mage::getModel('bs_instructor/instructor')->getCollection()->addProductFilter($courseId);
            $instructors = $instructors->toOptionHash();

            $courses = $courses->toSkuOptionHash();

            $groups = Mage::getModel('bs_kst/kstgroup')->getCollection()->addFieldToFilter('course_id', $courseId);

            if($groupId > 0){
                $groups->addFieldToFilter('entity_id', $groupId);
            }

            $groups = $groups->toOptionHash();

            $this->addColumn(
                'course_id',
                array(
                    'header'    => Mage::helper('bs_kst')->__('Course'),
                    'index'     => 'course_id',
                    'type'      => 'options',
                    'options'   => $courses,
                )
            );

            $this->addColumn(
                'group_id',
                array(
                    'header'    => Mage::helper('bs_kst')->__('Group'),
                    'index'     => 'group_id',
                    'type'      => 'options',
                    'options'   => $groups
                )
            );

            $curriculumIds = array();

            $curriculum = Mage::getModel('bs_traininglist/curriculum')->getCollection()->addProductFilter($courseId)->getFirstItem();

            if($curriculum->getId()){
                $curriculumIds[] = $curriculum->getId();
            }


            $subs = array();
            if(count($curriculumIds)){
                $subs = Mage::getResourceModel('bs_kst/kstsubject_collection');
                $subs->addFieldToFilter('curriculum_id', array('in'=>$curriculumIds));
                $subs = $subs->toOptionHash();
            }


            $this->addColumn(
                'kstsubject_id',
                array(
                    'header'    => Mage::helper('bs_kst')->__('Subject'),
                    'index'     => 'kstsubject_id',
                    'type'      => 'options',
                    'options'   => $subs,

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
                'item_ref',
                array(
                    'header'    => Mage::helper('bs_kst')->__('Ref Doc'),
                    'index'     => 'item_ref',
                )
            );
            $this->addColumn(
                'item_taskcode',
                array(
                    'header'    => Mage::helper('bs_kst')->__('Task Code'),
                    'index'     => 'item_taskcode',
                )
            );
            $this->addColumn(
                'item_taskcat',
                array(
                    'header'    => Mage::helper('bs_kst')->__('Task Cat'),
                    'index'     => 'item_taskcat',
                )
            );
            $this->addColumn(
                'item_applicable',
                array(
                    'header'    => Mage::helper('bs_kst')->__('Applicable For'),
                    'index'     => 'item_applicable',
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
                'instructor_id',
                array(
                    'header'    => Mage::helper('bs_kst')->__('Instructor'),
                    'index'     => 'instructor_id',
                    'type'      => 'options',
                    'options'   => $instructors,
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
                'status',
                array(
                    'header'  => Mage::helper('bs_kst')->__('Status'),
                    'index'   => 'status',
                    'type'    => 'options',
                    'options' => array(
                        '1' => Mage::helper('bs_kst')->__('Complete'),
                        '0' => Mage::helper('bs_kst')->__('Incomplete'),
                    )
                )
            );


            //$this->addExportType('*/*/exportCsv', Mage::helper('bs_kst')->__('CSV'));
            $this->addExportType('*/*/exportExcel', Mage::helper('bs_kst')->__('Excel'));
            //$this->addExportType('*/*/exportXml', Mage::helper('bs_kst')->__('XML'));


        }





        return parent::_prepareColumns();
    }

    /**
     * prepare mass action
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstprogress_Grid
     * @author Bui Phong
     */
    protected function _prepareMassaction()
    {
        $this->setMassactionIdField('entity_id');
        $this->getMassactionBlock()->setFormFieldName('kstprogress');

        $isAllowedEdit = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstprogress/edit");
        $isAllowedDelete = Mage::getSingleton('admin/session')->isAllowed("bs_kst/kstprogress/delete");

        if($isAllowedDelete){
            $this->getMassactionBlock()->addItem(
                'delete',
                array(
                    'label'=> Mage::helper('bs_kst')->__('Delete'),
                    'url'  => $this->getUrl('*/*/massDelete'),
                    'confirm'  => Mage::helper('bs_kst')->__('Are you sure?')
                )
            );
        }

        if($isAllowedEdit){

            $instructors = array();
            if($this->getRequest()->getParam('course_id', false)) {
                $courseId = $this->getRequest()->getParam('course_id');

                $instructors = Mage::getModel('bs_instructor/instructor')->getCollection()->addProductFilter($courseId);
                $instructors = $instructors->toOptionHash();

            }


            $this->getMassactionBlock()->addItem(
                'instructor',
                array(
                    'label'      => Mage::helper('bs_kst')->__('Update Instructor'),
                    'url'        => $this->getUrl('*/*/massInstructor', array('_current'=>true)),
                    'additional' => array(
                        'instructor' => array(
                            'name'   => 'instructor',
                            'type'   => 'select',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_kst')->__('Instructor'),
                            'values' => $instructors
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'aircraft',
                array(
                    'label'      => Mage::helper('bs_kst')->__('Update A/C'),
                    'url'        => $this->getUrl('*/*/massAircraft', array('_current'=>true)),
                    'additional' => array(
                        'aircraft' => array(
                            'name'   => 'aircraft',
                            'type'   => 'text',
                            'class'  => 'required-entry',
                            'label'  => Mage::helper('bs_kst')->__('A/C Reg'),
                        )
                    )
                )
            );

            $this->getMassactionBlock()->addItem(
                'complete_date',
                array(
                    'label'      => Mage::helper('bs_kst')->__('Update Complete Date'),
                    'url'        => $this->getUrl('*/*/massCompleteDate', array('_current'=>true)),
                    'additional' => array(
                        'complete_date' => array(
                            'name'   => 'complete_date',
                            'type'   => 'date',
                            'class'  => 'required-entry',
                            'image' => $this->getSkinUrl('images/grid-cal.gif'),
                            'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                            'label'  => Mage::helper('bs_kst')->__('Date'),

                        )
                    )
                )
            );

//            $this->getMassactionBlock()->addItem(
//                'status',
//                array(
//                    'label'      => Mage::helper('bs_kst')->__('Update status'),
//                    'url'        => $this->getUrl('*/*/massStatus', array('_current'=>true)),
//                    'additional' => array(
//                        'status' => array(
//                            'name'   => 'status',
//                            'type'   => 'select',
//                            'class'  => 'required-entry',
//                            'label'  => Mage::helper('bs_kst')->__('Status'),
//                            'values' => array(
//                                '1' => Mage::helper('bs_kst')->__('Complete'),
//                                //'9999' => Mage::helper('bs_kst')->__('Incomplete'),
//                                //Make this 9999 to avoid hacking
//                            )
//                        )
//                    )
//                )
//            );

            $this->getMassactionBlock()->addItem(
                'inmass',
                array(
                    'label'      => Mage::helper('bs_kst')->__('Update Everything At Once'),
                    'url'        => $this->getUrl('*/*/edit', array('_current'=>true, 'course_id'=>$courseId)),
                )
            );





        }
        return $this;
    }

    /**
     * get the row url
     *
     * @access public
     * @param BS_KST_Model_Kstprogress
     * @return string
     * @author Bui Phong
     */
    public function getRowUrl($row)
    {
        return false;//$this->getUrl('*/*/edit', array('id' => $row->getId()));
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
     * @return BS_KST_Block_Adminhtml_Kstprogress_Grid
     * @author Bui Phong
     */
    protected function _afterLoadCollection()
    {
        $this->getCollection()->walk('afterLoad');
        parent::_afterLoadCollection();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('kstprogressGrid_filter_kstsubject_id') != undefined){
                        $('kstprogressGrid_filter_kstsubject_id').observe('change', function(){
                            kstprogressGridJsObject.doFilter();
                        });
                    }

                    if($('kstprogressGrid_filter_course_id') != undefined){
                        $('kstprogressGrid_filter_course_id').observe('change', function(){
                            $('kstprogressGrid_filter_kstsubject_id').selectedIndex = 0;
                            kstprogressGridJsObject.doFilter();
                        });
                    }

                    if($('kstprogressGrid_filter_group_id') != undefined){
                        $('kstprogressGrid_filter_group_id').observe('change', function(){
                            kstprogressGridJsObject.doFilter();
                        });
                    }

                    $$('table.data tr td.last').each(function(el){
                        if($(el).innerHTML.indexOf('Complete') != -1){
                            $(el).up('tr').setStyle({'background-color':'green', 'color':'#fff'});
                        }

                    });




                </script>";
        return parent::_afterToHtml($html); // TODO: Change the autogenerated stub
    }
}
