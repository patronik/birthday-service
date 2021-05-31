<?php

use App\Helpers\Interval;
use Illuminate\Http\Request;
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use App\BirthdayInfo;

/**
 * Returns the list of all people with information about their birthdays
 */
$router->get('/person', function (Request $request, Client $mongoClient, Interval $intervalHelper) use ($router) {
    $collection = $mongoClient->birthday->person;
    $result = [];
    foreach ($collection->find() as $person) {
        /** @var BirthdayInfo $birthdayInfo */
        $birthdayInfo = $intervalHelper->collectInfo(
            $person['birthdate']->toDatetime(), $person['timezone']
        );
        $item = [
            'name' => $person['name'],
            'birthdate' => $birthdayInfo->getBirthDate()->format('Y-m-d'),
            'timezone' => $person['timezone'],
            'isBirthday' => $birthdayInfo->getIsBirthdayToday(),
            'interval' => [
                'y' => $birthdayInfo->getIntervalToNextBirthday()->format('%y'),
                'm' => $birthdayInfo->getIntervalToNextBirthday()->format('%m'),
                'd' => $birthdayInfo->getIntervalToNextBirthday()->format('%d'),
                'h' => $birthdayInfo->getIntervalToNextBirthday()->format('%h'),
                'i' => $birthdayInfo->getIntervalToNextBirthday()->format('%i'),
                's' => $birthdayInfo->getIntervalToNextBirthday()->format('%s'),
            ],
        ];
        if ($birthdayInfo->getIsBirthdayToday()) {
            $item['message'] = sprintf(
                '%s is %d years old today (%d hours remaining in %s)',
                $person['name'],
                $birthdayInfo->getCurrentAge(),
                $birthdayInfo->getIntervalToEndOfDay()->format('%h'),
                $person['timezone']
            );
        } else {
            $item['message'] = sprintf(
                '%s is %d years old in %d months, %d days in %s',
                $person['name'],
                $birthdayInfo->getNextAge(),
                $birthdayInfo->getIntervalToNextBirthday()->format('%m'),
                $birthdayInfo->getIntervalToNextBirthday()->format('%d'),
                $person['timezone']
            );
        }
        $result[] = $item;
    }
    return response()->json($result);
});

/**
 * Creates a new entry in database
 */
$router->post('/person', function (Request $request, Client $mongoClient) use ($router) {
    $params = $request->all();

    if (empty($params['name']) || empty($params['birthdate']) || empty($params['timezone'])) {
        return response()->json(['status' => 'err', 'message' => 'Required parameters are missing'], 400);
    }

    if (!in_array($params['timezone'], DateTimeZone::listIdentifiers())) {
        return response()->json(['status' => 'err', 'message' => 'Invalid timezone'], 400);
    }

    try {
        $birthDatetime = new \DateTime($params['birthdate'], new \DateTimeZone($params['timezone']));
    } catch (Exception $e) {
        return response()->json(['status' => 'err', 'message' => 'Invalid birth date'], 400);
    }

    $birthDatetime->setTimezone(new DateTimeZone('UTC'));

    $mongoClient->birthday->person->insertOne(
        [
            'name' => $params['name'],
            'birthdate' => new UTCDateTime($birthDatetime->getTimestamp() * 1000),
            'timezone' => $params['timezone']
        ]
    );

    return response()->json(['status' => 'ok', 'message' => 'New entry has been created'], 201);
});

