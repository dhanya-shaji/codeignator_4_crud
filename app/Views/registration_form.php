<script src="https://cdn01.jotfor.ms/static/prototype.forms.js" type="text/javascript"></script>
<script src="https://cdn02.jotfor.ms/static/jotform.forms.js?3.3.28735" type="text/javascript"></script>
<script src="https://cdn03.jotfor.ms/js/vendor/jquery-1.8.0.min.js?v=3.3.28735" type="text/javascript"></script>
<script defer src="https://cdn01.jotfor.ms/js/vendor/maskedinput.min.js?v=3.3.28735" type="text/javascript"></script>
<script defer src="https://cdn02.jotfor.ms/js/vendor/jquery.maskedinput.min.js?v=3.3.28735" type="text/javascript"></script>
<script src="https://cdn03.jotfor.ms//common/timezonePicker.js" type="text/javascript"></script>
<script type="text/javascript">	JotForm.newDefaultTheme = true;
	JotForm.extendsNewTheme = false;
	JotForm.newPaymentUIForNewCreatedForms = true;
	JotForm.newPaymentUI = true;
	JotForm.clearFieldOnHide="disable";
	JotForm.submitError="jumpToFirstError";

	JotForm.init(function(){
	/*INIT-START*/
      JotForm.setPhoneMaskingValidator( 'input_5_full', '(###) ###-####' );

 JotForm.calendarMonths = ["January","February","March","April","May","June","July","August","September","October","November","December"];
 JotForm.calendarDays = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
 JotForm.calendarOther = {"today":"Today"};
 var languageOptions = document.querySelectorAll('#langList li'); 
 for(var langIndex = 0; langIndex < languageOptions.length; langIndex++) { 
   languageOptions[langIndex].on('click', function(e) { setTimeout(function(){ JotForm.setCalendar("6", false, {"days":{"monday":true,"tuesday":true,"wednesday":true,"thursday":true,"friday":true,"saturday":true,"sunday":true},"future":true,"past":true,"custom":false,"ranges":false,"start":"","end":""}); }, 0); });
 } 
 JotForm.onTranslationsFetch(function() { JotForm.setCalendar("6", false, {"days":{"monday":true,"tuesday":true,"wednesday":true,"thursday":true,"friday":true,"saturday":true,"sunday":true},"future":true,"past":true,"custom":false,"ranges":false,"start":"","end":""}); });

    JotForm.calendarMonths = ["January","February","March","April","May","June","July","August","September","October","November","December"];
    JotForm.calendarDays = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];
    JotForm.calendarOther = "Today";

    JotForm.checkAppointmentAvailability = function checkAppointmentAvailability(day, slot, data) {
	  if (!(day && slot && data && data[day])) return false;
	  return data[day][slot];
	};

    (function init(props) {
	  var PREFIX = window.location.href.indexOf('jotform.pro') > -1 || window.location.pathname.indexOf('build') > -1 || window.location.pathname.indexOf('form-templates') > -1 || window.location.pathname.indexOf('pdf-templates') > -1 || window.location.pathname.indexOf('table-templates') > -1 || window.location.pathname.indexOf('approval-templates') > -1 ? '/server.php' : JotForm.server;

	  // boilerplate
	  var effectsOut = null;
	  var changed = {};
	  var constructed = false;
	  var _window = window,
	      document = _window.document;

	  var wrapper = document.querySelector('#' + props.qid.value);
	  var uniqueId = props.qid.value;
	  var element = wrapper.querySelector('.appointmentField');
	  var input = wrapper.querySelector('#input_' + props.id.value + '_date');
	  var tzInput = wrapper.querySelector('#input_' + props.id.value + '_timezone');
	  var timezonePickerCommon = void 0;
	  var isTimezonePickerFromCommonLoaded = false;

	  function debounce(func, wait, immediate) {
	    var timeout = void 0;
	    return function wrappedFN() {
	      for (var _len = arguments.length, args = Array(_len), _key = 0; _key < _len; _key++) {
	        args[_key] = arguments[_key];
	      }

	      var context = this;
	      var later = function later() {
	        timeout = null;
	        if (!immediate) func.apply.apply(func, [context].concat(args));
	      };
	      var callNow = immediate && !timeout;
	      clearTimeout(timeout);
	      timeout = setTimeout(later, wait);
	      if (callNow) func.apply.apply(func, [context].concat(args));
	    };
	  }

	  var classNames = function classNames(obj) {
	    return Object.keys(obj).reduce(function (acc, key) {
	      if (!obj[key]) return acc;
	      return [].concat(acc, [key]);
	    }, []).join(' ');
	  };

	  var assignObject = function assignObject() {
	    for (var _len2 = arguments.length, objects = Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
	      objects[_key2] = arguments[_key2];
	    }

	    return objects.reduce(function (acc, obj) {
	      Object.keys(obj).forEach(function (key) {
	        acc[key] = obj[key];
	      });

	      return acc;
	    }, {});
	  };

	  var objectEntries = function objectEntries(obj) {
	    return Object.keys(obj).reduce(function (acc, key) {
	      var value = obj[key];
	      var pair = [key, value];
	      return [].concat(acc, [pair]);
	    }, []);
	  };

	  var fillArray = function fillArray(arr, value) {
	    var newArr = [];
	    for (var i = 0; i < arr.length; i++) {
	      newArr.push(value);
	    }
	    return newArr;
	  };

	  var getJSON = function getJSON(url, cb) {
	    return new Ajax.Request(url, {
	      evalJSON: 'force',
	      method: 'GET',
	      onComplete: function onComplete(response) {
	        return cb(response.responseJSON);
	      }
	    });
	  };

	  var beforeRender = function beforeRender() {
	    if (effectsOut) {
	      effectsOut();
	      effectsOut = null;
	    }
	  };

	  var afterRender = function afterRender() {
	    effectsOut = effects();
	  };

	  var setState = function setState(newState) {
	    var changedKeys = Object.keys(newState).filter(function (key) {
	      return state[key] !== newState[key];
	    });

	    if (!changedKeys.length) {
	      return;
	    }

	    changed = changedKeys.reduce(function (acc, key) {
	      var _assignObject;

	      return assignObject({}, acc, (_assignObject = {}, _assignObject[key] = state[key], _assignObject));
	    }, changed);

	    state = assignObject({}, state, newState);
	    if (constructed) {
	      render();
	    }
	  };

	  var isStartWeekOnMonday = function isStartWeekOnMonday() {
	    var _props = props,
	        startDay = _props.startWeekOn.value;

	    return !startDay || startDay === 'Monday';
	  };

	  var monthNames = function monthNames() {
	    return (JotForm.calendarMonthsTranslated || JotForm.calendarMonths || _Utils.specialOptions.Months.value).map(function (monthName) {
	      return String.prototype.locale ? monthName.locale() : monthName;
	    });
	  };
	  var daysOfWeek = function daysOfWeek() {
	    return (JotForm.calendarDaysTranslated || JotForm.calendarDays || _Utils.specialOptions.Days.value).map(function (dayName) {
	      return String.prototype.locale ? dayName.locale() : dayName;
	    });
	  };
	  // we need remove unnecessary "Sunday", if there is time field on the form
	  var dayNames = function dayNames() {
	    var days = daysOfWeek().length === 8 ? daysOfWeek().slice(1) : daysOfWeek();
	    return isStartWeekOnMonday() ? days : [days[days.length - 1]].concat(days.slice(0, 6));
	  };

	  var oneHour = 1000 * 60 * 60;
	  // const oneDay = oneHour * 24;

	  var intPrefix = function intPrefix(d) {
	    if (d < 10) {
	      return '0' + d;
	    }

	    return '' + d;
	  };

	  var toFormattedDateStr = function toFormattedDateStr(date) {
	    var _props2 = props,
	        _props2$dateFormat$va = _props2.dateFormat.value,
	        format = _props2$dateFormat$va === undefined ? 'yyyy-mm-dd' : _props2$dateFormat$va;

	    if (!date) return;
	    if (typeof date === 'string') {
	      var _date$split = date.split('-'),
	          _year = _date$split[0],
	          _month = _date$split[1],
	          _day = _date$split[2];

	      return format.replace(/yyyy/, _year).replace(/mm/, _month).replace(/dd/, _day);
	    }

	    var year = date.getFullYear();
	    var month = intPrefix(date.getMonth() + 1);
	    var day = intPrefix(date.getUTCDate());
	    return format.replace(/yyyy/, year).replace(/mm/, month).replace(/dd/, day);
	  };

	  var toDateStr = function toDateStr(date) {
	    if (!date) return;
	    var year = date.getFullYear();
	    var month = intPrefix(date.getMonth() + 1);
	    var day = intPrefix(date.getDate());

	    return year + '-' + month + '-' + day;
	  };

	  var toDateTimeStr = function toDateTimeStr(date) {
	    if (!date) return;
	    var ymd = toDateStr(date);
	    var hour = intPrefix(date.getHours());
	    var minute = intPrefix(date.getMinutes());
	    return ymd + ' ' + hour + ':' + minute;
	  };

	  var getTimezoneOffset = function getTimezoneOffset(timezone) {
	    if (!timezone) {
	      return 0;
	    }
	    var cityArgs = timezone.split(' ');
	    var splitted = cityArgs[cityArgs.length - 1].replace(/\(GMT|\)/g, '').split(':');

	    if (!splitted) {
	      return 0;
	    }

	    return parseInt(splitted[0] || 0, 10) + (parseInt(splitted[1] || 0, 10) / 60 || 0);
	  };

	  var getGMTSuffix = function getGMTSuffix(offset) {
	    if (offset === 0) {
	      return '';
	    }

	    var offsetMinutes = Math.abs(offset) % 60;
	    var offsetHours = Math.abs(offset - offsetMinutes) / 60;

	    var str = intPrefix(offsetHours) + ':' + intPrefix(offsetMinutes);

	    if (offset < 0) {
	      return '+' + str;
	    }

	    return '-' + str;
	  };

	  // const toJSDate = (dateStr, timezone) => {
	  //   if (!dateStr) return;

	  //   const gmtSuffix = getGMTSuffix(timezone || state.timezone);

	  //   return new Date(`${dateStr} GMT${gmtSuffix}`);
	  // };

	  var getYearMonth = function getYearMonth(date) {
	    if (!date) return;

	    var _date$split2 = date.split('-'),
	        y = _date$split2[0],
	        m = _date$split2[1];

	    return y + '-' + m;
	  };

	  var getMondayBasedDay = function getMondayBasedDay(date) {
	    var day = date.getUTCDay();
	    if (day === 0) {
	      return 6; // sunday index
	    }
	    return day - 1;
	  };

	  var getDay = function getDay(date) {
	    return isStartWeekOnMonday() ? getMondayBasedDay(date) : date.getUTCDay();
	  };

	  var getUserTimezone = function getUserTimezone() {
	    var _props3 = props,
	        _props3$autoDetectTim = _props3.autoDetectTimezone;
	    _props3$autoDetectTim = _props3$autoDetectTim === undefined ? {} : _props3$autoDetectTim;
	    var autoDetectValue = _props3$autoDetectTim.value,
	        _props3$timezone = _props3.timezone;
	    _props3$timezone = _props3$timezone === undefined ? {} : _props3$timezone;
	    var timezoneAtProps = _props3$timezone.value;

	    if (autoDetectValue === 'No') {
	      return timezoneAtProps;
	    }

	    try {
	      var tzStr = Intl.DateTimeFormat().resolvedOptions().timeZone;
	      if (tzStr) {
	        var tz = tzStr + ' (GMT' + getGMTSuffix(new Date().getTimezoneOffset()) + ')';
	        return tz;
	      }
	    } catch (e) {
	      console.error(e.message);
	    }

	    return props.timezone.value;
	  };

	  var passedProps = props;

	  var getStateFromProps = function getStateFromProps() {
	    var newProps = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : {};

	    var startDate = new Date(newProps.startDate ? newProps.startDate.value : '');
	    var today = new Date();
	    var date = toDateStr(new Date(Math.max(startDate, today) || today));

	    return {
	      date: date,
	      timezones: state ? state.timezones : { loading: true }
	    };
	  };

	  var getFirstAvailableDates = function getFirstAvailableDates(cb) {
	    var _props4 = props,
	        _props4$formID = _props4.formID,
	        formID = _props4$formID === undefined ? global.__formInfo.id : _props4$formID;
	    var _state = state,
	        _state$timezone = _state.timezone,
	        timezone = _state$timezone === undefined ? getUserTimezone() : _state$timezone;


	    if (!formID || !timezone) return;

	    // eslint-disable-next-line max-len
	    var url = PREFIX + '?action=getAppointments&formID=' + formID + '&timezone=' + encodeURIComponent(timezone) + '&ncTz=' + new Date().getTime() + '&firstAvailableDates';

	    return getJSON(url, function (_ref) {
	      var content = _ref.content;
	      return cb(content);
	    });
	  };

	  var state = getStateFromProps(props);

	  var loadTimezones = function loadTimezones(cb) {
	    setState({
	      timezones: { loading: true }
	    });

	    getJSON((props.cdnconfig.CDN || '/') + 'assets/form/timezones.json?ncTz=' + new Date().getTime(), function (data) {
	      var timezones = objectEntries(data).reduce(function (acc, _ref2) {
	        var group = _ref2[0],
	            cities = _ref2[1];

	        acc.push({
	          group: group,
	          cities: cities
	        });
	        return acc;
	      }, []);

	      cb(timezones);
	    });
	  };

	  var loadMonthData = function loadMonthData(startDate, endDate, cb) {
	    var _props5 = props,
	        _props5$formID = _props5.formID,
	        formID = _props5$formID === undefined ? (typeof global === 'undefined' ? 'undefined' : _typeof(global)) === 'object' ? global.__formInfo.id : null : _props5$formID,
	        id = _props5.id.value;
	    var _state2 = state,
	        timezone = _state2.timezone;


	    if (!formID || !timezone) return;

	    // eslint-disable-next-line max-len
	    var url = PREFIX + '?action=getAppointments&formID=' + formID + '&qid=' + id + '&timezone=' + encodeURIComponent(timezone) + '&startDate=' + toDateStr(startDate) + '&endDate=' + toDateStr(endDate) + '&ncTz=' + new Date().getTime();

	    return getJSON(url, function (_ref3) {
	      var data = _ref3.content;
	      return cb(data);
	    });
	  };

	  var generateMonthData = function generateMonthData(startDate, endDate, data) {
	    var d1 = startDate.getDate();
	    var d2 = endDate.getDate();
	    var dPrefix = startDate.getFullYear() + '-' + intPrefix(startDate.getMonth() + 1) + '-';

	    var daysLength = d2 - d1 + 1 || 0;
	    var days = fillArray(new Array(daysLength), '');

	    var slots = days.reduce(function (acc, x, day) {
	      var _assignObject2;

	      var dayStr = '' + dPrefix + intPrefix(day + 1);
	      return assignObject(acc, (_assignObject2 = {}, _assignObject2[dayStr] = data[dayStr] || false, _assignObject2));
	    }, {});

	    var availableDays = Object.keys(data).filter(function (d) {
	      return !Array.isArray(slots[d]) && !!slots[d];
	    });

	    return {
	      availableDays: availableDays,
	      slots: slots
	    };
	  };

	  var lastReq = void 0;

	  var updateMonthData = function updateMonthData(startDate, endDate, data) {
	    var _generateMonthData = generateMonthData(startDate, endDate, data),
	        availableDays = _generateMonthData.availableDays,
	        slots = _generateMonthData.slots;

	    if (JSON.stringify(slots) === JSON.stringify(state.slots)) {
	      return;
	    }

	    setState({
	      availableDays: availableDays,
	      slots: slots
	    });
	  };

	  var getDateRange = function getDateRange(dateStr) {
	    var _dateStr$split = dateStr.split('-'),
	        y = _dateStr$split[0],
	        m = _dateStr$split[1];

	    var startDate = new Date(y, m - 1, 1);
	    var endDate = new Date(y, m, 0);
	    return [startDate, endDate];
	  };

	  var load = function load() {
	    var _state3 = state,
	        dateStr = _state3.date;

	    var _getDateRange = getDateRange(dateStr),
	        startDate = _getDateRange[0],
	        endDate = _getDateRange[1];

	    setState(assignObject({ loading: true }, generateMonthData(startDate, endDate, {})));

	    var req = loadMonthData(startDate, endDate, function (data) {
	      if (lastReq !== req) {
	        return;
	      }

	      updateMonthData(startDate, endDate, data);
	      var _state4 = state,
	          availableDays = _state4.availableDays,
	          forcedStartDate = _state4.forcedStartDate,
	          forcedEndDate = _state4.forcedEndDate,
	          slots = _state4.slots;


	      var firstAvailable = availableDays.find(function (d) {
	        var foundSlot = Object.keys(slots[d]).find(function (slot) {
	          var slotDate = dateInTimeZone(new Date((d + ' ' + slot).replace(/-/g, '/')));

	          if (forcedStartDate && slotDate > forcedStartDate) return false;
	          if (forcedEndDate && slotDate < forcedEndDate) return false;

	          return true;
	        });

	        return foundSlot;
	      });

	      var newDate = availableDays.indexOf(dateStr) === -1 && firstAvailable;

	      setState({
	        loading: false,
	        date: newDate || dateStr
	      });
	    });

	    lastReq = req;
	  };

	  var dateInTimeZone = function dateInTimeZone(date) {
	    var timezone = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : state.timezone;

	    if (!date) return;
	    var diffTime = (getTimezoneOffset(timezone) - state.nyTz) * oneHour;
	    return new Date(date.getTime() - diffTime);
	  };

	  var dz = function dz(date, tz1, tz2) {
	    if (!date) return;
	    var diffTime = (tz1 - tz2) * oneHour;
	    return new Date(date.getTime() - diffTime);
	  };

	  var revertDate = function revertDate(d, t1, t2) {
	    if (!d) return '';

	    var pDate = new Date(d.replace(/-/, '/'));
	    var utz = getTimezoneOffset(getUserTimezone());
	    var tz1 = getTimezoneOffset(t1) - utz;
	    var tz2 = getTimezoneOffset(t2) - utz;

	    var val = dz(pDate, tz1, tz2);

	    return toDateTimeStr(val);
	  };

	  var amPmConverter = function amPmConverter(_time) {
	    var _props6 = props,
	        _props6$timeFormat = _props6.timeFormat;
	    _props6$timeFormat = _props6$timeFormat === undefined ? {} : _props6$timeFormat;
	    var _props6$timeFormat$va = _props6$timeFormat.value,
	        timeFormat = _props6$timeFormat$va === undefined ? '24 Hour' : _props6$timeFormat$va;

	    if (!_time || !(typeof _time === 'string') || _time.indexOf('M') > -1 || !timeFormat || timeFormat === '24 Hour') {
	      return _time;
	    }
	    var time = _time.substring(0, 2);
	    var hour = time % 12 || 12;
	    var ampm = time < 12 ? 'AM' : 'PM';
	    return '' + hour + _time.substring(2, 5) + ' ' + ampm;
	  };

	  var validate = function validate(dateStr, cb) {
	    var _state5 = state,
	        defaultValue = _state5.defaultValue;


	    if (JotForm.isEditMode() && defaultValue === dateStr) {
	      return cb(true);
	    }

	    var parts = dateStr.split(' ');
	    var slot = parts.slice(1).join(' ');

	    var _parts$0$split = parts[0].split('-'),
	        y = _parts$0$split[0],
	        m = _parts$0$split[1],
	        d = _parts$0$split[2];

	    var startDate = new Date(y, m - 1, 1);
	    var endDate = new Date(y, m, 0);

	    loadMonthData(startDate, endDate, function (data) {
	      var day = y + '-' + m + '-' + d;
	      var isValid = JotForm.checkAppointmentAvailability(day, amPmConverter(slot), data);
	      cb(isValid);
	      if (!isValid) {
	        var _assignObject3;

	        // add unavailable slot if it is not in response for deselection
	        data[day] = assignObject({}, data[day], (_assignObject3 = {}, _assignObject3[slot] = false, _assignObject3));
	      }

	      // still in same month
	      if (state.date.indexOf(y + '-' + m) === 0) {
	        updateMonthData(startDate, endDate, data);
	      }
	    });
	  };

	  // let validationInterval;

	  var validation = function validation(_value) {
	    var shouldValidate = _value || $(input).hasClassName('validate');

	    if (!shouldValidate) {
	      $(input).addClassName('valid');
	      JotForm.corrected(input);
	      JotForm.runConditionForId(props.id.value);
	      return;
	    }

	    if (!_value) {
	      $(input).removeClassName('valid');
	      JotForm.errored(input, JotForm.texts.required);
	      JotForm.runConditionForId(props.id.value);
	      return;
	    }

	    validate(_value, function (isValid) {
	      if (isValid) {
	        $(input).addClassName('valid');
	        JotForm.corrected(input);
	        JotForm.runConditionForId(props.id.value);
	        return;
	      }

	      // clearInterval(validationInterval);

	      var parts = _value.split(' ');
	      var dateStr = parts[0];

	      var date = new Date(dateStr);
	      var day = getDay(date);
	      var datetime = dayNames()[day] + ', ' + monthNames()[date.getMonth()] + ' ' + intPrefix(date.getUTCDate()) + ', ' + date.getFullYear();

	      var time = parts.slice(1).join(' ');
	      var errorText = JotForm.texts.slotUnavailable.replace('{time}', time).replace('{date}', datetime);

	      $(input).removeClassName('valid');
	      JotForm.errored(input, errorText);
	      JotForm.runConditionForId(props.id.value);
	    });
	  };

	  var setValue = function setValue(value) {
	    input.value = value ? toDateTimeStr(dateInTimeZone(new Date(value.replace(/-/g, '/')))) : '';

	    setState({
	      value: value
	    });

	    // trigger input event for supporting progress bar widget
	    input.triggerEvent('input');

	    // clearInterval(validationInterval);

	    validation(value);
	    // validationInterval = setInterval(() => { validation(state.value); }, 1000 * 5);
	  };

	  var handleClick = function handleClick(e) {
	    var target = e.target;

	    var $target = $(target);

	    if (!$target || !$target.hasClassName) {
	      return;
	    }

	    if ($target.hasClassName('disabled') && !$target.hasClassName('active')) {
	      return;
	    }

	    e.preventDefault();
	    var value = target.dataset.value;

	    setValue($target.hasClassName('active') ? undefined : value);
	  };

	  var setTimezone = function setTimezone(timezone) {
	    tzInput.value = timezone;
	    setState({ timezone: timezone });
	    if (input.value) {
	      var date = toDateTimeStr(dz(new Date(input.value.replace(/-/g, '/')), state.nyTz, getTimezoneOffset(state.timezone)));
	      setDate(date.split(' ')[0]);
	      setState({ value: date });
	    }
	  };

	  var handleTimezoneChange = function handleTimezoneChange(e) {
	    var target = e.target;
	    var timezone = target.value;

	    setTimezone(timezone);
	  };

	  var setDate = function setDate(date) {
	    return setState({ date: date });
	  };

	  var handleDateChange = function handleDateChange(e) {
	    var target = e.target;
	    var date = target.dataset.value;


	    if (!date) return;

	    setDate(date);
	  };

	  var handleMonthYearChange = function handleMonthYearChange(e) {
	    var _e$target = e.target,
	        dataset = _e$target.dataset,
	        inputVal = _e$target.value;
	    var name = dataset.name;

	    if (!name) {
	      return;
	    }

	    var _state6 = state,
	        date = _state6.date;

	    var oldDate = new Date(date);
	    var oldMonth = oldDate.getMonth();
	    var oldYear = oldDate.getFullYear();
	    var oldDay = oldDate.getUTCDate();

	    var value = inputVal || e.target.getAttribute('value');

	    if (name === 'month') {
	      var newDate = new Date(oldYear, value, oldDay);
	      var i = 1;
	      while ('' + newDate.getMonth() !== '' + value && i < 10) {
	        newDate = new Date(oldYear, value, oldDay - i);
	        i++;
	      }

	      return setDate(toDateStr(newDate));
	    }

	    return setDate(toDateStr(new Date(value, oldMonth, oldDay)));
	  };

	  var toggleMobileState = function toggleMobileState() {
	    var $wrapper = $(wrapper);
	    if ($wrapper.hasClassName('isOpenMobile')) {
	      $wrapper.removeClassName('isOpenMobile');
	    } else {
	      $wrapper.addClassName('isOpenMobile');
	    }
	  };

	  var keepSlotsScrollPosition = function keepSlotsScrollPosition() {
	    var visibleSlot = element.querySelector('.appointmentSlots.slots .slot.active, .appointmentSlots.slots .slot:not(.disabled)');

	    element.querySelector('.appointmentSlots.slots').scrollTop = visibleSlot ? visibleSlot.offsetTop : 0;
	  };

	  var setTimezonePickerState = function setTimezonePickerState() {
	    var _state7 = state,
	        timezone = _state7.timezone;
	    var _props7 = props,
	        _props7$autoDetectTim = _props7.autoDetectTimezone;
	    _props7$autoDetectTim = _props7$autoDetectTim === undefined ? {} : _props7$autoDetectTim;
	    var autoDetecTimezoneValue = _props7$autoDetectTim.value,
	        _props7$timezone = _props7.timezone;
	    _props7$timezone = _props7$timezone === undefined ? {} : _props7$timezone;
	    var timezoneValueProps = _props7$timezone.value;

	    if (autoDetecTimezoneValue === 'No') {
	      timezonePickerCommon.setSelectedTimezone(timezoneValueProps);
	    } else {
	      timezonePickerCommon.setSelectedTimezone(timezone);
	    }
	    timezonePickerCommon.setIsAutoSelectTimezoneOpen(autoDetecTimezoneValue);
	  };

	  var handleUIUpdate = function handleUIUpdate() {
	    try {
	      var breakpoints = {
	        450: 'isLarge',
	        225: 'isNormal',
	        175: 'shouldBreakIntoNewLine'
	      };

	      var offsetWidth = function () {
	        try {
	          var appointmentCalendarRow = element.querySelector('.appointmentFieldRow.forCalendar');
	          var appointmendCalendar = element.querySelector('.appointmentCalendar');
	          return appointmentCalendarRow.getBoundingClientRect().width - appointmendCalendar.getBoundingClientRect().width;
	        } catch (e) {
	          return null;
	        }
	      }();

	      if (offsetWidth === null || parseInt(wrapper.readAttribute('data-breakpoint-offset'), 10) === offsetWidth) {
	        return;
	      }

	      var breakpoint = Object.keys(breakpoints).reduce(function (prev, curr) {
	        return Math.abs(curr - offsetWidth) < Math.abs(prev - offsetWidth) ? curr : prev;
	      });
	      var breakpointName = breakpoints[breakpoint];

	      wrapper.setAttribute('data-breakpoint', breakpointName);
	      wrapper.setAttribute('data-breakpoint-offset', offsetWidth);
	    } catch (e) {
	      // noop.
	    }
	  };

	  var uiUpdateInterval = void 0;

	  var effects = function effects() {
	    clearInterval(uiUpdateInterval);

	    var shouldLoad = changed.timezone && changed.timezone !== state.timezone || // time zone changed
	    changed.date && getYearMonth(changed.date) !== getYearMonth(state.date); // y-m changed

	    changed = {};

	    if (shouldLoad) {
	      load();
	    }

	    var cancelBtn = element.querySelector('.cancel');

	    if (cancelBtn) {
	      cancelBtn.addEventListener('click', function () {
	        setValue(undefined);
	      });
	    }

	    var forSelectedDate = element.querySelector('.forSelectedDate span');

	    if (forSelectedDate) {
	      forSelectedDate.addEventListener('click', function () {
	        setDate(state.value.split(' ')[0]);
	      });
	    }

	    if (isTimezonePickerFromCommonLoaded) {
	      setTimezonePickerState();
	      var timezonePickerWrapper = element.querySelector('.forTimezonePicker');
	      timezonePickerCommon.init(timezonePickerWrapper);
	    } else {
	      element.querySelector('.timezonePicker').addEventListener('change', handleTimezoneChange);
	    }

	    element.querySelector('.calendar .days').addEventListener('click', handleDateChange);
	    element.querySelector('.monthYearPicker').addEventListener('change', handleMonthYearChange);
	    element.querySelector('.dayPicker').addEventListener('click', handleDateChange);
	    element.querySelector('.selectedDate').addEventListener('click', toggleMobileState);

	    Array.prototype.slice.call(element.querySelectorAll('.monthYearPicker .pickerArrow')).forEach(function (el) {
	      return el.addEventListener('click', handleMonthYearChange);
	    });
	    Array.prototype.slice.call(element.querySelectorAll('.slot')).forEach(function (el) {
	      return el.addEventListener('click', handleClick);
	    });

	    keepSlotsScrollPosition();
	    uiUpdateInterval = setInterval(handleUIUpdate, 250);

	    JotForm.runAllCalculations();
	  };

	  var onTimezoneOptionClick = function onTimezoneOptionClick(timezoneValue) {
	    setTimezone(timezoneValue);
	  };

	  var renderMonthYearPicker = function renderMonthYearPicker() {
	    var _state8 = state,
	        date = _state8.date;

	    var _split = (date || '-').split('-'),
	        year = _split[0],
	        month = _split[1];

	    var yearOpts = fillArray(new Array(20), '').map(function (v, i) {
	      return '' + (2020 + i);
	    });

	    var prevMonthButtonText = props.prevMonthButtonText && props.prevMonthButtonText.text || 'Previous month';
	    var nextMonthButtonText = props.nextMonthButtonText && props.nextMonthButtonText.text || 'Next month';
	    var prevYearButtonText = props.prevYearButtonText && props.prevYearButtonText.text || 'Previous year';
	    var nextYearButtonText = props.nextYearButtonText && props.nextYearButtonText.text || 'Next year';

	    return '\n      <div class=\'monthYearPicker\'>\n        <div class=\'pickerItem\'>\n          <select class=\'pickerMonth\' data-name=\'month\'>\n            ' + monthNames().map(function (monthName, i) {
	      return '<option ' + (parseInt(month, 10) === i + 1 ? 'selected' : '') + ' value=\'' + i + '\'>' + monthName + '</option>';
	    }).join('') + '\n          </select>\n          <button type=\'button\' class=\'pickerArrow pickerMonthArrow prev\' value=\'' + (parseInt(month, 10) - 2) + '\' data-name=\'month\' aria-label="' + prevMonthButtonText + '"></button>\n          <button type=\'button\' class=\'pickerArrow pickerMonthArrow next\' value=\'' + parseInt(month, 10) + '\' data-name=\'month\' aria-label="' + nextMonthButtonText + '"></button>\n        </div>\n        <div class=\'pickerItem\'>\n          <select class=\'pickerYear\' data-name=\'year\'>\n            ' + yearOpts.map(function (yearName) {
	      return '<option ' + (year === yearName ? 'selected' : '') + '>' + yearName + '</option>';
	    }).join('') + '\n          </select>\n          <button type=\'button\' class=\'pickerArrow pickerYearArrow prev\' value=\'' + (parseInt(year, 10) - 1) + '\' data-name=\'year\' aria-label="' + prevYearButtonText + '"/>\n          <button type=\'button\' class=\'pickerArrow pickerYearArrow next\' value=\'' + (parseInt(year, 10) + 1) + '\' data-name=\'year\' aria-label="' + nextYearButtonText + '"/>\n        </div>\n      </div>\n    ';
	  };

	  var getNav = function getNav() {
	    var _state9 = state,
	        availableDays = _state9.availableDays,
	        dateStr = _state9.date;


	    var next = void 0;
	    var prev = void 0;

	    var _dateStr$split2 = dateStr.split('-'),
	        year = _dateStr$split2[0],
	        month = _dateStr$split2[1];

	    if (availableDays) {
	      var dayIndex = availableDays.indexOf(dateStr);
	      if (dayIndex > 0) {
	        prev = availableDays[dayIndex - 1];
	      } else {
	        var prevDate = new Date(year, month - 1, 0);
	        prev = toDateStr(prevDate);
	      }
	      if (dayIndex + 1 < availableDays.length) {
	        next = availableDays[dayIndex + 1];
	      } else {
	        var nextDate = new Date(year, month, 1);
	        next = toDateStr(nextDate);
	      }
	    }

	    return { prev: prev, next: next };
	  };

	  var renderDayPicker = function renderDayPicker() {
	    var _state10 = state,
	        loading = _state10.loading;

	    var _getNav = getNav(),
	        prev = _getNav.prev,
	        next = _getNav.next;

	    var prevDayButtonText = props.prevDayButtonText && props.prevDayButtonText.text || 'Previous day';
	    var nextDayButtonText = props.nextDayButtonText && props.nextDayButtonText.text || 'Next day';

	    return '\n      <div class=\'appointmentDayPicker dayPicker\'>\n        <button type=\'button\' ' + (loading || !prev ? 'disabled' : '') + ' class="appointmentDayPickerButton prev" ' + (prev && 'data-value="' + prev + '"') + ' aria-label="' + prevDayButtonText + '">&lt;</button>\n        <button type=\'button\' ' + (loading || !next ? 'disabled' : '') + ' class="appointmentDayPickerButton next" ' + (next && 'data-value="' + next + '"') + ' aria-label="' + nextDayButtonText + '">&gt;</button>\n      </div>\n    ';
	  };

	  var renderTimezonePicker = function renderTimezonePicker() {
	    var _state11 = state,
	        timezone = _state11.timezone,
	        timezones = _state11.timezones;


	    return '\n      <div class=\'timezonePickerWrapper\'> \n        <select class=\'timezonePicker\'>\n          ' + (!timezones.loading && timezones.map(function (_ref4) {
	      var group = _ref4.group,
	          cities = _ref4.cities;
	      return '\n                <optgroup label="' + group + '">\n                  ' + cities.map(function (val) {
	        return '<option ' + (timezone.indexOf((group + '/' + val).split(' ')[0]) > -1 ? 'selected' : '') + ' value=\'' + group + '/' + val + '\'>' + val + '</option>';
	      }).join('') + '\n                </optgroup>\n              ';
	    }).join('')) + '\n        </select>\n        <div class=\'timezonePickerName\'>' + timezone + '</div>\n      </div>\n    ';
	  };

	  var renderCalendarDays = function renderCalendarDays() {
	    var _state12 = state,
	        slots = _state12.slots,
	        dateStr = _state12.date,
	        value = _state12.value,
	        availableDays = _state12.availableDays;

	    var days = slots ? Object.keys(slots) : [];
	    var todayStr = toDateStr(new Date());

	    if (!days.length) {
	      return '';
	    }

	    var firstDay = getDay(new Date(days[0]));
	    days.unshift.apply(days, fillArray(new Array(firstDay), 'precedingDay'));

	    var trailingDays = Math.ceil(days.length / 7) * 7 - days.length;
	    days.push.apply(days, fillArray(new Array(trailingDays), 'trailingDay'));

	    var weeks = days.map(function (item, i) {
	      return i % 7 === 0 ? days.slice(i, i + 7) : null;
	    }).filter(function (a) {
	      return a;
	    });

	    var dateValue = value && value.split(' ')[0];

	    return '\n      ' + weeks.map(function (week) {
	      return '<div class=\'calendarWeek\'>' + week.map(function (day) {
	        var dayObj = new Date(day);
	        if (day === 'precedingDay' || day === 'trailingDay') {
	          return '<div class="calendarDay ' + day + ' empty"></div>';
	        }
	        var active = day === dateStr;
	        var isToday = todayStr === day;
	        var beforeStartDate = state.forcedStartDate ? state.forcedStartDate > dayObj : false;
	        var afterEndDate = state.forcedEndDate ? state.forcedEndDate < dayObj : false;
	        var isUnavailable = availableDays.indexOf(day) === -1 || beforeStartDate || afterEndDate;
	        var isSelected = day === dateValue;
	        var classes = 'calendarDay ' + classNames({
	          isSelected: isSelected,
	          isToday: isToday,
	          isUnavailable: isUnavailable,
	          isActive: active
	        });
	        return '<div \n                      class=\'' + classes + '\' \n                      data-value=\'' + day + '\'\n                      role="button"\n                      aria-disabled="' + (isUnavailable ? true : false) + '"  \n                    >\n                        <span class=\'calendarDayEach\'>' + day.split('-')[2].replace(/^0/, '') + '</span>\n                    </div>';
	      }).join('') + '</div>';
	    }).join('') + '\n    ';
	  };

	  var renderEmptyState = function renderEmptyState() {
	    /* eslint-disable */
	    return '\n      <div class="appointmentSlots-empty">\n        <div class="appointmentSlots-empty-container">\n          <div class="appointmentSlots-empty-icon">\n            <svg width="124" height="102" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">\n              <defs>\n                <path d="M55 32.001c0 21.54 17.46 39 39 39 3.457 0 6.81-.45 10-1.294v34.794H0v-104l71 .001c-9.7 7.095-16 18.561-16 31.5z" id="a"/>\n              </defs>\n              <g fill="none" fill-rule="evenodd">\n                <g transform="translate(-1 -1)">\n                  <mask id="b" fill="#fff">\n                    <use xlink:href="#a"/>\n                  </mask>\n                  <g mask="url(#b)">\n                    <path d="M18.85 52.001c9.858 0 17.85 7.992 17.85 17.85 0 9.859-7.992 17.85-17.85 17.85S1 79.71 1 69.851c0-9.858 7.992-17.85 17.85-17.85zm5.666 10.842L17.38 69.98l-2.44-2.44a2.192 2.192 0 00-3.1 3.1l3.99 3.987a2.192 2.192 0 003.098 0l8.687-8.686a2.191 2.191 0 00-3.1-3.099z" fill="#D5D6DA"/>\n                    <path d="M92.043 10.643H81.597V7.576A6.582 6.582 0 0075.023 1a6.582 6.582 0 00-6.574 6.575v3.067H41.833V7.576A6.582 6.582 0 0035.26 1a6.582 6.582 0 00-6.574 6.575v3.149a2.187 2.187 0 00-.585-.082H19.37c-6.042 0-10.957 4.916-10.957 10.958v27.126a2.192 2.192 0 004.383 0V33.215h86.211a2.192 2.192 0 000-4.383H12.795v-7.231a6.582 6.582 0 016.574-6.575H28.1c.203 0 .398-.03.585-.08v2.82a6.582 6.582 0 006.574 6.574c3.625 0 10.574-2.95 10.574-6.574v-2.74H68.45v2.74a6.582 6.582 0 006.574 6.574c3.625 0 7.574-2.95 7.574-6.574v-2.74h9.446a6.582 6.582 0 016.574 6.575v73.072a3.95 3.95 0 01-3.946 3.945h-77.95a3.95 3.95 0 01-3.944-3.944v-5.67c0-1.047-.981-2.192-2.192-2.192-1.21 0-2.191.981-2.191 2.192v5.67c0 4.592 3.736 8.327 8.327 8.327h77.95c4.592 0 8.328-3.736 8.328-8.328V21.601c0-6.042-4.915-10.958-10.957-10.958zM37.45 17.766a2.194 2.194 0 01-2.191 2.191 2.194 2.194 0 01-2.191-2.191V7.576c0-1.209.983-2.192 2.19-2.192 1.21 0 2.192.983 2.192 2.192v10.19zm39.764 0a2.194 2.194 0 01-2.191 2.191 2.194 2.194 0 01-2.191-2.191V7.576c0-1.209.983-2.192 2.191-2.192 1.208 0 2.191.983 2.191 2.192v10.19z" fill="#D5D6DA" fill-rule="nonzero"/>\n                    <path d="M55.68 63.556c-4.592 0-8.328 3.736-8.328 8.327 0 4.592 3.736 8.328 8.327 8.328 4.592 0 8.328-3.736 8.328-8.328 0-4.591-3.736-8.327-8.328-8.327zm0 12.272a3.95 3.95 0 01-3.945-3.945 3.95 3.95 0 013.944-3.944 3.95 3.95 0 013.945 3.944 3.95 3.95 0 01-3.945 3.945zm26.854-12.272c-4.591 0-8.327 3.736-8.327 8.327 0 4.592 3.736 8.328 8.327 8.328 4.592 0 8.328-3.736 8.328-8.328 0-4.591-3.736-8.327-8.328-8.327zm0 12.272a3.95 3.95 0 01-3.944-3.945 3.95 3.95 0 013.944-3.944 3.95 3.95 0 013.945 3.944 3.95 3.95 0 01-3.945 3.945zM30.126 36.701c-4.591 0-8.327 3.736-8.327 8.328 0 4.591 3.736 8.327 8.327 8.327 4.592 0 8.328-3.736 8.328-8.327 0-4.592-3.736-8.328-8.328-8.328zm0 12.272a3.95 3.95 0 01-3.944-3.944 3.95 3.95 0 013.944-3.945 3.95 3.95 0 013.945 3.945 3.95 3.95 0 01-3.945 3.944z" fill="#D5D6DA" fill-rule="nonzero"/>\n                    <path d="M83.836 36.701c-4.592 0-8.328 3.736-8.328 8.328 0 4.591 3.736 8.327 8.328 8.327 4.591 0 8.327-3.736 8.327-8.327 0-4.592-3.736-8.328-8.327-8.328zm0 12.272a3.95 3.95 0 01-3.945-3.944 3.95 3.95 0 013.945-3.945 3.95 3.95 0 013.944 3.945 3.95 3.95 0 01-3.944 3.944z" fill="#2B3245" fill-rule="nonzero"/>\n                    <path d="M56.981 36.701c-4.592 0-8.327 3.736-8.327 8.328 0 4.591 3.735 8.327 8.327 8.327 4.592 0 8.327-3.736 8.327-8.327 0-4.592-3.735-8.328-8.327-8.328zm0 12.272a3.95 3.95 0 01-3.944-3.944 3.95 3.95 0 013.944-3.945 3.95 3.95 0 013.945 3.945 3.95 3.95 0 01-3.945 3.944z" fill="#D5D6DA" fill-rule="nonzero"/>\n                    <path d="M68.829 11.201l.001 6.375a6.375 6.375 0 006.146 6.371l.229.004a6.375 6.375 0 006.371-6.146l.004-.229-.001-6.375h6.871c6.627 0 12 5.373 12 12v8.4H11.2v-8.4c0-6.627 5.373-12 12-12h5.849l.001 6.75a6 6 0 005.775 5.996l.225.004h.375a6.375 6.375 0 006.375-6.375l-.001-6.375h27.03z" fill="#D5D6DA"/>\n                  </g>\n                </g>\n                <path d="M92 0c17.673 0 32 14.327 32 32 0 17.673-14.327 32-32 32-17.673 0-32-14.327-32-32C60 14.327 74.327 0 92 0zm21.268 15.365L75.365 53.268A26.884 26.884 0 0092 59c14.912 0 27-12.088 27-27a26.88 26.88 0 00-5.732-16.635zM92 5C77.088 5 65 17.088 65 32c0 6.475 2.28 12.417 6.079 17.069l37.99-37.99A26.888 26.888 0 0092 5z" fill="#D5D6DA"/>\n              </g>\n            </svg>\n          </div>\n          <div class="appointmentSlots-empty-text">' + JotForm.texts.noSlotsAvailable + '</div>\n        </div>\n      </div>\n    ';
	    /* eslint-enable */
	  };

	  var dateWithAMPM = function dateWithAMPM() {
	    var date = arguments.length > 0 && arguments[0] !== undefined ? arguments[0] : '';
	    var _props8 = props,
	        _props8$timeFormat = _props8.timeFormat;
	    _props8$timeFormat = _props8$timeFormat === undefined ? {} : _props8$timeFormat;
	    var _props8$timeFormat$va = _props8$timeFormat.value,
	        timeFormat = _props8$timeFormat$va === undefined ? '24 Hour' : _props8$timeFormat$va;

	    var time = new Date(date.replace(/-/g, '/')).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hourCycle: timeFormat === 'AM/PM' ? 'h12' : 'h23' });
	    var day = date && date.split(' ')[0];
	    return day + ' ' + time;
	  };

	  var renderSlots = function renderSlots() {
	    var _state13 = state,
	        dateStr = _state13.date,
	        _state13$value = _state13.value,
	        dateValue = _state13$value === undefined ? '' : _state13$value,
	        _state13$defaultValue = _state13.defaultValue,
	        defaultValue = _state13$defaultValue === undefined ? '' : _state13$defaultValue,
	        timezone = _state13.timezone,
	        forcedStartDate = _state13.forcedStartDate,
	        forcedEndDate = _state13.forcedEndDate;

	    var dateSlots = state.slots && state.slots[dateStr] || {};

	    var stateValue = dateWithAMPM(dateValue);
	    var defaultValueTZ = revertDate(defaultValue, ' ', timezone);

	    var parsedDefaultVal = dateWithAMPM(defaultValueTZ);

	    var entries = objectEntries(dateSlots);

	    if (!entries || !entries.length) {
	      return renderEmptyState();
	    }

	    return entries.map(function (_ref5) {
	      var name = _ref5[0],
	          value = _ref5[1];

	      var rn = amPmConverter(name);
	      var slotValue = dateStr + ' ' + rn;
	      var realD = dateInTimeZone(new Date(slotValue.replace(/-/g, '/')));
	      var active = stateValue === slotValue;

	      var disabled = forcedStartDate && forcedStartDate > realD || forcedEndDate && forcedEndDate < realD || !(value || parsedDefaultVal === slotValue);

	      return '<div class="appointmentSlot slot ' + classNames({ disabled: disabled, active: active }) + '" data-value="' + slotValue + '" role="button">' + name + '</div>';
	    }).join('');
	  };

	  var renderDay = function renderDay() {
	    var _state14 = state,
	        dateStr = _state14.date;

	    var date = new Date(dateStr);
	    var day = getDay(date);

	    return '\n      <div class=\'appointmentDate\'>\n        ' + (dateStr && dayNames()[day] + ', ' + monthNames()[date.getUTCMonth()] + ' ' + intPrefix(date.getUTCDate())) + '\n      </div>\n    ';
	  };

	  var renderCalendar = function renderCalendar() {
	    var _state15 = state,
	        dateStr = _state15.date;


	    return '\n      <div class=\'selectedDate\'>\n        <input class=\'currentDate\' type=\'text\' value=\'' + toFormattedDateStr(dateStr) + '\' style=\'pointer-events: none;\' />\n      </div>\n      ' + renderMonthYearPicker() + '\n      <div class=\'appointmentCalendarDays days\'>\n        <div class=\'daysOfWeek\'>\n          ' + dayNames().map(function (day) {
	      return '<div class="dayOfWeek ' + day.toLowerCase() + '">' + day.toUpperCase().slice(0, 3) + '</div>';
	    }).join('') + '\n        </div>\n        ' + renderCalendarDays() + '\n      </div>\n    ';
	  };

	  var renderSelectedDate = function renderSelectedDate() {
	    var _state16 = state,
	        _state16$value = _state16.value,
	        value = _state16$value === undefined ? '' : _state16$value;

	    var dateStr = value && value.split(' ')[0];
	    var _props9 = props,
	        _props9$timeFormat = _props9.timeFormat;
	    _props9$timeFormat = _props9$timeFormat === undefined ? {} : _props9$timeFormat;
	    var _props9$timeFormat$va = _props9$timeFormat.value,
	        timeFormat = _props9$timeFormat$va === undefined ? '24 Hour' : _props9$timeFormat$va;


	    var date = new Date(dateStr);
	    var time = new Date(value.replace(/-/g, '/')).toLocaleTimeString('en-US', { hour: 'numeric', minute: 'numeric', hourCycle: timeFormat === 'AM/PM' ? 'h12' : 'h23' });
	    var day = getDay(date);
	    var datetime = dayNames()[day] + ', ' + monthNames()[date.getUTCMonth()] + ' ' + intPrefix(date.getUTCDate()) + ', ' + date.getFullYear();

	    var text = JotForm.texts.appointmentSelected.replace('{time}', time).replace('{date}', datetime);
	    var valEl = '<div style=\'display: none\' class=\'jsAppointmentValue\'>' + datetime + ' ' + time + '</div>';
	    return value ? valEl + '<div class=\'appointmentFieldRow forSelectedDate\'><span aria-live="polite">' + text + '</span><button type=\'button\' class=\'cancel\'>x</button></div>' : '';
	  };

	  var render = debounce(function () {
	    var _state17 = state,
	        loading = _state17.loading;


	    beforeRender();
	    element.innerHTML = '\n      <div class=\'appointmentFieldContainer\'>\n        <div class=\'appointmentFieldRow forCalendar\'>\n          <div class=\'calendar appointmentCalendar\'>\n            <div class=\'appointmentCalendarContainer\'>\n              ' + renderCalendar() + '\n            </div>\n          </div>\n          <div class=\'appointmentDates\'>\n            <div class=\'appointmentDateSelect\'>\n              ' + renderDay() + '\n              ' + renderDayPicker() + '\n            </div>\n            <div class=\'appointmentSlots slots ' + classNames({ isLoading: loading }) + '\'>\n              <div class=\'appointmentSlotsContainer\'>\n                ' + renderSlots() + '\n              </div>\n            </div>\n            <div class=\'appointmentCalendarTimezone forTimezonePicker\'>\n              ' + (isTimezonePickerFromCommonLoaded ? '' : renderTimezonePicker()) + '\n            </div>\n          </div>\n        </div>\n        ' + renderSelectedDate() + '\n      </div>\n    ';
	    afterRender();
	  });

	  var _update = function _update(newProps) {
	    state = assignObject({}, state, getStateFromProps(newProps));
	    props = newProps;
	    load();
	  };

	  input.addEventListener('change', function (e) {
	    if (!state.nyTz) return;
	    var date = e.target.value ? toDateTimeStr(dz(new Date(e.target.value.replace(/-/g, '/')), state.nyTz, getTimezoneOffset(state.timezone))) : '';
	    if (date) {
	      setDate(date.split(' ')[0]);
	      setState({ value: date, defaultValue: date });
	      validation(date);
	    }
	  });
	  tzInput.addEventListener('change', function (e) {
	    var defaultTimezone = e.target.value;
	    setTimezone(defaultTimezone);
	    setState({ defaultTimezone: defaultTimezone });
	  });
	  document.addEventListener('translationLoad', function () {
	    return render();
	  });

	  var getInitialData = function getInitialData(timezones) {
	    getFirstAvailableDates(function (data) {
	      var nyTz = -4;
	      try {
	        nyTz = getTimezoneOffset(timezones.find(function (_ref6) {
	          var group = _ref6.group;
	          return group === 'America';
	        }).cities.find(function (c) {
	          return c.indexOf('New_York') > -1;
	        }));
	      } catch (e) {
	        console.log(e);
	      }
	      JotForm.appointments.initialData = data;
	      JotForm.nyTz = nyTz;
	      JotForm.appointments.listeners.forEach(function (fn) {
	        return fn({ data: data, timezones: timezones, nyTz: nyTz });
	      });
	    });
	  };

	  if (!JotForm.appointments) {
	    JotForm.appointments = { listeners: [] };

	    loadTimezones(function (timezones) {
	      JotForm.timezones = timezones;
	      getInitialData(timezones);
	    });
	  }

	  var requestTry = 1;
	  var requestTimeout = 1000;

	  var construct = function construct(_ref7) {
	    var data = _ref7.data,
	        timezones = _ref7.timezones,
	        nyTz = _ref7.nyTz;

	    var qdata = data[props.id.value];
	    var _props10 = props,
	        _props10$autoDetectTi = _props10.autoDetectTimezone;
	    _props10$autoDetectTi = _props10$autoDetectTi === undefined ? {} : _props10$autoDetectTi;
	    var autoDetectValue = _props10$autoDetectTi.value;


	    if (autoDetectValue === 'No') {
	      load();
	    }

	    if (!qdata || qdata.error) {
	      constructed = true;

	      if (!qdata && requestTry < 4) {
	        requestTry += 1;
	        setTimeout(function () {
	          getInitialData(JotForm.timezones);
	        }, requestTry * requestTimeout);
	      }

	      return;
	    }

	    constructed = false;

	    var userTimezone = getUserTimezone();

	    var setUpdatedTimezone = function setUpdatedTimezone(currentTimezone) {
	      if (!currentTimezone) {
	        return currentTimezone;
	      }

	      var _currentTimezone$spli = currentTimezone.split('/'),
	          currentCont = _currentTimezone$spli[0],
	          currCity = _currentTimezone$spli[1];

	      var currentCity = currCity && currCity.split(' (')[0];
	      var group = timezones.find(function (timezone) {
	        return timezone.group === currentCont;
	      });
	      if (!group) {
	        return currentTimezone;
	      }
	      var matchedTimezone = group.cities.find(function (c) {
	        return c.indexOf(currentCity) > -1;
	      });

	      if (!matchedTimezone) return false;

	      return group.group + '/' + matchedTimezone;
	    };

	    var timezone = setUpdatedTimezone(userTimezone) || setUpdatedTimezone(props.timezone.value) || props.timezone.value;

	    if (window.timezonePickerCommon) {
	      isTimezonePickerFromCommonLoaded = true;
	      timezonePickerCommon = window.timezonePickerCommon({
	        id: uniqueId,
	        timezones: timezones,
	        selectedTimezone: timezone,
	        onOptionClick: onTimezoneOptionClick,
	        usePortal: true
	      });
	    }

	    setTimezone(timezone);
	    var dateStr = Object.keys(qdata)[0];

	    var _getDateRange2 = getDateRange(dateStr),
	        startDate = _getDateRange2[0],
	        endDate = _getDateRange2[1];

	    updateMonthData(startDate, endDate, qdata);
	    var _state18 = state,
	        availableDays = _state18.availableDays;

	    var newDate = availableDays.indexOf(dateStr) === -1 ? availableDays[0] : dateStr;

	    constructed = true;

	    setState({
	      timezones: timezones,
	      loading: false,
	      date: newDate || dateStr,
	      nyTz: nyTz
	    });

	    setTimeout(function () {
	      if (input.value) {
	        input.triggerEvent('change');
	      }
	    }, 100);
	  };

	  JotForm.appointments.listeners.push(construct);

	  if (JotForm.appointments.initialData) {
	    setTimeout(function () {
	      construct({
	        data: JotForm.appointments.initialData,
	        timezones: JotForm.timezones,
	        nyTz: JotForm.nyTz
	      });
	    }, requestTimeout);
	  }

	  JotForm.appointments[props.id.value] = {
	    update: function update(newProps) {
	      return _update(assignObject(passedProps, newProps));
	    },
	    forceStartDate: function forceStartDate(newDate) {
	      if (!newDate) {
	        setState({ forcedStartDate: undefined });
	        return;
	      }

	      try {
	        var forcedStartDate = new Date(newDate);
	        if ('' + forcedStartDate === '' + state.forcedStartDate) return;
	        var date = new Date(state.availableDays.find(function (d) {
	          return new Date(d + ' 23:59:59') >= forcedStartDate;
	        }));

	        if (!date.getTime()) {
	          date = forcedStartDate;
	        }

	        date = toDateStr(date);

	        setState({ forcedStartDate: forcedStartDate, date: date });
	      } catch (e) {
	        console.log(e);
	      }
	    },
	    forceEndDate: function forceEndDate(newDate) {
	      if (!newDate) {
	        setState({ forcedEndDate: undefined });
	        return;
	      }

	      try {
	        var forcedEndDate = new Date(newDate);
	        if ('' + forcedEndDate === '' + state.forcedEndDate) return;
	        var availableDays = state.availableDays.filter(function (d) {
	          return new Date(d + ' 00:00:00') <= forcedEndDate;
	        });

	        var date = new Date(availableDays.indexOf(state.date) > -1 ? state.date : availableDays[availableDays.length - 1]);

	        if (!date.getTime()) {
	          date = forcedEndDate;
	        }

	        date = toDateStr(date);

	        setState({ forcedEndDate: forcedEndDate, date: date });
	      } catch (e) {
	        console.log(e);
	      }
	    },
	    getComparableValue: function getComparableValue() {
	      return input.value && toDateTimeStr(dz(new Date(input.value.replace(/-/g, '/')), state.nyTz, getTimezoneOffset(props.timezone.value))) || '';
	    }
	  };

	  return _update;
	})({"text":{"text":"Question","value":"Appointment"},"labelAlign":{"text":"Label Align","value":"Top","dropdown":[["Auto","Auto"],["Left","Left"],["Right","Right"],["Top","Top"]]},"required":{"text":"Required","value":"No","dropdown":[["No","No"],["Yes","Yes"]]},"description":{"text":"Hover Text","value":"","textarea":true},"slotDuration":{"text":"Slot Duration","value":"60","dropdown":[[15,"15 min"],[30,"30 min"],[45,"45 min"],[60,"60 min"],["custom","Custom min"]],"hint":"Select how long each slot will be."},"startDate":{"text":"Start Date","value":""},"endDate":{"text":"End Date","value":""},"intervals":{"text":"Intervals","value":[{"from":"09:00","to":"17:00","days":["Mon","Tue","Wed","Thu","Fri"]}],"hint":"The hours will be applied to the selected days and repeated."},"useBlockout":{"text":"Blockout Custom Dates","value":"No","dropdown":[["No","No"],["Yes","Yes"]],"hint":"Disable certain date(s) in the calendar."},"blockoutDates":{"text":"Blockout dates","value":[{"startDate":"","endDate":""}]},"useLunchBreak":{"text":"Lunch Time","value":"Yes","dropdown":[["No","No"],["Yes","Yes"]],"hint":"Enable lunchtime in the calendar."},"lunchBreak":{"text":"Lunchtime hours","value":[{"from":"12:00","to":"14:00"}]},"timezone":{"text":"Timezone","value":"America/New_York (GMT-04:00)"},"timeFormat":{"text":"Time Format","value":"AM/PM","dropdown":[["24 Hour","24 Hour"],["AM/PM","AM/PM"]],"icon":"images/blank.gif","iconClassName":"toolbar-time_format_24"},"months":{"value":["January","February","March","April","May","June","July","August","September","October","November","December"],"hidden":true},"days":{"value":["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"],"hidden":true},"startWeekOn":{"text":"Start Week On","value":"Sunday","dropdown":[["Monday","Monday"],["Sunday","Sunday"]],"toolbar":false},"rollingDays":{"text":"Rolling Days","value":"","toolbar":false},"prevMonthButtonText":{"text":"Previous month","value":""},"nextMonthButtonText":{"text":"Next month","value":""},"prevYearButtonText":{"text":"Previous year","value":""},"nextYearButtonText":{"text":"Next year","value":""},"prevDayButtonText":{"text":"Previous day","value":""},"nextDayButtonText":{"text":"Next day","value":""},"appointmentType":{"hidden":true,"value":"single"},"autoDetectTimezone":{"hidden":true,"value":"Yes"},"dateFormat":{"hidden":true,"value":"mm/dd/yyyy"},"maxAttendee":{"hidden":true,"value":"5"},"maxEvents":{"hidden":true,"value":""},"minScheduleNotice":{"hidden":true,"value":"3"},"name":{"hidden":true,"value":"appointment"},"order":{"hidden":true,"value":"6"},"qid":{"toolbar":false,"value":"input_7"},"reminderEmails":{"hidden":true,"value":{"schedule":[{"value":"2","unit":"hour"}],"emailSettings":[]}},"type":{"hidden":true,"value":"control_appointment"},"useReminderEmails":{"hidden":true,"value":"No"},"id":{"toolbar":false,"value":"7"},"qname":{"toolbar":false,"value":"q7_appointment"},"cdnconfig":{"CDN":"https://cdn.jotfor.ms/"},"passive":false,"formProperties":{"products":false,"highlightLine":"Enabled","coupons":false,"useStripeCoupons":false,"taxes":false,"comparePaymentForm":"","paymentListSettings":false,"newPaymentUIForNewCreatedForms":"Yes","formBackground":"#F7F2FF"},"formID":212994847630062,"themeVersion":"v2"});
        JotForm.alterTexts(undefined);
	/*INIT-END*/
	});

   JotForm.prepareCalculationsOnTheFly([null,{"name":"registerHere","qid":"1","text":"REGISTER HERE","type":"control_head"},null,{"description":"","name":"name","qid":"3","text":"Name","type":"control_fullname"},{"description":"","name":"address","qid":"4","text":"Address","type":"control_address"},{"description":"","name":"phoneNumber","qid":"5","text":"Phone Number","type":"control_phone"},{"description":"","name":"date","qid":"6","text":"Date","type":"control_datetime"},{"description":"","name":"appointment","qid":"7","text":"Appointment","type":"control_appointment"},null,{"name":"submit","qid":"9","text":"Submit","type":"control_button"}]);
   setTimeout(function() {
JotForm.paymentExtrasOnTheFly([null,{"name":"registerHere","qid":"1","text":"REGISTER HERE","type":"control_head"},null,{"description":"","name":"name","qid":"3","text":"Name","type":"control_fullname"},{"description":"","name":"address","qid":"4","text":"Address","type":"control_address"},{"description":"","name":"phoneNumber","qid":"5","text":"Phone Number","type":"control_phone"},{"description":"","name":"date","qid":"6","text":"Date","type":"control_datetime"},{"description":"","name":"appointment","qid":"7","text":"Appointment","type":"control_appointment"},null,{"name":"submit","qid":"9","text":"Submit","type":"control_button"}]);}, 20); 
</script>
<style type="text/css">@media print{.form-section{display:inline!important}.form-pagebreak{display:none!important}.form-section-closed{height:auto!important}.page-section{position:initial!important}}</style>
<link type="text/css" rel="stylesheet" href="https://cdn01.jotfor.ms/themes/CSS/5e6b428acc8c4e222d1beb91.css?themeRevisionID=5f7ed99c2c2c7240ba580251"/>
<link type="text/css" rel="stylesheet" href="https://cdn02.jotfor.ms/css/styles/payment/payment_styles.css?3.3.28735" />
<link type="text/css" rel="stylesheet" href="https://cdn03.jotfor.ms/css/styles/payment/payment_feature.css?3.3.28735" />
<style type="text/css" id="form-designer-style">
    /* Injected CSS Code */

  
  
  
  
  
  /*PREFERENCES STYLE*/
  /* NEW THEME STYLE */

  /* colors */

  .form-textbox, .form-textarea {
    color: undefined;
  }
  .rating-item input:hover+label {
    color: rgba(65, 42, 104, 0.75);
  }
  li[data-type=control_fileupload] .qq-upload-button,
  .until-text,
  .form-submit-reset {
    color: rgba(65, 42, 104, 0.75);
  }

  .stageEmpty.isSmall{
    color: rgba(65, 42, 104, 0.75);
  }

  .rating-item label {
    color: rgba(65, 42, 104, 0.75);
  }
  .currentDate,
  .pickerItem select,
  .appointmentCalendar .calendarDay,
  .calendar.popup th,
  .calendar.popup table tbody td,
  .calendar-new-header>*,
  .form-collapse-table {
    color: #412A68;
  }
  .appointmentCalendar .dayOfWeek {
    color: #412A68;
  }
  .appointmentSlotsContainer > * {
    color: rgba(65, 42, 104, 0.75);
  }
  li[data-type=control_fileupload] .jfUpload-heading,
  ::placeholder,
  .form-dropdown.is-active,
  .form-dropdown:first-child,
  .form-spinner-input {
    color: #AE80FF;
  }
  .appointmentCalendar .calendarWeek .calendarDay.isUnavailable,
  .calendar tr.days td.otherDay,
  .calendar tr.days td:hover:not(.unselectable) {
    color: #AE80FF;
  }
  span.form-sub-label, label.form-sub-label, div.form-header-group .form-subHeader,
  .rating-item-title.for-to > label:first-child,
  .rating-item-title.for-from > label:first-child,
  .rating-item-title .editor-container * {
    color: #412A68;
  }
  .form-pagebreak-back{
    color: #FFFFFF;
  }
  .rating-item input:checked+label,
  .rating-item input:focus+label {
    color: #FFFFFF;
  }
  .clear-pad-btn {
    color: #FFFFFF;
  }
  .form-textbox::placeholder,
  .form-dropdown:not(.time-dropdown):not(:required),
  .form-dropdown:not(:required),
  .form-dropdown:required:invalid {
    color: #AE80FF;
  }
  /* border-colors */
  .form-dropdown,
  .form-textarea,
  .form-textbox,
  li[data-type=control_fileupload] .qq-upload-button,
  .rating-item label,
  .rating-item input:focus+label,
  .rating-item input:checked+label,
  .jf-form-buttons,
  .form-checkbox+label:before, .form-checkbox+span:before, .form-radio+label:before, .form-radio+span:before,
  .signature-pad-passive,
  .signature-wrapper,
  .appointmentCalendarContainer,
  .appointmentField .timezonePickerName,
  .appointmentDayPickerButton,
  .appointmentCalendarContainer .monthYearPicker .pickerItem+.pickerItem,
  .appointmentCalendarContainer .monthYearPicker,
  .appointmentCalendar .calendarDay.isActive .calendarDayEach, .appointmentCalendar .calendarDay.isToday .calendarDayEach, .appointmentCalendar .calendarDay:not(.empty):hover .calendarDayEach,
  .calendar.popup:before,
  .calendar-new-month,
  .form-matrix-column-headers, .form-matrix-table td, .form-matrix-table td:last-child,
  .form-matrix-table th, .form-matrix-table th:last-child, .form-matrix-table tr:last-child td, .form-matrix-table tr:last-child th, .form-matrix-table tr:not([role=group])+tr[role=group] th,
  .form-matrix-headers.form-matrix-column-headers,
  .isSelected .form-matrix-column-headers:nth-last-of-type(2),
  li[data-type=control_inline] input[type=email], li[data-type=control_inline] input[type=number],
  li[data-type=control_inline] input[type=tel], li[data-type=control_inline] input[type=text],
  .stageEmpty.isSmall {
    border-color: rgba(65, 42, 104, 0.75);
  }
  .rating-item input:hover+label {
    border-color: rgba(65, 42, 104, 0.75);
  }
  .appointmentSlot,
  .form-checkbox:checked+label:before, .form-checkbox:checked+span:before, .form-checkbox:checked+span label:before,
  .form-radio:checked+label:before, .form-radio:checked+span:before,
  .form-dropdown:focus, .form-textarea:focus, .form-textbox:focus, .signature-wrapper:focus,
  .form-line[data-payment="true"] .form-product-item .p_checkbox .checked,
  .form-dropdown:hover, .form-textarea:hover, .form-textbox:hover, .signature-wrapper:hover {
    border-color: rgba(65, 42, 104, 0.75);
  }

  .calendar tr.days td:hover:not(.unselectable):after {
    border-color: #AE80FF;
  }
  .form-header-group,
  .form-buttons-wrapper, .form-pagebreak, .form-submit-clear-wrapper,
  .form-pagebreak-next,
  .form-pagebreak-back,
  .form-checkbox:hover+label:before, .form-checkbox:hover+span:before, .form-radio:hover+label:before, .form-radio:hover+span:before{
    border-color: #7C56BD;
  }
  .form-pagebreak-back:focus, .form-pagebreak-next:focus, .form-submit-button:focus {
    border-color: rgba(65, 42, 104, 1);
  }
  /* background-colors */
  .form-line-active {
    background-color: #E5D8FC;
  }
  .form-line-error {
    background-color: #FFD6D6;
  }
  .form-matrix-column-headers, .form-matrix-row-headers,
  .form-spinner-button-container>*,
  .form-collapse-table,
  .form-collapse-table:hover,
  .appointmentDayPickerButton {
    background-color: #AE80FF;
  }
  .calendar.popup, .calendar.popup table,
  .calendar.popup table tbody td:after{
    background-color: #F7F2FF;
  }

  .appointmentCalendar .calendarDay.isActive .calendarDayEach,
  .appointmentFieldRow.forSelectedDate,
  .calendar.popup tr.days td.selected:after,
  .calendar.popup:after,
  .submit-button,
  .form-checkbox:checked+label:before, .form-checkbox:checked+span:before, .form-checkbox:checked+span label:before,
  .form-radio+label:after, .form-radio+span:after,
  .rating-item input:checked+label,
  .appointmentCalendar .calendarDay:after,
  .form-line[data-payment="true"] .form-product-item .p_checkbox .checked,
  .rating-item input:focus+label {
    background-color: #4F09C8;
  }
  .appointmentSlot.active {
    background-color: #4F09C8 !important;
  }
  .clear-pad-btn,
  .appointmentCalendar .dayOfWeek,
  .calendar.popup th {
    background-color: #7C56BD !important;
  }
  .appointmentField .timezonePicker:hover+.timezonePickerName,
  .form-spinner-button-container>*:hover {
    background-color: #A784E3;
  }
  .form-matrix-values,
  .form-matrix-values,
  .signature-wrapper,
  .signature-pad-passive,
  .rating-item label,
  .form-checkbox+label:before, .form-checkbox+span:before,
  .form-radio+label:before, .form-radio+span:before {
    background-color: #FFFFFF;
  }
  li[data-type=control_fileupload] .qq-upload-button {
    background-color: #FFFFFF;
  }
  .JotFormBuilder .appContainer #app li.form-line[data-type=control_matrix].isSelected
  .questionLine-editButton.forRemove:after, 
  .JotFormBuilder .appContainer #app li.form-line[data-type=control_matrix].isSelected .questionLine-editButton.forRemove:before {
    background-color: #FFFFFF;
  }
  .appointmentCalendarContainer, .appointmentSlot,
  .rating-item-title.for-to > label:first-child,
  .rating-item-title.for-from > label:first-child,
  .rating-item-title .editor-container *,
  .calendar-opened {
    background-color: transparent;
  }
  .page-section li.form-line-active[data-type="control_button"] {
    background-color: #E5D8FC;
  }
  .appointmentCalendar .calendarDay.isSelected:after {
    color: #FFFFFF;
  }
  /* shadow */
  .form-dropdown:hover, .form-textarea:hover, .form-textbox:hover, .signature-wrapper:hover,
  .calendar.popup:before,
  .jSignature:hover,
  li[data-type=control_fileupload] .qq-upload-button-hover,
  .form-line[data-payment="true"] .form-product-item .p_checkbox .checked,
  .form-line[data-payment="true"] .form-product-item .p_checkbox:hover .select_border,
  .form-checkbox:hover+label:before, .form-checkbox:hover+span:before, .form-radio:hover+label:before, .form-radio:hover+span:before,
  .calendar.popup:before {
    border-color: rgba(65, 42, 104, 0.5);
    box-shadow: 0 0 0 2px rgba(65, 42, 104, 0.25);
  }
  .form-dropdown:focus, .form-textarea:focus, .form-textbox:focus, .signature-wrapper:focus,
  li[data-type=control_fileupload] .qq-upload-button-focus,
  .form-checkbox:focus+label:before, .form-checkbox:focus+span:before, .form-radio:focus+label:before, .form-radio:focus+span:before,
  .calendar.popup:before {
    border-color: rgba(65, 42, 104, 1);
    box-shadow: 0 0 0 3px rgba(65, 42, 104, 0.25);
  }
  .calendar.popup table tbody td{
    box-shadow: none;
  }

  /* button colors */
  .submit-button {
    background-color: #412A68;
    border-color: #412A68;
  }
  .submit-button:hover {
    background-color: #27193E;
    border-color: #27193E;
  }
  .form-pagebreak-next {
    background-color: #7C56BD;
  }
  .form-pagebreak-back {
    background-color: #7C56BD;
  }
  .form-pagebreak-back:hover {
    background-color: #4A3471;
    border-color: #4A3471;
  }
  .form-pagebreak-next:hover {
    background-color: #4A3471;
    border-color: #4A3471;
  }
  .form-sacl-button, .form-submit-print {
    background-color: transparent;
    color: rgba(65, 42, 104, 0.75);
    border-color: rgba(65, 42, 104, 0.75);
  }
  .form-sacl-button:hover, .form-submit-print:hover,
  .appointmentSlot:not(.disabled):not(.active):hover,
  .appointmentDayPickerButton:hover,
  .rating-item input:hover+label {
    background-color: #A784E3;
  }

  /* payment styles */
  
  .form-line[data-payment=true] .form-textbox,
  .form-line[data-payment=true] .select-area,
  .form-line[data-payment=true] #coupon-input,
  .form-line[data-payment=true] #coupon-container input,
  .form-line[data-payment=true] input#productSearch-input,
  .form-line[data-payment=true] .form-product-category-item:after,
  .form-line[data-payment=true] .filter-container .dropdown-container .select-content,
  .form-line[data-payment=true] .form-textbox.form-product-custom_quantity,
  .form-line[data-payment="true"] .form-product-item .p_checkbox .select_border,
  .form-line[data-payment="true"] .form-product-item .form-product-container .form-sub-label-container span.select_cont,
  .form-line[data-payment=true] select.form-dropdown {
    border-color: rgba(65, 42, 104, 0.75);
    border-color: rgba(65,42,104,.4);
  }
  .form-line[data-payment="true"] hr,
  .form-line[data-payment=true] .p_item_separator,
  .form-line[data-payment="true"] .payment_footer.new_ui,
  .form-line.card-3col .form-product-item.new_ui,
  .form-line.card-2col .form-product-item.new_ui {
    border-color: rgba(65, 42, 104, 0.75);
    border-color: rgba(65,42,104,.2);
  }
  .form-line[data-payment=true] .form-product-category-item {
    border-color: rgba(65, 42, 104, 0.75);
    border-color: rgba(65,42,104,.3);
  }
  .form-line[data-payment=true] #coupon-input,
  .form-line[data-payment=true] .form-textbox.form-product-custom_quantity,
  .form-line[data-payment=true] input#productSearch-input,
  .form-line[data-payment=true] .select-area,
  .form-line[data-payment=true] .custom_quantity,
  .form-line[data-payment=true] .filter-container .select-content,
  .form-line[data-payment=true] .p_checkbox .select_border {
    background-color: #FFFFFF;
  }
  .form-line[data-payment=true] .form-product-category-item.title_collapsed.has_selected_product .selected-items-icon {
   background-color: rgba(65,42,104,.7);
   border-color: rgba(65,42,104,.7);
  }
  .form-line[data-payment=true].form-line.card-3col .form-product-item,
  .form-line[data-payment=true].form-line.card-2col .form-product-item {
   background-color: rgba(0,0,0,.05);
  }
  .form-line[data-payment=true] .payment-form-table input.form-textbox,
  .form-line[data-payment=true] .payment-form-table input.form-dropdown,
  .form-line[data-payment=true] .payment-form-table .form-sub-label-container > div,
  .form-line[data-payment=true] .payment-form-table span.form-sub-label-container iframe,
  .form-line[data-type=control_square] .payment-form-table span.form-sub-label-container iframe {
    border-color: rgba(65, 42, 104, 0.75);
  }

  /* icons */
  .appointmentField .timezonePickerName:before {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTYiIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNiAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0wIDcuOTYwMkMwIDMuNTY2MTcgMy41NTgyMSAwIDcuOTUyMjQgMEMxMi4zNTQyIDAgMTUuOTIwNCAzLjU2NjE3IDE1LjkyMDQgNy45NjAyQzE1LjkyMDQgMTIuMzU0MiAxMi4zNTQyIDE1LjkyMDQgNy45NTIyNCAxNS45MjA0QzMuNTU4MjEgMTUuOTIwNCAwIDEyLjM1NDIgMCA3Ljk2MDJaTTEuNTkzNzUgNy45NjAyQzEuNTkzNzUgMTEuNDc4NiA0LjQ0MzUgMTQuMzI4NCA3Ljk2MTkxIDE0LjMyODRDMTEuNDgwMyAxNC4zMjg0IDE0LjMzMDEgMTEuNDc4NiAxNC4zMzAxIDcuOTYwMkMxNC4zMzAxIDQuNDQxNzkgMTEuNDgwMyAxLjU5MjA0IDcuOTYxOTEgMS41OTIwNEM0LjQ0MzUgMS41OTIwNCAxLjU5Mzc1IDQuNDQxNzkgMS41OTM3NSA3Ljk2MDJaIiBmaWxsPSIjQUU4MEZGIi8+CjxwYXRoIGQ9Ik04LjM1ODA5IDMuOTc5OThINy4xNjQwNlY4Ljc1NjFMMTEuMzQzMiAxMS4yNjM2TDExLjk0MDIgMTAuMjg0NUw4LjM1ODA5IDguMTU5MDhWMy45Nzk5OFoiIGZpbGw9IiNBRTgwRkYiLz4KPC9zdmc+Cg==);
  }
  .appointmentCalendarContainer .monthYearPicker .pickerArrow.prev:after {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAiIGhlaWdodD0iNiIgdmlld0JveD0iMCAwIDEwIDYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik04LjU5NzgyIDUuMzM2NjRDOC45MzMxMiA1LjY0MDE4IDkuNDM5MzkgNS42MzM2IDkuNzU2MTMgNS4zMTY2OUMxMC4wODEzIDQuOTkxMzYgMTAuMDgxMyA0LjQ2MzU0IDkuNzU2MTMgNC4xMzgyMUM5LjYwOTA0IDQuMDAwMjYgOS42MDkwMyA0LjAwMDI2IDkuMDg5NDkgMy41MTUwOUM4LjQzNzQyIDIuOTA2MDkgOC40Mzc0MyAyLjkwNjA5IDcuNjU1MTEgMi4xNzU0N0M2LjA4OTU2IDAuNzEzMzUzIDYuMDg5NTYgMC43MTMzNTIgNS41Njc3MyAwLjIyNjAwN0M1LjI0MTA0IC0wLjA3MDYwMDUgNC43NTA4NSAtMC4wNjk1OTY3IDQuNDMyMzUgMC4yMjU4MzVMMC4yNjI1NCA0LjExODE1Qy0wLjA4MDU0NTkgNC40NTkzNiAtMC4wODcxNzExIDQuOTg3ODggMC4yNDE0NjggNS4zMTY2OUMwLjU1OTU1OCA1LjYzNDk2IDEuMDY5NzUgNS42NDA1OSAxLjM5NzAzIDUuMzM2NTNMNC45OTg5MSAxLjk3NTIyTDguNTk3ODIgNS4zMzY2NFoiIGZpbGw9IiM0MTJBNjgiLz4KPC9zdmc+Cg==);
  }
  .appointmentCalendarContainer .monthYearPicker .pickerArrow.next:after {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAiIGhlaWdodD0iNiIgdmlld0JveD0iMCAwIDEwIDYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0xLjQwMjE4IDAuMjI2OTE1QzEuMDY2ODcgLTAuMDc2Njg0OSAwLjU2MDYwMiAtMC4wNzAwODQ5IDAuMjQzODY5IDAuMjQ2ODE1Qy0wLjA4MTI4OTggMC41NzIxMTUgLTAuMDgxMjg5OCAxLjEwMDAyIDAuMjQzODY5IDEuNDI1MzJDMC4zOTA5NTYgMS41NjMyMiAwLjM5MDk2NiAxLjU2MzIyIDAuOTEwNTEgMi4wNDg0MkMxLjU2MjU3IDIuNjU3NDIgMS41NjI1NiAyLjY1NzQxIDIuMzQ0ODggMy4zODgwMkMzLjkxMDQ0IDQuODUwMTEgMy45MTA0MyA0Ljg1MDEyIDQuNDMyMjcgNS4zMzc1MkM0Ljc1ODk1IDUuNjM0MTIgNS4yNDkxNSA1LjYzMzEyIDUuNTY3NjQgNS4zMzc3Mkw5LjczNzQ2IDEuNDQ1NDJDMTAuMDgwNSAxLjEwNDEyIDEwLjA4NzEgMC41NzU2MTUgOS43NTg1MyAwLjI0NjgxNUM5LjQ0MDQ0IC0wLjA3MTQ4NDkgOC45MzAyNCAtMC4wNzcwODQ5IDguNjAyOTcgMC4yMjcwMTVMNS4wMDEwOCAzLjU4ODMyTDEuNDAyMTggMC4yMjY5MTVaIiBmaWxsPSIjNDEyQTY4Ii8+Cjwvc3ZnPgo=);
  }
  .appointmentField .timezonePickerName:after {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTAiIGhlaWdodD0iNiIgdmlld0JveD0iMCAwIDEwIDYiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0xIDFMNSA1TDkgMSIgc3Ryb2tlPSIjNDEyQTY4IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K);
    width: 11px;
  }
  li[data-type=control_datetime] [data-wrapper-react=true].extended>div+.form-sub-label-container .form-textbox:placeholder-shown,
  li[data-type=control_datetime] [data-wrapper-react=true]:not(.extended) .form-textbox:not(.time-dropdown):placeholder-shown,
  .appointmentCalendarContainer .currentDate {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTciIGhlaWdodD0iMTYiIHZpZXdCb3g9IjAgMCAxNyAxNiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTE1Ljk0ODkgNVYxNS4wMjZDMTUuOTQ4OSAxNS41NjM5IDE1LjUwMjYgMTYgMTQuOTUyMSAxNkgwLjk5NjgwNUMwLjQ0NjI4NSAxNiAwIDE1LjU2MzkgMCAxNS4wMjZWNUgxNS45NDg5Wk00LjE5MjQ1IDExLjQxNjdIMi4zNzQ3NEwyLjI4NTE1IDExLjQyNDdDMi4xMTA3OCAxMS40NTY1IDEuOTY4MDEgMTEuNTc5MiAxLjkwNzUyIDExLjc0MjJMMS44ODQzNyAxMS44MjY4TDEuODc2MzQgMTEuOTE2N1YxMy42NjY3TDEuODg0MzcgMTMuNzU2NUMxLjkxNjAyIDEzLjkzMTUgMi4wMzg0IDE0LjA3NDcgMi4yMDA4MyAxNC4xMzU0TDIuMjg1MTUgMTQuMTU4NkwyLjM3NDc0IDE0LjE2NjdINC4xOTI0NUw0LjI4MjAzIDE0LjE1ODZDNC40NTY0MSAxNC4xMjY5IDQuNTk5MTggMTQuMDA0MSA0LjY1OTY3IDEzLjg0MTFMNC42ODI4MiAxMy43NTY1TDQuNjkwODUgMTMuNjY2N1YxMS45MTY3TDQuNjgyODIgMTEuODI2OEM0LjY1MTE3IDExLjY1MTkgNC41Mjg3OSAxMS41MDg2IDQuMzY2MzUgMTEuNDQ3OUw0LjI4MjAzIDExLjQyNDdMNC4xOTI0NSAxMS40MTY3Wk04Ljg4MzI5IDExLjQxNjdINy4wNjU1OUw2Ljk3NiAxMS40MjQ3QzYuODAxNjIgMTEuNDU2NSA2LjY1ODg1IDExLjU3OTIgNi41OTgzNyAxMS43NDIyTDYuNTc1MjIgMTEuODI2OEw2LjU2NzE5IDExLjkxNjdWMTMuNjY2N0w2LjU3NTIyIDEzLjc1NjVDNi42MDY4NyAxMy45MzE1IDYuNzI5MjUgMTQuMDc0NyA2Ljg5MTY4IDE0LjEzNTRMNi45NzYgMTQuMTU4Nkw3LjA2NTU5IDE0LjE2NjdIOC44ODMyOUw4Ljk3Mjg4IDE0LjE1ODZDOS4xNDcyNiAxNC4xMjY5IDkuMjkwMDMgMTQuMDA0MSA5LjM1MDUxIDEzLjg0MTFMOS4zNzM2NyAxMy43NTY1TDkuMzgxNyAxMy42NjY3VjExLjkxNjdMOS4zNzM2NyAxMS44MjY4QzkuMzQyMDIgMTEuNjUxOSA5LjIxOTY0IDExLjUwODYgOS4wNTcyIDExLjQ0NzlMOC45NzI4OCAxMS40MjQ3TDguODgzMjkgMTEuNDE2N1pNNC4xOTI0NSA2LjgzMzMzSDIuMzc0NzRMMi4yODUxNSA2Ljg0MTM5QzIuMTEwNzggNi44NzMxNCAxLjk2ODAxIDYuOTk1OTEgMS45MDc1MiA3LjE1ODg3TDEuODg0MzcgNy4yNDM0NkwxLjg3NjM0IDcuMzMzMzNWOS4wODMzM0wxLjg4NDM3IDkuMTczMjFDMS45MTYwMiA5LjM0ODE1IDIuMDM4NCA5LjQ5MTM3IDIuMjAwODMgOS41NTIwNUwyLjI4NTE1IDkuNTc1MjhMMi4zNzQ3NCA5LjU4MzMzSDQuMTkyNDVMNC4yODIwMyA5LjU3NTI4QzQuNDU2NDEgOS41NDM1MyA0LjU5OTE4IDkuNDIwNzUgNC42NTk2NyA5LjI1NzhMNC42ODI4MiA5LjE3MzIxTDQuNjkwODUgOS4wODMzM1Y3LjMzMzMzTDQuNjgyODIgNy4yNDM0NkM0LjY1MTE3IDcuMDY4NTIgNC41Mjg3OSA2LjkyNTI5IDQuMzY2MzUgNi44NjQ2MUw0LjI4MjAzIDYuODQxMzlMNC4xOTI0NSA2LjgzMzMzWk04Ljg4MzI5IDYuODMzMzNINy4wNjU1OUw2Ljk3NiA2Ljg0MTM5QzYuODAxNjIgNi44NzMxNCA2LjY1ODg1IDYuOTk1OTEgNi41OTgzNyA3LjE1ODg3TDYuNTc1MjIgNy4yNDM0Nkw2LjU2NzE5IDcuMzMzMzNWOS4wODMzM0w2LjU3NTIyIDkuMTczMjFDNi42MDY4NyA5LjM0ODE1IDYuNzI5MjUgOS40OTEzNyA2Ljg5MTY4IDkuNTUyMDVMNi45NzYgOS41NzUyOEw3LjA2NTU5IDkuNTgzMzNIOC44ODMyOUw4Ljk3Mjg4IDkuNTc1MjhDOS4xNDcyNiA5LjU0MzUzIDkuMjkwMDMgOS40MjA3NSA5LjM1MDUxIDkuMjU3OEw5LjM3MzY3IDkuMTczMjFMOS4zODE3IDkuMDgzMzNWNy4zMzMzM0w5LjM3MzY3IDcuMjQzNDZDOS4zNDIwMiA3LjA2ODUyIDkuMjE5NjQgNi45MjUyOSA5LjA1NzIgNi44NjQ2MUw4Ljk3Mjg4IDYuODQxMzlMOC44ODMyOSA2LjgzMzMzWk0xMy41NzQxIDYuODMzMzNIMTEuNzU2NEwxMS42NjY4IDYuODQxMzlDMTEuNDkyNSA2Ljg3MzE0IDExLjM0OTcgNi45OTU5MSAxMS4yODkyIDcuMTU4ODdMMTEuMjY2MSA3LjI0MzQ2TDExLjI1OCA3LjMzMzMzVjkuMDgzMzNMMTEuMjY2MSA5LjE3MzIxQzExLjI5NzcgOS4zNDgxNSAxMS40MjAxIDkuNDkxMzcgMTEuNTgyNSA5LjU1MjA1TDExLjY2NjggOS41NzUyOEwxMS43NTY0IDkuNTgzMzNIMTMuNTc0MUwxMy42NjM3IDkuNTc1MjhDMTMuODM4MSA5LjU0MzUzIDEzLjk4MDkgOS40MjA3NSAxNC4wNDE0IDkuMjU3OEwxNC4wNjQ1IDkuMTczMjFMMTQuMDcyNSA5LjA4MzMzVjcuMzMzMzNMMTQuMDY0NSA3LjI0MzQ2QzE0LjAzMjkgNy4wNjg1MiAxMy45MTA1IDYuOTI1MjkgMTMuNzQ4IDYuODY0NjFMMTMuNjYzNyA2Ljg0MTM5TDEzLjU3NDEgNi44MzMzM1oiIGZpbGw9IiNBRjgwRkYiLz4KPHBhdGggZD0iTTEzLjA1MjIgMS4xMjVIMTUuMDQ1OEMxNS41OTYzIDEuMTI1IDE2LjA0MjYgMS42MDA3IDE2LjA0MjYgMi4xODc1VjRIMC4wOTM3NVYyLjE4NzVDMC4wOTM3NSAxLjYwMDcgMC41NDAwMzUgMS4xMjUgMS4wOTA1NiAxLjEyNUgzLjA4NDE3VjEuMDYyNUMzLjA4NDE3IDAuNDc1Njk3IDMuNTMwNDUgMCA0LjA4MDk3IDBDNC42MzE0OSAwIDUuMDc3NzggMC40NzU2OTcgNS4wNzc3OCAxLjA2MjVWMS4xMjVIMTEuMDU4NlYxLjA2MjVDMTEuMDU4NiAwLjQ3NTY5NyAxMS41MDQ5IDAgMTIuMDU1NCAwQzEyLjYwNTkgMCAxMy4wNTIyIDAuNDc1Njk3IDEzLjA1MjIgMS4wNjI1VjEuMTI1WiIgZmlsbD0iI0FGODBGRiIvPgo8L3N2Zz4K);
  }
  .form-star-rating-star.Stars {
    background-image: url(data:image/png;base64,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) !important;
  }
  .signature-pad-passive, .signature-placeholder:after {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTk4IiBoZWlnaHQ9IjQwIiB2aWV3Qm94PSIwIDAgMTk4IDQwIiBmaWxsPSJub25lIiB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciPgo8cGF0aCBkPSJNNzQuMTA0NCA2LjM0NTA4SDc1LjU4NTlDNzUuNTQxMiA0LjcxNDQgNzQuMDk5NCAzLjUzMTE2IDcyLjAzMTIgMy41MzExNkM2OS45ODc5IDMuNTMxMTYgNjguNDIxOSA0LjY5OTQ4IDY4LjQyMTkgNi40NTQ0NkM2OC40MjE5IDcuODcxMzYgNjkuNDM2MSA4LjcwMTYyIDcxLjA3MTcgOS4xNDQwOUw3Mi4yNzQ5IDkuNDcyMjFDNzMuMzYzNiA5Ljc2MDU2IDc0LjIwMzggMTAuMTE4NSA3NC4yMDM4IDExLjAyMzNDNzQuMjAzOCAxMi4wMTc3IDczLjI1NDMgMTIuNjczOSA3MS45NDY3IDEyLjY3MzlDNzAuNzYzNSAxMi42NzM5IDY5Ljc3OTEgMTIuMTQ2OSA2OS42ODk2IDExLjAzODNINjguMTQ4NEM2OC4yNDc5IDEyLjg4MjcgNjkuNjc0NyAxNC4wMjEyIDcxLjk1NjcgMTQuMDIxMkM3NC4zNDggMTQuMDIxMiA3NS43MjUxIDEyLjc2MzQgNzUuNzI1MSAxMS4wMzgzQzc1LjcyNTEgOS4yMDM3NSA3NC4wODk1IDguNDkyODEgNzIuNzk2OSA4LjE3NDYzTDcxLjgwMjYgNy45MTYxQzcxLjAwNzEgNy43MTIyNyA2OS45NDgyIDcuMzM5NCA2OS45NTMxIDYuMzY0OTdDNjkuOTUzMSA1LjQ5OTkxIDcwLjc0MzYgNC44NTg1OCA3MS45OTY0IDQuODU4NThDNzMuMTY0OCA0Ljg1ODU4IDczLjk5NSA1LjQwNTQ1IDc0LjEwNDQgNi4zNDUwOFoiIGZpbGw9IiNBRTgwRkYiLz4KPHBhdGggZD0iTTc3LjQ0MTYgMTMuODUyMkg3OC45MjgxVjYuMjE1ODJINzcuNDQxNlYxMy44NTIyWk03OC4xOTIzIDUuMDM3NTVDNzguNzA0NCA1LjAzNzU1IDc5LjEzMTkgNC42Mzk4MyA3OS4xMzE5IDQuMTUyNjFDNzkuMTMxOSAzLjY2NTM5IDc4LjcwNDQgMy4yNjI3IDc4LjE5MjMgMy4yNjI3Qzc3LjY3NTIgMy4yNjI3IDc3LjI1MjcgMy42NjUzOSA3Ny4yNTI3IDQuMTUyNjFDNzcuMjUyNyA0LjYzOTgzIDc3LjY3NTIgNS4wMzc1NSA3OC4xOTIzIDUuMDM3NTVaIiBmaWxsPSIjQUU4MEZGIi8+CjxwYXRoIGQ9Ik04NC4xMjk2IDE2Ljg2Qzg2LjA3MzUgMTYuODYgODcuNTc0OSAxNS45NzAxIDg3LjU3NDkgMTQuMDIxMlY2LjIxNTgySDg2LjExODNWNy40NTM3NUg4Ni4wMDg5Qzg1Ljc0NTQgNi45ODE0NSA4NS4yMTg0IDYuMTE2MzkgODMuNzk2NSA2LjExNjM5QzgxLjk1MjEgNi4xMTYzOSA4MC41OTQ4IDcuNTczMDYgODAuNTk0OCAxMC4wMDQyQzgwLjU5NDggMTIuNDQwMyA4MS45ODE5IDEzLjczNzggODMuNzg2NiAxMy43Mzc4Qzg1LjE4ODYgMTMuNzM3OCA4NS43MzA1IDEyLjk0NzQgODUuOTk4OSAxMi40NjAxSDg2LjA5MzRWMTMuOTYxNkM4Ni4wOTM0IDE1LjEzOTggODUuMjczMSAxNS42NjE4IDg0LjE0NDUgMTUuNjYxOEM4Mi45MDY2IDE1LjY2MTggODIuNDI0NCAxNS4wNDA0IDgyLjE2MDkgMTQuNjE3OEw4MC44ODMyIDE1LjE0NDhDODEuMjg1OSAxNi4wNjQ1IDgyLjMwNSAxNi44NiA4NC4xMjk2IDE2Ljg2Wk04NC4xMTQ3IDEyLjUwNDlDODIuNzg3MyAxMi41MDQ5IDgyLjA5NjIgMTEuNDg1NyA4Mi4wOTYyIDkuOTg0MjlDODIuMDk2MiA4LjUxNzY3IDgyLjc3MjQgNy4zNzkxNyA4NC4xMTQ3IDcuMzc5MTdDODUuNDEyMyA3LjM3OTE3IDg2LjEwODMgOC40MzgxMiA4Ni4xMDgzIDkuOTg0MjlDODYuMTA4MyAxMS41NjAzIDg1LjM5NzQgMTIuNTA0OSA4NC4xMTQ3IDEyLjUwNDlaIiBmaWxsPSIjQUU4MEZGIi8+CjxwYXRoIGQ9Ik05MS4wNTUgOS4zMTgwOUM5MS4wNTUgOC4xMDAwNSA5MS44MDA4IDcuNDA0MDMgOTIuODM0OSA3LjQwNDAzQzkzLjg0NDEgNy40MDQwMyA5NC40NTU2IDguMDY1MjUgOTQuNDU1NiA5LjE3MzkyVjEzLjg1MjJIOTUuOTQyMVY4Ljk5NDk0Qzk1Ljk0MjEgNy4xMDU3NCA5NC45MDMxIDYuMTE2MzkgOTMuMzQyIDYuMTE2MzlDOTIuMTkzNSA2LjExNjM5IDkxLjQ0MjggNi42NDgzNSA5MS4wODk4IDcuNDU4NzJIOTAuOTk1NFY2LjIxNTgySDg5LjU2ODVWMTMuODUyMkg5MS4wNTVWOS4zMTgwOVoiIGZpbGw9IiNBRTgwRkYiLz4KPHBhdGggZD0iTTEwMS43NiAxMy44NTIySDEwMy4yOTZWOS40MTI1NUgxMDguMzcyVjEzLjg1MjJIMTA5LjkxNFYzLjY3MDM3SDEwOC4zNzJWOC4wOTUwOEgxMDMuMjk2VjMuNjcwMzdIMTAxLjc2VjEzLjg1MjJaIiBmaWxsPSIjQUU4MEZGIi8+CjxwYXRoIGQ9Ik0xMTUuMzIzIDE0LjAwNjNDMTE2Ljk4OCAxNC4wMDYzIDExOC4xNjYgMTMuMTg2IDExOC41MDQgMTEuOTQzMUwxMTcuMDk3IDExLjY4OTVDMTE2LjgyOSAxMi40MTA0IDExNi4xODMgMTIuNzc4MyAxMTUuMzM4IDEyLjc3ODNDMTE0LjA2NSAxMi43NzgzIDExMy4yMSAxMS45NTMgMTEzLjE3IDEwLjQ4MTRIMTE4LjU5OVY5Ljk1NDQ2QzExOC41OTkgNy4xOTUyMiAxMTYuOTQ4IDYuMTE2MzkgMTE1LjIxOCA2LjExNjM5QzExMy4wOSA2LjExNjM5IDExMS42ODggNy43MzcxMyAxMTEuNjg4IDEwLjA4MzdDMTExLjY4OCAxMi40NTUyIDExMy4wNyAxNC4wMDYzIDExNS4zMjMgMTQuMDA2M1pNMTEzLjE3NSA5LjM2NzgxQzExMy4yMzUgOC4yODQgMTE0LjAyIDcuMzQ0MzcgMTE1LjIyOCA3LjM0NDM3QzExNi4zODIgNy4zNDQzNyAxMTcuMTM3IDguMTk5NDkgMTE3LjE0MiA5LjM2NzgxSDExMy4xNzVaIiBmaWxsPSIjQUU4MEZGIi8+CjxwYXRoIGQ9Ik0xMjAuMjQ4IDEzLjg1MjJIMTIxLjczNVY5LjE4ODgzQzEyMS43MzUgOC4xODk1NCAxMjIuNTA1IDcuNDY4NjYgMTIzLjU1OSA3LjQ2ODY2QzEyMy44NjggNy40Njg2NiAxMjQuMjE2IDcuNTIzMzUgMTI0LjMzNSA3LjU1ODE1VjYuMTM2MjdDMTI0LjE4NiA2LjExNjM5IDEyMy44OTIgNi4xMDE0NyAxMjMuNzAzIDYuMTAxNDdDMTIyLjgwOSA2LjEwMTQ3IDEyMi4wNDMgNi42MDg1OCAxMjEuNzY1IDcuNDI4ODlIMTIxLjY4NVY2LjIxNTgySDEyMC4yNDhWMTMuODUyMloiIGZpbGw9IiNBRTgwRkYiLz4KPHBhdGggZD0iTTEyOC42MzkgMTQuMDA2M0MxMzAuMzA1IDE0LjAwNjMgMTMxLjQ4MyAxMy4xODYgMTMxLjgyMSAxMS45NDMxTDEzMC40MTQgMTEuNjg5NUMxMzAuMTQ1IDEyLjQxMDQgMTI5LjQ5OSAxMi43NzgzIDEyOC42NTQgMTIuNzc4M0MxMjcuMzgxIDEyLjc3ODMgMTI2LjUyNiAxMS45NTMgMTI2LjQ4NiAxMC40ODE0SDEzMS45MTVWOS45NTQ0NkMxMzEuOTE1IDcuMTk1MjIgMTMwLjI2NSA2LjExNjM5IDEyOC41MzUgNi4xMTYzOUMxMjYuNDA3IDYuMTE2MzkgMTI1LjAwNSA3LjczNzEzIDEyNS4wMDUgMTAuMDgzN0MxMjUuMDA1IDEyLjQ1NTIgMTI2LjM4NyAxNC4wMDYzIDEyOC42MzkgMTQuMDA2M1pNMTI2LjQ5MSA5LjM2NzgxQzEyNi41NTEgOC4yODQgMTI3LjMzNiA3LjM0NDM3IDEyOC41NDUgNy4zNDQzN0MxMjkuNjk4IDcuMzQ0MzcgMTMwLjQ1NCA4LjE5OTQ5IDEzMC40NTkgOS4zNjc4MUgxMjYuNDkxWiIgZmlsbD0iI0FFODBGRiIvPgo8cGF0aCBkPSJNMSAzNi4wMjI5QzEyLjI0NjEgMzkuMjIwNSAyMy4xODIgMzUuMDMyOCAzMi41MDg0IDI4Ljg1MTFDMzcuNDQwNCAyNS41ODIyIDQyLjMzNDEgMjEuNjY4NyA0NS4zMzI5IDE2LjUxMDFDNDYuNTI4MyAxNC40NTM5IDQ3Ljk4OTMgMTAuODg0NCA0NC4yMjcxIDEwLjg1MjhDNDAuMTMzNyAxMC44MTgzIDM3LjA4NjQgMTQuNTE0MiAzNS41NTg4IDE3Ljg3NDRDMzMuMzY4MSAyMi42OTMzIDMzLjI5MSAyOC40MDA0IDM1Ljk2NTYgMzMuMDQ0MUMzOC40OTcxIDM3LjQzOTYgNDIuNzQ0NSAzOS41MTg0IDQ3LjgxMTQgMzguNjYzOUM1My4xMDM3IDM3Ljc3MTMgNTcuNzMwNCAzNC4xNTYyIDYxLjU3NjUgMzAuNjc4NUM2Mi45OTMgMjkuMzk3NiA2NC4zMjA5IDI4LjA0NzUgNjUuNTQyIDI2LjU4NTdDNjUuNjg0MiAyNi40MTU1IDY2LjE4NDIgMjUuNTc5OCA2Ni41MDggMjUuNTIxOEM2Ni42Mjg0IDI1LjUwMDIgNjYuODA2NCAyOS4xNjQ1IDY2LjgzODUgMjkuMzY0M0M2Ny4xMjU1IDMxLjE1NDMgNjguMDI5NCAzMy4xNzA2IDcwLjE0MzEgMzMuMjMxOEM3Mi44MzMyIDMzLjMwOTcgNzUuMDgyNiAzMS4wNTkxIDc2Ljg5MjIgMjkuNDAxOEM3Ny41MDI2IDI4Ljg0MjggNzkuNDQyNSAyNi4xNjAxIDgwLjQ3NjQgMjYuMTYwMUM4MC45MDE0IDI2LjE2MDEgODEuNzI0OSAyOC4zMDM4IDgxLjkxMjcgMjguNTg4M0M4NC4zOTcyIDMyLjM1MjMgODguMDQ0NiAzMC45ODk0IDkwLjg3MzMgMjguMzUwNUM5MS4zOTM0IDI3Ljg2NTMgOTQuMTc4MSAyMy45ODM5IDk1LjMwOTEgMjQuNjgzMkM5Ni4yMjAzIDI1LjI0NjYgOTYuNjIxNyAyNi41NzY1IDk3LjA4ODYgMjcuNDYxOEM5Ny44NDg0IDI4LjkwMjkgOTguODEwNyAyOS45Mjk0IDEwMC40MTkgMzAuNDY1N0MxMDMuOTEyIDMxLjYzMSAxMDcuNjggMjguMzYzIDExMS4yMjIgMjguMzYzQzExMi4yNTUgMjguMzYzIDExMi43ODMgMjguOTMxNiAxMTMuMzMyIDI5LjcxNDhDMTE0LjA4MSAzMC43ODIzIDExNC44NTMgMzEuNTI3NiAxMTYuMjA1IDMxLjgxNzVDMTIwLjM5MyAzMi43MTU1IDEyMy44MjIgMjguNzM5OSAxMjcuODcyIDI5LjA4ODlDMTI5LjA1MyAyOS4xOTA3IDEyOS45MzUgMzAuMzgxNiAxMzAuODIxIDMxLjAxNjRDMTMyLjYwOSAzMi4yOTY5IDEzNC43NTkgMzMuMTgzNiAxMzYuOTQ4IDMzLjQ5NDdDMTQwLjQ1NyAzMy45OTM0IDE0My45NzUgMzMuMzMyNiAxNDcuMzk1IDMyLjU5MzVDMTUzLjMgMzEuMzE3NCAxNTkuMTQ3IDI5Ljc5NTggMTY1LjA2MiAyOC41NjMzIiBzdHJva2U9IiNBRTgwRkYiIHN0cm9rZS13aWR0aD0iMS41IiBzdHJva2UtbGluZWNhcD0icm91bmQiIHN0cm9rZS1saW5lam9pbj0icm91bmQiLz4KPHBhdGggZD0iTTE5Ni41MTUgMTUuMDc3OEwxODQuNDkyIDAuNTUxNzk1QzE4NC4yNTcgMC4yNjc4MSAxODMuODM4IDAuMjI4MjYgMTgzLjU1NCAwLjQ2MzMwN0wxODAuNjQ5IDIuODY3ODhDMTgwLjM2NSAzLjEwMjkzIDE4MC4zMjUgMy41MjI0IDE4MC41NiAzLjgwNjM4TDE5Mi41ODMgMTguMzMyNEMxOTIuNyAxOC40NzQxIDE5Mi44NjQgMTguNTU1MSAxOTMuMDM0IDE4LjU3MTJDMTkzLjIwNCAxOC41ODcyIDE5My4zOCAxOC41MzgyIDE5My41MjIgMTguNDIwOUwxOTYuNDI3IDE2LjAxNjRDMTk2LjcxMSAxNS43ODEzIDE5Ni43NSAxNS4zNjE4IDE5Ni41MTUgMTUuMDc3OFoiIGZpbGw9IiNBRTgwRkYiLz4KPHBhdGggZD0iTTE4MS40MzYgNi45NTcyTDE3MC44NTUgOS44MjU5M0MxNzAuNjIyIDkuODg5MDEgMTcwLjQ0MSAxMC4wNzI5IDE3MC4zODMgMTAuMzA3MUwxNjYuMTU1IDI3LjEwMTdMMTczLjk3NSAyMC42MjkxQzE3My4yNDUgMTkuMjYxMiAxNzMuNTUgMTcuNTE4OSAxNzQuNzkgMTYuNDkyMUMxNzYuMjA2IDE1LjMxOTggMTc4LjMxMiAxNS41MTkxIDE3OS40ODMgMTYuOTM0NkMxODAuNjU1IDE4LjM1MDggMTgwLjQ1NiAyMC40NTYxIDE3OS4wNDEgMjEuNjI3OEMxNzguMzMzIDIyLjIxMzkgMTc3LjQ1MiAyMi40NTc3IDE3Ni42MDMgMjIuMzc3NkMxNzUuOTY0IDIyLjMxNzQgMTc1LjM0MyAyMi4wNzQgMTc0LjgyNSAyMS42NTY4TDE2Ny4wMDUgMjguMTI4NkwxODQuMjk0IDI3LjExMzdDMTg0LjUzNCAyNy4wOTk2IDE4NC43NDkgMjYuOTU3MSAxODQuODU0IDI2Ljc0MDFMMTg5LjY1IDE2Ljg4MTRMMTgxLjQzNiA2Ljk1NzJaIiBmaWxsPSIjQUU4MEZGIi8+Cjwvc3ZnPgo=);
  }
  .form-spinner-button.form-spinner-up:before {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMTQiIHZpZXdCb3g9IjAgMCAxNCAxNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik03LjUgMTIuNDAwNEw3LjUgNy40MDAzOUwxMi41IDcuNDAwMzlDMTIuNzc2IDcuNDAwMzkgMTMgNy4xNzYzOSAxMyA2LjkwMDM5QzEzIDYuNjI0MzkgMTIuNzc2IDYuNDAwMzkgMTIuNSA2LjQwMDM5TDcuNSA2LjQwMDM5TDcuNSAxLjQwMDM5QzcuNSAxLjEyNDM5IDcuMjc2IDAuOTAwMzkgNyAwLjkwMDM5QzYuNzI0IDAuOTAwMzkgNi41IDEuMTI0MzkgNi41IDEuNDAwMzlMNi41IDYuNDAwMzlMMS41IDYuNDAwMzlDMS4yMjQgNi40MDAzOSAxIDYuNjI0MzkgMSA2LjkwMDM5QzEgNy4xNzYzOSAxLjIyNCA3LjQwMDM5IDEuNSA3LjQwMDM5TDYuNSA3LjQwMDM5TDYuNSAxMi40MDA0QzYuNSAxMi42NzY0IDYuNzI0IDEyLjkwMDQgNyAxMi45MDA0QzcuMjc2IDEyLjkwMDQgNy41IDEyLjY3NjQgNy41IDEyLjQwMDRaIiBmaWxsPSJ3aGl0ZSIgc3Ryb2tlPSJ3aGl0ZSIgc3Ryb2tlLXdpZHRoPSIwLjUiLz4KPC9zdmc+Cg==);
  }
  .form-spinner-button.form-spinner-down:before {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMTQiIGhlaWdodD0iMiIgdmlld0JveD0iMCAwIDE0IDIiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0xMi41IDEuNDAwMzlMNy41IDEuNDAwMzlMMS41IDEuNDAwMzlDMS4yMjQgMS40MDAzOSAxIDEuMTc2MzkgMSAwLjkwMDM5QzEgMC42MjQzOSAxLjIyNCAwLjQwMDM5IDEuNSAwLjQwMDM5TDYuNSAwLjQwMDM5TDEyLjUgMC40MDAzOTFDMTIuNzc2IDAuNDAwMzkxIDEzIDAuNjI0MzkxIDEzIDAuOTAwMzkxQzEzIDEuMTc2MzkgMTIuNzc2IDEuNDAwMzkgMTIuNSAxLjQwMDM5WiIgZmlsbD0id2hpdGUiIHN0cm9rZT0id2hpdGUiIHN0cm9rZS13aWR0aD0iMC41Ii8+Cjwvc3ZnPgo=);
  }
  .form-collapse-table:after{
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjgiIGhlaWdodD0iMjgiIHZpZXdCb3g9IjAgMCAyOCAyOCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik0yOCAxNEMyOCA2LjI2ODAxIDIxLjczMiAtOS40OTkzNWUtMDcgMTQgLTYuMTE5NTllLTA3QzYuMjY4MDEgLTIuNzM5ODRlLTA3IC05LjQ5OTM1ZS0wNyA2LjI2ODAxIC02LjExOTU5ZS0wNyAxNEMtMi43Mzk4NGUtMDcgMjEuNzMyIDYuMjY4MDEgMjggMTQgMjhDMjEuNzMyIDI4IDI4IDIxLjczMiAyOCAxNFpNOC4wMDI0IDExLjcwMDNDNy45OTI0NCAxMS41ODEzIDguMDEzNjMgMTEuNDYxNyA4LjA2MzU5IDExLjM1NDlDOC4xMTM0NyAxMS4yNDgyIDguMTkwMDUgMTEuMTU4NSA4LjI4NDc5IDExLjA5NTlDOC4zNzk1MiAxMS4wMzMyIDguNDg4NjUgMTEgOC41OTk5OSAxMUwxOS40IDExQzE5LjUxMTMgMTEgMTkuNjIwNSAxMS4wMzMyIDE5LjcxNTIgMTEuMDk1OUMxOS44MDk5IDExLjE1ODUgMTkuODg2NSAxMS4yNDgyIDE5LjkzNjQgMTEuMzU0OUMxOS45Nzc5IDExLjQ0NDQgMTkuOTk5NiAxMS41NDI5IDIwIDExLjY0MjlDMjAgMTEuNzgyIDE5Ljk1NzkgMTEuOTE3MyAxOS44OCAxMi4wMjg2TDE0LjQ4IDE5Ljc0MjlDMTQuNDI0MSAxOS44MjI3IDE0LjM1MTYgMTkuODg3NSAxNC4yNjgzIDE5LjkzMjFDMTQuMTg1IDE5Ljk3NjggMTQuMDkzMSAyMCAxNCAyMEMxMy45MDY4IDIwIDEzLjgxNSAxOS45NzY4IDEzLjczMTcgMTkuOTMyMUMxMy42NDg0IDE5Ljg4NzUgMTMuNTc1OSAxOS44MjI3IDEzLjUyIDE5Ljc0MjlMOC4xMTk5OSAxMi4wMjg2QzguMDUzMDggMTEuOTMzIDguMDEyMzYgMTEuODE5MyA4LjAwMjQgMTEuNzAwM1oiIGZpbGw9IndoaXRlIi8+Cjwvc3ZnPgo=);
  }
  li[data-type=control_fileupload] .qq-upload-button:before {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzkiIGhlaWdodD0iMjgiIHZpZXdCb3g9IjAgMCAzOSAyOCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTMyLjM3NSAxMi4xODc1QzMxLjUgNS42ODc1IDI2IDAuODc1IDE5LjM3NSAwLjg3NUMxMy42ODc1IDAuODc1IDguNzUgNC40Mzc1IDYuOTM3NSA5LjgxMjVDMi44NzUgMTAuNjg3NSAwIDE0LjE4NzUgMCAxOC4zNzVDMCAyMi45Mzc1IDMuNTYyNSAyNi43NSA4LjEyNSAyNy4xMjVIMzEuODc1SDMxLjkzNzVDMzUuNzUgMjYuNzUgMzguNzUgMjMuNSAzOC43NSAxOS42MjVDMzguNzUgMTUuOTM3NSAzNiAxMi43NSAzMi4zNzUgMTIuMTg3NVpNMjYuMDYyNSAxNS42ODc1QzI1LjkzNzUgMTUuODEyNSAyNS44MTI1IDE1Ljg3NSAyNS42MjUgMTUuODc1QzI1LjQzNzUgMTUuODc1IDI1LjMxMjUgMTUuODEyNSAyNS4xODc1IDE1LjY4NzVMMjAgMTAuNVYyMi43NUMyMCAyMy4xMjUgMTkuNzUgMjMuMzc1IDE5LjM3NSAyMy4zNzVDMTkgMjMuMzc1IDE4Ljc1IDIzLjEyNSAxOC43NSAyMi43NVYxMC41TDEzLjU2MjUgMTUuNjg3NUMxMy4zMTI1IDE1LjkzNzUgMTIuOTM3NSAxNS45Mzc1IDEyLjY4NzUgMTUuNjg3NUMxMi40Mzc1IDE1LjQzNzUgMTIuNDM3NSAxNS4wNjI1IDEyLjY4NzUgMTQuODEyNUwxOC45Mzc1IDguNTYyNUMxOSA4LjUgMTkuMDYyNSA4LjQzNzUgMTkuMTI1IDguNDM3NUMxOS4yNSA4LjM3NSAxOS40Mzc1IDguMzc1IDE5LjYyNSA4LjQzNzVDMTkuNjg3NSA4LjUgMTkuNzUgOC41IDE5LjgxMjUgOC41NjI1TDI2LjA2MjUgMTQuODEyNUMyNi4zMTI1IDE1LjA2MjUgMjYuMzEyNSAxNS40Mzc1IDI2LjA2MjUgMTUuNjg3NVoiIGZpbGw9IiNBRTgwRkYiLz4KPC9zdmc+Cg==);;
  }
  .appointmentDayPickerButton {
    background-image: url(data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNiIgaGVpZ2h0PSIxMCIgdmlld0JveD0iMCAwIDYgMTAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxwYXRoIGQ9Ik0xIDlMNSA1TDEgMSIgc3Ryb2tlPSIjNDEyQTY4IiBzdHJva2Utd2lkdGg9IjIiIHN0cm9rZS1saW5lY2FwPSJyb3VuZCIgc3Ryb2tlLWxpbmVqb2luPSJyb3VuZCIvPgo8L3N2Zz4K);
  }

  /* NEW THEME STYLE */
  /*PREFERENCES STYLE*//*PREFERENCES STYLE*/
    .form-all {
      font-family: Inter, sans-serif;
    }
    .form-all .qq-upload-button,
    .form-all .form-submit-button,
    .form-all .form-submit-reset,
    .form-all .form-submit-print {
      font-family: Inter, sans-serif;
    }
    .form-all .form-pagebreak-back-container,
    .form-all .form-pagebreak-next-container {
      font-family: Inter, sans-serif;
    }
    .form-header-group {
      font-family: Inter, sans-serif;
    }
    .form-label {
      font-family: Inter, sans-serif;
    }
  
    .form-label.form-label-auto {
      
    display: block;
    float: none;
    text-align: left;
    width: 100%;
  
    }
  
    .form-line {
      margin-top: 12px;
      margin-bottom: 12px;
    }
  
    .form-all {
      max-width: 752px;
      width: 100%;
    }
  
    .form-label.form-label-left,
    .form-label.form-label-right,
    .form-label.form-label-left.form-label-auto,
    .form-label.form-label-right.form-label-auto {
      width: 230px;
    }
  
    .form-all {
      font-size: 16px
    }
    .form-all .qq-upload-button,
    .form-all .qq-upload-button,
    .form-all .form-submit-button,
    .form-all .form-submit-reset,
    .form-all .form-submit-print {
      font-size: 16px
    }
    .form-all .form-pagebreak-back-container,
    .form-all .form-pagebreak-next-container {
      font-size: 16px
    }
  
    .supernova .form-all, .form-all {
      background-color: #F7F2FF;
    }
  
    .form-all {
      color: #412A68;
    }
    .form-header-group .form-header {
      color: #412A68;
    }
    .form-header-group .form-subHeader {
      color: #412A68;
    }
    .form-label-top,
    .form-label-left,
    .form-label-right,
    .form-html,
    .form-checkbox-item label,
    .form-radio-item label {
      color: #412A68;
    }
    .form-sub-label {
      color: #5b4482;
    }
  
    .supernova {
      background-color: #faf8fe;
    }
    .supernova body {
      background: transparent;
    }
  
    .form-textbox,
    .form-textarea,
    .form-dropdown,
    .form-radio-other-input,
    .form-checkbox-other-input,
    .form-captcha input,
    .form-spinner input {
      background-color: #FFFFFF;
    }
  
    .supernova {
      background-image: none;
    }
    #stage {
      background-image: none;
    }
  
    .form-all {
      background-image: none;
    }
  
  .ie-8 .form-all:before { display: none; }
  .ie-8 {
    margin-top: auto;
    margin-top: initial;
  }
  
  /*PREFERENCES STYLE*//*__INSPECT_SEPERATOR__*/
    /* Injected CSS Code */
