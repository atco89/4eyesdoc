/**
 * @constructor
 * @param app
 */
function FullCalendar(app) {

    this.id = null;
    this.selector = null;
    this.patient = null;
    this.examination = null;
    this.doctor = null;
    this.startDateTime = null;
    this.examinationStatus = null;

    /**
     * @return boolean
     */
    this.periodIsValid = function (date) {
        let currentTimestamp = (new Date).getTime();
        let selectedFieldTimestamp = new Date(date.format()).getTime();
        return currentTimestamp > selectedFieldTimestamp;
    };

    /**
     * @return void
     */
    this.availableDoctors = function () {
        let that = this;
        let data = {
            examination: that.examination,
            startDateTime: that.startDateTime
        };

        $.ajax({
            type: 'POST',
            url: 'partial/appointments/available-doctors',
            data: data,
            dataType: 'html',
            beforeSend: function (xhr) {
                $('#doctor').html('<option value="" selected>Učitavanje...</option>').selectpicker('refresh');
            },
            error: function () {
                $('#doctor').html('<option value="" selected>Greška!</option>').selectpicker('refresh');
            }
        }).done(function (response) {
            $('#doctor').html(response).selectpicker('refresh');
        })
    };

    /**
     * @return void
     */
    this.render = function (selector) {
        let that = this;
        that.selector = selector;
        $(selector).fullCalendar({
            /* ===== base ===== */
            theme: false,
            locale: 'sr',
            defaultView: 'agendaWeek',
            firstDay: 1,
            navLinks: true,
            editable: true,
            eventLimit: true,
            /* ===== buttons ===== */
            customButtons: {
                cardboard: {
                    text: 'Otvaranje kartona',
                    click: function () {
                        window.open('patients/cardboard/create', '_self');
                    }
                }
            },
            header: {
                left: 'prev,next cardboard',
                center: 'title',
                right: 'month,agendaWeek,agendaDay'
            },
            /* ===== time settings ===== */
            slotDuration: app.duration,
            slotLabelInterval: app.duration,
            slotLabelFormat: 'HH:mm',
            minTime: app.startWork,
            maxTime: app.endWork,
            /* ===== events ===== */
            events: {
                url: 'api/examinations/all',
                type: 'GET',
                beforeSend: function () {
                    app.showLoader(true);
                },
                complete: function () {
                    app.showLoader(false);
                }
            },
            dayClick: function (date) {
                if (that.periodIsValid(date)) {
                    app.showModal('partial/appointments/expired');
                    return false;
                }
                app.showModal('partial/appointments/schedule', function () {
                    $('#start-date-time').val(date.format('DD.MM.YYYY HH:mm'));
                    $('.selectpicker').selectpicker('render');
                });
            },
            eventClick: function (calEvent) {
                if (calEvent.editable) {
                    that.id = calEvent.id;
                    app.showModal('appointments/schedule/update/' + calEvent.id, function () {
                        $('.selectpicker').selectpicker('render');
                    });
                } else {
                    app.showModal('partial/appointments/expired');
                }
            },
            eventDrop: function (event) {
                that.updateOnEvent(event);
            },
            eventResize: function (event) {
                that.updateOnEvent(event);
            }
        });
    };

    /**
     * @return void
     */
    this.storeEvent = function () {
        let that = this;
        let data = {
            patient: that.patient,
            examination: that.examination,
            doctor: that.doctor,
            startDateTime: that.startDateTime
        };

        $.post('partial/appointments/schedule', data, function (response) {
            $('#modal').html(response).find('.selectpicker').selectpicker('refresh');
        });

        $(that.selector).fullCalendar('refetchEvents');
    };

    /**
     * @return void
     */
    this.updateEvent = function () {
        let that = this;
        let data = {
            examination: that.examination,
            doctor: that.doctor,
            examinationStatus: that.examinationStatus,
            startDateTime: that.startDateTime,
            endDateTime: that.endDateTime
        };

        $.post('appointments/schedule/update/' + that.id, data, function (response) {
            $('#modal').html(response).find('.selectpicker').selectpicker('refresh');
            $(that.selector).fullCalendar('refetchEvents');
        });
    };

    /**
     * @return void
     */
    this.updateOnEvent = function (event) {
        let that = this;
        let data = {
            id: event.id,
            startDateTime: event.start.format('DD.MM.YYYY HH:mm'),
            endDateTime: event.end.format('DD.MM.YYYY HH:mm')
        };

        $.post('api/examinations/updateOnEvent', data, function () {
            $(that.selector).fullCalendar('refetchEvents');
        });
    };

}