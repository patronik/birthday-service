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
        $dateTimeFrom = $dateTimeFrom ?? new \DateTime('now');
        $dateTimeFrom->setTimezone($timezone);

        $birthDateDateTime->setTimezone($timezone);
        $birthdayInfo->setBirthDate($birthDateDateTime);

        $intervalToNow = $birthDateDateTime->diff($dateTimeFrom);

        $birthdayInfo->setCurrentAge((int) $intervalToNow->format('%y'));

        $lastBirthdayDatetime = (clone $birthDateDateTime)->add(
            new \DateInterval('P' . $birthdayInfo->getCurrentAge() . 'Y')
        );

        $intervalFromLastBirthdayToNow = $lastBirthdayDatetime->diff($dateTimeFrom);

        $birthdayInfo->setIsBirthdayToday((int)$intervalFromLastBirthdayToNow->format('%days') == 0);

        if ($birthdayInfo->getIsBirthdayToday()) {
            $birthdayInfo->setIntervalToEndOfDay(
                (new \Datetime('now', $timezone))->diff(new \DateTime('tomorrow', $timezone))
            );
        }

        $nextBirthdayDatetime = (clone $birthDateDateTime)->add(
            new \DateInterval('P' . $birthdayInfo->getNextAge() . 'Y')
        );

        $birthdayInfo->setIntervalToNextBirthday($dateTimeFrom->diff($nextBirthdayDatetime));

        return $birthdayInfo;
    }
}
