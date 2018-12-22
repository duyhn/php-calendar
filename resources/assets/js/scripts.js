$(document).ready(function () {
    var calendar = $('#calendar').fullCalendar({  // assign calendar
        header: {
            left: 'prev,next today',
            center: 'title',
            right: 'month,agendaWeek,agendaDay'
        },
        // defaultView: 'agendaWeek',
        defaultView: 'month',
        editable: true,
        selectable: true,
        allDaySlot: false,

        // events: "http://calendar.local?view=1",  // request to load current events
        events: function (start, end, timezone, callback) {
            $.ajax({
                url: '/calendar',
                data: {
                    start: $.fullCalendar.moment(start).format('DD-MM-YYYY h:mm'),
                    end: $.fullCalendar.moment(end).format('DD-MM-YYYY h:mm')
                },
                success: function (data) {
                    var events = [];
                    var color;
                    data.forEach(function (element) {
                        events.push({
                            id: element.id,
                            title: element.name,
                            start: element.start_date,
                            end: element.end_date,
                            color: element.color
                        });
                    });
                    callback(events);
                }
            });
        },

        eventClick: function (event, jsEvent, view) {  // when some one click on any event
            endtime = $.fullCalendar.moment(event.end).format('h:mm');
            starttime = $.fullCalendar.moment(event.start).format('dddd, MMMM Do YYYY, h:mm');
            var mywhen = starttime + ' – ' + endtime;

            $('#modalTitle').html(event.title);
            $('#modalWhen').text(mywhen);
            $('#eventID').val(event.id);
            $('#calendarModal').modal();
        },
        select: function (start, end, jsEvent) {  // click on empty time slot
            endtime = $.fullCalendar.moment(end).format('h:mm');
            starttime = $.fullCalendar.moment(start).format('dddd, MMMM Do YYYY, h:mm');
            var mywhen = starttime + ' – ' + endtime;
            start = moment(start).format();
            end = moment(end).format();
            $('#createEventModal #startTime').val(start);
            $('#createEventModal #endTime').val(end);
            $('#createEventModal #when').text(mywhen);
            $('#createEventModal').modal('toggle');
        },
        eventDrop: function (event, delta) { // event drag and drop
            var data = {
                name: event.title,
                start_date: moment(event.start).format(),
                end_date: moment(event.end).format(),
                id: event.id
            };
            $.ajax({
                url: '/calendar/update',
                data: data,
                type: "POST",
                success: function (json) {
                    event.color = json.status.color;
                    $("#calendar").fullCalendar('updateEvent', event);
                }
            });
        },
        eventResize: function (event) {  // resize to increase or decrease time of event
            var data = {
                name: event.title,
                start_date: moment(event.start).format(),
                end_date: moment(event.end).format(),
                id: event.id
            };
            $.ajax({
                url: '/calendar/update',
                data: data,
                type: "POST",
                success: function (json) {
                    event.color = json.status.color;
                    $("#calendar").fullCalendar('updateEvent', event);
                }
            });
        }
    });

    $('#submitButton').on('click', function (e) { // add event submit
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doSubmit(); // send to form submit function
    });

    $('#deleteButton').on('click', function (e) { // delete event clicked
        // We don't want this to act as a link so cancel the link action
        e.preventDefault();
        doDelete(); //send data to delete function
    });

    function doDelete() {  // delete event 
        $("#calendarModal").modal('hide');
        var eventID = $('#eventID').val();
        $.ajax({
            url: '/calendar/delete',
            data: 'id=' + eventID,
            type: "POST",
            success: function (json) {
                $("#calendar").fullCalendar('removeEvents', eventID);
            }
        });
    }
    function doSubmit() { // add event
        $("#createEventModal").modal('hide');
        var name = $('#name').val();
        var startTime = $('#startTime').val();
        var endTime = $('#endTime').val();

        $.ajax({
            url: '/calendar/create',
            data: 'name=' + name + '&start_date=' + startTime + '&end_date=' + endTime,
            type: "POST",
            success: function (json) {
                $("#calendar").fullCalendar('renderEvent',
                    {
                        id: json.id,
                        title: json.name,
                        start: json.start_date,
                        end: json.end_date,
                        color: json.status.color,
                    },
                    true);
            }
        });

    }
    $('.deleteClose').on('click', function (e) { // delete event clicked
        $("#calendarModal").modal("hide");
    });
    $('.addClose').on('click', function (e) { // delete event clicked
        $("#createEventModal").modal("hide");
    });
});