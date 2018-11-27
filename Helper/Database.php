<?php

namespace MagestyApps\GeoIP\Helper;

use Magento\Framework\App\Config\Storage\WriterInterface as ConfigWriter;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\Request\Http as HttpRequest;

class Database extends AbstractHelper
{
    const XML_PATH_UPDATED_AT = 'magestyapps_geoip/database/last_update';

    /**
     * @var HttpRequest
     */
    private $httpRequest;

    /**
     * @var ConfigWriter
     */
    private $configModel;

    /**
     * Database constructor.
     * @param Context $context
     * @param HttpRequest $httpRequest
     * @param ConfigWriter $configModel
     */
    public function __construct(
        Context $context,
        HttpRequest $httpRequest,
        ConfigWriter $configModel
    ) {
        parent::__construct($context);

        $this->httpRequest = $httpRequest;
        $this->configModel = $configModel;
    }

    /**
     * Get the folder with GeoIP database
     *
     * @return string
     */
    public function getDatabaseFolder()
    {
        $databaseDir = BP . '/var/geoip_database/';
        if (!file_exists($databaseDir)) {
            mkdir($databaseDir);
        }

        return $databaseDir;
    }

    /**
     * Get local path to MaxMind's database
     *
     * @return string
     */
    public function getDatabasePath()
    {
        return $this->getDatabaseFolder() . 'database.mmdb' ;
    }

    /**
     * Get current visitor's IP address
     *
     * @return string
     */
    public function getCustomerIp()
    {
        $ip = $this->httpRequest->getClientIp();

        if (strpos($ip, ',') !== false) {
            $ipArr = explode(',', $ip);
            $ip = $ipArr[count($ipArr) - 1];
        }

        return trim($ip);
    }

    /**
     * Convert country code to universal format (for internal usage)
     *
     * @param $code
     * @return string
     */
    public function prepareCountryCode($code)
    {
        $code = strtolower($code);
        return $code;
    }

    /**
     * @param $time
     */
    public function setUpdatedAt($time)
    {
        $this->configModel->save(
            self::XML_PATH_UPDATED_AT,
            $time
        );
    }
}
