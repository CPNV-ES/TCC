var getLocalization = function (culture) {
    var localization = null;
    switch (culture) {
        case "fr":
            localization =
             {
                 // separator of parts of a date (e.g. '/' in 11/05/1955)
                 '/': "/",
                 // separator of parts of a time (e.g. ':' in 05:44 PM)
                 ':': ":",
                 // the first day of the week (0 = Sunday, 1 = Monday, etc)
                 firstDay: 1,
                 days: {
                     names: ["Dimanche","Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi"],
                     namesAbbr: ["dim.","lun.","mar.","mer.","jeu.","ven.","sam."],
                     namesShort: ["di","lu","ma","me","je","ve","sa"]
                 },

                 months: {
                     names: ["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre",""],
                     namesAbbr: ["janv.","févr.","mars","avr.","mai","juin","juil.","août","sept.","oct.","nov.","déc.",""]
                 },
                 // AM and PM designators in one of these forms:
                 // The usual view, and the upper and lower case versions
                 //      [standard,lowercase,uppercase]
                 // The culture does not use AM or PM (likely all standard date formats use 24 hour time)
                 //      null
                 AM: ["AM", "am", "AM"],
                 PM: ["PM", "pm", "PM"],
                 eras: [
                 // eras in reverse chronological order.
                 // name: the name of the era in this culture (e.g. A.D., C.E.)
                 // start: when the era starts in ticks (gregorian, gmt), null if it is the earliest supported era.
                 // offset: offset in years from gregorian calendar
                 { "name": "A.D.", "start": null, "offset": 0 }
                 ],
                 twoDigitYearMax: 2029,
                 patterns:
                  {
                      d: "dd.MM.yyyy",
                      D: "dddd, d. MMMM yyyy",
                      t: "HH:mm",
                      T: "HH:mm:ss",
                      f: "dddd, d. MMMM yyyy HH:mm",
                      F: "dddd, d. MMMM yyyy HH:mm:ss",
                      M: "dd MMMM",
                      Y: "MMMM yyyy"

                  },
                 percentsymbol: "%",
                 currencysymbol: "€",
                 currencysymbolposition: "after",
                 decimalseparator: '.',
                 thousandsseparator: ',',
                 pagergotopagestring: "Allez à",
                 pagershowrowsstring: "Zeige Zeile:",
                 pagerrangestring: " von ",
                 weekViewString: "Hebdomadaire",
                 pagerpreviousbuttonstring: "suivant",
                 pagernextbuttonstring: "précédent",
                 pagerfirstbuttonstring: "first",
                 pagerlastbuttonstring: "last",
                 groupsheaderstring: "Faites glisser une colonne et déposez-le ici pour le groupe par cette colonne",
                 sortascendingstring: "Tri ascendant",
                 sortdescendingstring: "Tri descendant",
                 sortremovestring: "Retirer tri",
                 groupbystring: "Group By this column",
                 groupremovestring: "Remove from groups",
                 filterclearstring: "Effacer",
                 filterstring: "Filtres",
                 filtershowrowstring: "Afficher les lignes:",
                 filterorconditionstring: "Ou",
                 filterandconditionstring: "Et",
                 filterselectallstring: "(Tout sélectionner)",
                 filterchoosestring: "S'il vous plaît sélectionnez:",
                 filterstringcomparisonoperators: ['vide', 'pas vide', 'contient', 'contient(gu)',
                    'non inclus', 'non inclus(gu)', 'commence par', 'commence par(gu)',
                    'se termine par', 'se termine par(gu)', 'égal', 'égal(gu)', 'null', 'non null'],
                 filternumericcomparisonoperators: ['égal', 'pas égal', 'moins que', 'inférieur ou égal', 'supérieur', 'supérieur ou égal', 'null', 'non null'],
                 filterdatecomparisonoperators: ['égal', 'pas égal', 'moins que', 'inférieur ou égal', 'supérieur', 'supérieur ou égal', 'null', 'non null'],
                 filterbooleancomparisonoperators: ['égal', 'pas égal'],
                 validationstring: "La valeur saisie est invalide",
                 emptydatastring: "Pas de données",
                 filterselectstring: "Sélectionnez un filtre",
                 loadtext: "Chargement...",
                 clearstring: "Effacer",
                 todaystring: "Aujourd'hui",
                 loadingErrorMessage: "Die Daten werden noch geladen und Sie können eine Eigenschaft nicht festgelegt oder eine Methode aufrufen . Sie können tun, dass, sobald die Datenbindung abgeschlossen ist. jqxScheduler wirft die ' bindingComplete ' Ereignis, wenn die Bindung abgeschlossen ist.",
                 editRecurringAppointmentDialogTitleString: "Bearbeiten Sie wiederkehrenden Termin",
                 editRecurringAppointmentDialogContentString: "Wollen Sie nur dieses eine Vorkommen oder die Serie zu bearbeiten ?",
                 editRecurringAppointmentDialogOccurrenceString: "Vorkommen bearbeiten",
                 editRecurringAppointmentDialogSeriesString: "Bearbeiten Die Serie",
                 editDialogTitleString: "Termin bearbeiten",
                 editDialogCreateTitleString: "Nouvelle réservation",
                 contextMenuEditAppointmentString: "Termin bearbeiten",
                 contextMenuCreateAppointmentString: "Erstellen Sie Neuer Termin",
                 editDialogSubjectString: "Sujet",
                 editDialogLocationString: "Place",
                 editDialogFromString: "De",
                 editDialogToString: "à",
                 editDialogAllDayString: "tout au long de la journée",
                 editDialogExceptionsString: "Ausnahmen",
                 editDialogResetExceptionsString: "Zurücksetzen auf Speichern",
                 editDialogDescriptionString: "Description",
                 editDialogResourceIdString: "Calendrier",
                 editDialogStatusString: "Status",
                 editDialogColorString: "Couleur",
                 editDialogColorPlaceHolderString: "Sélectionner une couleur",
                 editDialogTimeZoneString: "Fuseau horaire",
                 editDialogSelectTimeZoneString: "Sélectionner un fuseau horaire",
                 editDialogSaveString: "Sauver",
                 editDialogDeleteString: "Supprimer",
                 editDialogCancelString: "Annuler",
                 editDialogRepeatString: "Répétition",
                 editDialogRepeatEveryString: "Wiederholen alle",
                 editDialogRepeatEveryWeekString: "woche(n)",
                 editDialogRepeatEveryYearString: "Jahr (en)",
                 editDialogRepeatEveryDayString: "Tag (e)",
                 editDialogRepeatNeverString: "Jamais",
                 editDialogRepeatDailyString: "Täglich",
                 editDialogRepeatWeeklyString: "Wöchentlich",
                 editDialogRepeatMonthlyString: "Monatlich",
                 editDialogRepeatYearlyString: "Jährlich",
                 editDialogRepeatEveryMonthString: "Monate (n)",
                 editDialogRepeatEveryMonthDayString: "Day",
                 editDialogRepeatFirstString: "erste",
                 editDialogRepeatSecondString: "zweite",
                 editDialogRepeatThirdString: "dritte",
                 editDialogRepeatFourthString: "vierte",
                 editDialogRepeatLastString: "letzte",
                 editDialogRepeatEndString: "Ende",
                 editDialogRepeatAfterString: "Nach",
                 editDialogRepeatOnString: "Am",
                 editDialogRepeatOfString: "von",
                 editDialogRepeatOccurrencesString: "Eintritt (e)",
                 editDialogRepeatSaveString: "Vorkommen Speichern",
                 editDialogRepeatSaveSeriesString: "Save Series",
                 editDialogRepeatDeleteString: "Vorkommen löschen",
                 editDialogRepeatDeleteSeriesString: "Series löschen",
                 editDialogStatuses:
                 {
                     free: "Libre",
                     tentative: "Provisoire",
                     busy: "Occupé",
                     outOfOffice: "Ausserhaus"
                 }
             }
            break;
        case "en":
        default:
            localization =
            {
                // separator of parts of a date (e.g. '/' in 11/05/1955)
                '/': "/",
                // separator of parts of a time (e.g. ':' in 05:44 PM)
                ':': ":",
                // the first day of the week (0 = Sunday, 1 = Monday, etc)
                firstDay: 0,
                days: {
                    // full day names
                    names: ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"],
                    // abbreviated day names
                    namesAbbr: ["Sun", "Mon", "Tue", "Wed", "Thu", "Fri", "Sat"],
                    // shortest day names
                    namesShort: ["Su", "Mo", "Tu", "We", "Th", "Fr", "Sa"]
                },
                months: {
                    // full month names (13 months for lunar calendards -- 13th month should be "" if not lunar)
                    names: ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December", ""],
                    // abbreviated month names
                    namesAbbr: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec", ""]
                },
                // AM and PM designators in one of these forms:
                // The usual view, and the upper and lower case versions
                //      [standard,lowercase,uppercase]
                // The culture does not use AM or PM (likely all standard date formats use 24 hour time)
                //      null
                AM: ["AM", "am", "AM"],
                PM: ["PM", "pm", "PM"],
                eras: [
                // eras in reverse chronological order.
                // name: the name of the era in this culture (e.g. A.D., C.E.)
                // start: when the era starts in ticks (gregorian, gmt), null if it is the earliest supported era.
                // offset: offset in years from gregorian calendar
                { "name": "A.D.", "start": null, "offset": 0 }
                ],
                twoDigitYearMax: 2029,
                patterns: {
                    // short date pattern
                    d: "M/d/yyyy",
                    // long date pattern
                    D: "dddd, MMMM dd, yyyy",
                    // short time pattern
                    t: "h:mm tt",
                    // long time pattern
                    T: "h:mm:ss tt",
                    // long date, short time pattern
                    f: "dddd, MMMM dd, yyyy h:mm tt",
                    // long date, long time pattern
                    F: "dddd, MMMM dd, yyyy h:mm:ss tt",
                    // month/day pattern
                    M: "MMMM dd",
                    // month/year pattern
                    Y: "yyyy MMMM",
                    // S is a sortable format that does not vary by culture
                    S: "yyyy\u0027-\u0027MM\u0027-\u0027dd\u0027T\u0027HH\u0027:\u0027mm\u0027:\u0027ss",
                    // formatting of dates in MySQL DataBases
                    ISO: "yyyy-MM-dd hh:mm:ss",
                    ISO2: "yyyy-MM-dd HH:mm:ss",
                    d1: "dd.MM.yyyy",
                    d2: "dd-MM-yyyy",
                    d3: "dd-MMMM-yyyy",
                    d4: "dd-MM-yy",
                    d5: "H:mm",
                    d6: "HH:mm",
                    d7: "HH:mm tt",
                    d8: "dd/MMMM/yyyy",
                    d9: "MMMM-dd",
                    d10: "MM-dd",
                    d11: "MM-dd-yyyy"
                },
                percentsymbol: "%",
                currencysymbol: "$",
                currencysymbolposition: "before",
                decimalseparator: '.',
                thousandsseparator: ',',
                pagergotopagestring: "Go to page:",
                pagershowrowsstring: "Show rows:",
                pagerrangestring: " of ",
                pagerpreviousbuttonstring: "previous",
                pagernextbuttonstring: "next",
                pagerfirstbuttonstring: "first",
                pagerlastbuttonstring: "last",
                groupsheaderstring: "Drag a column and drop it here to group by that column",
                sortascendingstring: "Sort Ascending",
                sortdescendingstring: "Sort Descending",
                sortremovestring: "Remove Sort",
                groupbystring: "Group By this column",
                groupremovestring: "Remove from groups",
                filterclearstring: "Clear",
                filterstring: "Filter",
                filtershowrowstring: "Show rows where:",
                filterorconditionstring: "Or",
                filterandconditionstring: "And",
                filterselectallstring: "(Select All)",
                filterchoosestring: "Please Choose:",
                filterstringcomparisonoperators: ['empty', 'not empty', 'enthalten', 'enthalten(match case)',
                   'does not contain', 'does not contain(match case)', 'starts with', 'starts with(match case)',
                   'ends with', 'ends with(match case)', 'equal', 'equal(match case)', 'null', 'not null'],
                filternumericcomparisonoperators: ['equal', 'not equal', 'less than', 'less than or equal', 'greater than', 'greater than or equal', 'null', 'not null'],
                filterdatecomparisonoperators: ['equal', 'not equal', 'less than', 'less than or equal', 'greater than', 'greater than or equal', 'null', 'not null'],
                filterbooleancomparisonoperators: ['equal', 'not equal'],
                validationstring: "Entered value is not valid",
                emptydatastring: "No data to display",
                filterselectstring: "Select Filter",
                loadtext: "Loading...",
                clearstring: "Clear",
                todaystring: "Today"
            }
            break;
    }
    return localization;
}