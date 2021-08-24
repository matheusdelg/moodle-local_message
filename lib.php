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

function local_message_before_footer() {

    global $DB, $USER;

    $query = "SELECT lm.id, lm.messagetype, lm.messagetext FROM {local_message} lm
              LEFT JOIN {local_message_read} lmr ON lm.id = lmr.messageid
              WHERE lmr.userid <> :userid OR lmr.userid IS NULL";

    $all_notifications = $DB->get_records_sql($query, ['userid' => $USER->id]);

    // Salvar o tipo da mensagem no BD remove essa conversão com dicionários.
    $notification_type = [
        '0' => \core\output\notification::NOTIFY_INFO,
        '1' => \core\output\notification::NOTIFY_SUCCESS,
        '2' => \core\output\notification::NOTIFY_WARNING,
        '3' => \core\output\notification::NOTIFY_ERROR,
    ];

    // Mostra todas as mensagens ao usuário:
    foreach ($all_notifications as $n) {
        \core\notification::add($n->messagetext, $notification_type[$n->messagetype]);

        // Registra que o usuário leu as mensagens:
        $read_record = (object) [
            'messageid' => $n->id,
            'userid' => $USER->id,
            'timeread' => time(),
        ];

        $DB->insert_record('local_message_read', $read_record);
    }   
}