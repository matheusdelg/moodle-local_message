<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local message version information
 *
 * @package    local
 * @subpackage message
 * @copyright  2021 Matheus Delgado de Azevedo
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../config.php');
require_once($CFG->dirroot . '/local/message/classes/form/edit.php');


global $DB;

// Configurações básicas de uma página no Moodle:
$PAGE->set_url(new moodle_url('/local/message/manage.php'));
$PAGE->set_context(\context_system::instance()); // Mais em: https://docs.moodle.org/39/en/Context
$PAGE->set_title(get_string('pluginname', 'local_message'));

// Idealmente, o processamento de formulários deve ser feito antes da renderização da página. A 
// definição do objeto form está na pasta "classes/form":
$mform = new edit_message_form();

if ($mform->is_cancelled()) {
    //Handle form cancel operation, if cancel button is present on form
    redirect($CFG->wwwroot . '/local/message/manage.php');
}
else if ($fromform = $mform->get_data()) {
    //In this case you process validated data. $mform->get_data() returns data posted in form.
    $record = (object) [
        'messagetext' => $fromform->messagetext,
        'messagetype' => $fromform->messagetype,
    ];

    $DB->insert_record('local_message', $record);
    redirect($CFG->wwwroot . '/local/message/manage.php', get_string('notificationadded', 'local_message'));
} 

// O objeto $OUTPUT renderiza o visual da página.
echo $OUTPUT->header();
//displays the form
$mform->display();
echo $OUTPUT->footer();