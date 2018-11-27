<?php
/**
 * Copyright © 2018 MagestyApps. All rights reserved.
 *  * See COPYING.txt for license details.
 */

namespace MagestyApps\GeoIP\Block\Adminhtml\System\Config\Database;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class UpdatedAt extends Field
{
    /**
     * @param AbstractElement $element
     * @return string
     */
    protected function _getElementHtml(AbstractElement $element)
    {
        return date('M d,Y \a\t H:i:s', strtotime($element->getValue()));
    }
}
