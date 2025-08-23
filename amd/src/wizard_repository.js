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
 * Gateway to the webservices.
 *
 * @module     tool_calllearning/modal_wizard
 * @copyright  2025 Laurent David <laurent@call-learning.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Ajax from 'core/ajax';
import Notification from 'core/notification';

/**
 * Wizard repository class.
 */
class WizardRepository {

    /**
     * Get action for this wizard (next, previous, finish).
     * @param {Object} args The data to get.
     * @return {Promise} The promise.
     */
    static getFollowingAction(args) {
        const request = {
            methodname: 'tool_calllearning_wizard_action',
            args: args
        };
        return Ajax.call([request])[0]
            .fail(Notification.exception);
    }

    /**
     * Get action for this wizard (next, previous, finish).
     * @param {Object} args The data to get.
     * @return {Promise} The promise.
     */
    static getStepContent(args) {
        const request = {
            methodname: 'tool_calllearning_wizard_step_content',
            args: args
        };
        return Ajax.call([request])[0]
            .fail(Notification.exception);
    }

    /**
     * Submit form data via the core_form_dynamic_form web service.
     *
     * @param {Object} args The form data to submit.
     * @return {Promise} The promise resolving the server response.
     */
    static submitDynamicForm(args) {
        const request = {
            methodname: 'core_form_dynamic_form',
            args: args
        };
        return Ajax.call([request])[0]
            .fail(Notification.exception);
    }
}

export default WizardRepository;