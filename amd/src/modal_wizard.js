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
 * General modal wizard.
 *
 * @module     tool_calllearning/modal_wizard
 * @copyright  2025 Laurent David <laurent@call-learning.fr>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

import Modal from 'core/modal';
import Notification from 'core/notification';
import * as CustomEvents from 'core/custom_interaction_events';
import $ from 'jquery';
import WizardRepository from './wizard_repository';
/**
 * ModalWizard class
 */
export default class ModalWizard extends Modal {
    static TYPE = 'WIZARD';
    static TEMPLATE = 'tool_calllearning/modal_wizard';
    static EVENTS = {
        next: 'modal-wizard:next',
        previous: 'modal-wizard:previous',
    };

    wizardUniqueId = null;
    currentStepId = null;

    /**
     * Constructor
     *
     * Shows the required form inside a modal dialogue
     *
     * @param {HTMLElement} root The root element of the modal.
     */
    constructor(root) {
        super(root);
        this.removeOnClose = true; // Default to removing the modal on close.
        if (!this.getFooter().find(this.getActionSelector('cancel')).length) {
            Notification.exception({message: 'No cancel button found'});
        }
    }


    /**
     * Configure the modal.
     *
     * @param {ModalConfig} param0 The configuration options
     */
    configure(param0 = {}) {
        super.configure(param0);
        const {wizardUniqueId = null} = param0;
        this.wizardUniqueId = wizardUniqueId;
        if (!this.wizardUniqueId) {
            Notification.exception({message: 'No wizardUniqueId provided'});
        }
    }
    /**
     * Register all event listeners.
     */
    registerEventListeners() {
        // Call the parent registration.
        super.registerEventListeners();

        // Register to close on save/cancel.
        this.registerCloseOnSave();
        this.registerCloseOnCancel();
        this.registerEventNext();
        this.registerEventPrevious();
    }

    /**
     * Register a listener to update the content when next button is pressed.
     *
     * @method registerEventNext
     */
    registerEventNext() {
        // Handle the clicking of the Cancel button.
        this.getModal().on(CustomEvents.events.activate, this.getActionSelector('next'), (e, data) => {
            const nextEvent = new CustomEvent(ModalWizard.EVENTS.next, {detail: this});
            this.getRoot().trigger(nextEvent, this);
            if (!nextEvent.isDefaultPrevented()) {
                data.originalEvent.preventDefault();

            }
        });
    }

    /**
     * Register a listener update the content when previous button is pressed.
     *
     * @method registerEventPrevious
     */
    registerEventPrevious() {
        // Handle the clicking of the Cancel button.
        this.getModal().on(CustomEvents.events.activate, this.getActionSelector('previous'), (e, data) => {
            const previousEvent = new CustomEvent(ModalWizard.EVENTS.previous, {detail: this});
            this.getRoot().trigger(previousEvent, this);
            if (!previousEvent.isDefaultPrevented()) {
                data.originalEvent.preventDefault();
            }
        });
    }

    /**
     * Load content dynamically for the wizard step using the WizardRepository.
     *
     * If step is empty, we load the first step (indexed as 0).
     * @param {String} stepId The ID of the step to load.
     */
    loadStepContent(stepId = '') {
        const params = {
            stepid: stepId,
            wizardid: this.wizardUniqueId
        };
        WizardRepository.getStepContent(params)
            .then((response) => {
                this.currentStepId = response.stepid;
                if (response.type === 'form') {
                    this.setFormContent(response.html);
                } else {
                    this.setNonInteractiveContent(response.html);
                }
            })
            .catch(() => {
                Notification.alert('Error', 'Failed to load step content.', '');
            });
    }

    /**
     * Set the content for a form step.
     *
     * @param {String} html The HTML content of the form.
     */
    setFormContent(html) {
        const bodyContent = Promise.resolve({html: html});
        this.getModal().setBodyContent(bodyContent);

        // Attach form validation logic.
        this.getModal().getRoot().on('submit', 'form', (e) => {
            e.preventDefault();
            if (this.validateForm()) {
                this.submitFormAjax();
            }
        });
    }

    /**
     * Set the content for a non-interactive step.
     *
     * @param {String} html The HTML content.
     */
    setNonInteractiveContent(html) {
        const bodyContent = Promise.resolve({html: html});
        this.getModal().setBodyContent(bodyContent);
    }

    /**
     * Submit the form via the WizardRepository's submitDynamicForm method.
     */
    async submitFormAjax() {
        // Validate form elements before submission.
        if (!this.validateForm()) {
            Notification.alert('Validation Error', 'Please correct the errors in the form.', '');
            return;
        }

        // Disable buttons during submission.
        this.disableButtons();

        // Serialize form data.
        const form = this.getModal().getRoot().find('form');
        const formData = form.serialize();

        // Use the WizardRepository to submit the form.
        WizardRepository.submitDynamicForm({
            formdata: formData,
            form: 'form_wizard'
        })
        .then((response) => {
            if (response.error) {
                // Handle server-side validation errors.
                this.setFormContent(response.exception.message);
                this.enableButtons();
            } else if (!response.data.submitted) {
                // Reload the form with validation errors.
                this.setFormContent(response.data.html);
                this.enableButtons();
            } else {
                // Form submitted successfully, proceed to the next step.
                this.loadStepContent(response.data.nextstep);
            }
        })
        .catch((exception) => {
            Notification.exception(exception);
            this.enableButtons();
        });
    }

    /**
     * Validate form elements.
     *
     * @return {Boolean} Whether client-side validation has passed.
     */
    validateForm() {
        const invalid = this.getModal().getRoot().find('[aria-invalid="true"], .error');
        if (invalid.length) {
            invalid.first().focus();
            return false;
        }
        return true;
    }

    /**
     * Disable buttons during form submission.
     */
    disableButtons() {
        this.getModal().getFooter().find('[data-action]').attr('disabled', true);
    }

    /**
     * Enable buttons after form submission (on validation error).
     */
    enableButtons() {
        this.getModal().getFooter().find('[data-action]').removeAttr('disabled');
    }
}

ModalWizard.registerModalType();
