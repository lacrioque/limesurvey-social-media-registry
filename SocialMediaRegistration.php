<?php

/**
 * Some extra quick-menu items to ease everyday usage
 *
 * @since 2016-04-22
 * @author Olle Härstedt
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

    private $buttons = array();

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


    }

    public function getFacebook(&$appendForm){
        $settings = $this->getPluginSettings(true);

        if($settings['facebookApiKey'] != ''){
            $script = "<script>"
            ."var facebookApiKey = '".$settings['facebookApiKey']."';"
            .include('./assets/fblogin.js')
            ."</script>";
            Yii::app()->clientScript->registerScript('fbcode',$script);
            $appendForm.="<fb:login-button scope=\"public_profile,email\" onlogin=\"checkLoginState();\"></fb:login-button>";

        }
                
    }

    public function getGoogle(&$appendForm){
        $settings = $this->getPluginSettings(true);

        if($settings['googleApiKey'] != ''){
            Yii::app()->clientScript->registerScriptFile("https://apis.google.com/js/platform.js");
            $script = "<script>"
            ."var googleApiKey = '".$settings['googleApiKey']."';"
            .include('./assets/googlelogin.js')
            ."</script>";
            Yii::app()->clientScript->registerScript('fbcode',$script);
            $appendForm.="<div class=\"g-signin2\" data-onsuccess=\"onSignIn\"></div>";

        }
                
    }


}
