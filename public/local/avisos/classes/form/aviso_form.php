<?php

namespace local_avisos\form;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->libdir . '/formslib.php');

// aqui vai ficar o formulário do plugin, que é a interface que o usuário vai interagir para criar, editar e excluir os avisos
// não acessa o banco de dados, apenas define os campos do formulário e valida os dados

class aviso_form extends \moodleform
{
    public function definition()
    {
        $mform = $this->_form;

        //exemplo: cria <input type="hidden" name="id">
        
        $mform->addElement(
            'hidden',
            'id'
        );

        $mform->setType(
            'id',
            PARAM_INT
        );

        $mform->addElement(
            'text',
            'title',
            get_string('title', 'local_avisos')
        );

        $mform->setType(
            'title',
            PARAM_TEXT
        );

        $mform->addRule(
            'title',
            get_string('required'),
            'required',
            null,
            'client'
        );

        $mform->addElement(
            'textarea',
            'message',
            get_string('message', 'local_avisos'),
            [
                'rows' => 8,
                'cols' => 60,
            ]
        );

        $mform->setType(
            'message',
            PARAM_TEXT
        );

        $mform->addRule(
            'message',
            get_string('required'),
            'required'
        );

        $mform->addElement(
            'advcheckbox',
            'enabled',
            get_string('enabled', 'local_avisos')
        );

        $mform->setDefault(
            'enabled',
            1
        );

        $mform->addElement(
            'date_time_selector',
            'timestart',
            get_string('timestart', 'local_avisos'),
            [
                'optional' => true,
            ]
        );

        $mform->addElement(
            'date_time_selector',
            'timeend',
            get_string('timeend', 'local_avisos'),
            [
                'optional' => true,
            ]
        );

        $this->add_action_buttons();
    }

    public function validation($data, $files)
    {
        $errors = parent::validation($data, $files);

        if (
            !empty($data['timestart'])
            &&
            !empty($data['timeend'])
            &&
            $data['timeend'] <= $data['timestart']
        ) {
            $errors['timeend'] =
                get_string(
                    'timeenderror',
                    'local_avisos'
                );
        }

        return $errors;
    }
}