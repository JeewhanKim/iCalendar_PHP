#iCalendar_PHP



##Introduction

A browser friendly .ics file generator written in PHP.
You can INSERT / UPDATE / DELETE iCalendar files client-side.

I couldn't find any libraries about .ics file handlers written in PHP, so naturally I built my own.
Note that in general iCal_controller gives no guarantees if you didn't add any functions to handle it.



##Example [js]

[INSERT]
```javascript
$.post("ical_controller.php", {controlType: 'INSERT', summary: 'OFF> ' + currentDesignerName, startDate: ical_startDate, endDate: ical_endDate, icalCreated: ''}
```

[UPDATE]
```javascript
$.post("ical_controller.php", {controlType: 'UPDATE', startDate: ical_startDate, endDate: ical_endDate, summary: summary, icalCreated: created}
```

[DELETE]
```javascript
$.post("ical_controller.php", {controlType: 'DELETE', icalCreated: created}
```


##Dependencies

The Controller uses one PHP Library called 'Bennu' and sample js file uses jQuery.
* [bennu_rfc2445](http://bennu.sourceforge.net/)



##References

* http://keith-wood.name/icalendar.html
* http://en.wikipedia.org/wiki/ICalendar
* https://github.com/nwcell/ics.js
* http://bennu.sourceforge.net/
