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

        /** @var BirthdayInfo $birthdayInfoFromNow */
        $birthdayInfoFromTomorrow = $intervalHelper->collectInfo(
            $birthDateDatetime, 'Europe/Kiev', (clone $nowDatetime)->modify('+1 day'));

        $this->assertTrue(
            (int) $birthdayInfoFromTomorrow->getIntervalToNextBirthday()->format('%a') <
            (int) $birthdayInfoFromNow->getIntervalToNextBirthday()->format('%a')
        );
    }
}
