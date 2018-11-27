<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 *  * See COPYING.txt for license details.
 */

namespace MagestyApps\GeoIP\Controller\Adminhtml\Database;

use Magento\Backend\App\Action;

class UpdateSuccess extends Action
{
    const ADMIN_RESOURCE = 'MagestyApps_GeoIP::settings';


    /**
     * Update action
     */
    public function execute()
    {
        $this->messageManager->addSuccessMessage(__('GeoIP database was successfully updated'));
        $this->_redirect('adminhtml/system_config/edit', ['section' => 'magestyapps_geoip']);
    }
}
