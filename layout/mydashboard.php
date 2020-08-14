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
 * A two column layout for the ledor theme.
 *
 * @package   theme_ledor
 * @copyright 2016 Damyon Wiese
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

user_preference_allow_ajax_update('drawer-open-nav', PARAM_ALPHA);
require_once(dirname($CFG->libdir) . '/course/externallib.php');
require_once(dirname($CFG->libdir) . '/course/classes/external/course_summary_exporter.php');

if (isloggedin()) {
    $navdraweropen = (get_user_preferences('drawer-open-nav', 'true') == 'true');
} else {
    $navdraweropen = false;
}
$extraclasses = [];
if ($navdraweropen) {
    $extraclasses[] = 'drawer-open-left';
}
$bodyattributes = $OUTPUT->body_attributes($extraclasses);
$blockshtml = $OUTPUT->blocks('side-pre');
$hasblocks = strpos($blockshtml, 'data-block=') !== false;
$regionmainsettingsmenu = $OUTPUT->region_main_settings_menu();
$context = get_ledor_template_context();

$requiredproperties = \core_course\external\course_summary_exporter::define_properties();
$fields = join(',', array_keys($requiredproperties));
$hiddencourses = get_hidden_courses_on_timeline();
$courses = course_get_enrolled_courses_for_logged_in_user(0, 0, 'fullname', $fields, COURSE_DB_QUERY_LIMIT, [], $hiddencourses);
list($inprogress, $processedcount) = course_filter_courses_by_timeline_classification($courses,  'inprogress', 0);
$context["inprogress"] = $inprogress;
foreach ($inprogress as $course) {
    $course->viewurl = new moodle_url("/course/view.php?id=:id", ['id'=>$course->id]);
};
// echo "<pre>";foreach ($filteredcourses as $value) {var_dump($value);}die();

echo $OUTPUT->render_from_template('theme_ledor/mydashboard', $context);
