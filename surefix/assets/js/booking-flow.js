/**
 * 6-step booking flow (FRD Form 2 — Service Booking), used on every
 * appliance page. Pure client-side for now: "Confirm Booking" generates a
 * placeholder booking ID and shows the thank-you step, but nothing is sent
 * to a server yet — that lands in Phase 2 (api/submit-booking.php + the
 * `bookings` table).
 */
(function () {
  'use strict';

  var widget = document.getElementById('bookingWidget');
  if (!widget) return;

  var steps = Array.prototype.slice.call(widget.querySelectorAll('.booking-step'));
  var dots = Array.prototype.slice.call(widget.querySelectorAll('[data-step-dot]'));
  var totalSteps = steps.length;
  var currentStep = 1;

  var state = {
    applianceType: '', problems: [], address: '', notes: '', lat: null, lng: null,
    date: '', slot: '', name: '', mobile: '', email: ''
  };

  function showStep(n) {
    currentStep = n;
    steps.forEach(function (el) {
      el.classList.toggle('is-active', parseInt(el.dataset.step, 10) === n);
    });
    dots.forEach(function (el) {
      var dn = parseInt(el.dataset.stepDot, 10);
      el.classList.toggle('is-active', dn === n);
      el.classList.toggle('is-complete', dn < n);
    });
    hideHint(n);
    widget.scrollIntoView({ behavior: 'smooth', block: 'start' });

    if (n === 2) loadMap();
    if (n === 3) renderSlots();
    if (n === 5) renderReview();
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

  function collectStep(n) {
    if (n === 1) {
      var typeEl = widget.querySelector('input[name="applianceType"]:checked');
      state.applianceType = typeEl ? typeEl.nextElementSibling.textContent : '';
      state.problems = Array.prototype.map.call(
        widget.querySelectorAll('input[name="problem[]"]:checked'),
        function (el) { return el.nextElementSibling.textContent; }
      );
    }
    if (n === 2) {
      state.address = document.getElementById('bookAddress').value.trim();
      state.notes = document.getElementById('bookNotes').value.trim();
    }
    if (n === 4) {
      state.name = document.getElementById('bookName').value.trim();
      state.mobile = document.getElementById('bookMobile').value.trim();
      state.email = document.getElementById('bookEmail').value.trim();
    }
  }

  function validateStep(n) {
    if (n === 1) {
      if (!widget.querySelectorAll('input[name="problem[]"]:checked').length) {
        showHint(1); return false;
      }
      return true;
    }
    if (n === 2) {
      if (!state.address) { showHint(2); return false; }
      return true;
    }
    if (n === 3) {
      var date = document.getElementById('bookDate').value;
      if (!date || !state.slot) { showHint(3); return false; }
      return true;
    }
    if (n === 4) {
      if (!state.name || !/^[6-9]\d{9}$/.test(state.mobile)) { showHint(4); return false; }
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
      showStep(Math.min(currentStep + 1, totalSteps - 1));
    } else if (action === 'prev') {
      showStep(Math.max(currentStep - 1, 1));
    } else if (action === 'confirm') {
      confirmBooking();
    } else if (action === 'locate') {
      useMyLocation();
    }
  });

  widget.addEventListener('input', function (e) {
    if (e.target.id === 'bookMobile') {
      e.target.value = e.target.value.replace(/\D/g, '').slice(0, 10);
    }
    if (e.target.id === 'bookAddress' && e.target.value.trim()) {
      hideHint(2);
    }
  });

  // ---- Step 3: time slots ---------------------------------------------------
  var SLOT_START_HOUR = 8;
  var SLOT_END_HOUR = 22;

  function renderSlots() {
    var dateInput = document.getElementById('bookDate');
    var grid = document.getElementById('slotGrid');
    var today = new Date();
    var minDate = toDateStr(today);
    if (!dateInput.value) dateInput.value = minDate;
    dateInput.min = minDate;
    var maxD = new Date(today);
    maxD.setDate(maxD.getDate() + 13);
    dateInput.max = toDateStr(maxD);

    dateInput.onchange = buildSlotGrid;
    buildSlotGrid();

    function buildSlotGrid() {
      grid.innerHTML = '';
      state.slot = '';
      var selectedDate = new Date(dateInput.value + 'T00:00:00');
      var isToday = dateInput.value === minDate;
      var earliest = new Date();
      earliest.setHours(earliest.getHours() + 1);

      var slots = [];
      for (var h = SLOT_START_HOUR; h < SLOT_END_HOUR; h++) {
        var slotStart = new Date(selectedDate.getTime());
        slotStart.setHours(h, 0, 0, 0);
        if (isToday && slotStart < earliest) continue;
        slots.push(slotStart);
      }

      if (!slots.length) {
        grid.innerHTML = '<p class="slot-grid__empty">No more slots available today — please choose another date.</p>';
        return;
      }

      slots.forEach(function (s, i) {
        var label = formatSlotLabel(s);
        var id = 'slot-' + i;
        var input = document.createElement('input');
        input.type = 'radio';
        input.name = 'slot';
        input.id = id;
        input.className = 'selector-chip';
        var lab = document.createElement('label');
        lab.setAttribute('for', id);
        lab.textContent = label;
        grid.appendChild(input);
        grid.appendChild(lab);
        input.addEventListener('change', function () {
          state.slot = label;
          state.date = dateInput.value;
          hideHint(3);
        });
        if (i === 0) {
          input.checked = true;
          state.slot = label;
          state.date = dateInput.value;
        }
      });
    }
  }

  function toDateStr(d) {
    var m = ('0' + (d.getMonth() + 1)).slice(-2);
    var day = ('0' + d.getDate()).slice(-2);
    return d.getFullYear() + '-' + m + '-' + day;
  }
  function formatSlotLabel(d) {
    var end = new Date(d.getTime() + 60 * 60 * 1000);
    return formatHour(d) + ' – ' + formatHour(end);
  }
  function formatHour(d) {
    var h = d.getHours();
    var ampm = h >= 12 ? 'PM' : 'AM';
    var h12 = h % 12 === 0 ? 12 : h % 12;
    return h12 + ':00 ' + ampm;
  }
  function formatReviewDate(dateStr) {
    var d = new Date(dateStr + 'T00:00:00');
    return d.toLocaleDateString('en-IN', { weekday: 'short', day: 'numeric', month: 'short' });
  }

  // ---- Step 2: Google Map picker ---------------------------------------------
  var mapLoaded = false, mapInstance = null, marker = null, geocoder = null;

  function loadMap() {
    if (mapLoaded) return;
    mapLoaded = true;
    if (!window.SUREFIX_MAPS_KEY) return;

    window.__initBookingMap = initBookingMap;
    var script = document.createElement('script');
    script.src = 'https://maps.googleapis.com/maps/api/js?key=' +
      encodeURIComponent(window.SUREFIX_MAPS_KEY) + '&libraries=places&callback=__initBookingMap';
    script.async = true;
    script.onerror = function () {
      var el = document.getElementById('bookingMap');
      if (el) el.innerHTML = '<div class="booking-map__placeholder">Map unavailable — you can still enter your address above.</div>';
    };
    document.head.appendChild(script);
  }

  function initBookingMap() {
    var el = document.getElementById('bookingMap');
    if (!el || !window.google) return;
    el.innerHTML = '';
    var center = { lat: parseFloat(el.dataset.lat), lng: parseFloat(el.dataset.lng) };
    mapInstance = new google.maps.Map(el, { center: center, zoom: 13, disableDefaultUI: true, zoomControl: true });
    marker = new google.maps.Marker({ position: center, map: mapInstance, draggable: true });
    geocoder = new google.maps.Geocoder();

    marker.addListener('dragend', function () {
      var pos = marker.getPosition();
      state.lat = pos.lat(); state.lng = pos.lng();
      reverseGeocode(pos);
    });
    mapInstance.addListener('click', function (e) {
      marker.setPosition(e.latLng);
      state.lat = e.latLng.lat(); state.lng = e.latLng.lng();
      reverseGeocode(e.latLng);
    });

    setupAutocomplete();
  }

  // Address field autocomplete — typing an address suggests real places
  // (biased to Bengaluru) and picking one moves the map pin to match.
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
      state.address = place.formatted_address || input.value;
      input.value = state.address;
      if (mapInstance && marker) {
        mapInstance.setCenter(loc);
        mapInstance.setZoom(16);
        marker.setPosition(loc);
      }
      hideHint(2);
    });
  }

  function reverseGeocode(latLng) {
    if (!geocoder) return;
    geocoder.geocode({ location: latLng }, function (results, status) {
      if (status === 'OK' && results[0]) {
        document.getElementById('bookAddress').value = results[0].formatted_address;
        state.address = results[0].formatted_address;
        hideHint(2);
      }
    });
  }

  function useMyLocation() {
    if (!navigator.geolocation) return;
    loadMap();
    navigator.geolocation.getCurrentPosition(function (pos) {
      var latLng = { lat: pos.coords.latitude, lng: pos.coords.longitude };
      state.lat = latLng.lat; state.lng = latLng.lng;
      var attempts = 0;
      (function tryMove() {
        attempts++;
        if (mapInstance && marker) {
          mapInstance.setCenter(latLng);
          mapInstance.setZoom(16);
          marker.setPosition(latLng);
          reverseGeocode(latLng);
        } else if (attempts < 20) {
          setTimeout(tryMove, 300);
        }
      })();
    }, function () {
      showHint(2, 'Could not access your location — please type your address instead.');
    }, { timeout: 8000 });
  }

  // ---- Step 5: review ---------------------------------------------------------
  function renderReview() {
    var summary = document.getElementById('reviewSummary');
    var rows = [
      ['Appliance', widget.dataset.appliance + (state.applianceType ? ' — ' + state.applianceType : '')],
      ['Problem', state.problems.join(', ') || '—'],
      ['Address', state.address + (state.notes ? ' (' + state.notes + ')' : '')],
      ['Date & Time', state.date ? formatReviewDate(state.date) + ', ' + state.slot : '—'],
      ['Name', state.name],
      ['Mobile', state.mobile],
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

  // ---- Step 6: confirm ---------------------------------------------------------
  function confirmBooking() {
    collectStep(4);
    if (!validateStep(4)) { showStep(4); return; }
    // Placeholder ID for this UI demo only — Phase 2 replaces this with a
    // server-generated ID persisted to the `bookings` table.
    var bookingId = 'SF' + Date.now().toString(36).toUpperCase().slice(-6);
    document.getElementById('bookingIdOut').textContent = bookingId;
    document.getElementById('bookingWindowOut').textContent = state.slot + ' on ' + formatReviewDate(state.date);
    showStep(6);
  }

  showStep(1);
})();
