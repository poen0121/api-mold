<?php

namespace App\Notifications\User\Message;

use App\Exceptions\Message\NoticeExceptionCode as ExceptionCode;
use App\Notifications\User\Message\Notice;
use TokenAuth;

class Letter extends Notice
{

    /**
     * The notice type.
     *
     * @var string
     */
    protected $type = 'letter';

    /**
     * Create a new notification instance.
     *
     * @param object $sender
     * @param string $subject
     * @param string $message
     * @param array $note
     * @return void
     */
    public function __construct(object $sender, string $subject, string $message, array $note = [])
    {
        /* Check sender */
        if (!TokenAuth::getAuthGuard($sender) || TokenAuth::model() === get_class($sender) || !$sender->exists) {
            throw new ExceptionCode(ExceptionCode::INVALID_SENDER);
        }
        /* Content formeat */
        $content = [];
        $content['message'] = $message;
        $content['note'] = $note;
        $content['sender'] = [
            'type' => TokenAuth::getAuthGuard($sender),
            'uid' => $sender->uid,
        ];
        /* Push message */
        parent::__construct($subject, $content, $this->type);
    }
}
