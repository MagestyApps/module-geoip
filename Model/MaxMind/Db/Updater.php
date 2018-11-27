<?php

namespace MagestyApps\GeoIP\Model\MaxMind\Db;

use Magento\Framework\Exception\LocalizedException;
use MagestyApps\GeoIP\Helper\Database as DbHelper;
use Magento\Framework\Stdlib\DateTime\DateTime;

class Updater
{
    const UPDATE_SOURCE_CITY = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-City.mmdb.gz';
    const UPDATE_SOURCE_COUNTRY = 'http://geolite.maxmind.com/download/geoip/database/GeoLite2-Country.mmdb.gz';

    private $dbMinSize = 500000; // 500Kb

    /**
     * @var DbHelper
     */
    private $_dbHelper;

    /**
     * @var DateTime
     */
    private $_dateTime;

    /**
     * DbUpdate constructor.
     * @param DbHelper $dbHelper
     * @param DateTime $dateTime
     */
    public function __construct(DbHelper $dbHelper, DateTime $dateTime)
    {
        $this->_dbHelper = $dbHelper;
        $this->_dateTime = $dateTime;
    }

    /**
     * Download database archive from MaxMind's website
     *
     * @return $this
     * @throws \Exception
     */
    public function download()
    {
        $source = $this->getUpdateSource();
        $destination = $this->getUpdateDestination();

        $sourceFile = curl_init($source);
        $newFile = @fopen($destination, "wb");

        if (!$sourceFile) {
            throw new LocalizedException(
                __('DataBase source is temporary unavailable')
            );
        }

        if (!$newFile) {
            throw new LocalizedException(
                __("Can't create new file. Access denied.")
            );
        }

        curl_setopt($sourceFile, CURLOPT_FILE, $newFile);
        $data = curl_exec($sourceFile);

        curl_close($sourceFile);
        fclose($newFile);

        return $this;
    }

    /**
     * Extract archive with GeoIP database
     *
     * @return bool
     */
    public function extract()
    {
        $source = $this->getUpdateDestination();
        $destination = $this->_dbHelper->getDatabasePath();

        $sourceFile = @gzopen($source, "rb");
        $newFile = @fopen($destination, "wb");

        if (!$sourceFile || !$newFile) {
            return false;
        }

        while ($string = gzread($sourceFile, 4096)) {
            fwrite($newFile, $string, strlen($string));
        }

        gzclose($sourceFile);
        fclose($newFile);

        $this->_dbHelper->setUpdatedAt($this->_dateTime->gmtDate());

        return true;
    }

    /**
     * Create backup of current GeoIP database
     *
     * @return bool
     */
    public function createBackup()
    {
        $dbPath = $this->_dbHelper->getDatabasePath();
        if (!file_exists($dbPath)) {
            return true;
        }
        return copy($dbPath, $dbPath . '_backup_' . time());
    }

    /**
     * Get source from which database should be downloaded
     *
     * @return string
     */
    public function getUpdateSource()
    {
        return self::UPDATE_SOURCE_CITY;
    }

    /**
     * Get the file to which database should be uploaded
     *
     * @return string
     */
    public function getUpdateDestination()
    {
        return $this->_dbHelper->getDatabasePath() . '_temp.gz';
    }

    /**
     * Check whether DB file is ok
     *
     * @return bool
     */
    public function checkDatabase()
    {
        $path = $this->_dbHelper->getDatabasePath();
        if (!file_exists($path)) {
            return false;
        }

        if (filesize($path) < $this->dbMinSize) {
            return false;
        }

        return true;
    }
}
