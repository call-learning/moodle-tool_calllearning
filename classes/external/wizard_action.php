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
 * Wizard action
 *
 * @package tool_calllearning
 * @copyright 2025 Laurent David <laurent@call-learning.fr>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_calllearning\external;

use core_external\external_api;
use core_external\external_function_parameters;
use core_external\external_single_structure;
use core_external\external_value;

class wizard_action extends external_api {
    /**
     * Checks the parameters and executes the action.
     *
     * @param string $currentstepuid The UID of the current step.
     * @param string $wizarduid The UID of the wizard.
     * @param string $action The action to execute, either 'next' or 'previous'.
     * @return array An array containing the next step UID, modal type, and modal content
     * @throws \restricted_context_exception
     */
    public static function execute(
        string $currentstepuid,
        string $wizarduid,
        string $action,
    ): array {

        [
            'currentstepuid' => $currentstepuid,
            'wizarduid' => $wizarduid,
            'action' => $action,
        ] = self::validate_parameters(self::execute_parameters(), [
            'currentstepuid' => $currentstepuid,
            'wizarduid' => $wizarduid,
            'action' => $action,
        ]);

        $wizard = \tool_calllearning\local\wizard\wizard_manager::from_wizard_id($wizarduid);
        switch ($action) {
            case 'next':
                $nextstep = $wizard->get_next_step($currentstepuid);
                if ($nextstep === null) {
                    throw new \moodle_exception('No next step found for the current step.');
                }
                break;
            case 'previous':
                $nextstep = $wizard->get_previous_step($currentstepuid);
                if ($nextstep === null) {
                    throw new \moodle_exception('No previous step found for the current step.');
                }
                break;
            default:
                throw new \moodle_exception('Invalid action specified.');
        }
        return [
            'nextstep' => $nextstep->get_uid(),
            'modaltype' => $nextstep->get_modal_type(),
            'modalcontentclass' => $nextstep->get_modal_content_class(),
        ];
    }

    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'currentstepuid' => new external_value(PARAM_ALPHAEXT, 'The UID of the current step'),
            'wizarduid' => new external_value(PARAM_ALPHAEXT, 'The UID of the wizard'),
            'action' => new external_value(PARAM_ALPHAEXT, 'The action to execute'),
        ]);
    }

    /**
     * Describe the return structure of the external service.
     *
     * @return external_single_structure
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'nextstep' => new external_value(PARAM_ALPHANUMEXT, 'The next step of the wizard'),
            'modaltype' => new external_value(PARAM_ALPHA, 'The type of modal to display'),
            'modalcontentclass' => new external_value(PARAM_RAW, 'The full class of the modal content'),
        ]);
    }
}