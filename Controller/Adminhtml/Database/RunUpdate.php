<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 *  * See COPYING.txt for license details.
 */

namespace MagestyApps\GeoIP\Controller\Adminhtml\Database;

use Magento\Backend\App\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use MagestyApps\GeoIP\Model\MaxMind\Db\Updater;

class RunUpdate extends Action
{
    const ADMIN_RESOURCE = 'MagestyApps_GeoIP::settings';

    /**
     * @var Updater
     */
    private $databaseUpdater;

    /**
     * @var JsonFactory
     */
    private $jsonFactory;

    /**
     * RunUpdate constructor.
     * @param Action\Context $context
     * @param Updater $databaseUpdater
     */
    public function __construct(
        Action\Context $context,
        Updater $databaseUpdater,
        JsonFactory $jsonFactory
    ) {
        parent::__construct($context);

        $this->databaseUpdater = $databaseUpdater;
        $this->jsonFactory = $jsonFactory;
    }

    /**
     * RunUpdate action
     */
    public function execute()
    {
        $step = $this->getRequest()->getParam('step', false);
        $nextStep = false;
        $success = true;
        $error = false;
        $result = [];

        try {
            if ($step == 'start') {
                $result['text'] = __('Downloading database archive');
                $nextStep = 'download';
            } elseif ($step == 'download') {
                $this->databaseUpdater->download();
                $result['text'] = __('Creating current database backup');
                $nextStep = 'backup';
            } elseif ($step == 'backup') {
                $this->databaseUpdater->createBackup();
                $result['text'] = __('Uncompressing archive');
                $nextStep = 'unpack';
            } elseif ($step == 'unpack') {
                $this->databaseUpdater->extract();
                $result['text'] = __('Deleting temporary files');
                $nextStep = 'delete';
            } elseif ($step == 'delete') {
                unlink($this->databaseUpdater->getUpdateDestination());
                $result['text'] = __('Finished');
                $result['stop'] = true;
                $result['url'] = $this->getUrl('magestyapps_geoip/database/updateSuccess');
            }
        } catch (\Exception $e) {
            $success = false;
            $error = $e->getMessage();
        }

        if ($nextStep) {
            $result['url'] = $this->getUrl('*/*/runUpdate', ['step' => $nextStep]);
        }

        if (!$success) {
            $result['error'] = true;
            $result['text'] = $error ? $error : __('An error occured while updating GeoIP database');
            $result['stop']  = true;
            $result['url']   = '';
        }

        $response = $this->jsonFactory->create();
        $response->setData($result);

        return $response;
    }
}
