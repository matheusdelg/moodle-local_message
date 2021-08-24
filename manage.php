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

global $DB;

// Configurações básicas de uma página no Moodle:
$PAGE->set_url(new moodle_url('/local/message/manage.php'));
$PAGE->set_context(\context_system::instance()); // Mais em: https://docs.moodle.org/39/en/Context
$PAGE->set_title("Notificações do sistema");


// Informações do BD com o objeto $DB:
$messages_from_db = $DB->get_records('local_message');

// Para renderizar páginas a partir de um template, é necessário criar um arquivo .mustache na
// pasta "templates", e referenciar pelo método $OUTPUT->render_from_template. A variável
// $templateContext é um objeto PHP com pares chave-conteúdo.
$templateContext = (object) [
    'plugin_title' => 'Notificações do sistema',
    'plugin_desc'  => 'Mensagens e notificações a serem exibidas no sistema. É possível gerenciar
    os conteúdos e mensagens a partir da opção',
    'plugin_edit' => 'Editar mensagens',
    'current_messages' => array_values($messages_from_db),
    'edit_url' => new moodle_url('/local/message/edit.php'), 
];

// O objeto $OUTPUT renderiza o visual da página.
echo $OUTPUT->header();

echo $OUTPUT->render_from_template('local_message/manage', $templateContext);

echo $OUTPUT->footer();
 

