<?php

namespace App;


class BirthdayInfo
{
    /**
     * @var \Datetime
     */
    protected $birthDate;

    /**
     * @var \DateInterval
     */
    protected $intervalToNextBirthday;

    /**
     * @var \DateInterval
     */
    protected $intervalToEndOfDay;

    /**
     * @var bool
     */
    protected $isBirthdayToday;

    /**
     * @var int
     */
    protected $currentAge;


    /**
     * @return \DateInterval
     */
    public function getIntervalToNextBirthday(): \DateInterval
    {
        return $this->intervalToNextBirthday;
    }

    /**
     * @param \DateInterval $intervalToNextBirthday
     */
    public function setIntervalToNextBirthday(\DateInterval $intervalToNextBirthday): void
    {
        $this->intervalToNextBirthday = $intervalToNextBirthday;
    }

    /**
     * @return \DateInterval
     */
    public function getIntervalToEndOfDay(): \DateInterval
    {
        return $this->intervalToEndOfDay;
    }

    /**
     * @param \DateInterval $intervalToEndOfDay
     */
    public function setIntervalToEndOfDay(\DateInterval $intervalToEndOfDay): void
    {
        $this->intervalToEndOfDay = $intervalToEndOfDay;
    }

    /**
     * @return bool
     */
    public function getIsBirthdayToday(): bool
    {
        return $this->isBirthdayToday;
    }

    /**
     * @param bool $isBirthdayToday
     */
    public function setIsBirthdayToday(bool $isBirthdayToday): void
    {
        $this->isBirthdayToday = $isBirthdayToday;
    }

    /**
     * @return int
     */
    public function getCurrentAge(): int
    {
        return $this->currentAge;
    }

    /**
     * @param int $currentAge
     */
    public function setCurrentAge(int $currentAge): void
    {
        $this->currentAge = $currentAge;
    }

    /**
     * @return int
     */
    public function getNextAge(): int
    {
        return $this->currentAge + 1;
    }

    /**
     * @return \Datetime
     */
    public function getBirthDate(): \Datetime
    {
        return $this->birthDate;
    }

    /**
     * @param \Datetime $birthDate
     */
    public function setBirthDate(\Datetime $birthDate): void
    {
        $this->birthDate = clone $birthDate;
    }
}
