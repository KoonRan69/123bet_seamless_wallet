<?php

$action = [
  'login' => [
    'action_type' => 'login',
    'message' => 'Login'
  ],
  'register' => [
    'action_type' => 'register',
    'message' => 'Register by'
  ],
  'register_agent' => [
    'action_type' => 'register Agent',
    'message' => 'Register by Agent'
  ],
  'logout' => [
    'action_type' => 'logout',
    'message' => 'Logout by'
  ],
  'add_member' => [
    'action_type' => 'add_member',
    'message' => 'Add member by'
  ],
  'transfer' => [
    'action_type' => 'transfer',
    'message' => 'Transfer '
  ],
  'withdraw' => [
    'action_type' => 'withdraw',
    'message' => 'Withdraw '
  ],
];

return [
  'action' => $action,
  'sbobet' => [
    'url' => 'https://ex-api-yy.xxttgg.com',
    'CompanyKey' => '2F4B29F27A4E497AB8FC779944E54A01', 
    'prefix' => 'now_',
    'ServerId' => 'YY-ADMIN',
   	'lang' => 'en',
    'currency' => 'VND',
    'Agent' => 'Betnow_Sbobet_VND',
    'content_type' => 'application/json',
  ],

];
