



function refresh_date(link) {
    var date = $(".datepicker-here").val(),
        arr = date.split(' - '),
        date_start = arr[0],
        date_finish = arr[1];

    link = link + "&date_start=" + date_start+"&date_finish=" + date_finish;
    //alert(link);
    document.location.href = link;
}
