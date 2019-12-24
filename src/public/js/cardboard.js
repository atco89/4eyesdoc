/**
 * @constructor
 */
function Cardboard() {

    this.name = null;
    this.surname = null;
    this.email = null;
    this.dialNumber = null;
    this.phoneNumber = null;
    this.professionName = null;

    /**
     * @return void
     */
    this.storeContactPerson = function () {
        let that = this;
        let data = {
            name: that.name,
            surname: that.surname,
            dial_number: that.dialNumber,
            phone_number: that.phoneNumber
        };

        $.post('partial/contact-person/modal', data, function (response) {
            $('#modal').html(response).find('.selectpicker').selectpicker('render');
            $.get('partial/contact-person/select', null, function (response) {
                $('#contact-person').html(response).selectpicker('refresh');
            });
        });
    };

    /**
     * @return void
     */
    this.storeProfession = function () {
        let that = this;
        let data = {
            profession: that.professionName
        };

        $.post('partial/profession/modal', data, function (response) {
            $('#modal').html(response);
            $.get('partial/profession/select', null, function (response) {
                $('#professions').html(response).selectpicker('refresh');
            });
        });
    };

    /**
     * @return void
     */
    this.storeAssociate = function () {
        let that = this;
        let data = {
            name: that.name,
            email: that.email,
            dial_number: that.dialNumber,
            phone_number: that.phoneNumber
        };

        $.post('partial/associate/modal', data, function (response) {
            $('#modal').html(response).find('.selectpicker').selectpicker('render');
            $.get('partial/associates/select', null, function (response) {
                $('#associates-list').html(response).selectpicker('refresh');
            });
        });
    };

}