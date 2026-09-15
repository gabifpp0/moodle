<?php

require_once('../../config.php');

use local_avisos\form\aviso_form;
use local_avisos\local\manager;

// esse arquivo é responsável por criar e editar avisos institucionais
// 

require_login();

$context = context_system::instance();

require_capability(
    'local/avisos:manage',
    $context
);

$id = optional_param(
    'id',
    0,
    PARAM_INT
);

$url = new moodle_url(
    '/local/avisos/edit.php'
);

if ($id) {
    $url->param('id', $id);
}

$PAGE->set_url($url);
$PAGE->set_context($context);

$manager = new manager();

if ($id) {

    $record = $manager->get($id);

    $PAGE->set_title(
        get_string(
            'editnotice',
            'local_avisos'
        )
    );

    $PAGE->set_heading(
        get_string(
            'editnotice',
            'local_avisos'
        )
    );

} else {

    $record = null;

    $PAGE->set_title(
        get_string(
            'createnotice',
            'local_avisos'
        )
    );

    $PAGE->set_heading(
        get_string(
            'createnotice',
            'local_avisos'
        )
    );
}

$form = new aviso_form($url);

if ($record) {
    $form->set_data($record);
}

if ($form->is_cancelled()) {

    redirect(
        new moodle_url(
            '/local/avisos/manage.php'
        )
    );

}

if ($data = $form->get_data()) {

    $manager->save($data);

    redirect(
        new moodle_url(
            '/local/avisos/manage.php'
        ),
        get_string(
            'noticesaved',
            'local_avisos'
        ),
        null,
        \core\output\notification::NOTIFY_SUCCESS
    );

}

echo $OUTPUT->header();

$form->display();

echo $OUTPUT->footer();