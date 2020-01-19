/**
 * ReportsModule
 */
class ReportsModule {

    /**
     *
     * @param {string} url
     * @param {string} dateStart
     * @param {string} dateEnd
     * @param {function} complete
     * @return {{method: string, data: {"end-date": *, "start-date": *}, complete: complete, url: *}}
     */
    filter(url, dateStart, dateEnd, complete) {
        return {
            url: url,
            method: 'POST',
            data: {
                'start-date': dateStart,
                'end-date': dateEnd
            },
            complete: function (response) {
                complete(response);
            }
        };
    }

    /**
     *
     * @param {string} url
     * @return void
     */
    chart(url) {
        var fetures = [];
        var featuresList = {
            width: '1024',
            height: '510',
            left: '100',
            top: '100',
            resizable: 'no',
            scrollbars: 'no',
            toolbar: 'no',
            menubar: 'no'
        };

        for (var feture in featuresList) {
            if (featuresList.hasOwnProperty(feture)) {
                fetures.push(feture + '=' + featuresList[feture]);
            }
        }

        window.open(url, '_blank', fetures.join(', '));
    }
}
