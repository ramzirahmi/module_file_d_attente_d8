<?php

namespace Drupal\file_attente\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormBase;



class FormUnpublishNode extends FormBase
{

    public function getFormId()
    {
        return 'Unpublished_node_forms';
    }

    public function buildForm(array $form, FormStateInterface $form_state)
    {

        $node_storage=\Drupal::entityTypeManager()->getStorage('node');
        $nodes=$node_storage->loadMultiple();
        foreach ($nodes as $node) {
            $content[$node->get('nid')->value] = $node->get('title')->value;
            }
        $form['node'] = [
            '#type' => 'select',
            '#title' => $this->t('Node'),
            '#options' =>$content,
            '#required' => 'TRUE',
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12'],
        ];
        $form['status'] = [
            '#type' => 'select',
            '#title' => $this->t('Status'),
            '#options' => [
                true => $this->t('Published'),
                false => $this->t('UnPublished'),
            ],
            '#required' => 'TRUE',
            '#wrapper_attributes' => ['class' => 'col-md-6 col-xs-12'],
        ];
        $form['submit'] = [
            '#type' => 'submit',
            '#value' => $this->t('Submit'),
        ];

        return $form;
    }

    public function validateForm(array &$form, FormStateInterface $form_state)
    {

    }

    public function submitForm(array &$form, FormStateInterface $form_state)
    {
        $node_storage=\Drupal::entityTypeManager()->getStorage('node');
        drupal_set_message($this->t('The node @node will @status on next file d attente progrissing', array(
            '@node'=>$node_storage->load($form_state->getValue('node'))->get('title')->value,
            '@status'=>$form_state->getValue('status')==true? 'Published':'Unpublished',
        )));
        $data['nid']=$form_state->getValue('node');
        $data['status']=$form_state->getValue('status');
        $queue=\Drupal::queue('node_queue');
        $queue->createQueue();
        $queue->createItem($data);
    }

}