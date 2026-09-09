<?php

require_once('../../config.php');

require_login();

$context = context_system::instance();

$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/avisos/index.php'));
$PAGE->set_title('Avisos institucionais');
$PAGE->set_heading('Avisos institucionais');

echo $OUTPUT->header();

echo html_writer::tag('h2', 'Meu primeiro plugin Moodle');
echo html_writer::tag('p', 'Olá, Moodle!');

echo $OUTPUT->footer();