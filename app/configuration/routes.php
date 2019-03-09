<?php

return array (
	array (
		'route'      => '/login',
		'controller' => 'index',
		'action'     => 'login'
	),
	array (
		'route'      => '/logout',
		'controller' => 'index',
		'action'     => 'logout'
	),
	array (
		'route'      => '/about',
		'controller' => 'index',
		'action'     => 'about'
	),
	
	/////
	array (
		'route'      => '/events/new',
		'controller' => 'event',
		'action'     => 'create'
	),
	array (
		'route'      => '/events/(.{6})/edit',
		'controller' => 'event',
		'action'     => 'edit',
		'params'     => array ('id')
	),
	array (
		'route'      => '/events/(.{6})/users',
		'controller' => 'event',
		'action'     => 'add_user',
		'params'     => array ('id')
	),
	array (
		'route'      => '/events/(.{6})/usersdel/(\d+)',
		'controller' => 'event',
		'action'     => 'delete_user',
		'params'     => array ('idEvent', 'id')
	),
	array (
		'route'      => '/events/(.{6})/comments',
		'controller' => 'event',
		'action'     => 'add_comment',
		'params'     => array ('id')
	),
	array (
		'route'      => '/events/(.{6})/commentsdel/([\w\d]+)',
		'controller' => 'event',
		'action'     => 'delete_comment',
		'params'     => array ('idEvent', 'id')
	),
	array (
		'route'      => '/events/(.{6})',
		'controller' => 'event',
		'action'     => 'see',
		'params'     => array ('id')
	),
	array (
		'route'      => '/events/(.{6})/auth',
		'controller' => 'event',
		'action'     => 'auth',
		'params'     => array ('id')
	),
	array (
		'route'      => '/events/(.{6})/polls/new',
		'controller' => 'poll',
		'action'     => 'create',
		'params'     => array ('id')
	),
	/////
	array (
		'route'      => '/polls/new',
		'controller' => 'poll',
		'action'     => 'create'
	),
	array (
		'route'      => '/polls/create',
		'controller' => 'poll',
		'action'     => 'createalone'
	),
	array (
		'route'      => '/polls/(.{6})',
		'controller' => 'poll',
		'action'     => 'see',
		'params'     => array ('id')
	),
	array (
		'route'      => '/polls/(.{6})/vote',
		'controller' => 'poll',
		'action'     => 'vote',
		'params'     => array ('id')
	),
);
