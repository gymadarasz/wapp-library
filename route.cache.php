<?php $routes=array (
  'public' => 
  array (
    'GET' => 
    array (
      '' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\LoginTemplateResponder',
        1 => 'getLoginFormResponse',
      ),
      'login' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\LoginTemplateResponder',
        1 => 'getLoginFormResponse',
      ),
      'registry' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\RegistryTemplateResponder',
        1 => 'getRegistryFormResponse',
      ),
      'resend' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\RegistryTemplateResponder',
        1 => 'getResendResponse',
      ),
      'activate' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\ActivateTemplateResponder',
        1 => 'getActivateResponse',
      ),
      'reset' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\PasswordResetTemplateResponder',
        1 => 'getPasswordResetFormResponse',
      ),
      'example' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Example\\Example',
        1 => 'getPublic',
      ),
    ),
    'POST' => 
    array (
      '' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\LoginTemplateResponder',
        1 => 'getLoginResponse',
      ),
      'login' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\LoginTemplateResponder',
        1 => 'getLoginResponse',
      ),
      'registry' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\RegistryTemplateResponder',
        1 => 'getRegistryResponse',
      ),
      'reset' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\PasswordResetTemplateResponder',
        1 => 'getPasswordResetRequestResponse',
      ),
      'change' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\PasswordChangeTemplateResponder',
        1 => 'getPasswordChangeResponse',
      ),
    ),
  ),
  'protected' => 
  array (
    'GET' => 
    array (
      'logout' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Account\\Template\\LogoutTemplateResponder',
        1 => 'getLogoutResponse',
      ),
      '' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Example\\Example',
        1 => 'getProtected',
      ),
      'example' => 
      array (
        0 => 'Madsoft\\Library\\Responder\\Example\\Example',
        1 => 'getProtected',
      ),
      'my-scripts/list' => 
      array (
        0 => 'Madsoft\\Talkbot\\MyScripts',
        1 => 'viewList',
      ),
      'my-scripts/create' => 
      array (
        0 => 'Madsoft\\Talkbot\\MyScripts',
        1 => 'viewCreate',
      ),
    ),
    'POST' => 
    array (
      'my-scripts/create' => 
      array (
        0 => 'Madsoft\\Talkbot\\MyScripts',
        1 => 'doCreate',
      ),
    ),
  ),
);