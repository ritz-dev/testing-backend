@extends('layouts.app')

@section('content')
    <!DOCTYPE html>
    <html>

    <head>
        <meta charset="utf-8" />
        {{-- <script src="https://cdn.jsdelivr.net/npm/fullcalendar-scheduler@6.1.6/index.global.min.js"></script> --}}
        <script src="{{ asset('assets/js/index.global.min.js') }}"></script>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var calendarEl = document.getElementById("calendar");
                var modal = document.getElementById('eventModal');
                var closeBtn = modal.querySelector('.close');

                var barbers = <?php echo json_encode($barbers); ?>; // PHP data for resources
                var appointments = <?php echo json_encode($appointments); ?>; // PHP data for events
                // console.log(barbers);
                // console.log(appointments);
                var calendar = new FullCalendar.Calendar(calendarEl, {
                    timeZone: "UTC",
                    initialView: "resourceTimeGridDay",
                    datesAboveResources: true,
                    selectable: true,
                    headerToolbar: {
                        left: "prev,next",
                        center: "title",
                        right: "resourceTimeGridDay,resourceTimeGridFourDay",
                    },
                    views: {
                        resourceTimeGridFourDay: {
                            type: "resourceTimeGridDay",
                            duration: {
                                days: 3
                            }, // column to show how many days
                            buttonText: "3 days",
                        },
                    },
                    // dateClick: function (info) {
                    // alert("Clicked on: " + info.dateStr);
                    // alert('Current view: ' + info.view.type);
                    // change the day's background color just for fun
                    // info.dayEl.style.backgroundColor = 'red';
                    // },
                    // select: function(info) {
                    // alert('selected ' + info.startStr + ' to ' + info.endStr);
                    // },

                    allDaySlot: false,

                    slotDuration: '00:10:00', // Set the time interval to 15 minutes
                    slotMinTime: "09:00:00", // Set the shop open time
                    slotMaxTime: "20:00:00", // Set the shop close time
                    resources: barbers.map(function(barber) {
                        return {
                            id: barber.id,
                            title: barber.barber_name
                        };
                    }),


                    eventClick: function(info) {
                        var event = info.event;
                        // console.log(event);
                        // Show the modal on event click
                        modal.style.display = 'block';

                        // Populate the event details in the modal
                        document.getElementById('eventTitle').textContent =  'Customer: ' + event.title;
                        document.getElementById('eventCustomerPh').textContent =  'Phone Number: ' + event.extendedProps.customer_ph;
                        document.getElementById('eventStart').textContent = 'Start Time: ' + event.extendedProps.start_time;
                        document.getElementById('eventService').textContent = 'Service: ' + event.extendedProps.service;
                        let displayDate = new Date(event.extendedProps.date);
                        document.getElementById('eventDate').textContent = 'Date: ' + String(displayDate.getDate()).padStart(2, "0") + '-' + String(displayDate.getMonth() + 1).padStart(2, "0") + "-" + displayDate.getFullYear();
                        document.getElementById('eventDuration').textContent = 'Duration: ' + event.extendedProps.duration + ' mins';

                    },

                    events: appointments.map(function(appointment) {
                        var startTime = appointment.date + "T" + appointment.start_time;
                        var endTime = appointment.date + "T" + appointment.end_time;

                        var booked_barber = barbers.find(function(barber) {
                            if (barber.uniqueid === appointment.barber_unid) {
                                return (barber.id);
                            }

                        });
                        return {
                            resourceId: booked_barber.id,
                            title: appointment.customer_name,
                            start: startTime,
                            end: endTime,
                            extendedProps: {
                                customer_ph : appointment.customer_ph,
                                start_time : appointment.start_time,
                                service: appointment.serviceName,
                                duration: appointment.duration,
                                date:appointment.date,
                            }
                        };

                    }),

                });

                // Close the modal when close button is clicked
                closeBtn.addEventListener('click', function() {
                    modal.style.display = 'none';


                });

                calendar.render();
            });
        </script>
        <style>
            html,
            body {
                margin: 0;
                padding: 0;
                font-family: Arial, Helvetica Neue, Helvetica, sans-serif;
                font-size: 14px;
            }

            #calendar {
                max-width: 1100px;
                margin: 40px auto;
            }

            .modal {
                display: none;
                position: fixed;
                z-index: 1;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                overflow: auto;
                background-color: rgba(0, 0, 0, 0.5);
            }

            .modal-content {
                background-color: white;
                margin: 15% auto;
                padding: 20px;
                border: 1px solid #888;
                width: 300px;
            }

            .close {
                color: #aaa;
                float: right;
                font-size: 28px;
                font-weight: bold;
                cursor: pointer;
                margin-left:230px;
            }

            .close:hover,
            .close:focus {
                color: black;
                text-decoration: none;
                cursor: pointer;
            }
        </style>
    </head>

    <body>
        <div id="calendar"></div>

        <div id="eventModal" class="modal">
            <div class="modal-content position-relative">
                <span class="close" style="position: absolute; top: 5; right: 5">&times;</span>
                <h4 class="mb-4">Appointment Details</h4>
                <p id="eventTitle"></p>
                <p id="eventCustomerPh"></p>
                <p id="eventService"></p>
                <p id="eventStart"></p>
                <p id="eventDate"></p>
                <p id="eventDuration"></p>

            </div>
        </div>


    </body>

    </html>
@endsection
