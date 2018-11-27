<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 *  * See COPYING.txt for license details.
 */

namespace MagestyApps\GeoIP\Controller\Adminhtml\Database;

use Magento\Backend\App\Action;

class Update extends Action
{
    const ADMIN_RESOURCE = 'MagestyApps_GeoIP::settings';

    /**
     * Update action
     */
    public function execute()
    {
        $this->_view->loadLayout();
        $this->_view->renderLayout();
    }
}
