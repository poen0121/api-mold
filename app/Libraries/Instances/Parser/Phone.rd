Documentation PHP:

 >> Information

    Title		: Phone Parser
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    11-17-2020		Poen		10-17-2022	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Parser/Phone.php) :
    The functional base class.

 >> Aliases

    use Phone;

 >> Note

    The area code uses the ISO_3166-1 alpha2 area code.

    Reference : https://zh.wikipedia.org/wiki/ISO_3166-1

 >> Learn

    Usage 1 :
    Parse phone baseinfo.

    Example :
    --------------------------------------------------------------------------
    use Phone;

    $phone = Phone::parse('+886930684635');

    Usage 2 :
    Get the phone parse area.

    Example :
    --------------------------------------------------------------------------
    use Phone;

    $phone = Phone::parse('+886930684635');

    $phone->getArea();

    Usage 3 :
    Get the phone parse type.

    Example :
    --------------------------------------------------------------------------
    use Phone;

    $phone = Phone::parse('+886930684635');
    
    $phone->getType();

    Usage 4 :
    Get the phone parse format number.

    Example :
    --------------------------------------------------------------------------
    use Phone;

    $phone = Phone::parse('+886930684635');
    
    $phone->getFormat();

    Usage 5 :
    Get the phone parse code.

    Example :
    --------------------------------------------------------------------------
    use Phone;

    $phone = Phone::parse('+886930684635');
    
    $phone->getCode();

    Usage 6 :
    If there is no international code, baseinfo can be parsed from area express.

    Example :
    --------------------------------------------------------------------------
    use Phone;

    $phone = Phone::parse('0930684635', ['TW']);

    Usage 7 :
    Get the phone parse national number.

    Example :
    --------------------------------------------------------------------------
    use Phone;

    $phone = Phone::parse('+886930684635');
    
    $phone->getNational();
