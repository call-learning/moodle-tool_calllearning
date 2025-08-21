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
 * @package tool_calllearning\output
 * @copyright 2025 Laurent David <laurent@call-learning.fr>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class wizard_step {
    public function __construct(
        protected string $uid,
        protected string $title,
        protected string $description,
        protected string $icon,
        protected string $action,
        protected ?string $nextstepuid = null,
        protected ?string $previousstepuid = null,
        protected ?string $template = null,
        protected ?string $formclass = null,
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
     * Get the template for the modal
     *
     * @return string The title of the step.
     */
    public function get_template() {
        return $this->template;
    }
}
