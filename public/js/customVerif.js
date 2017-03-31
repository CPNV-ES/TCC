/**
 * Created by Struan.FORSYTH on 24.01.2017.
 */

VERIF.RULES.int_neg = {
        control: function control(res, data) {
                res(/^[+-]\d+|\d+$/.test(data.trim()));
        },
        errorText: 'Ce champ doit être nombre entier.'
};

VERIF.RULES.double_neg = {
        control: function control(res, data) {
                res(/^[+-]?\d*\.?\d*$/.test(data.trim()));
        },
        errorText: 'Ce champ doit être nombre entier.'
};

VERIF.RULES.date_us = {
        control: function control(res, data) {
                res(/^\d{4}-\d{2}\-\d{2}|\d{2}\.\d{2}\.\d{4}$/.test(data.trim()));
        },
        errorText: 'Ce champ doit être une date [aaaa-mm-jj].'
};

VERIF.RULES.date_us_greater = {
        control: function control(res, data, firstDate) {
                var dateEnd = new Date(data.trim());
                dateEnd.setHours(0, 0, 0, 0);

                var dateFirstString = $("#" + firstDate).val();

                var dateFirst = new Date(dateFirstString.trim());
                dateFirst.setHours(0, 0, 0, 0);

                dateFirst.setMonth(dateFirst.getMonth() + 6);

                res(dateEnd >= dateFirst);
        },
        errorText: 'La date doit avoir une différence d\'au moins {0}',
        argToError: function argToError(arg) {
                var y = Math.floor(arg / 12);
                var m = arg % 12;
                return (y > 0 ? y + (y == 1 ? ' an' : ' ans') : '') + (m > 0 ? ' et ' : '') + (m > 0 ? m + ' mois' : '');
        }
};

VERIF.RULES.time_long = {
  control: function control(res, data) {
    console.log(data);
    res(/^\d{2}:\d{2}(:\d{2}|)$/.test(data.trim()));
  },
  errorText: 'Ce champs doit être un temp [HH:mm:ss]'
}

VERIF.RULES.time_greater = {
        control: function control(res, data, firstTime) {
                var timeEnd = new Date();
                var timeEndTable = data.trim().split(':');
                timeEnd.setHours(timeEndTable[0], timeEndTable[1], 0, 0);

                console.log(timeEnd);

                var timeStartString = $("#" + firstTime).val();
                var timeStartTable = timeStartString.trim().split(':');
                console.log(timeStartString);

                var timeStart = new Date();
                timeStart.setHours(timeStartTable[0], timeStartTable[1], 0, 0);
                console.log(timeStart);

                return timeEnd > timeStart;
        },
        errorText: 'Le temps doit être suppérieur au temps d\'ouverture.'
};
