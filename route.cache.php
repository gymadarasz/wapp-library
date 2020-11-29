<?php $routes=array (
  'public' => 
  array (
    'GET' => 
    array (
      '' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\LoginTemplateResponder',
        'method' => 'getLoginFormResponse',
      ),
      'login' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\LoginTemplateResponder',
        'method' => 'getLoginFormResponse',
      ),
      'registry' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\RegistryTemplateResponder',
        'method' => 'getRegistryFormResponse',
      ),
      'resend' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\RegistryTemplateResponder',
        'method' => 'getResendResponse',
      ),
      'activate' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\ActivateTemplateResponder',
        'method' => 'getActivateResponse',
      ),
      'reset' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\PasswordResetTemplateResponder',
        'method' => 'getPasswordResetFormResponse',
      ),
      'example' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Example\\Example',
        'method' => 'getPublic',
      ),
    ),
    'POST' => 
    array (
      '' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\LoginTemplateResponder',
        'method' => 'getLoginResponse',
      ),
      'login' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\LoginTemplateResponder',
        'method' => 'getLoginResponse',
      ),
      'registry' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\RegistryTemplateResponder',
        'method' => 'getRegistryResponse',
      ),
      'reset' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\PasswordResetTemplateResponder',
        'method' => 'getPasswordResetRequestResponse',
      ),
      'change' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\PasswordChangeTemplateResponder',
        'method' => 'getPasswordChangeResponse',
      ),
    ),
  ),
  'protected' => 
  array (
    'GET' => 
    array (
      'logout' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Account\\Template\\LogoutTemplateResponder',
        'method' => 'getLogoutResponse',
      ),
      '' => 
      array (
        'class' => 'Madsoft\\Talkbot\\MyScripts',
        'method' => 'viewList',
      ),
      'example' => 
      array (
        'class' => 'Madsoft\\Library\\Responder\\Example\\Example',
        'method' => 'getProtected',
      ),
      'my-scripts/list' => 
      array (
        'class' => 'Madsoft\\Talkbot\\MyScripts',
        'method' => 'viewList',
      ),
      'my-scripts/create' => 
      array (
        'class' => 'Madsoft\\Talkbot\\MyScripts',
        'method' => 'viewCreate',
      ),
      'my-scripts/edit' => 
      array (
        'class' => 'Madsoft\\Talkbot\\MyScripts',
        'method' => 'editScript',
      ),
    ),
    'POST' => 
    array (
      'my-scripts/create' => 
      array (
        'class' => 'Madsoft\\Talkbot\\MyScripts',
        'method' => 'doCreate',
      ),
      'my-scripts/edit' => 
      array (
        'class' => 'Madsoft\\Talkbot\\MyScripts',
        'method' => 'doEdit',
      ),
    ),
  ),
);