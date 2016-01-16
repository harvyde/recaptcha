<?php

App::uses('AppHelper', 'View/Helper');

/**
 * @package Croogo.Croogo.View.Helper
 * @link http://bakery.cakephp.org/articles/view/recaptcha-component-helper-for-cakephp
 */
class RecaptchaHelper extends AppHelper {
	public $helpers = array('Form');

	public function display_form($output_method = 'return', $error = null, $use_ssl = false) {
		$this->Form->unlockField('g-recaptcha-response');
//		$this->Form->unlockField('recaptcha_challenge_field');
//		$this->Form->unlockField('recaptcha_response_field');
		$data = $this->__form(Configure::read("Recaptcha.pubKey"),$error,$use_ssl);
		if ($output_method == "echo")
			echo $data;
		else
			return $data;
	}


	/**
	 * Gets the challenge HTML (javascript and non-javascript version).
	 * This is called from the browser, and the resulting reCAPTCHA HTML widget
	 * is embedded within the HTML form it was called from.
	 * @param string $pubkey A public key for reCAPTCHA
	 * @param string $error The error given by reCAPTCHA (optional, default is null)
	 * @param boolean $use_ssl Should the request be made over ssl? (optional, default is false)
	 * @return string - The HTML to be embedded in the user's form.
	 */
	private function __form($pubkey, $error = null, $use_ssl = false) {
		if ($pubkey == null || $pubkey == '') {
			die ("To use reCAPTCHA you must get an API key from <a href='http://recaptcha.net/api/getkey'>http://recaptcha.net/api/getkey</a>");
		}

		if ($use_ssl) {
			$server = Configure::read('Recaptcha.apiSecureServer');
		} else {
			$server = Configure::read('Recaptcha.apiServer');
		}

		$errorpart = "";
		if ($error) {
		   $errorpart = "&amp;error=" . $error;
		}

		$r = "<div class=\"g-recaptcha\" data-sitekey=\"%s\"></div>
            <script type=\"text/javascript\"
                    src=\"https://www.google.com/recaptcha/api.js?hl=en\"></script>";

		$r = __($r, $pubkey);
		return $r;
	}

}
