Documentation PHP:

 >> Information

    Title		: Time Period
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    09-24-2020		Poen		01-01-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Detector/Period.php) :
    The functional base class.

 >> Learn

    Usage 1 :
    Verify a clock time format.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    Period::verifyMoment('18:00:00');

    Usage 2 :
    Verify a date time format.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    Period::verifyDatetime('2020-11-01 00:00:00');

    Usage 3 :
    Create clock time range mode to check including start and end.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    // 18:00:00 ~ 00:00:00
    Period::onClock('18:00:00', '00:00:00')->within('19:00:00');
    // 00:00:00 ~ 18:00:00
    Period::onClock('00:00:00', '18:00:00')->within('16:00:00');

    Usage 4 :
    Create date time range mode to check including start and end.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    // 2020-11-01 00:00:00 ~ 2020-11-01 18:00:00
    Period::onSchedule('2020-11-01 00:00:00', '2020-11-01 18:00:00')->within('2020-11-01 16:00:00');
    Period::onSchedule('2020-11-01 18:00:00', '2020-11-01 00:00:00')->within('2020-11-01 16:00:00');

    Usage 5 :
    Create clock time range mode to check excluding start and end.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    // 18:00:01 ~ 23:59:59
    Period::onClock('18:00:00', '00:00:00')->within('19:00:00', false);
    // 00:00:01 ~ 17:59:59
    Period::onClock('00:00:00', '18:00:00')->within('16:00:00', false);

    Usage 6 :
    Create date time range mode to check excluding start and end.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    // 2020-11-01 00:00:01 ~ 2020-11-01 17:59:59
    Period::onSchedule('2020-11-01 00:00:00', '2020-11-01 18:00:00')->within('2020-11-01 16:00:00', false);
    Period::onSchedule('2020-11-01 18:00:00', '2020-11-01 00:00:00')->within('2020-11-01 16:00:00', false);

    Usage 7 :
    Get the clock time range mode to difference in seconds.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    // 18:00:00 ~ 00:00:00
    Period::onClock('18:00:00', '00:00:00')->diffSec();
    // 00:00:00 ~ 18:00:00
    Period::onClock('00:00:00', '18:00:00')->diffSec();

    Usage 8 :
    Get the date time range mode to difference in seconds.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    // 2020-11-01 00:00:00 ~ 2020-11-01 18:00:00
    Period::onSchedule('2020-11-01 00:00:00', '2020-11-01 18:00:00')->diffSec();
    Period::onSchedule('2020-11-01 18:00:00', '2020-11-01 00:00:00')->diffSec();

    Usage 9 :
    Get the clock time range mode to calculate the number of cycles with the time difference.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    // 18:00:00 ~ 00:00:00
    Period::onClock('18:00:00', '00:00:00')->cycleCount(60);
    // 00:00:00 ~ 18:00:00
    Period::onClock('00:00:00', '18:00:00')->cycleCount(60);
    // Get the remainder
    Period::onClock('00:00:00', '18:00:00')->cycleCount(60, $remainder);

    Usage 10 :
    Get the date time range mode to calculate the number of cycles with the time difference.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Detector\Period;

    // 2020-11-01 00:00:00 ~ 2020-11-01 18:00:00
    Period::onSchedule('2020-11-01 00:00:00', '2020-11-01 18:00:00')->cycleCount(60);
    Period::onSchedule('2020-11-01 18:00:00', '2020-11-01 00:00:00')->cycleCount(60);
    // Get the remainder
    Period::onSchedule('2020-11-01 18:00:00', '2020-11-01 00:00:00')->cycleCount(60, $remainder);

