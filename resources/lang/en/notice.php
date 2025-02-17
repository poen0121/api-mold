<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Notify Content
    |--------------------------------------------------------------------------
    |
    | Set the notify content relative name list.
    |
    | Example :
    | 'content' => [
    |    Tag name => [
    |        Content type description => Display content,
    |    ],
    |    // Other tag
    | ]
    */

    'content' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Notify Subject
    |--------------------------------------------------------------------------
    |
    | Set the notify subject relative name list.
    |
    | Example :
    | 'subject' => [
    |   Subject description => Display subject
    | ]
    */

    'subject' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Bulletin Label Type
    |--------------------------------------------------------------------------
    |
    | Set the bulletin label type code relative name list.
    |
    | Example :
    | 'bulletin_labelables' => [
    |   Label type code => Type name,
    | ]
    */

    'bulletin_labelables' => [
        'normal' => 'Normal',
        'system' => 'System',
    ],

    /*
    |--------------------------------------------------------------------------
    | Notify Type
    |--------------------------------------------------------------------------
    |
    | Set the type code relative name list.
    |
    | Example :
    | 'type' => [
    |   Type code => Type name,
    | ]
    */

    'type' => [
        'none' => 'Notification',
        'letter' => 'Letter',
        'bulletin:normal' => 'Bulletin',
        'bulletin:system' => 'System',
    ],
];
