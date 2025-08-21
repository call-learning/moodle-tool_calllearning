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
 * Web services
 *
 * @package tool_calllearning
 * @copyright 2025 Laurent David <laurent@call-learning.fr>
 * @license http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

$functions = [
    'tool_calllearning_wizard_action' => [
        'classname' => \tool_calllearning\external\wizard_action::class,
        'methodname' => 'execute',
        'description' => 'Execute an action and returns the next step of the wizard.',
        'type' => 'write',
        'ajax' => true,
        'loginrequired' => true,
    ],
];