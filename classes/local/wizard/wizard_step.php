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
 * A class representing a single step for the wizard.
 *
 * @package tool_calllearning
 * @copyright 2025 Laurent David <laurent@call-learning.fr>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class wizard_step {
    /**
     * Constructor.
     *
     * @param string $uid The unique identifier of the step.
     * @param string $title The title of the step.
     * @param string $description The description of the step.
     * @param string $icon The icon of the step.
     * @param string|null $nextstepuid The UID of the next step (if any).
     * @param string|null $previousstepuid The UID of the previous step (if any).
     */
    protected function __construct(
        protected string $uid,
        protected string $title,
        protected string $description,
        protected string $icon,
        protected ?string $nextstepuid = null,
        protected ?string $previousstepuid = null,
    ) {

    }

    /**
     * Get the title of the step.
     *
     * @return string The title of the step.
     */
    public function get_uid(): string {
        return $this->uid;
    }

    /**
     * Get the type of the step.
     *
     * @return string The type of the step.
     */
    abstract public function get_type(): string;


    /**
     * Create a wizard step from data object.
     *
     * @param object $data The data object containing step information.
     * @return self The created wizard step instance.
     * @throws \invalid_argument_exception If the step type is invalid.
     */
    public static function create(array $data): self {
        $data = (object)$data;
        $classmap = [
            'form' => wizard_step_form::class,
            'content' => wizard_step_static_content::class,
        ];
        if (!isset($classmap[$data->type])) {
            throw new \invalid_argument_exception('Invalid step type: ' . $data->type);
        }
        $classname = $classmap[$data->type];
        $uid = $data->uid ?? uniqid('step_', true);
        return new $classname(
            $data->uid,
            $data->title,
            $data->description ?? '',
            $data->icon ?? '',
            $data->nextstepuid ?? null,
            $data->previousstepuid ?? null,
        );
    }
}
