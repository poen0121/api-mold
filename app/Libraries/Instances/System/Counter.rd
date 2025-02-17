Documentation PHP:

 >> Information

    Title		: System Counter
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    03-22-2021		Poen		03-22-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/System/Counter.php) :
    The functional base class.

    Data cache storage operation depends on the system parameter configuration.

    Data storage is attached to the system parameter model.

    Transaction mode to update.

    Read the total number in loose mode.

    Total number of allowed processing is 0 ~ PHP_INT_MAX.

    Allowed base quantity value is 0 ~ PHP_INT_MAX.

    Key name entered must not exceed 128 bytes!

 >> Base Usage

    Step 1 :

    Increment the value by 1.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\Counter;

    $total = Counter::increment('users');

    Step 2 :

    Increment the custom value.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\Counter;

    $total = Counter::increment('users', 10);

    Step 3 :

    Decrement the value by 1.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\Counter;

    $total = Counter::decrement('users');

    Step 4 :

    Decrement the custom value.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\Counter;

    $total = Counter::decrement('users', 10);

    Step 5 :

    Get the total.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\System\Counter;

    $total = Counter::total('users');

