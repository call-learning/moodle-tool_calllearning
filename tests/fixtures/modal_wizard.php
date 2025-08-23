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
     * @package tool_calllearning
     * @copyright 2025 Laurent David <laurent@call-learning.fr>
     * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
     */

    use tool_calllearning\local\wizard\wizard_info;
    use tool_calllearning\local\wizard\wizard_manager;
    use tool_calllearning\local\wizard\wizard_step;
    use tool_calllearning\output\wizard_action_button;

    require_once(__DIR__ . '/../../../../../config.php');

    global $CFG, $PAGE, $OUTPUT;
    $PAGE->set_url('/admin/tool/calllearning/tests/fixtures/modal_wizard.php');
    $PAGE->add_body_class('limitedwidth');
    require_login();
    $PAGE->set_context(core\context\system::instance());
    $PAGE->set_title('Wizard modal test page');
    $wizardinfo = new wizard_info();
    $wizardinfo->add_step(wizard_step::create(
        [
            'type' => 'content',
            'title' => 'This is the title for step 2',
            'contentstatic' => '<p>This is some static content for step 1</p>'
        ]
    ));
    $wizardinfo->add_step(wizard_step::create(
        [
            'type' => 'content',
            'title' => 'This is the title for step 2',
            'contentwidget' => 'tool_calllearning\fixtures\wizard\forms\example_content'
        ]
    ));
    $wizardinfo->add_step(wizard_step::create(
        [
            'type' => 'form',
            'title' => 'This is the title for step 2',
            // Usually a form class (like: \tool_calllearning\local\wizard\forms\example_form), but we need to be able to test it with
            // Behat so we point to a fake form class that belongs to the fixtures and not depend on autoloading.
            'formclass' => 'tool_calllearning\fixtures\wizard\forms\example_form'
        ]
    ));

    $wmanager = new wizard_manager(
        $wizardinfo
    );
    /* @var \core_renderer $OUTPUT */
    echo $OUTPUT->header();
    echo $OUTPUT->heading('Wizard modal test page');
    $wizardwg = new wizard_action_button(
        $wmanager->get_wizard_uid(),
        "Wizard modal test",
        '<p>Wizard modal test <strong>Super</strong>page</p>'
    );
    echo $OUTPUT->render($wizardwg);
    echo $OUTPUT->footer();