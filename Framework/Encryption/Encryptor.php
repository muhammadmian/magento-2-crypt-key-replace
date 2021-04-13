<?php

namespace MM\CryptKeyReplace\Framework\Encryption;

use Magento\Framework\App\DeploymentConfig;
use Magento\Framework\Encryption\KeyValidator;
use Magento\Framework\Math\Random;

class Encryptor extends \Magento\Framework\Encryption\Encryptor
{

    const CONFIG_TABLE = "core_config_data";

    /* CONFIG PATHS */
    const XML_CRYPT_OVERRIDE_EN = "system/crypt_key/replace_crypt_key";
    const XML_CRYPT_OVERRIDE_VA = "system/crypt_key/replacement_crypt_key";

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    public function __construct(
        Random $random,
        DeploymentConfig $deploymentConfig,
        KeyValidator $keyValidator = null,
        \Magento\Framework\App\ResourceConnection $resourceConnection
    ){
        $this->resourceConnection = $resourceConnection;

        parent::__construct(
            $random,
            $deploymentConfig,
            $keyValidator
        );
    }

    /**
     * @param string $data
     * @return string
     */
    public function encrypt($data)
    {
        $this->addOldEncryptionKey();
        return parent::encrypt($data);
    }

    /**
     * @param string $data
     * @return string
     * @throws \Exception
     */
    public function decrypt($data)
    {
        $this->addOldEncryptionKey();
        return parent::decrypt($data);
    }

    /**
     * Gets new encryption key configured in DB (if enabled)
     * And adds it as the first Index Key in $keys array
     */
    public function addOldEncryptionKey(){
        try {
            $connection  = $this->resourceConnection->getConnection();
            $tableName   = $connection->getTableName(self::CONFIG_TABLE);
            $enabledQ    = 'SELECT * FROM ' . $tableName . ' where path = "' . self::XML_CRYPT_OVERRIDE_EN. '"';
            $enabled     = $this->resourceConnection->getConnection()->fetchRow($enabledQ);
            if($enabled && is_array($enabled)&& (int)$enabled['value'] === 1){
                $keyQ        = 'SELECT * FROM ' . $tableName . ' where path = "' . self::XML_CRYPT_OVERRIDE_VA. '"';
                $cryptKey    = $this->resourceConnection->getConnection()->fetchRow($keyQ);

                if($cryptKey && is_array($cryptKey)&& (int)$cryptKey['value']) {
                    $key = $cryptKey['value'];
                    if ($key && $key != "" && !in_array($key, $this->keys)) {
                        array_unshift($this->keys, $key);
                    }
                }
            }
        }catch (\Exception $e){
            // Do no add any crypt key if an exception occurs.
        }
    }

}
