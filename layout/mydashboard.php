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
require_once($CFG->libdir . '/behat/lib.php');

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
// $templatecontext['flatnavigation'] = $PAGE->flatnav;
$context = get_ledor_template_context();
$context["hascourses"] = true;
//var_dump(array_keys(get_defined_vars()));die();
$context["inprogress"] = [];
foreach (course_get_enrolled_courses_for_logged_in_user() as $key => $course) {
    $context["inprogress"][] = (object)[
        "viewurl"=>new moodle_url("/course/view.php?id=:id", ['id'=>$course->id]),
        "fullname"=>$course->fullname,
    ];
};
/*
$DB->get_records_sql("SELECT   distinct c.id, c.fullname
FROM     avs2_course c 
            inner join avs2_enrol e on (c.id=e.courseid)
                inner join avs2_role r on (e.roleid=r.id and r.archetype='student')
                inner join avs2_user_enrolments ue on (e.id=ue.enrolid)
where    ue.userid = ?
order by 2", [$USER->id]);
*/
/*
[
    ["fullnamedisplay"=> "Course #1", "viewurl"=>"#"],
    ["fullnamedisplay"=> "Course #2", "viewurl"=>"#"]
];
*/
// echo "<pre>";foreach ($context["inprogress"] as $key => $value) {var_dump($value);}die();
echo $OUTPUT->render_from_template('theme_ledor/mydashboard', $context);
