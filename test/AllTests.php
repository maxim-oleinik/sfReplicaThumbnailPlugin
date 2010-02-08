<?php
require_once(dirname(__FILE__) . '/bootstrap.php');

/**
 * All Tests
 */
class sfReplicaThumbnailPlugin_AllTests extends PHPUnit_Framework_TestSuite
{
    /**
     * TestSuite
     */
    public static function suite()
    {
        $suite = new PHPUnit_Framework_TestSuite('sfReplicaThumbnailPlugin');

        // Replica tests
        $suite->addTestFile(DIR_SF_REPLICA . '/lib/vendor/Replica/test/AllTests.php');

        // Plugin tests
        $runner = new PHPUnit_TextUI_TestRunner(new PHPUnit_Runner_StandardTestSuiteLoader);
        $suite->addTest($runner->getTest(dirname(__FILE__).'/unit'));

        return $suite;
    }

}
