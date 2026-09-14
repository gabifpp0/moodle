<?php

defined('MOODLE_INTERNAL') || die();
// aqui vai ficar as permissões do plugin, como quem pode ver e quem pode gerenciar os avisos
// o mooddle define por capacidades, que são as permissões do plugin, e depois você pode atribuir essas capacidades para os papéis do moodle
$capabilities = [

    'local/avisos:view' => [
        'captype' => 'read',
        'contextlevel' => CONTEXT_SYSTEM,

        'archetypes' => [
            'user' => CAP_ALLOW,
        ],
    ],

    'local/avisos:manage' => [
        'riskbitmask' => RISK_XSS,
        'captype' => 'write',
        'contextlevel' => CONTEXT_SYSTEM,

        'archetypes' => [
            'manager' => CAP_ALLOW,
        ],
    ],

];