<?php

namespace Test\Models;

use Tests\TestCase;
use App\Models\Calendar;
use DateTime;

class CalendarTest extends TestCase
{
    /**
     * Test validationData function
     *
     * @param String $name          Calendar name
     * @param Date   $start         Calendar start date
     * @param Date   $end           Calendar end date
     * @param Status  $expectStatus Status
     *
     * @dataProvider providerTestValidationData
     */
    public function testValidationData($name, $start, $end, $expectStatus)
    {
        $calendar = new Calendar();
        $calendar->start_date =  $start->format('Y-m-d h:i:s');;
        $calendar->end_date = $end->format('Y-m-d h:i:s');;
        $calendar->validationData();
        $this->assertEquals($expectStatus, $calendar->status, $name);
    }

    /**
     * Provider test case validation data
     *
     * @return Array
     */
    public function providerTestValidationData()
    {
        $data = [];
        $startDate = new DateTime();
        $startDate->modify('+1 day');
        $endDate = new DateTime();
        $endDate->modify('+2 day');
        $data[] =["test planning", $startDate, $endDate, Calendar::PLANNING];
        $startDate = new DateTime();
        $startDate->modify('-2 day');
        $endDate = new DateTime();
        $endDate->modify('+1 day');
        $data[] = ["test doing", $startDate, $endDate, Calendar::DOING];
        $startDate = new DateTime();
        $startDate->modify('-2 day');
        $endDate = new DateTime();
        $endDate->modify('-1 day');
        $data[] = ["test compelete", $startDate, $endDate, Calendar::COMPLETE];
        return $data;
    }

    /**
     * Test save function
     *
     * @param String $name         Name test
     * @param Array  $data         Data test
     * @param number $expectResult Expect result
     *
     * @dataProvider providerTestStore
     */
    public function testSave($name, $data, $expectResult)
    {
        $calendar = new Calendar();
        $calendar->fill($data);
        $calendar->save();
        $this->assertEquals($expectResult, empty($calendar->id), $name);
    }

    /**
     * Provider test case store data
     *
     * @return Array
     */
    public function providerTestStore()
    {
        $data[] = [
            "test success",
            [
                "name" => "Duy Ho Ngc",
                "start_date"=> "2018-12-21T01:30:00",
                "end_date" => "2018-12-21T23:30:00",
            ],
            empty(1)
        ];

        $data[] = [
            "test fail",
            [
                "name" => "Duy Ho Ngc",
                "start_date"=> "2018-12-21T01:30:00",
            ],
            0
        ];
        return $data;
    }

    /**
     * Test get all function success
     */
    public function testAllSuccess()
    {
        $calendar = new Calendar();
        $calendars = $calendar->all();
        $this->assertEquals(false, empty($calendars));
    }
}
