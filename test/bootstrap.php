<?php

define('DIR_SF_REPLICA', realpath(dirname(__FILE__).'/..'));


// Replica
require_once DIR_SF_REPLICA . '/lib/vendor/Replica/include.php';

// Symfony
$sfDir = DIR_SF_REPLICA . '/../../lib/vendor/symfony/lib';
require_once($sfDir . '/config/sfConfig.class.php');
sfConfig::set('sf_symfony_lib_dir', $sfDir);


// Plugin
require_once DIR_SF_REPLICA . '/lib/sfReplicaThumbnail.php';
require_once DIR_SF_REPLICA . '/lib/image/sfReplicaImageDoctrine.php';
require_once DIR_SF_REPLICA . '/lib/helper/ReplicaHelper.php';

// Doctrine
require_once(DIR_SF_REPLICA . '/../../lib/vendor/symfony/lib/plugins/sfDoctrinePlugin/lib/vendor/doctrine/Doctrine.php');
spl_autoload_register(array('Doctrine', 'autoload'));
Doctrine_Manager::connection('mysql://username:password@localhost/test');

// Test
require_once DIR_SF_REPLICA . '/test/sfReplicaThumbnailTestCase.php';
PHPUnit_Util_Filter::addDirectoryToWhitelist(DIR_SF_REPLICA . '/lib');
