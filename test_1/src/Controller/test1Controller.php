<?php
/*
Rachid Toualbi
*/

namespace Drupal\test_1\Controller;


use Drupal\Core\Database\Database;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\ChangedCommand;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\InvokeCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;


class test1Controller extends FormBase{


public function getFormId() {
    return 'ajax_example_form';
  }

public function buildForm(array $form, FormStateInterface $form_state) {

$connection = \Drupal::database();

$result = $connection->query("SELECT * from rating");

// Pulls entries from the table and add them to the array of options, add an id to the span inside the label to update content later
$options = array();
while ($row = $result->fetchAssoc()) {
$options[$row["idrating"]] = $row["ratingname"]." <span id=opt".$row["idrating"].">(".$row["ratingvalue"].")</span>";
}
//Place the radio buttons
$form['#prefix'] = '<div id=mytest>';
$form['num'] = array(
  '#type' => 'radios',
  '#options' => $options,
  '#description' => t('Your feedback is very important for us...'),
  '#default_value' => $options['punt'],

);

//Ad  the submit button with the ajax callback
	
  $form['sb'] = [
        '#type' => 'submit',
        '#value' => 'save',
		
		 '#ajax' => array(
        'callback' => 'Drupal\test_1\Controller\test1Controller::retval',
        'event' => 'click',
        'progress' => array(
          'type' => 'throbber',
          'message' => 'Saving..',
        ),       
      ),
	  
    ];

$form['#suffix'] = '</div>';
    return $form;
  }
 
 
public function retval(array &$form, FormStateInterface $form_state) {

$ajax_response = new AjaxResponse();

$connection = \Drupal::database();

// Get the new rating after update
$result = $connection->query("SELECT * from rating where idrating='".$_POST['num']."'");

if ($row = $result->fetchAssoc()) {
$val = $row["ratingvalue"];
}

//Update the radio labels and display the thank you message

  $ajax_response->addCommand(new HtmlCommand('#opt'.$_POST['num'],"(".$val.")"));

  $ajax_response->addCommand(new HtmlCommand('#edit-num--wrapper--description','Thank you for your feedback'));

  $ajax_response->addCommand(new InvokeCommand('#edit-num--wrapper--description', 'css', array('color', 'green')));

  return $ajax_response;
  
}
    
  
  public function submitForm(array &$form, FormStateInterface $form_state) {

  // if a value is sent, procede with the update
  
	  if (isset($_POST['num'])) {
	//this message should be after mysql affected rows is called
      drupal_set_message("Thank you");
				
	  $connection = \Drupal::database();

	  $result = $connection->query("update rating set ratingvalue=ratingvalue+1 where idrating ='".$_POST['num']."' ");

		  }
  }
  



  
}
