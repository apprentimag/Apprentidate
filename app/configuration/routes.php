<?php

return array (
	array (
		'route'      => '/creer_un_evenement',
		'controller' => 'event',
		'action'     => 'create'
	),
	array (
		'route'      => '/ajouter_utilisateur\?e=([\d\w]{6})',
		'controller' => 'event',
		'action'     => 'add_user',
		'params'     => array ('id')
	),
	array (
		'route'      => '/ajouter_commentaire\?e=([\d\w]{6})',
		'controller' => 'event',
		'action'     => 'add_comment',
		'params'     => array ('id')
	),
	array (
		'route'      => '/([\d\w]{6})',
		'controller' => 'event',
		'action'     => 'see',
		'params'     => array ('id')
	),
);
