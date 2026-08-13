/**
 * 8-step booking flow (Type → Brand → Problem → Location → Schedule →
 * Contact → Verify OTP → Review), used on every appliance page. Location
 * and Schedule follow the client's "template 2" reference
 * (clientFeedback/02/surefix-booking-flow - template 2.html) adapted to our
 * glass/orange brand — Location is a detect-my-location/manual-address flow
 * with no visible map (avoids the map-rendering step entirely), Schedule is
 * a quick-date row + custom calendar + Morning/Afternoon/Evening time chips.
 * There is no service/price-selection step — every visit has a flat ₹199+
 * diagnostic charge shown on the Review step. Pure client-side for now:
 * "Confirm Booking" generates a placeholder booking ID and shows the
 * thank-you step, but nothing is sent to a server yet — that lands in
 * Phase 2 (api/submit-booking.php + the `bookings` table). The OTP step is
 * a UI-only mock (code 1234, no real SMS) for the same reason.
 */
(function () {
  'use strict';

  var widget = document.getElementById('bookingWidget');
  if (!widget) return;

  var steps = Array.prototype.slice.call(widget.querySelectorAll('.booking-step'));
  var totalSteps = steps.length;      // 8 interactive steps (1 hidden) + 1 thank-you step
  var INTERACTIVE_STEPS = 7;
  var currentStep = 1;

  // Step 7 (Verify OTP) is hidden for now — not a finished feature yet.
  // Its markup/JS all stay in place; navigation just skips over it so
  // Contact Details (6) goes straight to Review (8). Flip this back to
  // false to re-enable it later.
  var OTP_STEP_HIDDEN = true;
  var OTP_STEP = 7;

  var state = {
    applianceType: '', brand: '', problems: [],
    location: { mode: null, address: '', landmark: '', notes: '', tag: 'Home' },
    lat: null, lng: null,
    emergency: false, date: '', dateIsCustom: false, slot: '',
    name: '', mobile: '', email: '', otpVerified: false, otpSentFor: ''
  };

  function showStep(n) {
    currentStep = n;
    steps.forEach(function (el) {
      el.classList.toggle('is-active', parseInt(el.dataset.step, 10) === n);
    });
    updateProgress(n);
    hideHint(n);
    scrollToWidgetTop();

    if (n === 4) { loadGeocoder(); renderLocationUI(); }
    if (n === 5) renderSchedule();
    if (n === 7) enterOtpStep();
    if (n === 8) renderReview();
  }

  // Maps a real .booking-step data-step number to the step number shown in
  // the progress bar, collapsing the hidden OTP step out of the count.
  function displayStepNumber(n) {
    return (OTP_STEP_HIDDEN && n > OTP_STEP) ? n - 1 : n;
  }

  // Step-index math that skips over the hidden OTP step in both directions,
  // so Contact Details -> Continue goes straight to Review, and Review ->
  // Back goes straight to Contact Details.
  function nextStepFrom(n) {
    var next = Math.min(n + 1, totalSteps - 1);
    if (OTP_STEP_HIDDEN && next === OTP_STEP) next++;
    return next;
  }
  function prevStepFrom(n) {
    var prev = Math.max(n - 1, 1);
    if (OTP_STEP_HIDDEN && prev === OTP_STEP) prev--;
    return prev;
  }

  function updateProgress(n) {
    var shown = Math.min(displayStepNumber(n), INTERACTIVE_STEPS);
    var pct = Math.round((shown / INTERACTIVE_STEPS) * 100);
    var label = document.getElementById('bpLabel');
    var pctEl = document.getElementById('bpPct');
    var fill = document.getElementById('bpFill');
    if (label) label.textContent = 'Step ' + shown + ' of ' + INTERACTIVE_STEPS;
    if (pctEl) pctEl.textContent = pct + '%';
    if (fill) fill.style.width = pct + '%';
  }

  // Scrolls the widget to a stable position under the sticky navbar. Blurring
  // the active field first matters on mobile: if a text input is still
  // focused when a step changes, the on-screen keyboard's own closing
  // reflow fires at the same time as our smooth scroll and the two fight
  // each other, causing a visible "jump/drag". Blurring lets the keyboard
  // close first so only one scroll happens.
  function scrollToWidgetTop() {
    if (document.activeElement && document.activeElement !== document.body && typeof document.activeElement.blur === 'function') {
      document.activeElement.blur();
    }
    requestAnimationFrame(function () {
      var navbar = document.getElementById('navbar');
      var offset = (navbar ? navbar.getBoundingClientRect().height : 0) + 12;
      var target = Math.max(widget.getBoundingClientRect().top + window.pageYOffset - offset, 0);
      if (Math.abs(window.pageYOffset - target) > 4) {
        window.scrollTo({ top: target, behavior: 'smooth' });
      }
    });
  }

  function hideHint(n) {
    var hint = widget.querySelector('.booking-widget__hint[data-hint="' + n + '"]');
    if (hint) hint.classList.remove('is-visible');
  }
  function showHint(n, msg) {
    var hint = widget.querySelector('.booking-widget__hint[data-hint="' + n + '"]');
    if (hint) {
      if (msg) hint.textContent = msg;
      hint.classList.add('is-visible');
    }
  }

  function optionName(input) {
    var label = input.nextElementSibling;
    if (!label) return '';
    var nameEl = label.querySelector('.option-card__name');
    return nameEl ? nameEl.textContent.trim() : label.textContent.trim();
  }

  function collectStep(n) {
    if (n === 1) {
      var typeEl = widget.querySelector('input[name="applianceType"]:checked');
      state.applianceType = typeEl ? optionName(typeEl) : '';
    }
    if (n === 2) {
      var brandEl = widget.querySelector('input[name="brand"]:checked');
      state.brand = brandEl ? optionName(brandEl) : '';
    }
    if (n === 3) {
      // Most appliance pages use a flat multi-select checkbox list
      // (name="problem[]"); AC/TV use a single radio group spanning two
      // labeled Repair/Service sections (name="problem") so only one can
      // be picked. Both patterns feed the same state.problems array.
      state.problems = Array.prototype.map.call(
        widget.querySelectorAll('input[name="problem[]"]:checked, input[name="problem"]:checked'),
        function (el) { return el.value; }
      );
    }
    if (n === 4) {
      state.location.address = document.getElementById('bookAddress').value.trim();
      state.location.landmark = document.getElementById('bookLandmark').value.trim();
      state.location.notes = document.getElementById('bookNotes').value.trim();
      var tagEl = widget.querySelector('input[name="addrTag"]:checked');
      state.location.tag = tagEl ? optionName(tagEl).trim() : 'Home';
    }
    if (n === 6) {
      state.name = document.getElementById('bookName').value.trim();
      state.mobile = document.getElementById('bookMobile').value.trim();
      state.email = document.getElementById('bookEmail').value.trim();
    }
  }

  function validateStep(n) {
    if (n === 3) {
      if (!widget.querySelectorAll('input[name="problem[]"]:checked, input[name="problem"]:checked').length) {
        showHint(3); return false;
      }
      return true;
    }
    if (n === 4) {
      if (!state.location.address) { showHint(4); return false; }
      return true;
    }
    if (n === 5) {
      if (!state.emergency && (!state.date || !state.slot)) { showHint(5); return false; }
      return true;
    }
    if (n === 6) {
      if (!state.name || !/^[6-9]\d{9}$/.test(state.mobile)) {
        showHint(6); return false;
      }
      return true;
    }
    if (n === 7) {
      if (!state.otpVerified) {
        showHint(7, 'Please enter the 4-digit code and tap Verify OTP to continue.');
        return false;
      }
      return true;
    }
    return true;
  }

  widget.addEventListener('click', function (e) {
    var btn = e.target.closest('[data-action]');
    if (!btn) return;
    var action = btn.dataset.action;

    if (action === 'next') {
      collectStep(currentStep);
      if (!validateStep(currentStep)) return;
      showStep(nextStepFrom(currentStep));
    } else if (action === 'prev') {
      showStep(prevStepFrom(currentStep));
    } else if (action === 'skip-brand') {
      widget.querySelectorAll('input[name="brand"]').forEach(function (el) { el.checked = false; });
      state.brand = '';
      showStep(nextStepFrom(currentStep));
    } else if (action === 'confirm') {
      confirmBooking();
    } else if (action === 'locate') {
      useMyLocation(btn);
    } else if (action === 'manual-address') {
      useManualAddress();
    } else if (action === 'toggle-emergency') {
      toggleEmergency();
    } else if (action === 'select-quick-date') {
      selectDate(btn.dataset.value, false);
    } else if (action === 'select-calendar-day') {
      selectDate(btn.dataset.value, true);
    } else if (action === 'toggle-calendar') {
      calendarOpen = !calendarOpen;
      renderDateRow(); renderCalendarPanel();
    } else if (action === 'cal-prev') {
      calMonthOffset = Math.max(calMonthOffset - 1, 0);
      renderCalendarPanel();
    } else if (action === 'cal-next') {
      calMonthOffset++;
      renderCalendarPanel();
    } else if (action === 'select-time') {
      selectTime(decodeURIComponent(btn.dataset.value));
    } else if (action === 'resend-otp') {
      resendOtp();
    } else if (action === 'verify-otp') {
      verifyOtp();
    }
  });

  widget.addEventListener('keydown', function (e) {
    if ((e.key === 'Enter' || e.key === ' ') && e.target.id === 'emergencyCard') {
      e.preventDefault();
      toggleEmergency();
    }
  });

  widget.addEventListener('input', function (e) {
    if (e.target.id === 'bookMobile') {
      e.target.value = e.target.value.replace(/\D/g, '').slice(0, 10);
    }
    if (e.target.id === 'bookAddress' && e.target.value.trim()) {
      hideHint(4);
    }
  });

  // ---- Step 4: Location (detect / manual, no visible map) -------------------
  // The locate-card (with its "Detect my location" button) stays visible
  // for both the initial and manual states — "Enter address manually" only
  // reveals the address fields underneath it, it doesn't replace anything.
  // It's replaced by the detected-card only once a location is actually
  // auto-detected, which still offers "Edit" back to manual.
  function renderLocationUI() {
    var locateCard = document.getElementById('locateCard');
    var detectedCard = document.getElementById('detectedCard');
    var fields = document.getElementById('addressFields');
    var mode = state.location.mode;

    locateCard.hidden = mode === 'auto';
    detectedCard.hidden = mode !== 'auto';
    fields.hidden = !mode;

    if (mode) {
      document.getElementById('bookAddress').value = state.location.address;
      document.getElementById('bookLandmark').value = state.location.landmark;
    }
    if (mode === 'auto') {
      document.getElementById('detectedAddrOut').textContent = state.location.address;
    }
  }

  function useManualAddress() {
    state.location.mode = 'manual';
    renderLocationUI();
    var addr = document.getElementById('bookAddress');
    if (addr) addr.focus();
  }

  function useMyLocation(triggerBtn) {
    if (!navigator.geolocation) { useManualAddress(); return; }
    loadGeocoder();
    if (triggerBtn) { triggerBtn.disabled = true; triggerBtn.classList.add('is-loading'); }
    navigator.geolocation.getCurrentPosition(function (pos) {
      var latLng = { lat: pos.coords.latitude, lng: pos.coords.longitude };
      state.lat = latLng.lat; state.lng = latLng.lng;
      var attempts = 0;
      (function tryGeocode() {
        attempts++;
        if (geocoder) {
          reverseGeocode(latLng);
          if (triggerBtn) { triggerBtn.disabled = false; triggerBtn.classList.remove('is-loading'); }
        } else if (attempts < 20) {
          setTimeout(tryGeocode, 300);
        } else if (triggerBtn) {
          triggerBtn.disabled = false; triggerBtn.classList.remove('is-loading');
          showHint(4, 'Could not verify your location — please enter your address manually.');
        }
      })();
    }, function () {
      if (triggerBtn) { triggerBtn.disabled = false; triggerBtn.classList.remove('is-loading'); }
      showHint(4, 'Could not access your location — please enter your address manually.');
    }, { timeout: 8000 });
  }

  // ---- Step 5: Schedule (quick dates + custom calendar + grouped slots) -----
  var SLOT_START_HOUR = 8;
  var SLOT_END_HOUR = 22;
  var SLOT_INTERVAL_MIN = 30;
  var calendarOpen = false;
  var calMonthOffset = 0;

  function toggleEmergency() {
    state.emergency = !state.emergency;
    var card = document.getElementById('emergencyCard');
    card.classList.toggle('is-active', state.emergency);
    card.setAttribute('aria-pressed', state.emergency ? 'true' : 'false');
    if (state.emergency) {
      hideHint(5);
      renderSlotGroups();
    }
  }

  function renderSchedule() {
    if (!state.date) { state.date = toDateStr(new Date()); state.dateIsCustom = false; }
    renderDateRow();
    renderCalendarPanel();
    renderSlotGroups();
  }

  function buildQuickDates() {
    var today = new Date();
    var out = [];
    for (var i = 0; i < 4; i++) {
      var d = new Date(today);
      d.setDate(today.getDate() + i);
      out.push({
        label: i === 0 ? 'Today' : i === 1 ? 'Tomorrow' : d.toLocaleDateString('en-US', { weekday: 'short' }),
        dnum: d.getDate(),
        dmon: d.toLocaleDateString('en-US', { month: 'short' }),
        iso: toDateStr(d)
      });
    }
    return out;
  }

  function renderDateRow() {
    var wrap = document.getElementById('dateRow');
    var dates = buildQuickDates();
    var html = dates.map(function (d) {
      var selected = state.date === d.iso && !state.dateIsCustom;
      return '<button type="button" class="date-card' + (selected ? ' selected' : '') + '" data-action="select-quick-date" data-value="' + d.iso + '">' +
        '<span class="date-card__dow">' + d.label + '</span><span class="date-card__num">' + d.dnum + '</span><span class="date-card__mon">' + d.dmon + '</span></button>';
    }).join('');
    var customSelected = state.dateIsCustom;
    var customLabel = customSelected ? formatCustomDateShort(state.date) : 'Custom';
    html += '<button type="button" class="date-card date-card--custom' + (customSelected ? ' selected' : '') + '" data-action="toggle-calendar"><i class="fa-solid fa-calendar-days"></i><span class="date-card__dow">' + customLabel + '</span></button>';
    wrap.innerHTML = html;
  }

  function formatCustomDateShort(dateStr) {
    var d = new Date(dateStr + 'T00:00:00');
    return d.getDate() + ' ' + d.toLocaleDateString('en-US', { month: 'short' });
  }

  function renderCalendarPanel() {
    var panel = document.getElementById('calendarPanel');
    panel.hidden = !calendarOpen;
    if (!calendarOpen) return;

    var today = new Date();
    var base = new Date(today.getFullYear(), today.getMonth() + calMonthOffset, 1);
    var year = base.getFullYear(), month = base.getMonth();
    var firstDow = new Date(year, month, 1).getDay();
    var daysInMonth = new Date(year, month + 1, 0).getDate();
    var maxDate = new Date(today); maxDate.setDate(maxDate.getDate() + 13);
    var minDay = new Date(today.getFullYear(), today.getMonth(), today.getDate());

    var cells = '';
    for (var i = 0; i < firstDow; i++) cells += '<div class="cal-day is-empty"></div>';
    for (var d = 1; d <= daysInMonth; d++) {
      var cellDate = new Date(year, month, d);
      var iso = toDateStr(cellDate);
      var disabled = cellDate < minDay || cellDate > maxDate;
      var selected = state.date === iso && state.dateIsCustom;
      cells += '<div class="cal-day' + (disabled ? ' is-disabled' : '') + (selected ? ' selected' : '') + '"' +
        (disabled ? '' : ' data-action="select-calendar-day" data-value="' + iso + '"') + '>' + d + '</div>';
    }
    var monthLabel = base.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });
    var dow = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    panel.innerHTML =
      '<div class="cal-head"><span class="cal-head__title">' + monthLabel + '</span>' +
      '<div class="cal-nav">' +
      '<button type="button" class="cal-nav__btn" data-action="cal-prev" ' + (calMonthOffset <= 0 ? 'disabled' : '') + '><i class="fa-solid fa-chevron-left"></i></button>' +
      '<button type="button" class="cal-nav__btn" data-action="cal-next"><i class="fa-solid fa-chevron-right"></i></button>' +
      '</div></div>' +
      '<div class="cal-grid">' + dow.map(function (x) { return '<div class="cal-dow">' + x + '</div>'; }).join('') + cells + '</div>';
  }

  function selectDate(iso, isCustom) {
    state.date = iso;
    state.dateIsCustom = isCustom;
    state.slot = '';
    state.emergency = false;
    var card = document.getElementById('emergencyCard');
    card.classList.remove('is-active');
    card.setAttribute('aria-pressed', 'false');
    if (isCustom) calendarOpen = false;
    hideHint(5);
    renderDateRow();
    renderCalendarPanel();
    renderSlotGroups();
  }

  function renderSlotGroups() {
    var wrap = document.getElementById('slotGroups');
    var dateStr = state.date;
    if (!dateStr) { wrap.innerHTML = ''; return; }
    var selectedDate = new Date(dateStr + 'T00:00:00');
    var isToday = dateStr === toDateStr(new Date());
    var earliest = new Date();
    earliest.setHours(earliest.getHours() + 1);

    var groups = { Morning: [], Afternoon: [], Evening: [] };
    var startMin = SLOT_START_HOUR * 60;
    var endMin = SLOT_END_HOUR * 60;
    for (var m = startMin; m < endMin; m += SLOT_INTERVAL_MIN) {
      var slotStart = new Date(selectedDate.getTime());
      slotStart.setHours(0, m, 0, 0);
      if (isToday && slotStart < earliest) continue;
      var bucket = m < 12 * 60 ? 'Morning' : (m < 17 * 60 ? 'Afternoon' : 'Evening');
      groups[bucket].push(slotStart);
    }

    var icons = { Morning: 'fa-sun', Afternoon: 'fa-cloud-sun', Evening: 'fa-moon' };
    var html = '';
    var any = false;
    ['Morning', 'Afternoon', 'Evening'].forEach(function (name) {
      if (!groups[name].length) return;
      any = true;
      html += '<div class="slot-group"><div class="slot-group__title"><i class="fa-solid ' + icons[name] + '"></i> ' + name + '</div><div class="slot-wrap">';
      groups[name].forEach(function (s) {
        var label = formatTime(s);
        var selected = !state.emergency && state.slot === label;
        html += '<button type="button" class="slot-chip-btn' + (selected ? ' selected' : '') + '" data-action="select-time" data-value="' + encodeURIComponent(label) + '">' + label + '</button>';
      });
      html += '</div></div>';
    });
    wrap.innerHTML = any ? html : '<p class="slot-grid__empty">No more slots available today — please choose another date.</p>';
  }

  function selectTime(label) {
    state.slot = label;
    state.emergency = false;
    var card = document.getElementById('emergencyCard');
    card.classList.remove('is-active');
    card.setAttribute('aria-pressed', 'false');
    hideHint(5);
    renderSlotGroups();
  }

  function toDateStr(d) {
    var m = ('0' + (d.getMonth() + 1)).slice(-2);
    var day = ('0' + d.getDate()).slice(-2);
    return d.getFullYear() + '-' + m + '-' + day;
  }
  function formatTime(d) {
    var h = d.getHours();
    var min = d.getMinutes();
    var ampm = h >= 12 ? 'PM' : 'AM';
    var h12 = h % 12 === 0 ? 12 : h % 12;
    return h12 + ':' + ('0' + min).slice(-2) + ' ' + ampm;
  }
  function formatReviewDate(dateStr) {
    var d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-IN', { weekday: 'short', day: 'numeric', month: 'short' });
  }

  // ---- Geocoding (Google Maps JS API — Geocoder + Places only, no visible
  // map widget is rendered for this step) ---------------------------------
  var geocoderLoaded = false, geocoder = null;

  function mapUnavailable(reason) {
    if (window.console && console.warn) console.warn('SureFix location lookup unavailable: ' + reason);
    showHint(4, 'Location lookup is temporarily unavailable — please enter your address manually.');
  }

  // Google calls this global automatically when the API key itself is the
  // problem (invalid, unauthorized referrer, Maps JavaScript API not
  // enabled, or no billing account on the Google Cloud project).
  window.gm_authFailure = function () { mapUnavailable('Google Maps authentication failed — check the API key, enabled APIs and billing in Google Cloud Console.'); };

  function loadGeocoder() {
    if (geocoderLoaded) return;
    geocoderLoaded = true;
    if (!window.SUREFIX_MAPS_KEY) { mapUnavailable('no API key configured'); return; }

    window.__initGeocoder = function () {
      if (!window.google || !window.google.maps) { mapUnavailable('google.maps did not load'); return; }
      geocoder = new google.maps.Geocoder();
      setupAutocomplete();
    };
    var script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=' +
      encodeURIComponent(window.SUREFIX_MAPS_KEY) + '&libraries=places&callback=__initGeocoder';
    script.async = true;
    script.onerror = function () { mapUnavailable('script failed to load (network/ad-blocker?)'); };
    document.head.appendChild(script);
  }

  // Address field autocomplete — typing an address suggests real places
  // (biased to Bengaluru); picking one fills the address field directly.
  function setupAutocomplete() {
    var input = document.getElementById('bookAddress');
    if (!input || !window.google || !google.maps.places) return;

    var bengaluruBounds = new google.maps.LatLngBounds(
      new google.maps.LatLng(12.75, 77.35),
      new google.maps.LatLng(13.15, 77.85)
    );
    var autocomplete = new google.maps.places.Autocomplete(input, {
      fields: ['formatted_address', 'geometry'],
      componentRestrictions: { country: 'in' },
      bounds: bengaluruBounds,
      strictBounds: false
    });

    autocomplete.addListener('place_changed', function () {
      var place = autocomplete.getPlace();
      if (!place.geometry || !place.geometry.location) return;
      var loc = place.geometry.location;
      state.lat = loc.lat(); state.lng = loc.lng();
      state.location.address = place.formatted_address || input.value;
      input.value = state.location.address;
      hideHint(4);
    });
  }

  function reverseGeocode(latLng) {
    if (!geocoder) return;
    geocoder.geocode({ location: latLng }, function (results, status) {
      if (status === 'OK' && results[0]) {
        state.location.mode = 'auto';
        state.location.address = results[0].formatted_address;
        renderLocationUI();
        hideHint(4);
      } else {
        showHint(4, 'Could not determine your address — please enter it manually.');
      }
    });
  }

  // ---- Step 7: mock OTP verification -----------------------------------------
  // A dedicated step (rather than bundled into Contact Details) so the
  // "Verify OTP" action gets its own clear call-to-action, and so the user
  // can always get back to an editable mobile number via the ordinary Back
  // button — nothing on this step ever disables the previous step's fields.
  var resendTimerHandle = null;

  function enterOtpStep() {
    var phoneOut = document.getElementById('otpPhoneOut');
    if (phoneOut) phoneOut.textContent = state.mobile;

    // Already verified this exact number (e.g. user went Back to Review
    // then forward again) — leave the success state as-is, don't resend.
    if (state.otpVerified && state.otpSentFor === state.mobile) return;

    state.otpVerified = false;
    state.otpSentFor = state.mobile;
    document.querySelectorAll('.otp-box').forEach(function (b) {
      b.value = ''; b.disabled = false; b.classList.remove('is-error', 'is-success');
    });
    document.getElementById('otpError').hidden = true;
    document.getElementById('otpSuccess').hidden = true;
    document.getElementById('otpMeta').hidden = false;
    startResendTimer();
    var first = document.getElementById('otp0');
    if (first) first.focus();
  }

  function startResendTimer() {
    clearInterval(resendTimerHandle);
    var seconds = 30;
    var btn = document.getElementById('resendOtpBtn');
    btn.disabled = true;
    btn.textContent = 'Resend OTP in 0:' + (seconds < 10 ? '0' : '') + seconds;
    resendTimerHandle = setInterval(function () {
      seconds--;
      if (seconds <= 0) {
        clearInterval(resendTimerHandle);
        btn.disabled = false;
        btn.textContent = 'Resend OTP';
      } else {
        btn.textContent = 'Resend OTP in 0:' + (seconds < 10 ? '0' : '') + seconds;
      }
    }, 1000);
  }

  function resendOtp() {
    document.querySelectorAll('.otp-box').forEach(function (b) {
      b.value = ''; b.classList.remove('is-error');
    });
    document.getElementById('otpError').hidden = true;
    startResendTimer();
    var first = document.getElementById('otp0');
    if (first) first.focus();
  }

  widget.addEventListener('input', function (e) {
    if (!e.target.classList || !e.target.classList.contains('otp-box')) return;
    var boxes = Array.prototype.slice.call(document.querySelectorAll('.otp-box'));
    var i = boxes.indexOf(e.target);
    var v = e.target.value.replace(/\D/g, '').slice(0, 1);
    e.target.value = v;
    e.target.classList.remove('is-error');
    if (v && boxes[i + 1]) boxes[i + 1].focus();
  });
  widget.addEventListener('keydown', function (e) {
    if (!e.target.classList || !e.target.classList.contains('otp-box')) return;
    var boxes = Array.prototype.slice.call(document.querySelectorAll('.otp-box'));
    var i = boxes.indexOf(e.target);
    if (e.key === 'Backspace' && !e.target.value && boxes[i - 1]) boxes[i - 1].focus();
    if (e.key === 'Enter') verifyOtp();
  });

  function verifyOtp() {
    var boxes = Array.prototype.slice.call(document.querySelectorAll('.otp-box'));
    var code = boxes.map(function (b) { return b.value; }).join('');
    if (code.length < 4) return;
    if (code === '1234') {
      state.otpVerified = true;
      boxes.forEach(function (b) { b.classList.remove('is-error'); b.classList.add('is-success'); b.disabled = true; });
      document.getElementById('otpError').hidden = true;
      document.getElementById('otpSuccess').hidden = false;
      document.getElementById('otpMeta').hidden = true;
      clearInterval(resendTimerHandle);
      hideHint(7);
    } else {
      boxes.forEach(function (b) { b.classList.add('is-error'); });
      document.getElementById('otpError').hidden = false;
      setTimeout(function () {
        boxes.forEach(function (b) { b.value = ''; b.classList.remove('is-error'); });
        boxes[0].focus();
      }, 650);
    }
  }

  // ---- Step 8: review ---------------------------------------------------------
  function renderReview() {
    var summary = document.getElementById('reviewSummary');
    var scheduleText = state.emergency
      ? 'Emergency — within 60 minutes'
      : (state.date ? formatReviewDate(state.date) + ', ' + state.slot : '—');
    var locationText = '[' + state.location.tag + '] ' + state.location.address +
      (state.location.landmark ? ', ' + state.location.landmark : '');

    var rows = [
      ['Appliance', widget.dataset.appliance + (state.applianceType ? ' — ' + state.applianceType : '')],
      ['Brand', state.brand || 'Not specified'],
      ['Problem', state.problems.join(', ') || '—'],
      ['Location', locationText + (state.location.notes ? ' (' + state.location.notes + ')' : '')],
      ['Schedule', scheduleText],
      ['Name', state.name],
      ['Mobile', '+91 ' + state.mobile + (state.otpVerified ? ' ✓ Verified' : '')],
      ['Email', state.email || '—']
    ];
    summary.innerHTML = rows.map(function (r) {
      return '<div class="review-summary__row"><span>' + r[0] + '</span><strong>' + escapeHtml(r[1]) + '</strong></div>';
    }).join('');
  }

  function escapeHtml(str) {
    var div = document.createElement('div');
    div.textContent = str == null ? '' : String(str);
    return div.innerHTML;
  }

  // ---- Step 9: confirm ---------------------------------------------------------
  function confirmBooking() {
    var btn = document.getElementById('confirmBookingBtn');
    hideHint(8);
    if (btn) btn.disabled = true;

    var payload = {
      service: widget.dataset.appliance,
      applianceType: state.applianceType,
      brand: state.brand,
      problems: state.problems,
      address: state.location.address,
      landmark: state.location.landmark,
      area: state.location.tag,
      notes: state.location.notes,
      lat: state.lat,
      lng: state.lng,
      emergency: state.emergency,
      date: state.date,
      slot: state.slot,
      name: state.name,
      mobile: state.mobile,
      email: state.email
    };

    var base = window.SUREFIX_SITE_URL || '';
    fetch(base + '/api/submit-booking.php', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json' },
      body: JSON.stringify(payload)
    }).then(function (res) { return res.json(); }).then(function (data) {
      if (!data.success) {
        showHint(8, data.error || 'Something went wrong — please try again, or call us to book directly.');
        return;
      }
      var windowText = state.emergency
        ? 'Emergency — within 60 minutes'
        : state.slot + ' on ' + formatReviewDate(state.date);
      document.getElementById('bookingIdOut').textContent = data.bookingId;
      document.getElementById('bookingWindowOut').textContent = windowText;
      showStep(9);
    }).catch(function () {
      showHint(8, 'Could not reach the server — please try again, or call us to book directly.');
    }).finally(function () {
      if (btn) btn.disabled = false;
    });
  }

  showStep(1);
})();
