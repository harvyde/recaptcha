<?php

App::uses('Component', 'Controller');

/**
 * Recaptcha Component
 *
 * @package Harvinder.ReCaptcha.Controller.Component
 * @category Component
 * @link http://bakery.cakephp.org/articles/view/recaptcha-component-helper-for-cakephp
 */
class RecaptchaComponent extends Component
{
  public $publickey = '';
  public $privatekey = '';

  public $is_valid = false;
  public $error = "";

  protected $controller = null;

  public function startup(Controller $controller)
  {

    $this->publickey = Configure::read('Service.recaptcha_public_key', null);
    $this->privatekey = Configure::read('Service.recaptcha_private_key', null);
    $secret = $this->privatekey;

      require_once ROOT . "/plugins/ReCaptcha/Vendor/autoload.php";
    $this->ReCaptcha = new \ReCaptcha\ReCaptcha($secret);

    if (!$this->publickey || !$this->privatekey) {
      die('create settings Service.recaptcha_public_key and Service.recaptcha_private_key');
    }

    Configure::write("Recaptcha.pubKey", $this->publickey);
    Configure::write("Recaptcha.privateKey", $this->privatekey);

    $this->controller = $controller;
    $this->controller->helpers[] = 'ReCaptcha.Recaptcha';
  }

  /**
   * @param $request
   * @return bool
   * this code is taken from loc.sparreyconsulting.com/vendor/google/recaptcha/examples/example-captcha.php
   */
  public function valid($request)
  {
    if (isset($_POST['g-recaptcha-response'])):
//      var_export($_POST);
      $recaptcha = $this->ReCaptcha;
      $resp = $recaptcha->verify($_POST['g-recaptcha-response'], $_SERVER['REMOTE_ADDR']);
      if ($resp->isSuccess()):
        return true;
      else:
        return false;
      endif;
    endif;
  }

}
