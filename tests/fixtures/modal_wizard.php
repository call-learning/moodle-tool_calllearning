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
 * Test page for the wizard modal.
 *
 * @copyright 2025 Laurent David <laurent@call-learning.fr>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__ . '/../../../../../config.php');

//defined('BEHAT_SITE_RUNNING') || die();


global $CFG, $PAGE, $OUTPUT;
$PAGE->set_url('/admin/tool/calllearning/tests/fixtures/modal_wizard.php');
$PAGE->add_body_class('limitedwidth');
require_login();
$PAGE->set_context(core\context\system::instance());
$PAGE->set_title('Wizard modal test page');
/* @var \core_renderer $OUTPUT */
echo $OUTPUT->header();
echo $OUTPUT->heading('Wizard modal test page');
$wizardwg = new \tool_calllearning\output\wizard_action_button(
    'Hello there !',
    '<p>Wizard modal test <strong>Super</strong>page</p>>'
);
echo $OUTPUT->render($wizardwg);
echo $OUTPUT->footer();