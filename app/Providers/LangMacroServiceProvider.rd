Documentation PHP:

 >> Information

    Title		: Language
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    02-05-2020		Poen		10-22-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Providers/LangMacroServiceProvider.php) :
    The language base class.

    API language information.

    file > (config/app.php) :
    The locales array config.

 >> Aliases

    use Lang;

 >> Learn

    Usage 1 :
    Get the default language.
    Fields :
     Column string : language
     Column string : description

    Example :
    --------------------------------------------------------------------------
    use Lang;

    Lang::default();

    Usage 2 :
    Get the list of locales.
    Fields :
     Column string : language
     Column string : description

    Example :
    --------------------------------------------------------------------------
    use Lang;

    Lang::locales();

    Usage 3 :
    Get the current locale.
    Fields :
     Column string : language
     Column string : description

    Example :
    --------------------------------------------------------------------------
    use Lang;

    Lang::currentLocale();

    Usage 4 :
    Read the translation for the given page tag.

    Example :
    --------------------------------------------------------------------------
    use Lang;

    // Read the page tag and default null
    Lang::dict('dir.file', 'tag');

    // Default null
    Lang::dict('dir.file', 'tag', null);

    // Default word
    Lang::dict('dir.file', 'tag','default');

    // Default array
    Lang::dict('dir.file', 'tag', []);

    // Replace word code
    Lang::dict('dir.file', 'tag', 'Default is :code', ['code' => 'replace content']);

