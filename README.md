
#  Magento 2 Crypt Key Replace

[![Releases](https://img.shields.io/github/v/release/muhammadmian/magento-2-crypt-key-replace.svg)](https://github.com/muhammadmian/magento-2-crypt-key-replace/releases)

### Tested On

- Magento 2.3
- Magento 2.4



###Problem Explained

If you've migrated magento from one server to another, but you cannot directly edit your env.php file to change your Crypt Key, this module will help you to do that in admin. 

Magento's built in function to replace Crypt key will not work if you are still working with data that has already been encrypted with a different Crypt key.

This is because magento adds an index position of the crypt key used from the crypt key array to the encrypted data. 

**E.g. Old Setup:**  
Crypt Key Array: 
```
[ 
    0 => OLD_CRYPT_KEY 
]
```  
Previously Encrypted Data Value: **[0]**:34JHSDJ3H4JHDFJH34JJHFJHDFJHDF **(SUCCESS)**

**New Setup:**  
If you use Magento's built-in feature to add a new crypt key, it will be added as the 2nd index of the array.  
Crypt Key Array:
```
[ 
    0 => NEW_CRYPT_KEY
    1 => OLD_CRYPT_KEY 
]
```  
Previously Encrypted Data Value: **[0]**:34JHSDJ3H4JHDFJH34JJHFJHDFJHDF **(FAIL)**

The index 0 (Zero) of the crypt key array is no longer the old encryption key thus it will fail to decrypt the data and give up.

### Solution

This module adds the encryption key configured in magento admin as the first item in the crypt key array. 

``PLEASE NOTE: Any new data encrypted with the new encryption key will not decrypt``

### How to use

Simply install the module using composer.  
```composer require muhammadmian/magento-2-crypt-key-replace```

**Admin Config**  
Set the following config to **YES** and configure your new crypt key  
`Store > Configuration > Advanced > System > Enable Crypt Key Override`

Enjoy. 
