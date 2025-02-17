Documentation PHP:

 >> Information

    Title		: World Geodetic System (WGS84)
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    08-30-2020		Poen		11-27-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Calculator/WGS.php) :
    The functional base class.

 >> Note
    
    Take the 7th decimal place, the coordinate difference is about 10 cm.

 >> Learn

    Usage 1 :
    Measure the moving distance in meters based on the moving coordinates.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Calculator\WGS;

    $wgs = new WGS();
    $wgs->origin(25.0341971, 121.5645381)->distance(25.0331571, 121.5635511);

    Usage 2 :
    Measure the azimuth in a clockwise direction with north according to the start and end coordinates.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Calculator\WGS;

    $wgs = new WGS();
    $wgs->origin(25.0341971, 121.5645381)->azimuth(25.0331571, 121.5635511);

    Usage 3 :
    According to the starting coordinate , azimuth and distance, the destination coordinates are calculated.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Calculator\WGS;

    $wgs = new WGS();
    $wgs->origin(25.0341971, 121.5645381);
    $azimuth = $wgs->azimuth(25.0331571, 121.5635511);
    $distance = $wgs->distance(25.0331571, 121.5635511);
    list($latitude, $longitude) = $wgs->destination($azimuth, $distance);
