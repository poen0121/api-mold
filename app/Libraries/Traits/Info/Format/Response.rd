Documentation PHP:

 >> Information

    Title		: Info Foramt Response
    Revision	: 1.0.0
    Notes		:

    Revision History:
    When			Create		When		Edit		Description
    ---------------------------------------------------------------------------
    11-19-2020		Poen		11-19-2020	Poen		Code maintenance.
    ---------------------------------------------------------------------------

 >> About

    file > (app/Libraries/Traits/Info/Format/Response.php) :
    The functional base class.

    Attach status label to data response format.

    Format: [
        'success' => Boolean,
        'data' => Array
    ]

 >> Learn

    Step 1 :
    In App\Notifications Class, Use App\Libraries\Traits\Info\Format\Response

    File : app/Notifications/Test/Message.php

    Example :
    --------------------------------------------------------------------------
    namespace App\Notifications\Test;

    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Notifications\Notification;
    use App\Libraries\Traits\Info\Format\Response;

    class Message extends Notification implements ShouldQueue
    {
        use Queueable;
        use Response;

    }

    ==========================================================================

    Available Functions :

    Usage 1 :
    Response the success message.

    Example :
    --------------------------------------------------------------------------
    public function toDatabase($notifiable)
    {
        return $this->success([
            'message' => 'Completed'
        ]);
    }

    Usage 2 :
    Response the failure message.

    Example :
    --------------------------------------------------------------------------
    public function toDatabase($notifiable)
    {
        return $this->failure([
            'message' => 'Lost'
        ]);
    }
