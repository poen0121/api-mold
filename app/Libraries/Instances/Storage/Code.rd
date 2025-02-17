Documentation PHP:

 >> Information

    Title		: Storage Code
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    10-30-2020		Poen		01-25-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Storage/Code.php) :
    The functional base class.

    Use Laravel cache processing.

    Automatically generate integer 6-digit authcode.

 >> Aliases

    use StorageCode;

 >> Note

    TTL :
    Use the ttl parameter of the fill function.
    Specify the length of time (in minutes) that the token will be valid for.
    Defaults to 3 minutes.
    If the ttl is set to 0, no code will be generated.

 >> Learn

    Usage 1 :
    Fill in the holder’s authcode.

    Example :
    --------------------------------------------------------------------------
    use StorageCode;

    $ttl = 5;

    $code = StorageCode::fill('custom', $ttl);

    Usage 2 :
    Get the the holder's authcode.

    Example :
    --------------------------------------------------------------------------
    use StorageCode;

    $ttl = 5;

    $code = StorageCode::fill('custom', $ttl);

    $code = StorageCode::get('custom');

    Usage 3 :
    Forget the the holder's authcode.

    Example :
    --------------------------------------------------------------------------
    use StorageCode;

    $ttl = 5;

    $code = StorageCode::fill('custom', $ttl);

    StorageCode::forget('custom');
