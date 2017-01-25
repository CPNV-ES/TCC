/**
 * Created by Struan.FORSYTH on 24.01.2017.
 */

VERIF.RULES.int_neg={
    control:(data)=>{return /^[+-]\d+|\d+$/.test(data.trim());},
    errorText:'Ce champs dois être nombre entier.'
}

VERIF.RULES.double_neg={
    control:(data)=>{return /^[+-]?\d*\.?\d*$/.test(data.trim());},
    errorText:'Ce champs dois être nombre entier.'
}

VERIF.RULES.date_us={
    control:(data)=>{
        return /^\d{4}-\d{2}\-\d{2}|\d{2}\.\d{2}\.\d{4}$/.test(data.trim());
    },
    errorText:'Ce champs dois être une date [jj.mm.aaaa].'
}

VERIF.RULES.date_us_greater={
    control:(data,firstDate)=>{
        let dateEnd=new Date(data.trim());
        dateEnd.setHours(0,0,0,0);

        var dateFirstString=$("#"+firstDate).val();

        let dateFirst=new Date(dateFirstString.trim());
        dateFirst.setHours(0,0,0,0);

        dateFirst.setMonth(dateFirst.getMonth() + 6);

        return (dateEnd>=dateFirst);
    },
    errorText:'La date doit avoir une différence d\'au moins 6 mois.'
}

VERIF.RULES.time_greater={
        control:(data,firstTime)=>{
        let timeEnd=new Date();
        let timeEndTable=data.trim().split(':');
        timeEnd.setHours(timeEndTable[0], timeEndTable[1], 0, 0);

        console.log(timeEnd);

        var timeStartString=$("#"+firstTime).val();
        let timeStartTable=timeStartString.trim().split(':');
        console.log(timeStartString);

        let timeStart=new Date();
        timeStart.setHours(timeStartTable[0], timeStartTable[1], 0, 0);
        console.log(timeStart);

        return (timeEnd>timeStart);
    },
    errorText:'Le temps doit doit être suppérieur au temps d\'ouverture.'
}
