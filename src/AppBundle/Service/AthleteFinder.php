<?php

namespace AppBundle\Service;

use AppBundle\Entity\Athlete;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Ferrandini\Urlizer;

class AthleteFinder
{
    const MIN_ALLOWED_SCORE = 0.2;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Checks wether or not this athlete might already exist in the DB based on birthday and first and last name.
     *
     * @param Athlete $candidate
     * @return bool
     */
    public function mightAlreadyExist(Athlete $candidate)
    {
        return $this->getBestMatchingAthlete($candidate) != null;
    }

    /**
     * Returns the Athlete with the same birthday and matching first and last names.
     *
     * @param Athlete $candidate
     * @return Athlete|null
     */
    public function getBestMatchingAthlete(Athlete $candidate)
    {
        $athletes = $this->em->getRepository('AppBundle:Athlete')->findBy(['birthday' => $candidate->getBirthday()]);
        $minScore = self::MIN_ALLOWED_SCORE;
        $bestMatch = null;

        foreach ($athletes as $athlete) {
            $score = $this->getScore($candidate, $athlete);

            if ($score < $minScore) {
                $minScore = $score;
                $bestMatch = $athlete;
            }
        }

        return $bestMatch;
    }

    /**
     * Computes a score. The closer the names, the closer to 0 is the score.
     *
     * @param Athlete $candidate
     * @param Athlete $athlete
     * @return float The score
     */
    private function getScore(Athlete $candidate, Athlete $athlete)
    {
        $score = levenshtein(Urlizer::urlize($candidate->getFirstName()), Urlizer::urlize($athlete->getFirstName()));
        $score += levenshtein(Urlizer::urlize($candidate->getLastName()), Urlizer::urlize($athlete->getLastName()));

        return $score / strlen(Urlizer::urlize($candidate->getFirstName() . $candidate->getLastName()));
    }
}
