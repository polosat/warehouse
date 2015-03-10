function DatePicker (config) {
  var iconWidth = 19;
  var iconHeight = 16;
  var inputPadding = 10;

  var minDate = new Date();
  var maxDate = new Date();
  minDate.setFullYear(config.minYear, config.minMonth, config.minDay);
  minDate.setHours(0, 0, 0, 0);
  maxDate.setFullYear(config.maxYear, config.maxMonth, config.maxDay);
  maxDate.setHours(0, 0, 0, 0);

  var wrapper;
  var input;
  var pattern;
  var icon;
  var popup;
  var headerLeft;
  var headerLeftArrow;
  var headerRight;
  var headerRightArrow;
  var headerCenter;
  var headerTitle;

  var days;
  var months;
  var years;
  var decades;
  var views;

  var popupVisible = false;
  var typedDate = null;

  var initialize = function() {
    input = document.getElementById(config.inputId);
    var width = input.clientWidth;
    input.style.paddingLeft = iconWidth + inputPadding + 'px';
    input.style.width = width - iconWidth - inputPadding + 'px';

    wrapper = document.createElement('div');
    wrapper.className = 'date-picker-wrapper';
    wrapper.style.width = input.offsetWidth + 'px';
    input.parentNode.insertBefore(wrapper, input.nextSibling);
    wrapper.appendChild(input);

    pattern = document.createElement('div');
    pattern.className = 'date-picker-pattern';
    pattern.style.display = input.value ? 'none' : 'block';
    pattern.innerHTML = config.inputPattern;
    pattern.style.lineHeight = wrapper.offsetHeight + 'px';
    pattern.style.width = input.clientWidth - iconWidth - inputPadding + 'px';
    pattern.style.paddingLeft = iconWidth + inputPadding + 'px';
    wrapper.insertBefore(pattern, input);

    icon = document.createElement('div');
    var iconBackground = document.createElement('div');
    iconBackground.className = 'date-picker-icon-background';
    iconBackground.style.width = iconWidth + inputPadding + 'px';
    iconBackground.style.height = wrapper.clientHeight + 'px';

    icon.className = 'date-picker-icon';
    var padding = (wrapper.clientHeight - iconHeight) >> 1;
    icon.style.margin = padding + 'px' + ' 6px';
    wrapper.insertBefore(icon, input);
    wrapper.insertBefore(iconBackground, icon);

    popup = document.createElement('div');
    popup.className = 'date-picker-popup';
    popup.tabIndex = -1;
    popup.style.display = 'none';
    wrapper.insertBefore(popup, input.nextSibling);
    popup.style.top = wrapper.offsetHeight + 'px';

    var popupHeader = document.createElement('div');
    popupHeader.className = 'date-picker-popup-header';
    popup.appendChild(popupHeader);

    headerLeft = document.createElement('div');
    headerLeftArrow = document.createTextNode('.');
    headerLeft.className = 'date-picker-header header-arrow';
    headerLeft.appendChild(headerLeftArrow);
    popupHeader.appendChild(headerLeft);
    headerLeft.unselectable = true;

    headerCenter = document.createElement('div');
    headerCenter.className = 'date-picker-header header-center';
    headerTitle = document.createTextNode('');
    headerCenter.appendChild(headerTitle);
    popupHeader.appendChild(headerCenter);
    headerCenter.unselectable = true;

    headerRight = document.createElement('div');
    headerRightArrow = document.createTextNode('.');
    headerRight.className = 'date-picker-header header-arrow';
    headerRight.appendChild(headerRightArrow);
    popupHeader.appendChild(headerRight);
    headerRight.unselectable = true;

    var popupBody = document.createElement('div');
    popupBody.className = 'date-picker-popup-body';
    popup.appendChild(popupBody);

    decades = createDecadesView(onDecadeClick);
    popupBody.appendChild(decades.element);

    years = createYearsView(onYearClick);
    popupBody.appendChild(years.element);

    months = createMonthsView(config.shortMonthsNames, onMonthClick);
    popupBody.appendChild(months.element);

    days = createDaysView(config.weekDaysNames, onDayClick);
    popupBody.appendChild(days.element);

    views = [days, months, years, decades];

    input.onfocus = onInputFocus;
    input.onblur = onInputBlur;
    input.onkeypress = onInputKeyPress;
    pattern.onmousedown = onPatternMouseDown;
    icon.onmousedown = onIconMouseDown;
    popup.onblur = onPopupBlur;
  };


  var onDayClick = function(row, col, e) {
    var cell = days.cells[row][col];
    if (!cell.disabled) {
      var date = cell.value;
      var year = date.getFullYear();
      year = ('0000' + Math.abs(year)).slice(-4) + (year < 0 ? '-' : '');
      var month = ('00' + (date.getMonth() + 1)).slice(-2);
      var day = ('00' + date.getDate()).slice(-2);
      input.value = day + '/' + month + '/' + year;
      input.focus();
    }
  };


  var onMonthClick = function(row, col, e) {
    var cell = months.cells[row][col];
    if (!cell.disabled) {
      activateDays(cell.value);
    }
  };


  var onYearClick = function(row, col, e) {
    var cell = years.cells[row][col];
    if (!cell.disabled) {
      activateMonths(cell.value);
    }
  };


  var onDecadeClick = function(row, col, e) {
    var cell = decades.cells[row][col];
    if (!cell.disabled) {
      activateYears(cell.value);
    }
  };


  var setupHeader = function(title, onclickCenter, onclickLeft, onclickRight) {
    headerTitle.nodeValue = title;
    if (onclickCenter) {
      headerCenter.className = 'date-picker-header header-center selectable-header';
      headerCenter.onmousedown = onclickCenter;
    }
    else {
      headerCenter.className = 'date-picker-header header-center';
      headerCenter.onmousedown = preventBlur;
    }

    if (onclickLeft) {
      headerLeft.className = 'date-picker-header header-arrow selectable-header';
      headerLeft.onmousedown = onclickLeft;
      headerLeftArrow.nodeValue  = '<';
    }
    else {
      headerLeft.className = 'date-picker-header header-arrow';
      headerLeft.onmousedown = preventBlur;
      headerLeftArrow.nodeValue  = '\u00A0';
    }

    if (onclickRight) {
      headerRight.className = 'date-picker-header header-arrow selectable-header';
      headerRight.onmousedown = onclickRight;
      headerRightArrow.nodeValue  = '>';
    }
    else {
      headerRight.className = 'date-picker-header header-arrow';
      headerRight.onmousedown = preventBlur;
      headerRightArrow.nodeValue  = '\u00A0';
    }
  };


  var activateDays = function(dateToShow) {
    var date = new Date(dateToShow.valueOf());
    date.setDate(1);
    date.setHours(0, 0, 0, 0);

    var prevMonthDate = new Date(date.valueOf() - 1);
    var onclickLeft = (prevMonthDate > minDate) ? function() {
      activateDays(prevMonthDate);
      return false;
    } : null;

    var nextMonthDate = new Date(date.valueOf());
    nextMonthDate.setMonth(date.getMonth() + 1, 1);
    var onclickRight = (nextMonthDate <= maxDate) ? function() {
      activateDays(nextMonthDate);
      return false;
    } : null;

    var onclickCenter = (onclickLeft || onclickRight) ? function() {
      activateMonths(date.getFullYear());
      return false;
    } : null;

    var headerTitle = config.monthsNames[date.getMonth()] + ' ' + date.getFullYear();
    setupHeader(headerTitle, onclickCenter, onclickLeft, onclickRight);

    var dayOfWeek = config.sundayFirst ? date.getDay() : (date.getDay() + 6) % 7;
    var shift = 86400000 * (dayOfWeek ? dayOfWeek : 7);

    var className;
    var cells = days.cells;
    var typedDateValue = typedDate ? typedDate.valueOf() : null;
    var monthToShow = date.getMonth();
    date = new Date(date.valueOf() - shift);

    for (var row = 1; row < 7; row++) {
      for (var col = 0; col < 7; col++) {
        var cell = cells[row][col];

        if (date < minDate || date > maxDate) {
          cell.disabled = true;
          className = 'grayed';
        }
        else if (date.valueOf() == typedDateValue) {
          cell.disabled = true;
          className = 'selected-day';
        }
        else {
          cell.disabled = false;
          className = 'selectable';
          if (date.getMonth() != monthToShow) {
            className += ' grayed';
          }
        }

        cell.value.setFullYear(date.getFullYear(), date.getMonth(), date.getDate());
        cell.td.className = className;
        cell.text.nodeValue = date.getDate();

        date = new Date(date.valueOf() + 86400000);
      }
    }

    displayView(days);
  };


  var activateMonths = function(year) {
    var onclickLeft = (config.minYear < year) ? function() {
      activateMonths(year - 1);
      return false;
    } : null;

    var onclickRight = (config.maxYear > year) ? function() {
      activateMonths(year + 1);
      return false;
    } : null;

    var onclickCenter = (onclickLeft || onclickRight) ? function() {
      activateYears(year);
      return false;
    } : null;

    setupHeader(year, onclickCenter, onclickLeft, onclickRight);

    var month = 0;
    for (var i = 0; i < 3; i++) {
      for (var j = 0; j < 4; j++) {
        var cell = months.cells[i][j];
        var date = cell.value;
        date.setFullYear(year, month);
        cell.disabled = date < minDate || date > maxDate;
        cell.td.className = cell.disabled ? 'grayed' : 'selectable';
        month++;
      }
    }

    displayView(months);
  };


  var activateYears = function(year) {
    var firstYear = year - (year % 10 + 10) % 10;
    var lastYear = firstYear + 9;
    var thisYear = year;

    var onclickLeft = (config.minYear < firstYear) ? function() {
      activateYears(firstYear - 1);
      return false;
    } : null;

    var onclickRight = (config.maxYear > lastYear) ? function() {
      activateYears(lastYear + 1);
      return false;
    } : null;

    var onclickCenter = (onclickLeft || onclickRight) ? function() {
      activateDecades(thisYear);
      return false;
    } : null;

    var title = firstYear + ' \u2013 ' + lastYear;
    setupHeader(title, onclickCenter, onclickLeft, onclickRight);

    var className;
    year = firstYear - 1;

    for (var i = 0; i < 3; i++) {
      for (var j = 0; j < 4; j++) {
        var cell = years.cells[i][j];

        if (year < config.minYear || year > config.maxYear) {
          cell.disabled = true;
          className = 'grayed';
        }
        else if (i == 0 && j == 0 || i == 2 && j == 3) {
          cell.disabled = false;
          className = 'grayed selectable';
        }
        else {
          cell.disabled = false;
          className = 'selectable';
        }

        cell.value = year;
        cell.text.nodeValue = year;
        cell.td.className = className;

        year++;
      }
    }

    displayView(years);
  };


  var activateDecades = function(year) {
    var firstYear = year - (year % 10 + 10) % 10 - 50;
    var lastYear = firstYear + 119;

    var onclickLeft = (config.minYear < firstYear) ? function() {
      activateDecades(firstYear - 70);
      return false;
    } : null;

    var onclickRight = (config.maxYear > lastYear) ? function() {
      activateDecades(lastYear + 60);
      return false;
    } : null;

    var title = firstYear + ' \u2013 ' + lastYear;
    setupHeader(title, null, onclickLeft, onclickRight);

    var year2;
    var year1 = firstYear;
    for (var i = 0; i < 3; i++) {
      for (var j = 0; j < 4; j++) {
        year2 = year1 + 9;
        var cell = decades.cells[i][j];
        cell.value = year1 + 5;
        cell.text[0].nodeValue = year1;
        cell.text[1].nodeValue = year2;
        cell.disabled = year2 < config.minYear || year1 > config.maxYear;
        cell.td.className = cell.disabled  ? 'grayed' : 'selectable';
        year1 += 10;
      }
    }

    displayView(decades);
  };


  var onInputFocus = function() {
    pattern.style.display = 'none';
  };


  var onInputBlur = function() {
    var inputDate = input.value.trim();
    if (inputDate == '') {
      input.value = '';
      pattern.style.display = 'block';
    }
  };


  var onInputKeyPress = function(e) {
    e = e || event;
    if (e.ctrlKey || e.altKey || e.metaKey) return true;

    var chr = getChar(e);
    if (chr === null) return true;

    var text = input.value;
    if (chr == '/') return (text.match(/\//g) || []).length < 2;

    var length = text.length;
    return !(length < 10 && (chr < '0' || chr > '9') || length >= 10 && (chr != '-' || config.minYear >= 0));
  };


  var onPatternMouseDown = function() {
    input.focus();
    return false;
  };


  var onIconMouseDown = function() {
    if (popupVisible) {
      input.focus();
    }
    else {
      showPopup();
    }
    return false;
  };


  var onPopupBlur = function() {
    hidePopup();
  };


  var showPopup = function() {
    if (!popupVisible) {
      typedDate = getTypedDate();

      var dateToShow = typedDate ? typedDate : new Date();
      dateToShow = dateToShow < minDate ? minDate : (dateToShow > maxDate ? maxDate : dateToShow);
      activateDays(dateToShow);

      popupVisible = true;
      popup.style.display = 'block';
      popup.focus();
    }
  };


  var hidePopup = function() {
    if (popupVisible) {
      popupVisible = false;
      popup.style.display = 'none';
    }
  };


  var displayView = function(view) {
    view.element.style.display = 'block';
    for (var i = 0, count = views.length; i < count; i++) {
      if (views[i] != view) {
        views[i].element.style.display = 'none';
      }
    }
  };


  var createView = function(rows, cols, onclick) {
    var table = new Table(rows, cols);
    table.element.className = 'date-picker-calendar table-' + rows + 'x' + cols;

    for (var i = 0; i < rows; i++) {
      for (var j = 0; j < cols; j++) {
        var td = table.cells[i][j].td;
        td.onclick = function(row, col) {
          return function(event) {
            return onclick(row, col, event);
          }
        }(i, j);
        // prevents popup from loosing focus in IE when a child element is clicked
        td.unselectable = true;
        td.onmousedown = preventBlur;
      }
    }
    return table;
  };


  var createDaysView = function(shortWeekDays, onclick) {
    var view = createView(7, 7, onclick);
    for (var i = 0; i < 7; i++) {
      for (var j = 0; j < 7; j++) {
        var cell = view.cells[i][j];
        cell.text = document.createTextNode('');
        if (i == 0) {
          var dayOfWeek = config.sundayFirst ? (j + 6) % 7 : j;
          cell.text.nodeValue = shortWeekDays[dayOfWeek];
        }
        else {
          var date = new Date();
          date.setHours(0, 0, 0, 0);
          cell.value = date;
        }
        cell.td.appendChild(cell.text);
      }
    }
    return view;
  };


  var createMonthsView = function(shortMonths, onclick) {
    var view = createView(3, 4, onclick);

    for (var i = 0; i < 3; i++) {
      for (var j = 0; j < 4; j++) {
        var cell = view.cells[i][j];
        var month = i * 4 + j;
        var date = new Date();
        date.setFullYear(1970, month, 1);
        date.setHours(0, 0, 0, 0);
        cell.value = date;

        var td = cell.td;
        var text = document.createTextNode(shortMonths[month]);
        td.appendChild(text);
      }
    }
    return view;
  };


  var createYearsView = function(onclick) {
    var view = createView(3, 4, onclick);

    for (var i = 0; i < 3; i++) {
      for (var j = 0; j < 4; j++) {
        var cell = view.cells[i][j];
        cell.text = document.createTextNode('');
        cell.td.appendChild(cell.text);
      }
    }
    return view;
  };


  var createDecadesView = function(onclick) {
    var view = createView(3, 4, onclick);

    for (var i = 0; i < 3; i++) {
      for (var j = 0; j < 4; j++) {
        var cell = view.cells[i][j];
        var td = cell.td;
        var br = document.createElement('br');
        var dash = document.createTextNode('-');
        cell.text = [];
        cell.text[0] = document.createTextNode('');
        cell.text[1] = document.createTextNode('');
        td.innerHTML = '&nbsp;';
        td.insertBefore(cell.text[1], td.firstChild);
        td.insertBefore(br, cell.text[1]);
        td.insertBefore(dash, br);
        td.insertBefore(cell.text[0], dash);
      }
    }
    return view;
  };


  var getTypedDate = function() {
    var match = (config.minYear < 0) ? /^(\d{1,2})\/(\d{1,2})\/(\d{4}\-?)$/ : /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;
    var dmy = input.value.match(match);
    if (!dmy) return null;

    var day = dmy[1];
    var month = dmy[2] - 1;
    var year = (dmy[3].length == 4) ? dmy[3] : (-dmy[3].substr(0, 4));
    var date = new Date();
    date.setFullYear(year, month, day);
    date.setHours(0, 0, 0, 0);
    return (date.getDate() == day && date.getMonth() == month && date.getFullYear() == year) ? date : null;
  };


  var preventBlur = function () {
    return false;
  };

  initialize();
}


DatePicker.Config = function(inputId, inputPattern, weekDaysNames, shortMonthsNames, monthsNames, sundayFirst,
  minYear, minMonth, minDay, maxYear, maxMonth, maxDay) {
  this.inputId          = inputId;
  this.inputPattern     = inputPattern;
  this.weekDaysNames    = weekDaysNames;
  this.shortMonthsNames = shortMonthsNames;
  this.monthsNames      = monthsNames;
  this.sundayFirst      = sundayFirst;
  this.minYear          = minYear;
  this.minMonth         = minMonth;
  this.minDay           = minDay;
  this.maxYear          = maxYear;
  this.maxMonth         = maxMonth;
  this.maxDay           = maxDay;
};


function Table(rows, cols) {
  this.cells = [];
  var tableBody = document.createElement('TBODY');
  this.element = document.createElement('TABLE');
  this.element.appendChild(tableBody);

  for (var row = 0; row < rows; row++) {
    this.cells[row] = [];
    var tr = document.createElement('TR');
    tableBody.appendChild(tr);

    for (var col = 0; col < cols; col++) {
      var td = document.createElement('TD');
      tr.appendChild(td);
      this.cells[row][col] = new Table.Cell(td);
    }
  }
}


Table.Cell = function(td) {
  this.disabled = true;
  this.value = null;
  this.text = null;
  this.td = td;
};
