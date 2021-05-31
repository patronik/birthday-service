<?php

namespace App\Helpers;

use App\BirthdayInfo;

class Interval
{
    /**
     * Collect and return information about person's birthday
     */
    public function collectInfo(\DateTime $birthDateDateTime, string $timezone, \DateTime $dateTimeFrom = null) : BirthdayInfo
    {
        $birthdayInfo = new BirthdayInfo();

        $timezone = new \DateTimeZone($timezone);
        $nowDatetime = new \DateTime('now', $timezone);

        $birthDateDateTime->setTimezone($timezone);
        $birthdayInfo->setBirthDate($birthDateDateTime);

        $intervalToNow = $birthDateDateTime->diff($nowDatetime);

        $birthdayInfo->setCurrentAge((int) $intervalToNow->format('%y'));

        $lastBirthdayDatetime = (clone $birthDateDateTime)->add(
            new \DateInterval('P' . $birthdayInfo->getCurrentAge() . 'Y')
        );

        $intervalFromLastBirthdayToNow = $lastBirthdayDatetime->diff($nowDatetime);

        $birthdayInfo->setIsBirthdayToday((int)$intervalFromLastBirthdayToNow->format('%days') == 0);

        if ($birthdayInfo->getIsBirthdayToday()) {
            $tomorrowDatetime = new \DateTime('tomorrow', $timezone);
            $birthdayInfo->setIntervalToEndOfDay($nowDatetime->diff($tomorrowDatetime));
        }

        if ($dateTimeFrom !== null) {
            $dateTimeFrom->setTimezone($timezone);
        } else {
            $dateTimeFrom = new \DateTime('now', $timezone);
        }

        $nextBirthdayDatetime = (clone $birthDateDateTime)->add(
            new \DateInterval('P' . $birthdayInfo->getNextAge() . 'Y')
        );

        $birthdayInfo->setIntervalToNextBirthday($dateTimeFrom->diff($nextBirthdayDatetime));

        return $birthdayInfo;
    }
}
