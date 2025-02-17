Documentation PHP:

 >> Information

    Title		: Cell
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    12-03-2020		Poen		02-02-2021	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Abstracts/Base/Cell.php) :
    The functional base class.

 >> Return Format

    Return array format.
    [bool] success : Success status true or false
    [array] data : Data array

 >> Artisan Commands

    Create a object file.
    $php artisan make:cell <name>

 >> Learn

    Step 1 :
    Create cell object class.

    $php artisan make:cell Game\Login

    Example : App\Libraries\Cells\Game\LoginCell object class

    File : app/Libraries/Cells/Game/LoginCell.php

    Step 2 :
    Edit cell.

    Example :
    --------------------------------------------------------------------------
    // Get the validation rules that apply to the arguments input.
    protected function rules(): array
    {
        return [
            'id' -> 'required|integer',
            // Custom validation rules
        ];
    }

    // Execute the cell handle.
    protected function handle(): array
    {
        // You can use getInput function to get the value returned by validation rules
        // $this->getInput( Rules name )
        
        try {

            // Place your business logic here

            // Return success message
            return $this->success([
                //
            ]);
        } catch (\Throwable $th) {
            // Return failure message
            return $this->failure([
                'message' => $th->getMessage()
            ]);
            // Throw error
            // throw $th;
        }
    }

    Step 3 :
    Execute the cell handle to run.

    Example :
    --------------------------------------------------------------------------

    use App\Libraries\Cells\Game\LoginCell;

    $result = LoginCell::input()->run();

    $result = LoginCell::input(['id' => 1])->run();
