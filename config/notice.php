<?php
return [

    /*
     |--------------------------------------------------------------------------
     | Translatable Notice Type
     |--------------------------------------------------------------------------
     |
     | Set up a list of translatable type for notice transformer.
     | The translation field is subject and content.
     |
     | Example :
     | 'translatable' => [
     |    Notice type code,
     | ]
     */

    'translatable' => [
        //
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Bulletin Content Items
    |--------------------------------------------------------------------------
    |
    | Set the default list available for bulletin content items.
    |
    | Example :
    | 'bulletin_content' => [
    |    Item key => Default value,
    | ]
    |
    */

    'bulletin_content' =>  [
        'message' => null, // Content
        'note' => [], // Note
    ],
    
    /*
     |--------------------------------------------------------------------------
     | Bulletin Label Type 
     |--------------------------------------------------------------------------
     |
     | Set up a list of label type code for bulletin.
     |
     | Example :
     | 'bulletin_labelables' => [
     |    Label type code,
     | ]
     */

    'bulletin_labelables' => [
        'normal', // Default
        'system',
    ],

    /*
     |--------------------------------------------------------------------------
     | Entities Model For Bulletin Groups 
     |--------------------------------------------------------------------------
     |
     | Set up a list of notifiable guard model for bulletin.
     |
     | Example :
     | 'bulletin_groups' => [
     |    Unique guard model class for user,
     | ]
     */

    'bulletin_groups' => [
        // App\Entities\Member\Auth::class,
    ],

    /*
     |--------------------------------------------------------------------------
     | Entities Model For Letter Recipients
     |--------------------------------------------------------------------------
     |
     | Set up a list of notifiable guard model for letter.
     |
     | Example :
     | 'letter_recipients' => [
     |    Unique guard model class for user => [
     |       Unique guard model class for sender user,
     |    ],
     | ]
     */

    'letter_recipients' => [
        // App\Entities\Member\Auth::class => [
        //     App\Entities\Admin\Auth::class,
        // ],
    ],    
];
