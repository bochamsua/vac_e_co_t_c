<?php
/**
 * BS_KST extension
 * 
 * @category       BS
 * @package        BS_KST
 * @copyright      Copyright (c) 2015
 */
/**
 * Progress edit form tab
 *
 * @category    BS
 * @package     BS_KST
 * @author Bui Phong
 */
class BS_KST_Block_Adminhtml_Kstprogress_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
    /**
     * prepare the form
     *
     * @access protected
     * @return BS_KST_Block_Adminhtml_Kstprogress_Edit_Tab_Form
     * @author Bui Phong
     */
    protected function _prepareForm()
    {
        $form = new Varien_Data_Form();
        $form->setHtmlIdPrefix('kstprogress_');
        $form->setFieldNameSuffix('kstprogress');
        $this->setForm($form);

        $kstprogressIds = $this->getRequest()->getParam('kstprogress', false);

        $add = array();
        if($kstprogressIds){
            foreach ($kstprogressIds as $kstprogressId) {
                $add[] = Mage::getSingleton('bs_kst/kstprogress')->load($kstprogressId)->getPosition();
            }
            $fieldset = $form->addFieldset(
                'kstprogress_form',
                array('legend' => Mage::helper('bs_kst')->__('Update progress for task numbers: %s', implode(",", $add)))
            );
        }else {
            $fieldset = $form->addFieldset(
                'kstprogress_form',
                array('legend' => Mage::helper('bs_kst')->__('Fill out Items'))
            );
        }




        $courseId = $this->getRequest()->getParam('course_id');


        $currentProgress = Mage::registry('current_kstprogress');


        if (!$courseId){
            $courseId = $currentProgress->getCourseId();
        }

        $values = Mage::getModel('catalog/product')->getCollection()
            ->addAttributeToFilter('status',1)
            ->addAttributeToFilter('sku',array(array('like'=>'%KST%'), array('like'=>'%CRS%')))
            ->addAttributeToFilter('course_report',0);



        if($courseId){
            $values->addAttributeToFilter('entity_id',$courseId);
        }
        $values = $values->toSkuOptionArray();





        if($kstprogressIds){


            $fieldset->addField(
                'course_id',
                'text',
                array(
                    'label'     => Mage::helper('bs_kst')->__('Course'),
                    'name'      => 'course_id',
                    'readonly'  => true

                )
            );

            $fieldset->addField(
                'task_ids',
                'textarea',
                array(
                    'label'     => Mage::helper('bs_kst')->__('Tasks'),
                    'name'      => 'task_ids',
                    'readonly'  => true
                )
            );

            $fieldset->addField(
                'ac_reg',
                'text',
                array(
                    'label'     => Mage::helper('bs_kst')->__('A/C REG'),
                    'name'      => 'ac_reg',
                    'note'      => 'For example: A385'

                )
            );

            $instructors = Mage::getModel('bs_instructor/instructor')->getCollection()->addProductFilter($courseId);
            $instructors = $instructors->toFullOptionArray();

            $fieldset->addField(
                'instructor',
                'select',
                array(
                    'label'     => Mage::helper('bs_kst')->__('Instructor'),
                    'name'      => 'instructor',
                    'values'    => $instructors

                )
            );

            $fieldset->addField(
                'complete_date',
                'date',
                array(
                    'label' => Mage::helper('bs_kst')->__('Complete Date'),
                    'name'  => 'complete_date',
                    'image' => $this->getSkinUrl('images/grid-cal.gif'),
                    'format'  => Mage::app()->getLocale()->getDateFormat(Mage_Core_Model_Locale::FORMAT_TYPE_SHORT),
                    'note'      => 'For example: 31/03/2016'
                )
            );


            /*$fieldset->addField(
                'task_status',
                'select',
                array(
                    'label'  => Mage::helper('bs_kst')->__('Status'),
                    'name'   => 'task_status',
                    'values' => array(
                        array(
                            'value' => 1,
                            'label' => Mage::helper('bs_material')->__('Complete'),
                        ),
                        array(
                            'value' => 0,
                            'label' => Mage::helper('bs_material')->__('Incomplete'),
                        ),
                    ),
                )
            );*/

            $fieldset->addField(
                'trainee_feedback',
                'textarea',
                array(
                    'label'     => Mage::helper('bs_kst')->__('Your Feedback'),
                    'name'      => 'trainee_feedback',
                    'note'      => 'Leave this empty if everything was good'

                )
            );



        }else {
            $fieldset->addField(
                'course_id',
                'select',
                array(
                    'label'     => Mage::helper('bs_kst')->__('Course'),
                    'name'      => 'course_id',
                    'required'  => true,
                    'class' => 'required-entry',
                    'values'    => $values,
                )
            );




            $fieldset->addField(
                'number_group',
                'select',
                array(
                    'label'  => Mage::helper('bs_kst')->__('Number of Groups?'),
                    'name'   => 'number_group',
                    'values' => array(
                        array(
                            'value' => 1,
                            'label' => Mage::helper('bs_kst')->__('1'),
                        ),
                        array(
                            'value' => 2,
                            'label' => Mage::helper('bs_kst')->__('2'),
                        ),
                        array(
                            'value' => 3,
                            'label' => Mage::helper('bs_kst')->__('3'),
                        ),
                    ),
                )
            );

            /*$fieldset->addField(
                'vaeco_ids',
                'text',
                array(
                    'label'     => Mage::helper('bs_kst')->__('VAECO IDs'),
                    'name'      => 'vaeco_ids',
                    'required'  => true,
                    'class' => 'required-entry',
                    'note'      => 'VAECO IDs for each group. First ID is the leader of the group. Separate each group by "|". Separate each ID by ",". For example: VAE02907,VAE02908 | VAE02351,VAE02352'

                )
            );*/

            /*$fieldset->addField(
                'create_user',
                'select',
                array(
                    'label'  => Mage::helper('bs_kst')->__('Status'),
                    'name'   => 'create_user',
                    'values' => array(
                        array(
                            'value' => 1,
                            'label' => Mage::helper('bs_kst')->__('Yes'),
                        ),
                        array(
                            'value' => 0,
                            'label' => Mage::helper('bs_kst')->__('No'),
                        ),
                    ),
                )
            );*/
        }




        $formValues = Mage::registry('current_kstprogress')->getDefaultValues();
        if (!is_array($formValues)) {
            $formValues = array();
        }
        if (Mage::getSingleton('adminhtml/session')->getKstprogressData()) {
            $formValues = array_merge($formValues, Mage::getSingleton('adminhtml/session')->getKstprogressData());
            Mage::getSingleton('adminhtml/session')->setKstprogressData(null);
        } elseif (Mage::registry('current_kstprogress')) {
            $formValues = array_merge($formValues, Mage::registry('current_kstprogress')->getData());
        }

        if($kstprogressIds){
            $kstprogressIds = implode(",", $kstprogressIds);
            $formValues = array_merge($formValues, array('task_ids'=>$kstprogressIds));
        }
        $formValues = array_merge($formValues, array('course_id'=>$this->getRequest()->getParam('course_id')));

        $form->setValues($formValues);
        return parent::_prepareForm();
    }

    protected function _afterToHtml($html)
    {
        $html .= "<script>
                    if($('kstprogress_task_ids') != undefined){
                        $('kstprogress_task_ids').up('tr').setStyle({'display': 'none'});
                        if($('kstprogress_course_id') != undefined){
                            $('kstprogress_course_id').up('tr').setStyle({'display': 'none'});
                        }
                    }
                    
                    Event.observe(document, 'dom:loaded', function(e) {

                        updateGroupBlocks($('kstprogress_course_id')[$('kstprogress_course_id').selectedIndex].value, $('kstprogress_number_group')[$('kstprogress_number_group').selectedIndex].value);

                        $('kstprogress_course_id').observe('change', function(){
                            updateGroupBlocks($('kstprogress_course_id')[$('kstprogress_course_id').selectedIndex].value, $('kstprogress_number_group')[$('kstprogress_number_group').selectedIndex].value);
                        });
                        $('kstprogress_number_group').observe('change', function(){
                            updateGroupBlocks($('kstprogress_course_id')[$('kstprogress_course_id').selectedIndex].value, $('kstprogress_number_group')[$('kstprogress_number_group').selectedIndex].value);
                        });
                    });
                    
                    function updateGroupBlocks(course_id, number_group){
                        new Ajax.Request('".$this->getUrl('*/kst_kstprogress/getGroups')."', {
                            method : 'post',
                            parameters: {
                                'course_id'   : course_id,
                                'number_group': number_group
                            },
                            onSuccess : function(transport){
                                try{
                                    response = eval('(' + transport.responseText + ')');
                                } catch (e) {
                                    response = {};
                                }
                                if(response.groups){

                                    if($('kstprogress_kstprogress_after') != undefined){
                                        $('kstprogress_kstprogress_after').innerHTML = response.groups;
                                    }else {
                                        $('kstprogress_kstprogress_form').insert({ after: '<div id=\'kstprogress_kstprogress_after\'' + response.groups + '</div>' });
                                    }

                                }else {
                                    alert('Please check your data');
                                }
                
                            },
                            onFailure : function(transport) {
                                alert('Please check your data')
                            }
                        });
                    }




                </script>";

        return parent::_afterToHtml($html);
    }
}
