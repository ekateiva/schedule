<?php

namespace Spoleague\Schedule;

interface ScheduleInterface
{
    public function generate();

    public function setTeams(array $teams);

    public function getTeams();

    public function shuffleTeams();
}
