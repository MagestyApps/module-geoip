<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 *  * See COPYING.txt for license details.
 */

namespace MagestyApps\GeoIP\Observer;

use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Event\Observer;
use Magento\Framework\Message\ManagerInterface;
use MagestyApps\GeoIP\Model\MaxMind\Db\Updater;

class AddDatabaseNotification implements ObserverInterface
{
    /**
     * @var ManagerInterface
     */
    private $messageManager;

    /**
     * @var Updater
     */
    private $dbUpdate;

    /**
     * AddDatabaseNotification constructor.
     * @param ManagerInterface $messageManager
     * @param Updater $dbUpdate
     */
    public function __construct(
        ManagerInterface $messageManager,
        Updater $dbUpdate
    ) {
        $this->messageManager = $messageManager;
        $this->dbUpdate = $dbUpdate;
    }

    /**
     * Show a notice in admin if database file is broken
     *
     * @param Observer $observer
     */
    public function execute(Observer $observer)
    {
        if (!$this->dbUpdate->checkDatabase()) {
            $this->messageManager->addNoticeMessage(
                __('GeoIP database file is broken. Please, update it manually in Stores > Configuration > GeoIP Switcher > GeoIP Database.')
            );
        }
    }
}