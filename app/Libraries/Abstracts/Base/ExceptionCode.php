<?php

namespace App\Libraries\Abstracts\Base;

use Exception;
use Illuminate\Support\MessageBag;
use Lang;

abstract class ExceptionCode extends Exception
{
    /**
     * The input replace converter message tags.
     *
     * @var array
     */
    protected $replaceMessageTags = [];

    /**
     * @var MessageBag
     */
    protected $messageBag;

    /**
     * The http status code.
     * 
     * @var int
     */
    protected $statusCode;

    /**
     * Custom exception code constructor.
     *
     * @param int $code
     * @param array $replaceMessageTags
     * @param array $replaceSourceMessageTags
     * @param MessageBag|null $messageBag
     * @param int $statusCode
     * @return void
     */
    public function __construct(int $code, array $replaceMessageTags = [], array $replaceSourceMessageTags = [], ?MessageBag $messageBag = null, int $statusCode = 500)
    {
        $this->replaceMessageTags = $replaceMessageTags;

        $this->messageBag = $messageBag;

        $this->statusCode = $statusCode;

        $subclass = get_called_class();

        $message = (isset($subclass::DEBUG_MESSAGE[$code]) ? $subclass::DEBUG_MESSAGE[$code] : 'Unknown message.');

        $message = strtr($message, $replaceSourceMessageTags);

        parent::__construct($message, $code);
    }

    /**
     * Get the replace converter message.
     *
     * @param string $message
     * @return string
     */
    public function getReplaceConverterMessage(string $message): string
    {
        return strtr($message, $this->replaceMessageTags);
    }

    /**
     * @return MessageBag
     */
    public function getMessageBag()
    {
        return $this->messageBag;
    }

    /**
     * Get the http statsu code.
     * 
     * @return int
     */
    public function getStatusCode(): int
    {
        return $this->statusCode;
    }

    /**
     * Get the display code.
     * 
     * @return int
     */
    public function getDisplayCode(): int
    {
        /* Converter class name by ExceptionCode object */
        $objectConverter = $this->getExceptionConverter();
        $object = (isset($objectConverter[0]) ? $objectConverter : get_class($this));
        /* Http status code */
        $status = $this->getStatusCode();
        /* Error code */
        $code = $this->getCode();
        /* About language path */
        $retrieveObjectPage = 'exception.' . strtr($object, ['\\' => '.']) . '.converter';
        $retrieveBasePage = 'exception.converter';
        $retrieveBaseTag = 'customize.' . $status . '.' . $code;
        /* Retrieve error code */
        /* Retrieve row customizations from object language files */
        $resetCode = Lang::dict($retrieveObjectPage, $retrieveBaseTag . '.code', null);
        /* Retrieve row defaults from object language files */
        $resetCode = ($resetCode ?? Lang::dict($retrieveObjectPage, 'default.code', null));
        /* Swap error code by reset code */
        $codeError = config('exception');
        /* Return the reset code for the conversion */
        $resetCode = (isset($resetCode) && isset($codeError[$object][$resetCode]) ? $codeError[$object][$resetCode] : $resetCode); // Swap code error
        /* Retrieve row customizations from base language files */
        $resetCode = ($resetCode ?? Lang::dict($retrieveBasePage, $retrieveBaseTag . '.code', null));
        /* Retrieve row defaults from base language files */
        $resetCode = ($resetCode ?? Lang::dict($retrieveBasePage, 'default.code', null));
        /* Swap type */
        $resetCode = (string) $resetCode;
        /* Verify error code format */
        $resetCode = (isset($resetCode[0]) ? $resetCode : null);
        /* Return reset error code */
        return (isset($resetCode) ? (is_numeric($resetCode) ? (int) $resetCode : $resetCode) : 0);
    }

    /**
     * Get the display http status code.
     * 
     * @return int
     */
    public function getDisplayStatusCode(): int
    {
        /* Converter class name by ExceptionCode object */
        $objectConverter = $this->getExceptionConverter();
        $object = (isset($objectConverter[0]) ? $objectConverter : get_class($this));
        /* Http status code */
        $status = $this->getStatusCode();
        /* Error code */
        $code = $this->getCode();
        /* About language path */
        $retrieveObjectPage = 'exception.' . strtr($object, ['\\' => '.']) . '.converter';
        $retrieveBasePage = 'exception.converter';
        $retrieveBaseTag = 'customize.' . $status . '.' . $code;
        /* Retrieve status code */
        /* Retrieve row customizations from object language files */
        $resetStatus = Lang::dict($retrieveObjectPage, $retrieveBaseTag . '.status', null);
        /* Retrieve row defaults from object language files */
        $resetStatus = ($resetStatus ?? Lang::dict($retrieveObjectPage, 'default.status', null));
        /* Retrieve row customizations from base language files */
        $resetStatus = ($resetStatus ?? Lang::dict($retrieveBasePage, $retrieveBaseTag . '.status', null));
        /* Retrieve row defaults from base language files */
        $resetStatus = ($resetStatus ?? Lang::dict($retrieveBasePage, 'default.status', null));
        /* Verify status code format */
        $resetStatus = (preg_match('/^[1-5]{1}[0-9]{1}[0-9]{1}$/', $resetStatus) ? $resetStatus : null);
        /* Return reset status code */
        return (int) (isset($resetStatus) ? $resetStatus : 500);
    }

    /**
     * Get the display message.
     * 
     * @return string
     */
    public function getDisplayMessage(): string
    {
        /* Converter class name by ExceptionCode object */
        $objectConverter = $this->getExceptionConverter();
        $object = (isset($objectConverter[0]) ? $objectConverter : get_class($this));
        /* Http status code */
        $status = $this->getStatusCode();
        /* Error code */
        $code = $this->getCode();
        /* About language path */
        $retrieveObjectPage = 'exception.' . strtr($object, ['\\' => '.']) . '.converter';
        $retrieveBasePage = 'exception.converter';
        $retrieveBaseTag = 'customize.' . $status . '.' . $code;
        /* Retrieve message */
        /* Retrieve row customizations from object language files */
        $resetMessage = Lang::dict($retrieveObjectPage, $retrieveBaseTag . '.message', null);
        /* Retrieve row defaults from object language files */
        $resetMessage = ($resetMessage ?? Lang::dict($retrieveObjectPage, 'default.message', null));
        /* Retrieve row customizations from base language files */
        $resetMessage = ($resetMessage ?? Lang::dict($retrieveBasePage, $retrieveBaseTag . '.message', null));
        /* Retrieve row defaults from base language files */
        $resetMessage = ($resetMessage ?? Lang::dict($retrieveBasePage, 'default.message', null));
        /* Swap type */
        $resetMessage = (string) $resetMessage;
        /* Replace converter message tags by ExceptionCode object */
        $resetMessage = $this->getReplaceConverterMessage($resetMessage);
        /* Return reset message */
        return (isset($resetMessage[0]) ? $resetMessage : 'Unknown message.');
    }
}
