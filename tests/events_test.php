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
 * Events tests.
 *
 * @package    mod_giportfolio
 * @category   phpunit
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();
global $CFG;

/**
 * Events tests class.
 *
 * @package    mod_giportfolio
 * @category   phpunit
 * @copyright  2013 Frédéric Massart
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_giportfolio_events_testcase extends advanced_testcase {

    public function setUp() {
        $this->resetAfterTest();
    }

    public function test_chapter_created() {
        // There is no proper API to call to generate chapters for a giportfolio, so what we are
        // doing here is simply making sure that the events returns the right information.

        $course = $this->getDataGenerator()->create_course();
        $giportfolio = $this->getDataGenerator()->create_module('giportfolio', array('course' => $course->id));
        $giportfoliogenerator = $this->getDataGenerator()->get_plugin_generator('mod_giportfolio');
        $context = context_module::instance($giportfolio->cmid);

        $chapter = $giportfoliogenerator->create_chapter(array('giportfolioid' => $giportfolio->id));

        $event = \mod_giportfolio\event\chapter_created::create_from_chapter($giportfolio, $context, $chapter);

        // Triggering and capturing the event.
        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_giportfolio\event\chapter_created', $event);
        $this->assertEquals(context_module::instance($giportfolio->cmid), $event->get_context());
        $this->assertEquals($chapter->id, $event->objectid);
        $expected = array($course->id, 'giportfolio', 'add chapter', 'view.php?id='.$giportfolio->cmid.'&chapterid='.$chapter->id,
            $chapter->id, $giportfolio->cmid);
        $this->assertEventLegacyLogData($expected, $event);
        $this->assertEventContextNotUsed($event);
    }

    public function test_chapter_updated() {
        // There is no proper API to call to generate chapters for a giportfolio, so what we are
        // doing here is simply making sure that the events returns the right information.

        $course = $this->getDataGenerator()->create_course();
        $giportfolio = $this->getDataGenerator()->create_module('giportfolio', array('course' => $course->id));
        $giportfoliogenerator = $this->getDataGenerator()->get_plugin_generator('mod_giportfolio');
        $context = context_module::instance($giportfolio->cmid);

        $chapter = $giportfoliogenerator->create_chapter(array('giportfolioid' => $giportfolio->id));

        $event = \mod_giportfolio\event\chapter_updated::create_from_chapter($giportfolio, $context, $chapter);

        // Triggering and capturing the event.
        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_giportfolio\event\chapter_updated', $event);
        $this->assertEquals(context_module::instance($giportfolio->cmid), $event->get_context());
        $this->assertEquals($chapter->id, $event->objectid);
        $expected = array($course->id, 'giportfolio', 'update chapter', 'view.php?id='.$giportfolio->cmid.'&chapterid='.$chapter->id,
            $chapter->id, $giportfolio->cmid);
        $this->assertEventLegacyLogData($expected, $event);
        $this->assertEventContextNotUsed($event);
    }

    public function test_chapter_deleted() {
        // There is no proper API to call to delete chapters for a giportfolio, so what we are
        // doing here is simply making sure that the events returns the right information.

        $course = $this->getDataGenerator()->create_course();
        $giportfolio = $this->getDataGenerator()->create_module('giportfolio', array('course' => $course->id));
        $giportfoliogenerator = $this->getDataGenerator()->get_plugin_generator('mod_giportfolio');
        $context = context_module::instance($giportfolio->cmid);

        $chapter = $giportfoliogenerator->create_chapter(array('giportfolioid' => $giportfolio->id));

        $event = \mod_giportfolio\event\chapter_deleted::create_from_chapter($giportfolio, $context, $chapter);
        $legacy = array($course->id, 'giportfolio', 'update', 'view.php?id='.$giportfolio->cmid, $giportfolio->id, $giportfolio->cmid);

        // Triggering and capturing the event.
        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_giportfolio\event\chapter_deleted', $event);
        $this->assertEquals(context_module::instance($giportfolio->cmid), $event->get_context());
        $this->assertEquals($chapter->id, $event->objectid);
        $this->assertEventLegacyLogData($legacy, $event);
        $this->assertEventContextNotUsed($event);
    }

    public function test_course_module_instance_list_viewed() {
        // There is no proper API to call to trigger this event, so what we are
        // doing here is simply making sure that the events returns the right information.

        $course = $this->getDataGenerator()->create_course();
        $params = array(
            'context' => context_course::instance($course->id)
        );
        $event = \mod_giportfolio\event\course_module_instance_list_viewed::create($params);

        // Triggering and capturing the event.
        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_giportfolio\event\course_module_instance_list_viewed', $event);
        $this->assertEquals(context_course::instance($course->id), $event->get_context());
        $expected = array($course->id, 'giportfolio', 'view all', 'index.php?id='.$course->id, '');
        $this->assertEventLegacyLogData($expected, $event);
        $this->assertEventContextNotUsed($event);
    }

    public function test_course_module_viewed() {
        // There is no proper API to call to trigger this event, so what we are
        // doing here is simply making sure that the events returns the right information.

        $course = $this->getDataGenerator()->create_course();
        $giportfolio = $this->getDataGenerator()->create_module('giportfolio', array('course' => $course->id));

        $params = array(
            'context' => context_module::instance($giportfolio->cmid),
            'objectid' => $giportfolio->id
        );
        $event = \mod_giportfolio\event\course_module_viewed::create($params);

        // Triggering and capturing the event.
        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_giportfolio\event\course_module_viewed', $event);
        $this->assertEquals(context_module::instance($giportfolio->cmid), $event->get_context());
        $this->assertEquals($giportfolio->id, $event->objectid);
        $expected = array($course->id, 'giportfolio', 'view', 'view.php?id=' . $giportfolio->cmid, $giportfolio->id, $giportfolio->cmid);
        $this->assertEventLegacyLogData($expected, $event);
        $this->assertEventContextNotUsed($event);
    }

    public function test_chapter_viewed() {
        // There is no proper API to call to trigger this event, so what we are
        // doing here is simply making sure that the events returns the right information.

        $course = $this->getDataGenerator()->create_course();
        $giportfolio = $this->getDataGenerator()->create_module('giportfolio', array('course' => $course->id));
        $giportfoliogenerator = $this->getDataGenerator()->get_plugin_generator('mod_giportfolio');
        $context = context_module::instance($giportfolio->cmid);

        $chapter = $giportfoliogenerator->create_chapter(array('giportfolioid' => $giportfolio->id));

        $event = \mod_giportfolio\event\chapter_viewed::create_from_chapter($giportfolio, $context, $chapter);

        // Triggering and capturing the event.
        $sink = $this->redirectEvents();
        $event->trigger();
        $events = $sink->get_events();
        $this->assertCount(1, $events);
        $event = reset($events);

        // Checking that the event contains the expected values.
        $this->assertInstanceOf('\mod_giportfolio\event\chapter_viewed', $event);
        $this->assertEquals(context_module::instance($giportfolio->cmid), $event->get_context());
        $this->assertEquals($chapter->id, $event->objectid);
        $expected = array($course->id, 'giportfolio', 'view chapter', 'view.php?id=' . $giportfolio->cmid . '&amp;chapterid=' .
            $chapter->id, $chapter->id, $giportfolio->cmid);
        $this->assertEventLegacyLogData($expected, $event);
        $this->assertEventContextNotUsed($event);
    }

}
