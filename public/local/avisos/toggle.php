<?php

require_once('../../config.php');

use local_avisos\local\manager;

// esse arquivo é responsável por alternar o status de um aviso institucional (ativar ou desativar) e redirecionar para a página de gerenciamento

require_login();

$context = context_system::instance();

require_capability(
    'local/avisos:manage',
    $context
);

require_sesskey();

$id = required_param(
    'id',
    PARAM_INT
);

$manager = new manager();

$manager->toggle($id);

redirect(
    new moodle_url(
        '/local/avisos/manage.php'
    ),
    get_string(
        'statuschanged',
        'local_avisos'
    ),
    null,
    \core\output\notification::NOTIFY_SUCCESS
);