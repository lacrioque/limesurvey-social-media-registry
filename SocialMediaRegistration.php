<?php

/**
 * Registration through social Media accounts
 *
 * @since 2017-08-07
 * @author Markus Flür
 */
class SocialMediaRegistration extends \ls\pluginmanager\PluginBase
{
    static protected $description = 'Add social media registration for participants';
    static protected $name = 'SocialMediaRegistration';

    protected $storage = 'DbStorage';
    protected $settings = array(
        'info' => array(
            'type' => 'info',
            'content' => '<div class="well col-sm-8"><span class="fa fa-info-circle"></span>&nbsp;&nbsp;If you apply an API key the method will be activated for your customers.</div>'
        ),
        'googleApiKey' => array(
            'type' => 'string',
            'label' => 'Google API key',
            'default' => '',
            'help' => 'Add Google login for your participants'
        ),
        'facebookApiKey' => array(
            'type' => 'string',
            'label' => 'Facebook API key',
            'default' => '',
            'help' => 'Add Facebook login for your participants'
        ),
        'githubApiKey' => array(
            'type' => 'string',
            'label' => 'GitHub API key',
            'default' => '',
            'help' => 'Add GitHub login for your participants'
        ),
        'linkedinApiKey' => array(
            'type' => 'string',
            'label' => 'LinkedIn API key',
            'default' => '',
            'help' => 'Add LinkedIn login for your participants'
        ),
    );

    public function init()
    {
        $this->subscribe('beforeRegisterForm');
    }

    /**
     * Append the social media buttons to the registration form
     */
    public function beforeRegisterForm()
    {
        $event = $this->getEvent();
        $surveyid = $event->get('surveyid');
        $lang = $event->get('lang');
        $aRegistersErrors = $event->get('aRegistersErrors');

        $appendForm = "";
        $this->getFacebook($appendForm);
        $this->getGoogle($appendForm);
        $event->append('registerForm', array('append' => true, 'formAppend' => $appendForm));

    }

    public function getFacebook(&$appendForm){
        $settings = $this->getPluginSettings(true);
        
        if($settings['facebookApiKey']['current'] != ''){
            $script = "\n"
            ."var facebookApiKey = '".$settings['facebookApiKey']['current']."';\n"
            .file_get_contents(dirname(__FILE__).'/assets/fblogin.js')
            ."";
            Yii::app()->clientScript->registerScript('fbcode', $script, CClientScript::POS_BEGIN);
            //$appendForm.="<fb:login-button size=\"large\" scope=\"public_profile,email\" onlogin=\"checkLoginState();\"></fb:login-button>";
            $appendForm.='<div class="fb-login-button" data-max-rows="1" data-size="large" data-button-type="continue_with" ></div>';

        }
                
    }

    public function getGoogle(&$appendForm){
        $settings = $this->getPluginSettings(true);
        
        if($settings['googleApiKey']['current'] != ''){
            Yii::app()->clientScript->registerScriptFile("https://apis.google.com/js/platform.js", CClientScript::POS_BEGIN);
            $script = ""
            ."var googleApiKey = '".$settings['googleApiKey']['current']."';"
            .file_get_contents(dirname(__FILE__).'/assets/googlelogin.js')
            ."";
            Yii::app()->clientScript->registerScript('googlecode',$script, CClientScript::POS_BEGIN);
            $appendForm.="<div class=\"g-signin2\" data-onsuccess=\"onSignIn\"></div>";
        }
                
    }


}
