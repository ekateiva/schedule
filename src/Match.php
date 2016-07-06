<?php

namespace Spoleague\Schedule;

class Match
{
    public function setHomeTeam($homeTeam)
    {
        $this->homeTeam = $homeTeam;
        $this->formatted = (string) $homeTeam;

        return $this;
    }

    public function setAwayTeam($awayTeam)
    {
        $this->awayTeam = $awayTeam;
        $this->formatted .= "x{$awayTeam}";

        return $this;
    }

    public function setRound($round)
    {
        $this->round = $round;

        return $this;
    }

    public function setMatchNumber($number)
    {
        $this->matchNumber = $number;

        return $this;
    }

    public function setSeasonPart($seasonPart)
    {
        $this->seasonPart = $seasonPart;

        return $this;
    }
}
