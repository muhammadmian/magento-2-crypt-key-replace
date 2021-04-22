<?php
/**
 * Copyright © MuhammadMian.com, Inc. All rights reserved.
 */
namespace MM\CryptKeyReplace\Model\Config\Backend;

class Crypt extends \Magento\Framework\App\Config\Value
{
    const STR_PLACEHOLDER_TEXT = "***********CRYPT*KEY*SET***********";
    /**
     * Hide Set Crypt Key.
     *
     * @return void
     */
    protected function _afterLoad()
    {
        $value = $this->getValue();
        if ($value && isset($value) && is_string($value)) {
            $this->setValue("");
        }
    }

    /**
     * Processing object before save data
     *
     * @return $this
     */
    public function beforeSave()
    {
        if (is_string($this->getValue()) && $this->getValue() === self::STR_PLACEHOLDER_TEXT) {
            $this->setValue($this->getOldValue());
        }
        parent::beforeSave();
        return $this;
    }
}
