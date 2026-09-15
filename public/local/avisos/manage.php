<?php

require_once('../../config.php');

use local_avisos\local\manager;

// mostra a lista completa de avisos e oferece a interface para criar, editar e excluir os avisos
// busca todos os avisos
// prepara status e urls
// envia para o manage.mustache

require_login();

$context = context_system::instance();

require_capability(
    'local/avisos:manage',
    $context
);

$url = new moodle_url(
    '/local/avisos/manage.php'
);

$PAGE->set_url($url);
$PAGE->set_context($context);
$PAGE->set_title(
    get_string('manage', 'local_avisos')
);
$PAGE->set_heading(
    get_string('manage', 'local_avisos')
);

$manager = new manager();

$records = $manager->get_all();

$notices = [];

$now = time();

foreach ($records as $record) {

    if (!$record->enabled) {
        $status = 'disabled';
    } else if (!empty($record->timestart) && $record->timestart > $now) {
        $status = 'scheduled';
    } else if (!empty($record->timeend) && $record->timeend < $now) {
        $status = 'expired';
    } else {
        $status = 'active';
    }

    $notices[] = [
        'id' => $record->id,
        'title' => format_string($record->title),
        'enabled' => (bool) $record->enabled,

        'timestart' => !empty($record->timestart)
            ? userdate($record->timestart)
            : '-',

        'timeend' => !empty($record->timeend)
            ? userdate($record->timeend)
            : '-',

        'status' => get_string(
            'status_' . $status,
            'local_avisos'
        ),

        'editurl' => (new moodle_url(
            '/local/avisos/edit.php',
            ['id' => $record->id]
        ))->out(false),

        'deleteurl' => (new moodle_url(
            '/local/avisos/delete.php',
            ['id' => $record->id]
        ))->out(false),

        'toggleurl' => (new moodle_url(
            '/local/avisos/toggle.php',
            [
                'id' => $record->id,
                'sesskey' => sesskey(),
            ]
        ))->out(false),
    ];
}

$data = [
    'notices' => $notices,
    'hasnotices' => !empty($notices),

    'createurl' => (new moodle_url(
        '/local/avisos/edit.php'
    ))->out(false),
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template(
    'local_avisos/manage',
    $data
);

echo $OUTPUT->footer();