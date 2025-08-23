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

namespace tool_calllearning\output;

use core\output\renderer_base;

/**
 * Class wizard_action_button
 *
 * @package tool_calllearning\output
 * @copyright 2025 Laurent David <laurent@call-learning.fr>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class wizard_action_button implements \renderable, \templatable {

    /**
     * Constructor for the wizard class.
     */
    public function __construct(
        protected string $wizarduid,
        protected string $title = '',
        protected string $content = '',
        protected int $currentstep = 0,
        protected ?string $nextbuttontext = null,
        protected ?string $previousbuttontext = null,
        protected ?string $finishbuttontext = null,
        protected ?string $cancelbuttontext = null
    ) {
    }

    public function export_for_template(renderer_base $output): object {
        $data = new \stdClass();
        $data->title = $this->title;
        $data->currentstep = $this->currentstep;
        $data->nextbuttontext = $this->nextbuttontext ?? get_string('next');
        $data->previousbuttontext = $this->previousbuttontext ?? get_string('previous');
        $data->finishbuttontext = $this->finishbuttontext ?? get_string('save');
        $data->cancelbuttontext = $this->cancelbuttontext ?? get_string('cancel');
        $data->body = $this->content;
        $data->wizarduid = $this->wizarduid;
        return $data;
    }
}