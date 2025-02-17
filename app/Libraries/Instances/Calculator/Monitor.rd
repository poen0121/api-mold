Documentation PHP:

 >> Information

    Title		: Monitor
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    01-02-2021		Poen		03-02-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Calculator/Monitor.php) :
    The functional base class.

    Use Laravel redis mechanism to store driver dynamic processing.

    file > (config/monitor.php) :
    The redis driver about config.

    The current monitoring data pool statistics tool.

 >> Learn

    Usage 1 :
    Sit in the data pool.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Calculator\Monitor;

    $monitor = new Monitor('member');
    $monitor->sit('1294583');

    Usage 2 :
    Get the valid count.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Calculator\Monitor;

    $monitor = new Monitor('member');
    $monitor->sit('1294583');
    $monitor->count();
