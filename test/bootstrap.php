<?php

define('DIR_SF_REPLICA', realpath(dirname(__FILE__).'/..'));

// Replica
require DIR_SF_REPLICA . '/lib/vendor/Replica/include.php';

// Plugin
require DIR_SF_REPLICA . '/lib/image/sfReplicaImageDoctrine.php';

// Doctrine
require_once(DIR_SF_REPLICA . '/../../lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));
Doctrine_Manager::connection('mysql://username:password@localhost/test');
