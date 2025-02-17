Documentation PHP:

 >> Information

    Title		: Storage Data
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-24-2020		Poen		12-24-2020	Poen		Data maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Storage/Data.php) :
    The functional base class.

    Use Laravel cache processing.

    Customly generate time-sensitive data.

 >> Aliases

    use StorageData;

 >> Note

    TTL :
    Use the ttl parameter of the put function.
    Specify the length of time (in minutes) that the token will be valid for.
    Defaults to 3 minutes.
    If the ttl is set to 0, no data will be generated.

 >> Learn

    Usage 1 :
    Put in the holder’s data.

    Example :
    --------------------------------------------------------------------------
    use StorageData;

    $ttl = 5;

    StorageData::put('custom', ['custom data'], $ttl);

    Usage 2 :
    Get the the holder's data.

    Example :
    --------------------------------------------------------------------------
    use StorageData;

    $ttl = 5;

    StorageData::put('custom', ['custom data'], $ttl);

    $data = StorageData::get('custom');

    Usage 3 :
    Forget the the holder's data.

    Example :
    --------------------------------------------------------------------------
    use StorageData;

    $ttl = 5;

    StorageData::put('custom', ['custom data'], $ttl);

    StorageData::forget('custom');
