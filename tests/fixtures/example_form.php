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
namespace fixtures;
/**
 * Dynamic form for wizard actions.
 *
 * @package tool_calllearning
 * @copyright  2025 Laurent David <laurent@call-learning.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class example_form extends dynamic_form {

    /**
     * The current step in the form wizard.
     *
     * @var int $currentStep
     */
    private int $currentStep = 1;

    /**
     * Process the dynamic form submission.
     *
     * @return array Array containing the result of the form processing
     * @throws moodle_exception If access is denied
     */
    public function process_dynamic_submission(): array {
        $this->check_access_for_dynamic_submission();
        $formdata = $this->get_data();
        $action = $this->get_action();
        $processed = $modinfo->process_action_data($formdata, $action);

        if (!$processed['success']) {
            return [
                'result' => true,

            ];
        }
        return [
            'result' => true,
            'message' => $processed['message'] ?? get_string('actionprocessed', 'tool_calllearning'),
        ];
    }

    /**
     * Get the context for dynamic form submission.
     *
     * @return context The module context
     * @throws moodle_exception If module cannot be found
     */
    protected function get_context_for_dynamic_submission(): context {
        return \context_system::instance();
    }

    /**
     * Check access permissions for dynamic form submission.
     *
     * @return void
     * @throws moodle_exception If user doesn't have required capability
     */
    protected function check_access_for_dynamic_submission(): void {
        // Nothing to do here for the example.
    }

    /**
     * Get the page URL for dynamic form submission.
     *
     * @return moodle_url The URL to redirect to after form submission
     * @throws moodle_exception If component is invalid
     */
    protected function get_page_url_for_dynamic_submission(): moodle_url {
        return new moodle_url('admin/tool/calllearning/tests/fixtures/modal_wizard.php');
    }

    /**
     * Define the form elements for the wizard steps.
     *
     * @return void
     */
    protected function definition() {
        $mform = $this->_form;

        // Text field for sample input.
        $mform->addElement('text', 'sampletext', 'Sample Text');
        $mform->setType('sampletext', PARAM_TEXT);
        $mform->addRule('sampletext', null, 'required', null, 'client');

        // Checkbox for sample boolean input.
        $mform->addElement('advcheckbox', 'samplecheckbox', 'Sample Checkbox');
        $mform->setType('samplecheckbox', PARAM_BOOL);
    }
}
