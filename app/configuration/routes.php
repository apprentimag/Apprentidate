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
		'route'      => '/supprimer_utilisateur\?e=([\d\w]{6})&id=(\d+)',
		'controller' => 'event',
		'action'     => 'delete_user',
		'params'     => array ('idEvent', 'id')
	),
	array (
		'route'      => '/ajouter_commentaire\?e=([\d\w]{6})',
		'controller' => 'event',
		'action'     => 'add_comment',
		'params'     => array ('id')
	),
	array (
		'route'      => '/supprimer_commentaire\?e=([\d\w]{6})&id=([\w\d]+)',
		'controller' => 'event',
		'action'     => 'delete_comment',
		'params'     => array ('idEvent', 'id')
	),
	array (
		'route'      => '/([\d\w]{6})',
		'controller' => 'event',
		'action'     => 'see',
		'params'     => array ('id')
	),
);
