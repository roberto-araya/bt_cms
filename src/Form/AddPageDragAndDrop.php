<?php

namespace Drupal\bt_cms\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\page_manager\entity\Page;
use Drupal\Core\Url;

/**
 * Add a IPE panels page.
 */
class AddPageDragAndDrop extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'add_ipe_page_form';

  }

  /**
   * Clean strings.
   *
   * @param string $text
   *   Text to clean.
   *
   * @return mixed
   *   Text cleaned
   */
  public function cleanString($text) {
    // 1) convert á ô => a o.
    $text = preg_replace("/[áàâãªä]/u", "a", $text);
    $text = preg_replace("/[ÁÀÂÃÄ]/u", "A", $text);
    $text = preg_replace("/[ÍÌÎÏ]/u", "I", $text);
    $text = preg_replace("/[íìîï]/u", "i", $text);
    $text = preg_replace("/[éèêë]/u", "e", $text);
    $text = preg_replace("/[ÉÈÊË]/u", "E", $text);
    $text = preg_replace("/[óòôõºö]/u", "o", $text);
    $text = preg_replace("/[ÓÒÔÕÖ]/u", "O", $text);
    $text = preg_replace("/[úùûü]/u", "u", $text);
    $text = preg_replace("/[ÚÙÛÜ]/u", "U", $text);
    $text = preg_replace("/[’‘‹›‚]/u", "'", $text);
    $text = preg_replace("/[“”«»„]/u", '"', $text);
    $text = str_replace("–", "-", $text);
    $text = str_replace(" ", " ", $text);
    $text = str_replace("ç", "c", $text);
    $text = str_replace("Ç", "C", $text);
    $text = str_replace("ñ", "n", $text);
    $text = str_replace("Ñ", "N", $text);

    // 2) Translation CP1252. &ndash; => -.
    $trans = get_html_translation_table(HTML_ENTITIES);
    // Single Low-9 Quotation Mark.
    $trans[chr(130)] = '&sbquo;';
    // Latin Small Letter F With Hook.
    $trans[chr(131)] = '&fnof;';
    // Double Low-9 Quotation Mark.
    $trans[chr(132)] = '&bdquo;';
    // Horizontal Ellipsis.
    $trans[chr(133)] = '&hellip;';
    // Dagger.
    $trans[chr(134)] = '&dagger;';
    // Double Dagger.
    $trans[chr(135)] = '&Dagger;';
    // Modifier Letter Circumflex Accent.
    $trans[chr(136)] = '&circ;';
    // Per Mille Sign.
    $trans[chr(137)] = '&permil;';
    // Latin Capital Letter S With Caron.
    $trans[chr(138)] = '&Scaron;';
    // Single Left-Pointing Angle Quotation Mark.
    $trans[chr(139)] = '&lsaquo;';
    // Latin Capital Ligature OE.
    $trans[chr(140)] = '&OElig;';
    // Left Single Quotation Mark.
    $trans[chr(145)] = '&lsquo;';
    // Right Single Quotation Mark.
    $trans[chr(146)] = '&rsquo;';
    // Left Double Quotation Mark.
    $trans[chr(147)] = '&ldquo;';
    // Right Double Quotation Mark.
    $trans[chr(148)] = '&rdquo;';
    // Bullet.
    $trans[chr(149)] = '&bull;';
    // En Dash.
    $trans[chr(150)] = '&ndash;';
    // Em Dash.
    $trans[chr(151)] = '&mdash;';
    // Small Tilde.
    $trans[chr(152)] = '&tilde;';
    // Trade Mark Sign.
    $trans[chr(153)] = '&trade;';
    // Latin Small Letter S With Caron.
    $trans[chr(154)] = '&scaron;';
    // Single Right-Pointing Angle Quotation Mark.
    $trans[chr(155)] = '&rsaquo;';
    // Latin Small Ligature OE.
    $trans[chr(156)] = '&oelig;';
    // Latin Capital Letter Y With Diaeresis.
    $trans[chr(159)] = '&Yuml;';
    // Euro currency symbol.
    $trans['euro'] = '&euro;';
    ksort($trans);

    foreach ($trans as $k => $v) {
      $text = str_replace($v, $k, $text);
    }

    // 3) remove <p>, <br/> ...
    $text = strip_tags($text);

    // 4) &amp; => & &quot; => '.
    $text = html_entity_decode($text);

    // 5) remove Windows-1252 symbols like "TradeMark", "Euro"...
    $text = preg_replace('/[^(\x20-\x7F)]*/', '', $text);

    $targets = array('\r\n', '\n', '\r', '\t');
    $results = array(" ", " ", " ", "");
    $text = str_replace($targets, $results, $text);

    // Stop words.
    $commonWords = array(
      'a',
      'un',
      'una',
      'unas',
      'unos',
      'la',
      'lo',
      'las',
      'los',
      'que',
      'en',
      'de',
      'por',
      'y',
      'o',
      'con',
      'es',
    );

    $text = strtolower($text);
    $text = preg_replace('/\b(' . implode('|', $commonWords) . ')\b/', '', $text);
    $text = preg_replace('/  */', '_', $text);

    return ($text);
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['title'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Title'),
      '#maxlength' => 255,
      '#default_value' => '',
      '#required' => TRUE,
    ];
    $form['show_title'] = [
      '#type' => 'checkbox',
      '#title' => $this->t('Show title'),
      '#default_value' => 0,
    ];
    $form['description'] = [
      '#type' => 'textarea',
      '#title' => $this->t('Administrative description'),
      '#default_value' => '',
    ];
    $form['path'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Path'),
      '#maxlength' => 255,
      '#default_value' => '',
      '#required' => TRUE,
      '#element_validate' => [[$this, 'validatePath']],
    ];
    $form['create'] = array(
      '#type' => 'submit',
      '#value' => $this->t('Create page'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Create a new page entity.
    $page_values = array();
    $page = new Page($page_values, 'page');
    $page->set('description', $form_state->getValue('description'));
    $page->set('path', $form_state->getValue('path'));
    $page->set('use_admin_theme', FALSE);
    $page->set('id', 'ipe_' . $this->cleanString($form_state->getValue('title')));
    $page->set('label', $form_state->getValue('title'));

    // Create a page variant entity.
    $page_variant = \Drupal::entityManager()
      ->getStorage('page_variant')
      ->create(
        [
          'variant' => 'panels_variant',
          'page' => $page->id(),
          'id' => "{$page->id()}-panels_variant-0",
          'label' => $form_state->getValue('title'),
        ]
      );
    $page_variant->set('variant', 'panels_variant');
    $page_variant->set(
      'variant_settings',
      [
        'id' => 'panels_variant',
        'label' => NULL,
        'wight' => 0,
        'layout' => 'radix_boxton',
        'page_title' => ($form_state->getValue('show_title') == TRUE ? $form_state->getValue('title') : ''),
        'builder' => 'ipe',
      ]
    );
    $page_variant->setPageEntity($page);
    $page_variant->save();

    $page->addVariant($page_variant);
    $page->save();
    $form_state->setRedirectUrl(Url::fromRoute('bt_page_drag_and_drop'));
  }

  /**
   * {@inheritdoc}
   */
  public function validatePath(&$element, FormStateInterface $form_state) {
    // Ensure the path has a leading slash.
    $value = '/' . trim($element['#value'], '/');
    $form_state->setValueForElement($element, $value);

    // Ensure each path is unique.
    $path_query = \Drupal::entityQuery('page');
    $path_query->condition('path', $value);

    $path = $path_query->execute();
    if ($path) {
      $form_state->setErrorByName('path', $this->t('The page path must be unique.'));
    }
  }

}
