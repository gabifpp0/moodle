<?php

require_once('../../config.php');

use local_avisos\local\manager;

// esse arquivo é responsável por exibir a lista de avisos visíveis para o usuário logado

require_login();

$context = context_system::instance();

require_capability(
    'local/avisos:view',
    $context
);

$url = new moodle_url(
    '/local/avisos/index.php'
);

$PAGE->set_url($url);
$PAGE->set_context($context);

$PAGE->set_title(
    get_string(
        'pluginname',
        'local_avisos'
    )
);

$PAGE->set_heading(
    get_string(
        'pluginname',
        'local_avisos'
    )
);

$manager = new manager();

$records = $manager->get_visible();

$notices = [];

foreach ($records as $record) {

    $notices[] = [
        'id' => $record->id,
        'title' => format_string(
            $record->title
        ),
        'message' => format_text(
            $record->message,
            FORMAT_PLAIN
        ),
        'timestart' => !empty($record->timestart)
            ? userdate($record->timestart)
            : null,
        'timeend' => !empty($record->timeend)
            ? userdate($record->timeend)
            : null,
    ];
}

$data = [
    'notices' => $notices,
    'hasnotices' => !empty($notices),
];

echo $OUTPUT->header();

echo $OUTPUT->render_from_template(
    'local_avisos/index',
    $data
);

echo $OUTPUT->footer();