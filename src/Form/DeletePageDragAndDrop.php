<?php

/**
 * @file
 * Contains \Drupal\page_manager_ui\Form\PageDeleteForm.
 */

namespace Drupal\bt_cms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\page_manager\PageInterface;

/**
 * Provides a form for deleting a page entity.
 */
class DeletePageDragAndDrop extends FormBase {

    /**
     * {@inheritdoc}
     */
    public function getFormId() {
        return 'delete_ipe_page_form';
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state, PageInterface $page = NULL) {
        // Get page object
        $form_state->setValue('page', $page);

        // Set actions buttons elements
        $element = [
            'submit' => [
                '#type' => 'submit',
                '#value' => t('Confirm'),
                '#submit' => [[$this, 'submitForm']],
                '#button_type' => 'primary',
                '#weight' => 0,
            ],
            'cancel' => [
                '#type' => 'submit',
                '#value' => t('Cancel'),
                '#submit' => [[$this, 'cancelForm']],
                '#weight' => 1,
            ],
            '#type' => 'actions'
        ];

        // Build form
        $form['actions'] = $element;
        $form['#title'] = t('Are you sure wants delete %name', ['%name' => $page->label()]);

        $form['#attributes']['class'][] = 'confirmation';
        $form['description'] = array('#markup' => t('This action cannot be undone.'));
        $form['confirm'] = array('#type' => 'hidden', '#value' => 1);
        $form['#theme'] = 'confirm_form';

        return $form;
    }

    /**
     * {@inheritdoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state) {
        // Get the page object from $form_state
        $page = $form_state->getBuildInfo()['args'][0];
        $page->delete();
        drupal_set_message(t('The page %name has been removed.', ['%name' => $page->label()]));
        $form_state->setRedirectUrl(Url::fromRoute('bt_page_drag_and_drop'));

    }

  /**
   * {@inheritdoc}
   */
  public function cancelForm(array &$form, FormStateInterface $form_state) {
      $form_state->setRedirectUrl(Url::fromRoute('bt_page_drag_and_drop'));
  }
}