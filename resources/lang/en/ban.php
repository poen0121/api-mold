<?php
return [

    /*
    |--------------------------------------------------------------------------
    | Ban Release Description
    |--------------------------------------------------------------------------
    |
    | Set the ban release description name list.
    |
    | Example :
    | 'release' => [
    |   Description code project => Ban release description
    | ]
    */

    'release' => [
        'global' => 'Global Service',
        'admin' => 'Admin Service',
        'member' => 'Member Service',
     ],

    /*
    |--------------------------------------------------------------------------
    | API Interface Route Name Description
    |--------------------------------------------------------------------------
    |
    | Set the route name relative interface name list.
    |
    | Example :
    | 'interface' => [
    |   Route name => Route name description
    | ]
    */
    
    'interface' => [
        'doc.auth' => 'APIs Documentation Authorization Link',
        'auth.client.index' => 'Client Service Index',
        'auth.client.build' => 'Build Client Service',
        'auth.client.bans' => 'Search Client Service Bans',
        'auth.client.ban' => 'Search Client Service Ban',
        'auth.client.read' => 'Search Client Service Info',
        'auth.client.rewrite.ban' => 'Edit Client Service Ban',
        'auth.client.reschedule' => 'Edit Client Service usage period',
        'auth.client.disable' => 'Disable Client Service',
        'auth.client.enable' => 'Enable Client Service',
        'auth.client.rename' => 'Rename Client Service',
        'auth.client.reset.secret' => 'Reset Client Service Secret Key',
        'auth.user.types' => 'Login Types',
        'auth.user.online' => 'User Online Statistics',
        'auth.user.login' => 'User Login',
        'auth.user.logout' => 'User Logout',
        'auth.user.signature' => 'User Authorization Signature',
        'auth.user.signature.login' => 'User Signature Login',
        'auth.read.service' => 'Read Own Service Info',
        'auth.token.create' => 'Login Client Service',
        'auth.token.revoke' => 'Revoke Access Token',
        'auth.token.refresh' => 'Refresh Access Token',
        'feature.index' => 'Feature Index',
        'feature.read' => 'Search Feature Info',
        'system.interface.index' => 'All APIs Index',
        'system.interface.managed' => 'Managed APIs Index',
        'system.interface.managed.ban' => 'Search Managed APIs By Ban Number',
        'system.language.index' => 'API Language Index',
        'system.language.default' => 'API Language Default',
        'system.log.types' => 'System Log Types',
        'system.log.index' => 'System Log Index',
        'system.parameter.index' => 'System Parameter Index',
        'system.parameter.read' => 'Search System Parameter Info',
        'system.parameter.rewrite.value' => 'Edit System Parameter Value',
        'system.authority.types' => 'User Authority Types',
        'system.authority.global' => 'Global Authority',
        'system.authority.grant' => 'Grant Authority',
        'system.authority.remove' => 'Remove Authority',
        'system.authority.snapshot.build' => 'Build Authority Snapshot',
        'system.authority.snapshot.index' => 'Authority Snapshot Index',
        'system.authority.snapshot.read' => 'Search Authority Snapshot Info',
        'system.authority.snapshot.rename' => 'Rename Authority Snapshot',
        'system.authority.snapshot.delete' => 'Delete Authority Snapshot',
        'notice.bulletin.build' => 'Build Bulletin Notify Message',
        'notice.bulletin.disable' => 'Disable Bulletin Notify Message',
        'notice.bulletin.enable' => 'Enable Bulletin Notify Message',
        'notice.bulletin.index' => 'Bulletin Notification Index',
        'notice.bulletin.read' => 'Read Bulletin Notification Info',
        'notice.bulletin.label.types' => 'Bulletin Notify Available Label Types',
        'notice.bulletin.user.types' => 'Bulletin Notify Available User Types',
        'notice.letter.send' => 'Send Letter Notify Message',
        'notice.letter.user.types' => 'User Own Letters Target Types',
        'notice.messages' => 'User Own Notify Messages',
        'notice.unread' => 'User Own Unread Notice Messages',
        'notice.count' => 'User Own Unread Notice Count',
        'notice.mark' => 'Mark User Own Notice Message As Read',
        'notice.remove' => 'Remove User Own Read Notice Message',
        'sms.log.index' => 'SMS Log Index',
        'sms.log.read' => 'SMS Serial Info',
    ]
];