</style>

<form class="jotform-form" action="https://submit.jotform.com/submit/212994847630062/" method="post" name="form_212994847630062" id="212994847630062" accept-charset="utf-8" autocomplete="on">
  <input type="hidden" name="formID" value="212994847630062" />
  <input type="hidden" id="JWTContainer" value="" />
  <input type="hidden" id="cardinalOrderNumber" value="" />
  <div role="main" class="form-all">
    <style>
      .form-all:before { background: none;}
    </style>
    <ul class="form-section page-section">
      <li id="cid_1" class="form-input-wide" data-type="control_head">
        <div class="form-header-group  header-large">
          <div class="header-text httal htvam">
            <h1 id="header_1" class="form-header" data-component="header">
              REGISTER HERE
            </h1>
          </div>
        </div>
      </li>
      <li class="form-line" data-type="control_fullname" id="id_3">
        <label class="form-label form-label-top form-label-auto" id="label_3" for="first_3"> Name </label>
        <div id="cid_3" class="form-input-wide" data-layout="full">
          <div data-wrapper-react="true">
            <span class="form-sub-label-container" style="vertical-align:top" data-input-type="first">
              <input type="text" id="first_3" name="q3_name[first]" class="form-textbox" data-defaultvalue="" size="10" value="" data-component="first" aria-labelledby="label_3 sublabel_3_first" />
              <label class="form-sub-label" for="first_3" id="sublabel_3_first" style="min-height:13px" aria-hidden="false"> First Name </label>
            </span>
            <span class="form-sub-label-container" style="vertical-align:top" data-input-type="last">
              <input type="text" id="last_3" name="q3_name[last]" class="form-textbox" data-defaultvalue="" size="15" value="" data-component="last" aria-labelledby="label_3 sublabel_3_last" />
              <label class="form-sub-label" for="last_3" id="sublabel_3_last" style="min-height:13px" aria-hidden="false"> Last Name </label>
            </span>
          </div>
        </div>
      </li>
      <li class="form-line" data-type="control_address" id="id_4">
        <label class="form-label form-label-top form-label-auto" id="label_4" for="input_4_addr_line1"> Address </label>
        <div id="cid_4" class="form-input-wide" data-layout="full">
          <div summary="" class="form-address-table jsTest-addressField">
            <div class="form-address-line-wrapper jsTest-address-line-wrapperField">
              <span class="form-address-line form-address-street-line jsTest-address-lineField">
                <span class="form-sub-label-container" style="vertical-align:top">
                  <input type="text" id="input_4_addr_line1" name="q4_address[addr_line1]" class="form-textbox form-address-line" data-defaultvalue="" value="" data-component="address_line_1" aria-labelledby="label_4 sublabel_4_addr_line1" required="" />
                  <label class="form-sub-label" for="input_4_addr_line1" id="sublabel_4_addr_line1" style="min-height:13px" aria-hidden="false"> Street Address </label>
                </span>
              </span>
            </div>
            <div class="form-address-line-wrapper jsTest-address-line-wrapperField">
              <span class="form-address-line form-address-street-line jsTest-address-lineField">
                <span class="form-sub-label-container" style="vertical-align:top">
                  <input type="text" id="input_4_addr_line2" name="q4_address[addr_line2]" class="form-textbox form-address-line" data-defaultvalue="" value="" data-component="address_line_2" aria-labelledby="label_4 sublabel_4_addr_line2" />
                  <label class="form-sub-label" for="input_4_addr_line2" id="sublabel_4_addr_line2" style="min-height:13px" aria-hidden="false"> Street Address Line 2 </label>
                </span>
              </span>
            </div>
            <div class="form-address-line-wrapper jsTest-address-line-wrapperField">
              <span class="form-address-line form-address-city-line jsTest-address-lineField ">
                <span class="form-sub-label-container" style="vertical-align:top">
                  <input type="text" id="input_4_city" name="q4_address[city]" class="form-textbox form-address-city" data-defaultvalue="" value="" data-component="city" aria-labelledby="label_4 sublabel_4_city" required="" />
                  <label class="form-sub-label" for="input_4_city" id="sublabel_4_city" style="min-height:13px" aria-hidden="false"> City </label>
                </span>
              </span>
              <span class="form-address-line form-address-state-line jsTest-address-lineField ">
                <span class="form-sub-label-container" style="vertical-align:top">
                  <input type="text" id="input_4_state" name="q4_address[state]" class="form-textbox form-address-state" data-defaultvalue="" value="" data-component="state" aria-labelledby="label_4 sublabel_4_state" required="" />
                  <label class="form-sub-label" for="input_4_state" id="sublabel_4_state" style="min-height:13px" aria-hidden="false"> State / Province </label>
                </span>
              </span>
            </div>
            <div class="form-address-line-wrapper jsTest-address-line-wrapperField">
              <span class="form-address-line form-address-zip-line jsTest-address-lineField ">
                <span class="form-sub-label-container" style="vertical-align:top">
                  <input type="text" id="input_4_postal" name="q4_address[postal]" class="form-textbox form-address-postal" data-defaultvalue="" value="" data-component="zip" aria-labelledby="label_4 sublabel_4_postal" required="" />
                  <label class="form-sub-label" for="input_4_postal" id="sublabel_4_postal" style="min-height:13px" aria-hidden="false"> Postal / Zip Code </label>
                </span>
              </span>
            </div>
          </div>
        </div>
      </li>
      <li class="form-line" data-type="control_phone" id="id_5">
        <label class="form-label form-label-top form-label-auto" id="label_5" for="input_5_full"> Phone Number </label>
        <div id="cid_5" class="form-input-wide" data-layout="half">
          <span class="form-sub-label-container" style="vertical-align:top">
            <input type="tel" id="input_5_full" name="q5_phoneNumber[full]" data-type="mask-number" class="mask-phone-number form-textbox validate[Fill Mask]" data-defaultvalue="" style="width:310px" data-masked="true" value="" placeholder="(000) 000-0000" data-component="phone" aria-labelledby="label_5 sublabel_5_masked" />
            <label class="form-sub-label" for="input_5_full" id="sublabel_5_masked" style="min-height:13px" aria-hidden="false"> Please enter a valid phone number. </label>
          </span>
        </div>
      </li>
      <li class="form-line" data-type="control_datetime" id="id_6">
        <label class="form-label form-label-top form-label-auto" id="label_6" for="lite_mode_6"> Date </label>
        <div id="cid_6" class="form-input-wide" data-layout="half">
          <div data-wrapper-react="true">
            <div style="display:none">
              <span class="form-sub-label-container" style="vertical-align:top">
                <input type="tel" class="form-textbox validate[limitDate]" id="month_6" name="q6_date[month]" size="2" data-maxlength="2" data-age="" maxLength="2" value="" autoComplete="off" aria-labelledby="label_6 sublabel_6_month" />
                <span class="date-separate" aria-hidden="true">
                   -
                </span>
                <label class="form-sub-label" for="month_6" id="sublabel_6_month" style="min-height:13px" aria-hidden="false"> Month </label>
              </span>
              <span class="form-sub-label-container" style="vertical-align:top">
                <input type="tel" class="form-textbox validate[limitDate]" id="day_6" name="q6_date[day]" size="2" data-maxlength="2" data-age="" maxLength="2" value="" autoComplete="off" aria-labelledby="label_6 sublabel_6_day" />
                <span class="date-separate" aria-hidden="true">
                   -
                </span>
                <label class="form-sub-label" for="day_6" id="sublabel_6_day" style="min-height:13px" aria-hidden="false"> Day </label>
              </span>
              <span class="form-sub-label-container" style="vertical-align:top">
                <input type="tel" class="form-textbox validate[limitDate]" id="year_6" name="q6_date[year]" size="4" data-maxlength="4" data-age="" maxLength="4" value="" autoComplete="off" aria-labelledby="label_6 sublabel_6_year" />
                <label class="form-sub-label" for="year_6" id="sublabel_6_year" style="min-height:13px" aria-hidden="false"> Year </label>
              </span>
            </div>
            <span class="form-sub-label-container" style="vertical-align:top">
              <input type="text" class="form-textbox validate[limitDate, validateLiteDate]" id="lite_mode_6" size="12" data-maxlength="12" maxLength="12" data-age="" value="" data-format="mmddyyyy" data-seperator="-" placeholder="MM-DD-YYYY" autoComplete="off" aria-labelledby="label_6 sublabel_6_litemode" />
              <img class=" newDefaultTheme-dateIcon icon-liteMode" alt="Pick a Date" id="input_6_pick" src="https://cdn.jotfor.ms/images/calendar.png" data-component="datetime" aria-hidden="true" data-allow-time="No" data-version="v2" />
              <label class="form-sub-label" for="lite_mode_6" id="sublabel_6_litemode" style="min-height:13px" aria-hidden="false"> Date </label>
            </span>
          </div>
        </div>
      </li>
      <li class="form-line" data-type="control_appointment" id="id_7">
        <label class="form-label form-label-top" id="label_7" for="input_7"> Appointment </label>
        <div id="cid_7" class="form-input-wide" data-layout="full">
          <div id="input_7" class="appointmentFieldWrapper jfQuestion-fields">
            <input class="appointmentFieldInput " name="q7_appointment[date]" id="input_7_date" />
            <input class="appointmentFieldInput" name="q7_appointment[duration]" value="60" id="input_7_duration" />
            <input class="appointmentFieldInput" name="q7_appointment[timezone]" value="America/New_York (GMT-04:00)" id="input_7_timezone" />
            <div class="appointmentField">
            </div>
          </div>
        </div>
      </li>
      <li class="form-line" data-type="control_button" id="id_9">
        <div id="cid_9" class="form-input-wide" data-layout="full">
          <div data-align="auto" class="form-buttons-wrapper form-buttons-auto   jsTest-button-wrapperField">
            <button id="input_9" type="submit" class="form-submit-button submit-button jf-form-buttons jsTest-submitField" data-component="button" data-content="">
              Submit
            </button>
          </div>
        </div>
      </li>
      <li style="display:none">
        Should be Empty:
        <input type="text" name="website" value="" />
      </li>
    </ul>
  </div>
  <script>
  JotForm.showJotFormPowered = "new_footer";
  </script>
  <script>
  JotForm.poweredByText = "Powered by Jotform";
  </script>
  <input type="hidden" class="simple_spc" id="simple_spc" name="simple_spc" value="212994847630062" />
  <script type="text/javascript">
  var all_spc = document.querySelectorAll("form[id='212994847630062'] .si" + "mple" + "_spc");
for (var i = 0; i < all_spc.length; i++)
{
  all_spc[i].value = "212994847630062-212994847630062";
}
  </script>
</form>
<script src="https://cdn.jotfor.ms//js/vendor/smoothscroll.min.js?v=3.3.28735"></script>
<script src="https://cdn.jotfor.ms//js/errorNavigation.js?v=3.3.28735"></script>