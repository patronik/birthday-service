<?php

use App\Helpers\Interval;
use App\BirthdayInfo;

class BirthdayTest extends TestCase
{
    public function testAge()
    {
        $birthDateDatetime = new \DateTime('now');
        $birthDateDatetime->modify('-20 years');

        $intervalHelper = new Interval();
        /** @var BirthdayInfo $birthdayInfo */
        $birthdayInfo = $intervalHelper->collectInfo($birthDateDatetime, 'Europe/Kiev');

        $this->assertTrue($birthdayInfo->getCurrentAge() == 20);
    }

    public function testBirthdayToday()
    {
        $birthDateDatetime = new \DateTime('now');
        $birthDateDatetime->modify('-20 years');

        $intervalHelper = new Interval();
        /** @var BirthdayInfo $birthdayInfo */
        $birthdayInfo = $intervalHelper->collectInfo($birthDateDatetime, 'Europe/Kiev');

        $this->assertTrue($birthdayInfo->getIsBirthdayToday());
    }

    public function testBirthdayUpcoming()
    {
        $birthDateDatetime = new \DateTime('now');
        $birthDateDatetime->modify('-20 years');
        $birthDateDatetime->modify('-10 days');

        $intervalHelper = new Interval();
        /** @var BirthdayInfo $birthdayInfo */
        $birthdayInfo = $intervalHelper->collectInfo($birthDateDatetime, 'Europe/Kiev');

        $this->assertFalse($birthdayInfo->getIsBirthdayToday());
    }

    public function testIntervalToUpcoming()
    {
        $nowDatetime = new \DateTime('now');

        $nextBirthdayDatetime = clone $nowDatetime;
        $nextBirthdayDatetime->modify('+20 days');

        $birthDateDatetime = clone $nextBirthdayDatetime;
        $birthDateDatetime->modify('-20 years');

        $intervalHelper = new Interval();
        /** @var BirthdayInfo $birthdayInfoFromNow */
        $birthdayInfoFromNow = $intervalHelper->collectInfo($birthDateDatetime, 'Europe/Kiev');
        $calculatedNextBirthday = (clone $nowDatetime)->add(
            $birthdayInfoFromNow->getIntervalToNextBirthday()
        );

        $this->assertEquals(
            $calculatedNextBirthday->format('Y-m-d'),
            $nextBirthdayDatetime->format('Y-m-d')
        );

        /** @var BirthdayInfo $birthdayInfoFromTomorrow */
        $birthdayInfoFromTomorrow = $intervalHelper->collectInfo(
            $birthDateDatetime, 'Europe/Kiev', (clone $nowDatetime)->modify('+1 day'));

        $this->assertTrue(
            (int) $birthdayInfoFromTomorrow->getIntervalToNextBirthday()->format('%a') <
            (int) $birthdayInfoFromNow->getIntervalToNextBirthday()->format('%a')
        );
    }

    public function testMultipleDates()
    {
        $testData = [
            ['birth_date' => '05/10/1990', 'date_from' => '01/01/2021', 'next_birthday' => '05/10/2021'],
            ['birth_date' => '01/11/1980', 'date_from' => '01/01/2021', 'next_birthday' => '01/11/2021'],
            ['birth_date' => '07/25/1993', 'date_from' => '01/01/2021', 'next_birthday' => '07/25/2021'],
            ['birth_date' => '03/21/1999', 'date_from' => '01/01/2021', 'next_birthday' => '03/21/2021'],
            ['birth_date' => '02/28/2004', 'date_from' => '01/01/2021', 'next_birthday' => '02/28/2021'],
            ['birth_date' => '05/12/1990', 'date_from' => '01/01/2021', 'next_birthday' => '05/12/2021'],
            ['birth_date' => '01/01/1990', 'date_from' => '01/01/2021', 'next_birthday' => '01/01/2022'],
        ];

        $intervalHelper = new Interval();
        foreach ($testData as $testItem) {
            $dateFrom = new \DateTime($testItem['date_from']);
            /** @var BirthdayInfo $birthdayInfo */
            $birthdayInfo = $intervalHelper->collectInfo(
                new \DateTime($testItem['birth_date']), 'Europe/Kiev', $dateFrom
            );

            $calculatedBirthDate = (clone $dateFrom)->add($birthdayInfo->getIntervalToNextBirthday());
            $this->assertEquals(
                $calculatedBirthDate->format('m/d/Y'), $testItem['next_birthday']
            );
        }
    }
}
