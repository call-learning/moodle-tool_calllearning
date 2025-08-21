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

class wizard_action extends external_api {
    /**
     * Returns description of method parameters
     *
     * @return external_function_parameters
     */
    public static function execute_parameters(): external_function_parameters {
        return new external_function_parameters([
            'cmid' => new external_value(PARAM_INT, 'course module id', VALUE_REQUIRED),
            'groupid' => new external_value(PARAM_INT, 'bigbluebuttonbn group id', VALUE_DEFAULT, 0),
        ]);
    }

    /**
     * Checks the
     *
     * @param null|int $groupid
     * @return array (empty array for now)
     * @throws \restricted_context_exception
     */
    public static function execute(
        int $currentstep,
        string $action,
    ): array {


        return $result;
    }

    /**
     * Describe the return structure of the external service.
     *
     * @return external_single_structure
     * @since Moodle 3.3
     */
    public static function execute_returns(): external_single_structure {
        return new external_single_structure([
            'nextstep' => new external_value(PARAM_INT, 'The next step of the wizard'),
            'modaltype' => new external_value(PARAM_ALPHA, 'The type of modal to display'),
            'modalcontentclass' => new external_value(PARAM_ALPHAEXT, 'The full class of the modal content'),
        ]);
    }
}