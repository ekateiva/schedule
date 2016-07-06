<?php

use Spoleague\Schedule\Schedule;

class ScheduleTest extends PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->schedule = new Schedule;
    }

    /**
     * @test
     */
    public function it_throws_an_exception_if_no_teams_are_set()
    {
        $this->setExpectedException('Spoleague\Schedule\ScheduleException');
        $this->schedule->generate();
    }

    /**
     * @test
     */
    public function it_should_make_a_schedule_for_4_teams()
    {
        $this->schedule->setTeams([1, 2, 3, 4]);
        $schedule = $this->schedule->generate();

        $this->assertEquals('1x4', $schedule[1][1]->formatted);
        $this->assertEquals('2x3', $schedule[1][2]->formatted);

        $this->assertEquals('1x2', $schedule[3][1]->formatted);
        $this->assertEquals('3x4', $schedule[3][2]->formatted);
    }

    /**
     * @test
     */
    public function it_should_add_a_null_team_if_not_even_number_of_teams_are_set()
    {
        $this->schedule->setTeams([1, 2, 3]);
        $schedule = $this->schedule->generate();
        $result = $this->schedule->getTeams();

        $this->assertCount(4, $result);
        $this->assertNull($result[3]);
    }

    /**
     * @test
     */
    public function it_shuffles_the_teams()
    {
        $teams = [1,2,3,4];
        $this->schedule->setTeams($teams);
        $result = $this->schedule->shuffleTeams()->getTeams();

        $this->assertNotSame($teams, $result);
    }

    /**
     * @test
     */
    public function the_teams_should_play_twice_each_other_at_home_and_away()
    {
        $teams = [1,2,3,4];
        $this->schedule->setTeams($teams);
        $result = $this->schedule->setDoubleRound()->generate();

        $this->assertEquals('1x4', $result[1][1]->formatted);
        $this->assertEquals('2x3', $result[1][2]->formatted);

        $this->assertEquals('4x1', $result[4][1]->formatted);
        $this->assertEquals('3x2', $result[4][2]->formatted);
    }
}
