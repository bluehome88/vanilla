<?php
/**
 * @author Todd Burry <todd@vanillaforums.com>
 * @copyright 2009-2017 Vanilla Forums Inc.
 * @license GPLv2
 */

namespace VanillaTests;

use Garden\EventManager;
use Garden\Container\Container;
use Vanilla\Models\InstallModel;

/**
 * Allow a class to test against
 */
trait SiteTestTrait {
    /**
     * @var Container
     */
    private static $container;

    /**
     * @var array
     */
    private static $siteInfo;

    /**
     * @var array The addons to install.
     */
    protected static $addons = ['vanilla', 'conversations', 'stubcontent'];

    /**
     * Create the container for the site.
     *
     * @return Container Returns a container.
     */
    protected static function createContainer() {
        $folder = strtolower(EventManager::classBasename(get_called_class()));
        $bootstrap = new Bootstrap("http://vanilla.test/$folder");

        $container = new Container();
        $bootstrap->run($container);

        return $container;
    }

    /**
     * Install the site.
     */
    public static function setupBeforeClass() {
        $dic = self::$container = static::createContainer();


        /* @var TestInstallModel $installer */
        $installer = $dic->get(TestInstallModel::class);

        $installer->uninstall();
        $result = $installer->install([
            'site' => ['title' => EventManager::classBasename(get_called_class())],
            'addons' => static::$addons
        ]);

        self::$siteInfo = $result;

        /* @var \Gdn_Session $session */
        $session = $dic->get(\Gdn_Session::class);
        $session->start(self::$siteInfo['adminUserID'], false, false);
    }

    /**
     * Cleanup the container after testing is done.
     */
    public static function teardownAfterClass() {
        Bootstrap::cleanup(self::container());
    }

    /**
     * Get the container for the site info.
     *
     * @return Container Returns a container with site dependencies.
     */
    protected static function container() {
        return self::$container;
    }
}
