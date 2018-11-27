<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 *  * See COPYING.txt for license details.
 */

namespace MagestyApps\GeoIP\Cron;

use MagestyApps\GeoIP\Model\MaxMind\Db\Updater;

class UpdateDatabase
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

    /**
     * Delete all product flat tables for not existing stores
     *
     * @return void
     */
    public function execute()
    {
        $this->dbUpdate->download();
        //$this->dbUpdate->createBackup();
        $this->dbUpdate->extract();

        unlink($this->dbUpdate->getUpdateDestination());
    }
}
