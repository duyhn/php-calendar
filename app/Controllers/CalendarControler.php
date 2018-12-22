<?php
namespace App\Controllers;

use App\Models\Calendar;
use App\Controllers\Controller;
use App\Models\CalendarStatus;

class CalendarControler extends Controller
{

    private $calendar;

    /**
     * Construct function
     *
     * @param Calendar $calendar Calendar
     *
     * @return void
     */
    public function __construct(Calendar $calendar)
    {
        $this->calendar = $calendar;
    }
    /**
     * Index function
     *
     * @return Response
     */
    public function index()
    {
        $calendars = $this->calendar->all();
        $this->response($calendars);
    }

    /**
     * Storage function
     *
     * @param array $data Data
     *
     * @return Response
     */
    public function store($data)
    {
        try {
            $this->calendar->fill($data);
            $status = CalendarStatus::find($this->calendar->status);
            $this->calendar->status = $status->id;
            $this->calendar->save();
            $this->calendar->status = $status;
            $this->response($this->calendar, 201);
        } catch (Exception $error) {
            $this->response([], 400);
        }
    }

    /**
     * Show function
     *
     * @param Number $id Calendar id
     *
     * @return Response
     */
    public function show($id)
    {
        $calendar = Calendar::find($id);
        $this->response($calendar);
    }
    
    /**
     * Update function
     *
     * @param array $data Data
     *
     * @return Response
     */
    public function update($data)
    {
        $this->calendar->fill($data);
        $id = $data["id"];
        $this->calendar->id = $id;
        $status = CalendarStatus::find($this->calendar->status);
        $this->calendar->status = $status->id;
        $this->calendar->save();
        $this->calendar->status = $status;
        $this->response($this->calendar);
    }

    /**
     * Destroy function
     *
     * @param array $data Data
     *
     * @return Response
     */
    public function destroy($data)
    {
        $this->calendar->id = $data["id"];
        $this->calendar->delete();
        $this->response([
            "message" => "delete success!"
        ], 200);
    }
}
