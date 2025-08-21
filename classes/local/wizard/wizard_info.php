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

/**
 * A class representing the steps of the wizard.
 *
 * @package tool_calllearning\output
 * @copyright 2025 Laurent David <laurent@call-learning.fr>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class wizard_info {
    public function __construct(
        protected array $wizardsteps,
    ) {
        // Initialize the wizard steps array.
        $this->wizardsteps = array_values($this->wizardsteps); // Make sure the array is indexed from 0.
    }

    /**
     * Add a step to the wizard.
     *
     * @param wizard_step $step The step to add.
     */
    public function add_step(wizard_step $step): void {
        $this->wizardsteps[] = $step;
    }

    public function get_steps(): array {
        return $this->wizardsteps;
    }

    /**
     * Get a step by its UID.
     *
     * @param string $uid The UID of the step to get.
     * @return wizard_step|null The step if found, null otherwise.
     */
    public function get_next_step(string $currentstepuid): ?wizard_step {
        // First case there is a next step defined.
        foreach ($this->wizardsteps as $step) {
            if ($step->nextstepuid === $currentstepuid) {
                return $step;
            }
        }
        $currenstepindex = $this->get_step_index($currentstepuid);
        return $this->wizardsteps[$currenstepindex + 1] ?? null;
        return null;
    }

    /**
     * Get the step index for this given step UID.
     *
     * @param string $currentstepuid The UID of the current step.
     * @return int The index of the step in the wizard steps array, or -1 if not found.
     */
    public function get_step_index(string $currentstepuid): int {
        foreach ($this->wizardsteps as $index => $step) {
            if ($step->uid === $currentstepuid) {
                return $index;
            }
        }
        return -1; // Return -1 if the step is not found.
    }

    /**
     * Get the previous step of the wizard.
     *
     * @param string $currentstepuid The UID of the current step.
     * @return wizard_step|null The previous step if found, null otherwise.
     */
    public function get_previous_step(string $currentstepuid): ?wizard_step {
        // First case there is a previous step defined.
        foreach ($this->wizardsteps as $step) {
            if ($step->previousstepuid === $currentstepuid) {
                return $step;
            }
        }
        $currenstepindex = $this->get_step_index($currentstepuid);
        return $this->wizardsteps[$currenstepindex - 1] ?? null;
    }
    /**
     * Convert the wizard info to JSON format.
     *
     * @return string JSON representation of the wizard info.
     */
    public function to_json(): string {
        $data = [
            'wizardsteps' => array_map(function($step) {
                return $step->to_array();
            }, $this->wizardsteps)
        ];
        return json_encode($data);
    }

    /**
     * From JSON format to wizard info.
     */
    public static function from_json(string $json): self {
        $data = json_decode($json, true);
        $wizardsteps = [];
        foreach ($data['wizardsteps'] as $stepdata) {
            $wizardsteps[] = new wizard_step(
                $stepdata['uid'],
                $stepdata['title'],
                $stepdata['description'],
                $stepdata['icon'],
                $stepdata['action'],
                $stepdata['nextstepuid'] ?? null,
                $stepdata['previousstepuid'] ?? null,
                $stepdata['template'] ?? null,
                $stepdata['formclass'] ?? null
            );
        }
        return new self($wizardsteps);
    }

    /**
     * Get a step at a specific index.
     *
     * @param int $int The index of the step to get.
     * @return wizard_step|null The step if found, null otherwise.
     */
    public function get_step_at_index(int $int): ?wizard_step {
        if (isset($this->wizardsteps[$int])) {
            return $this->wizardsteps[$int];
        }
        return null; // Return null if the step is not found.
    }

    /**
     * Get a step by its UID.
     *
     * @param string $stepid The UID of the step to get.
     * @return wizard_step|null The step if found, null otherwise.
     */
    public function get_step_by_uid(string $stepid): ?wizard_step {
        foreach ($this->wizardsteps as $step) {
            if ($step->get_uid() === $stepid) {
                return $step;
            }
        }
        return null; // Return null if the step is not found.
    }

}
