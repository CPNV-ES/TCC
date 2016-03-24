$("#jqxcourt1").jqxCalendar({
    enableTooltips: true,
    width: "100%",
    height: 220,
    culture : 'fr-FR',
    theme: 'bootstrap',
    forwardText : "Suivant",
    backText : "Précédent"
});

$("#jqxcourt2").jqxCalendar({
    enableTooltips: true,
    width: "100%",
    height: 220,
    culture : 'fr-FR',
    theme: 'bootstrap',
    forwardText : "Suivant",
    backText : "Précédent"
});

// Create Date objects.
var date1 = new Date();
var date2 = new Date();
var date3 = new Date();
date1.setDate(5);
date2.setDate(15);
date3.setDate(16);
// Add special dates by invoking the addSpecialDate method.
$("#jqxcourt1").jqxCalendar('addSpecialDate', date1, '', 'Réservé');
$("#jqxcourt1").jqxCalendar('addSpecialDate', date2, '', 'Entrainement Dames');
$("#jqxcourt1").jqxCalendar('addSpecialDate', date3, '', 'Début tournois');
