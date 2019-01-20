<?php

namespace Drupal\test_1\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Provides a 'Test1' Block.
 *
 * @Block(
 *   id = "test_1",
 *   admin_label = @Translation("test1 block"),
 *   category = @Translation("test1 block"),
 * )
 */
class Test1Block extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
	  
    $form = \Drupal::formBuilder()->getForm('Drupal\test_1\Controller\test1Controller');

    return $form;	  
  }

}
