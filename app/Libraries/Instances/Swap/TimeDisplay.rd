Documentation PHP:

 >> Information

    Title		: TimeDisplay
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-21-2019		Poen		09-26-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Swap/TimeDisplay.php) :
    The functional base class.

    The client time zone depends on middleware 'App\Http\Middleware\Accept\Timezone' .

 >> Learn

    Usage 1 :
    Get the application local timezone.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Swap\TimeDisplay;

    TimeDisplay::getTZ();

    Usage 2 :
    Get the accept client timezone.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Swap\TimeDisplay;

    TimeDisplay::getCTZ();

    Usage 3 :
    Swap the client timezone datetime to the local timezone datetime.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Swap\TimeDisplay;

    TimeDisplay::asLocalTime('2018-01-01 00:00:00');

    Usage 4 :
    Swap the local timezone datetime to the client timezone datetime.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Swap\TimeDisplay;

    TimeDisplay::asClientTime('2018-01-01 00:00:00');
