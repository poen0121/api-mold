Documentation PHP:

 >> Information

    Title		: Matrix
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    01-19-2021		Poen		08-17-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Instances/Swap/Matrix.php) :
    The functional base class for arrays recursive swap.

 >> Learn

    Usage 1 :
    Replace null with an empty string recursively.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Swap\Matrix;

    $arr = Matrix::null2empty(['test' => null]);

    Usage 2 :
    Replace empty strings with null recursively.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Swap\Matrix;

    $arr = Matrix::empty2null(['test' => '']);

    Usage 3 :
    Replace custom with recursion.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Swap\Matrix;

    $arr = ['test' => null];

    Matrix::replace($arr, null, 'custom');

    Usage 4 :
    Replace rules with recursive indexes.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Swap\Matrix;

    $arr = [[
         'test' => '1'
      ],[
         'test' => '2'
    ]];

    $rule = ['test' => [
       '1' => 'One',
       '2' => 'Two'
    ]];

    Matrix::indexReplace($arr, $rule);

    Usage 5 :
    Replace rules with recursive values.

    Example :
    --------------------------------------------------------------------------
    use App\Libraries\Instances\Swap\Matrix;

    $arr = [[
         'test' => ['1', '2']
      ],[
         'test' => '2'
    ]];

    $rule = [
      '1' => 'One',
      '2' => 'Two'
    ];

    Matrix::allReplace($arr, $rule);

