$(document).ready(function() {
    // you may need to change this code if you are not using Bootstrap Datepicker
    
    var year = document.getElementById('form_startDate_year');
    var month = document.getElementById('form_startDate_month');
    var day = document.getElementById('form_startDate_day');

    var btn = document.getElementById('form_save');
    btn.addEventListener('click',(e)=>{
        //alert(year.options[year.selectedIndex].value + '/' + month.options[month.selectedIndex].value + '/'+ day.options[day.selectedIndex].value);
    });
    
});
