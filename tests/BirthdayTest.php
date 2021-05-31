<?php

use App\Helpers\Interval;
use App\BirthdayInfo;

class BirthdayTest extends TestCase
{
    public function testBirthdayToday()
    {
        $intervalHelper = new Interval();
        $birthDatetime = new \DateTime('now');
        $birthDatetime->modify('-20 years');

        /** @var BirthdayInfo $birthdayInfo */
        $birthdayInfo = $intervalHelper->collectInfo($birthDatetime, 'Europe/Kiev');
        
        $this->assertTrue($birthdayInfo->getIsBirthdayToday());
        $this->assertEquals($birthdayInfo->getCurrentAge(), 20);
    }

    public function testBirthdayUpcoming()
    {
        $intervalHelper = new Interval();
        $birthDatetime = new \DateTime('now');
        $birthDatetime->modify('-20 years');
        $birthDatetime->modify('-10 days');

        /** @var BirthdayInfo $birthdayInfo */
        $birthdayInfo = $intervalHelper->collectInfo($birthDatetime, 'Europe/Kiev');

        $this->assertFalse($birthdayInfo->getIsBirthdayToday());
        $this->assertEquals($birthdayInfo->getCurrentAge(), 20);
    }
}
