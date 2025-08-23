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

namespace tool_calllearning\local\wizard;

use InvalidArgumentException;

/**
 * A class representing the manager for the wizard steps.
 *
 * @package tool_calllearning
 * @copyright 2025 Laurent David <laurent@call-learning.fr>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class wizard_manager {
    /**
     * The wizard steps information.
     *
     * @param wizard_info $wizardinfo The wizard steps information.
     */
    public function __construct(
        protected wizard_info $wizardinfo,
        protected string $currentwizardid = "",
        protected int $currentstepindex = 0,
    ) {
        if (empty($this->currentwizardid)) {
            $this->currentwizardid = uniqid('wizard_', true);
        }
        // Initialize the current step index.
        $this->currentstepindex = 0;
        // If there are steps defined, set the current step index to the first step.
        $this->set_stored_current_stepid(
            $this->wizardinfo->get_step_at_index($this->currentstepindex)->get_uid() ?? ''
        );
        $this->store_current_wizard_info();
    }

    /**
     * Set the current step ID in the session.
     *
     * @param string $stepid The step ID to set as the current step.
     */
    protected function set_stored_current_stepid(string $stepid): void {
        global $SESSION; // We assume that the current step ID is stored in the session.
        $SESSION->calllearning_wizard_currentstepid = $stepid;
    }

    /**
     * Store the current wizard information in the session if needed.
     *
     * This method stores the current wizard information in the session so that it can be retrieved later.
     */
    protected function store_current_wizard_info(): void {
        global $SESSION; // We assume that the current step ID is stored in the session.
        if (empty($SESSION->calllearning_wizard_info)) {
            $SESSION->calllearning_wizard_info = [];
        }
        if (empty($SESSION->calllearning_wizard_info[$this->currentwizardid])) {
            $SESSION->calllearning_wizard_info[$this->currentwizardid] = $this->wizardinfo->to_json();
        }
    }

    /**
     * Get the wizard info.
     *
     * @return wizard_info The wizard info.
     */
    public static function from_wizard_id(int $wizardid): ?self {
        global $SESSION;
        if (empty($SESSION->calllearning_wizard_info[$wizardid])) {
            return null;
        }
        $wizardinfo = wizard_info::from_json($SESSION->calllearning_wizard_info[$wizardid]);
        return new self($wizardinfo, $wizardid);
    }

    /**
     * Get the step at a specific index.
     *
     * @param int $index The index of the step to get.
     * @return wizard_step|null The step at the specified index or null if not found.
     */
    public function set_current_step_from_index(int $stepindex): void {
        // Check if the step index is valid.
        if ($stepindex < 0 || $stepindex >= count($this->wizardinfo->get_steps())) {
            throw new InvalidArgumentException('Invalid step index provided.');
        }
        // Set the current step index.
        $this->currentstepindex = $stepindex;
        // Set the stored current step ID in the session.
        $this->set_stored_current_stepid(
            $this->wizardinfo->get_step_at_index($this->currentstepindex)->get_uid() ?? ''
        );
    }

    /**
     * Get the step at a specific index.
     *
     * @param string $stepid The ID of the step to get.
     * @return wizard_step|null The step at the specified index or null if not found.
     */
    public function set_current_step(string $stepid): void {
        // Check if the step ID is valid.
        $step = $this->wizardinfo->get_step_by_uid($stepid);
        if ($step === null) {
            throw new InvalidArgumentException('Invalid step ID provided.');
        }
        // Set the current step index.
        $this->currentstepindex = $this->wizardinfo->get_step_index($stepid);
        // Set the stored current step ID in the session.
        $this->set_stored_current_stepid($stepid);
    }

    /**
     * Get the current step.
     *
     * @return wizard_step|null The current step or null if no steps are defined.
     */
    public function get_current_step(): ?wizard_step {
        if (empty($this->wizardinfo->get_steps())) {
            return null;
        }
        return $this->wizardinfo->get_steps()[$this->currentstepindex] ?? null;
    }

    /**
     * Move to the next step if it exists.
     *
     * @return wizard_step|null The next step or null if no next step is defined.
     */
    public function action_next_step(): ?wizard_step {
        $nextstep = $this->wizardinfo->get_next_step($this->get_stored_current_stepid());
        if ($nextstep !== null) {
            $this->set_stored_current_stepid($nextstep->get_uid());
        }
        return $nextstep;
    }

    /**
     * Get the stored current step ID from the session.
     *
     * @return wizard_step|null The previous step or null if no previous step is defined.
     */
    protected function get_stored_current_stepid(): string {
        global $SESSION; // We assume that the current step ID is stored in the session.
        return $SESSION->calllearning_wizard_currentstepid ?? '';
    }

    /**
     * Move to the previous step if it exists.
     *
     * @return wizard_step|null The previous step or null if no next step is defined.
     */
    public function action_previous_step(): ?wizard_step {
        $previousstep = $this->wizardinfo->get_next_step($this->get_stored_current_stepid());
        if ($previousstep !== null) {
            $this->set_stored_current_stepid($previousstep->get_uid());
        }
        return $previousstep;
    }

    /**
     * Get the current wizard ID.
     *
     * @return string The current wizard ID (it should be unique).
     */
    public function get_wizard_uid(): string {
        return $this->currentwizardid;
    }
}
