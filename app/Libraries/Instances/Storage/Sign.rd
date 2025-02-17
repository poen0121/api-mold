Documentation PHP:

 >> Information

    Title		: Storage Sign
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    04-14-2020		Poen		03-10-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Storage/Sign.php) :
    The functional base class.

    Use Laravel cache mechanism to store driver dynamic processing.

    file > (config/signature.php) :
    The signature driver about config.

    When keep store and interim store are different types, the mode is mixed storage.

    Permanent data is stored on keep store, you can change to other stores in keep_store.

    Time-sensitive data is stored on interim store, you can change to other stores in interim_store.
    
    Note: 
    1. Signature can have custom additional tag using only characters such as ( A ~ Z 0 ~ 9 _ ) .
    2. Append custom tag for display only.

 >> Artisan Commands

    Set the signature secret key.
    $php artisan signature:secret

 >> Aliases

    use StorageSign;

 >> Note

    TTL :
    Use the ttl parameter of the build function.
    Specify the length of time (in minutes) that the token will be valid for.
    Defaults to 3 minutes.
    You can also set this to null, to yield a never expiring signature.

 >> Learn

    Usage 1 :
    Get a new signature code.

    Example :
    --------------------------------------------------------------------------
    use StorageSign;

    $ttl = 5;

    $code = StorageSign::build(['custom'], $ttl);

    Usage 2 :
    Get the data by signature code.

    Example :
    --------------------------------------------------------------------------
    use StorageSign;

    $ttl = 5;

    $code = StorageSign::build(['custom'], $ttl);

    $data = StorageSign::get($code);

    Usage 3 :
    Forget the data by signature code.

    Example :
    --------------------------------------------------------------------------
    use StorageSign;

    $ttl = 5;

    $code = StorageSign::build(['custom'], $ttl);

    StorageSign::forget($code);

    Usage 4 :
    Preload the signature code list and increase query speed.

    Example :
    --------------------------------------------------------------------------
    use StorageSign;

    $codes = [
       '334EEBA482B04D8EBC5077710345834B7E8D20659EE6CB32D34720BB40B12D8441E79970',
       'E42B9E5906534F8BAEC02BD0F3B8C0A01C59A5328218CC92EEFD10976C6C5B5C7A7A7920',
       '59A95A5D9F9B43D6A9CDD9E872FE79C57E8D20659EE6CB32D34720BB40B12D842E28A221'
    ];

    StorageSign::preload($codes);

    $data = StorageSign::get($codes[0]);

    Usage 5 :
    Get a new signature code and set the tag.

    Example :
    --------------------------------------------------------------------------
    use StorageSign;

    $ttl = 5;

    $code = StorageSign::build(['custom'], $ttl, 'CUSTOM_TAG');
