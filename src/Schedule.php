<?php

namespace Spoleague\Schedule;

class Schedule implements ScheduleInterface
{
    /** @var array an array of teams */
    protected $teams;

    /** @var bool  */
    protected static $doubleRoundRobin = false;

    /**
     * @param array $teams
     */
    public function setTeams(array $teams)
    {
        $this->teams = $teams;

        return $this;
    }

    /**
     * @return array
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Shuffle the teams array.
     *
     * @return $this
     */
    public function shuffleTeams()
    {
        $this->validateTeams();
        shuffle($this->teams);

        return $this;
    }

    /**
     * Use double Round Robin.
     *
     * @return $this
     */
    public function setDoubleRound()
    {
        self::$doubleRoundRobin = true;

        return $this;
    }

    /**
     * Generate full shcedule.
     *
     * @return array
     */
    public function generate()
    {
        $this->validateTeams();

        $numTeams = count($this->teams);

        // add a NULL team if the count is not even
        if ($numTeams % 2) {
            $this->teams[] = null;
            ++$numTeams;
        }

        // calculate the number of rounds and matches per round
        $numRounds = $numTeams - 1;
        $numMatchesPerRound = $numTeams / 2;

        $matchups = [];

        $matchNumber = 0;
        // generate each round
        for ($round = 1; $round <= $numRounds; ++$round) {

            // break the list in half
            $halves = array_chunk($this->teams, $numMatchesPerRound);
            // reverse the order of one half
            $halves[1] = array_reverse($halves[1]);

            // generate each match in the set
            for ($i = 1; $i <= $numMatchesPerRound; ++$i) {
                ++$matchNumber;
                // match each pair of elements
                $match = new Match();
                $match
                    ->setMatchNumber($matchNumber)
                    ->setRound($round)
                    ->setSeasonPart(1);

                if ($round % 2 == 1) {
                    $match->setHomeTeam($halves[0][$i - 1])
                            ->setAwayTeam($halves[1][$i - 1]);
                } else {
                    $match->setHomeTeam($halves[1][$i - 1])
                            ->setAwayTeam($halves[0][$i - 1]);
                }
                $matchups[$round][$i] = $match;

                if (self::$doubleRoundRobin) {
                    $doubleRoundRobinRound = $numMatchesPerRound * $numRounds;
                    $match = new Match();
                    $match
                        ->setMatchNumber($doubleRoundRobinRound + $matchNumber)
                        ->setRound($numRounds + $round)
                        ->setSeasonPart(2);

                    if ($round % 2 == 1) {
                        $match->setHomeTeam($halves[1][$i - 1])
                                ->setAwayTeam($halves[0][$i - 1]);
                    } else {
                        $match->setHomeTeam($halves[0][$i - 1])
                                ->setAwayTeam($halves[1][$i - 1]);
                    }
                    $matchupsSecond[$numRounds + $round][$i] = $match;
                }
            }
            // remove the first team and store
            $firstTeam = array_shift($this->teams);
            // move the last team to the second place
            array_unshift($this->teams, array_pop($this->teams));
            // place the first item back in the first position
            array_unshift($this->teams, $firstTeam);
        }

        if (self::$doubleRoundRobin) {
            $matchups = $matchups + $matchupsSecond;
        }

        return $matchups;
    }

    /**
     * Validate given teams.
     *
     * @throws Spoleague\Schedule\ScheduleException
     */
    protected function validateTeams()
    {
        if (! $this->teams || ! is_array($this->teams)) {
            throw new ScheduleException('No teams were set.');
        }
    }
}
