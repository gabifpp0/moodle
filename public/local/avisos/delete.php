<?php

require_once('../../config.php');

use local_avisos\local\manager;

// permite excluir avisos institucionais, mas antes pede confirmação para o usuário

require_login();

$context = context_system::instance();

require_capability(
    'local/avisos:manage',
    $context
);

$id = required_param(
    'id',
    PARAM_INT
);

$confirm = optional_param(
    'confirm',
    0,
    PARAM_BOOL
);

$url = new moodle_url(
    '/local/avisos/delete.php',
    ['id' => $id]
);

$PAGE->set_url($url);
$PAGE->set_context($context);

$PAGE->set_title(
    get_string(
        'deletenotice',
        'local_avisos'
    )
);

$PAGE->set_heading(
    get_string(
        'deletenotice',
        'local_avisos'
    )
);

$manager = new manager();

$notice = $manager->get($id);

if ($confirm && confirm_sesskey()) {

    $manager->delete($id);

    redirect(
        new moodle_url(
            '/local/avisos/manage.php'
        ),
        get_string(
            'noticedeleted',
            'local_avisos'
        ),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );
}

echo $OUTPUT->header();

echo $OUTPUT->confirm(
    get_string(
        'deleteconfirm',
        'local_avisos',
        format_string($notice->title)
    ),

    new moodle_url(
        '/local/avisos/delete.php',
        [
            'id' => $id,
            'confirm' => 1,
            'sesskey' => sesskey(),
        ]
    ),

    new moodle_url(
        '/local/avisos/manage.php'
    )
);

echo $OUTPUT->footer();