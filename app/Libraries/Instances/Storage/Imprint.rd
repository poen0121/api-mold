Documentation PHP:

 >> Information

    Title		: Storage Imprint
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    02-03-2022		Poen		03-10-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Storage/Imprint.php) :
    The functional base class.

    Use Laravel cache processing.

    Generate signature codes for future enrollment verification.

    Guest sign-in is one-time.

    file > (config/signature.php) :
    The signature driver about config.

    Data is only stored in the interim store, you can change to other stores in interim_store.

    Note: 
    1. Returns true if registered at this time.
    2. Returns false if the signature is not available.
    3. The sign time is valid for the ttl buffer period.
    4. Validate time using local time zone UTC.
    5. Signature can have custom additional tag using only characters such as ( A ~ Z 0 ~ 9 _ ) .
    6. Append custom tag for display only.

 >> Artisan Commands

    Set the signature secret key.
    $php artisan signature:secret

 >> Aliases

    use StorageImprint;

 >> Note

    TTL :
    Use the ttl parameter of the fill function.
    Specify the length of time (in minutes) that the token will be valid for.
    Defaults to 3 minutes.
    If the ttl is set to 0, no code will be generated.

 >> Learn

    Usage 1 :
    Get the datetime signature code for the appointment.

    Example :
    --------------------------------------------------------------------------
    use StorageImprint;

    $ttl = 5;

    $code = StorageImprint::build('2020-10-10 10:00:00', ['custom data'], $ttl);

    Usage 2 :
    Verify sign up signature.

    Example :
    --------------------------------------------------------------------------
    use StorageImprint;

    $ttl = 5;

    $code = StorageImprint::build('2020-10-10 10:00:00', ['custom data'], $ttl);

    // Get true or false
    StorageImprint::sign('custom', $code);

    Usage 3 :
    Get the data by signature code.

    Example :
    --------------------------------------------------------------------------
    use StorageImprint;

    $ttl = 5;

    $code = StorageImprint::build('2020-10-10 10:00:00', ['custom data'], $ttl);

    $data = StorageImprint::get($code);

    Usage 4 :
    Forget the data by signature code.

    Example :
    --------------------------------------------------------------------------
    use StorageImprint;

    $ttl = 5;

    $code = StorageImprint::build('2020-10-10 10:00:00', ['custom data'], $ttl);

    StorageImprint::forget($code);

    Usage 5 :
    Get the datetime signature code for the appointment and set the tag.

    Example :
    --------------------------------------------------------------------------
    use StorageImprint;

    $ttl = 5;

    $code = StorageImprint::build('2020-10-10 10:00:00', ['custom data'], $ttl, 'CUSTOM_TAG');
