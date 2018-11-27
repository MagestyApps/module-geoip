<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 *  * See COPYING.txt for license details.
 */

namespace MagestyApps\GeoIP\Block\Adminhtml\System\Config\Database;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Update extends Field
{
    /**
     * Adds update button to config field
     *
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        $url = $this->getUrl('magestyapps_geoip/database/update');

        /** @var \Magento\Backend\Block\Widget\Button $button */
        $button = $this->getLayout()->createBlock('Magento\Backend\Block\Widget\Button');
        $button->setType('button')
            ->setLabel(__('Update Database'))
            ->setOnClick("window.location = '".$url."'")
            ->toHtml();

        return $button->toHtml();
    }
}
