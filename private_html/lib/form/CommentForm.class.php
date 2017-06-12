<?php

class CommentForm extends BaseForm
{
  public function configure()
  {
    $this->setWidgets(array(
      'author'  => new sfWidgetFormInputText(array(), array('size' => '35', 'maxlength' => '35')),
      'mail'    => new sfWidgetFormInputText(array(), array('size' => '35', 'maxlength' => '35')),
      'web'     => new sfWidgetFormInputText(array(), array('size' => '35', 'maxlength' => '35')),
      'check'   => new sfWidgetFormInputText(array(), array('size' => '10', 'maxlength' => '10')),
      'content' => new sfWidgetFormTextarea(array(), array('cols' => '36', 'rows' => '8'))
    ));

    $this->widgetSchema->setLabels(array(
      'author'    => 'Name *',
      'mail'    => 'E-mail',
      'web'     => 'Website',
      'check'   => 'human?',
      'content' => 'Message *'
    ));

    $this->setValidators(array(
      'author'  => new sfValidatorString(),
      'mail'    => new sfValidatorEmail(array('required' => false)),
      'web'     => new sfValidatorUrl(array('required' => false)),
      'check'   => new sfValidatorInteger(),
      'content' => new sfValidatorString(array('trim' => true, 'min_length' => 5, 'max_length' => 1000))
    ));
    
    $this->widgetSchema->setNameFormat('a_comment[%s]');
    $this->validatorSchema->setPostValidator(
        new sfValidatorCallback(array('callback' => array($this, 'antiBotCheck')))
    );
  }

  public function antiBotCheck($validator, $values)
  {
    // get sum from users flash variable
    $sum = sfContext::getInstance()->getUser()->getAttribute('sum');
    
    if(!(empty($values["check"])) && intval($sum[2]) != $values["check"])
    {
      // sum is not correct, throw an error
      $error = new sfValidatorError($validator, 'Invalid sum');

      // throw an error bound to the password field
      throw new sfValidatorErrorSchema($validator, array('check' => $error));
    }
    
    // password is correct, return the clean values
    return $values;
  }
}
