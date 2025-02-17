Documentation PHP:

 >> Information

    Title		: Storage Period
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    01-23-2022		Poen		03-10-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Storage/Period.php) :
    The functional base class.

    Use Laravel cache processing.

    Generate a signature code for future regular attendance verification.

    Guest sign-in is one-time.

    file > (config/signature.php) :
    The signature driver about config.

    Data is only stored in the interim store, you can change to other stores in interim_store.

    Note: 
    1. If the attendance is registered at this time, return to 1.
    2. If the attendance is completed at this time, return to 2.
    3. Returns null if the signature is not available.
    4. Avoid time gap less than ttl.
    5. The sign time is valid for the ttl buffer period.
    6. Validate time using local time zone UTC.
    7. Signature can have custom additional tag using only characters such as ( A ~ Z 0 ~ 9 _ ) .
    8. Append custom tag for display only.

 >> Artisan Commands

    Set the signature secret key.
    $php artisan signature:secret

 >> Aliases

    use StoragePeriod;

 >> Note

    TTL :
    Use the ttl parameter of the fill function.
    Specify the length of time (in minutes) that the token will be valid for.
    Defaults to 3 minutes.
    If the ttl is set to 0, no code will be generated.

 >> Learn

    Usage 1 :
    Get the regular attendance signature code.

    Example :
    --------------------------------------------------------------------------
    use StoragePeriod;

    $ttl = 5;

    $code = StoragePeriod::build('2020-10-10 10:00:00', '2020-10-10 12:00:00', ['custom data'], $ttl);

    Usage 2 :
    Verify attendance signature count.

    Example :
    --------------------------------------------------------------------------
    use StoragePeriod;

    $ttl = 5;

    $code = StoragePeriod::build('2020-10-10 10:00:00', '2020-10-10 12:00:00', ['custom data'], $ttl);

    // Get count 1 or 2 or null
    $count = StoragePeriod::sign('custom', $code);

    Usage 3 :
    Get the data by signature code.

    Example :
    --------------------------------------------------------------------------
    use StoragePeriod;

    $ttl = 5;

    $code = StoragePeriod::build('2020-10-10 10:00:00', '2020-10-10 12:00:00', ['custom data'], $ttl);

    $data = StoragePeriod::get($code);

    Usage 4 :
    Forget the data by signature code.

    Example :
    --------------------------------------------------------------------------
    use StoragePeriod;

    $ttl = 5;

    $code = StoragePeriod::build('2020-10-10 10:00:00', '2020-10-10 12:00:00', ['custom data'], $ttl);

    StoragePeriod::forget($code);

    Usage 5 :
    Get the regular attendance signature code and set the tag.

    Example :
    --------------------------------------------------------------------------
    use StoragePeriod;

    $ttl = 5;

    $code = StoragePeriod::build('2020-10-10 10:00:00', '2020-10-10 12:00:00', ['custom data'], $ttl, 'CUSTOM_TAG');

