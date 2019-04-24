<?php

namespace MagestyApps\GeoIP\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use MagestyApps\GeoIP\Model\MaxMind\Db\Updater;

class InstallData implements InstallDataInterface
{
    /**
     * @var Updater
     */
    private $dbUpdate;

    /**
     * UpdateDatabase constructor.
     *
     * @param Updater $dbUpdate
     */
    public function __construct(
        Updater $dbUpdate
    ) {
        $this->dbUpdate = $dbUpdate;
    }

    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        $setup->startSetup();

        try {
            $this->dbUpdate->download();
            $this->dbUpdate->extract();
        } catch (\Exception $e) {}


        $setup->endSetup();
    }
}