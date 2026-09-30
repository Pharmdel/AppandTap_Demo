/*
 * Client-side stand-ins for the live portal's Livewire/Alpine behaviour:
 * the dashboard widget popups, the Midone "tw" modals, the SweetAlert2
 * confirmations and the little filters. Nothing is sent anywhere; accepted
 * actions just update the page, the way Livewire re-renders it upstream.
 */
(function () {
    const swal = (options) => (window.Swal ? Swal.fire(options) : Promise.resolve({ isConfirmed: window.confirm(options.title || '') }));

    /* ---------- Widget popups (<section x-show> in livewire/admin/dashboard/*) ---------- */
    function syncBodyScroll() {
        const anyOpen = document.querySelector('[data-popup]:not(.hidden)');
        document.body.classList.toggle('overflow-hidden', Boolean(anyOpen));
    }

    window.openPopup = function (id) {
        const popup = document.getElementById(id);
        if (!popup) { return; }
        popup.classList.remove('hidden');
        syncBodyScroll();
        document.dispatchEvent(new CustomEvent('popup:open', { detail: { id } }));
    };

    window.closePopup = function (popup) {
        if (!popup) { return; }
        popup.classList.add('hidden');
        syncBodyScroll();
    };

    /* ---------- Midone modals (data-tw-toggle / data-tw-dismiss) ---------- */
    window.showModal = (selector) => document.querySelector(selector)?.classList.add('show');
    window.hideModal = (selector) => document.querySelector(selector)?.classList.remove('show');

    document.addEventListener('click', function (event) {
        const target = event.target;

        const toggle = target.closest('[data-tw-toggle="modal"]');
        if (toggle) {
            showModal(toggle.dataset.twTarget);
        }

        const dismiss = target.closest('[data-tw-dismiss="modal"]');
        if (dismiss) {
            dismiss.closest('.modal')?.classList.remove('show');
            return;
        }

        const closer = target.closest('[data-popup-close]');
        if (closer) {
            event.preventDefault();
            closePopup(closer.closest('[data-popup]'));
            return;
        }

        const opener = target.closest('[data-popup-open]');
        if (opener) {
            // A real link inside a clickable card (Pharmacy's "No. of Patients")
            // still navigates, as it does upstream.
            const link = target.closest('a[href]');
            if (link && link !== opener && opener.contains(link) && !link.getAttribute('href').startsWith('javascript')) {
                return;
            }
            event.preventDefault();
            openPopup(opener.dataset.popupOpen);
        }
    });

    document.addEventListener('keydown', function (event) {
        if (event.key !== 'Escape') { return; }
        const modal = document.querySelector('.modal.show');
        if (modal) { modal.classList.remove('show'); return; }
        const popups = document.querySelectorAll('[data-popup]:not(.hidden)');
        if (popups.length) { closePopup(popups[popups.length - 1]); }
    });

    /* ---------- appointment-detail.blade.php ---------- */
    window.joinTimeWarning = function () {
        swal({
            title: 'You’re a little early',
            text: 'The video call hasn’t started yet. Please check back at the scheduled time.',
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'Ok',
        });
    };

    window.openResendEmail = function (email, id) {
        document.getElementById('resendEmails').value = email;
        document.getElementById('resendAppointmentId').value = id;
    };

    window.openResendEmailPopup = function (email, id) {
        document.getElementById('resendEmailPopup').value = email;
        document.getElementById('resendAppointmentIdPopup').value = id;
    };

    window.validateEmails = function (inputId) {
        const input = document.getElementById(inputId);
        const errors = input.closest('.modal').querySelector('[data-form-errors]');
        const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const valid = input.value.split(',').map((e) => e.trim()).every((e) => regex.test(e));
        errors.textContent = valid ? '' : 'Invalid email format';
        errors.classList.toggle('hidden', valid);
        return valid;
    };

    function setAppointmentStatus(id, status) {
        document.querySelectorAll(`[data-appointment="${id}"]`).forEach((row) => {
            row.querySelector('[data-appointment-status]').textContent = status;
            row.querySelectorAll('[data-status-actions]').forEach((set) => {
                set.classList.toggle('hidden', set.dataset.statusActions !== status);
            });
            row.querySelector('[data-join-button]')?.classList.toggle('hidden', status !== 'Approved');
        });
        document.dispatchEvent(new CustomEvent('appointment:updated', { detail: { id, status } }));
    }

    window.appointmentUpdate = function (type, id) {
        const messages = {
            booked: 'Would you like to confirm this appointment ?',
            resend: 'Would you like to resend confirmation mail ?',
            cancel: 'Would you cancel this appointment ?',
        };

        swal({
            title: messages[type],
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (!result.isConfirmed) { return; }

            if (type === 'resend') {
                ['resendEmails', 'resendEmailPopup'].forEach((field) => { const input = document.getElementById(field); if (input) { input.value = ''; } });
                hideModal('#book_confirm');
                hideModal('#book_confirm_popup');
                swal({ title: 'Booking confirmation email sent.', icon: 'success', timer: 1800, showConfirmButton: false });
                return;
            }

            setAppointmentStatus(id, type === 'booked' ? 'Approved' : 'Cancelled');
        });
    };

    /* ---------- group-dashboard.blade.php: Today's Re-order Reminder ---------- */
    function filterReorderRows(mode) {
        const popup = document.getElementById('reorder-reminder-popup');
        if (!popup) { return; }
        let visible = 0;
        popup.querySelectorAll('[data-reorder-row]').forEach((row) => {
            const show = mode === 'today' ? row.dataset.today === '1' : row.dataset.ordered === '0';
            row.classList.toggle('hidden', !show);
            visible += show ? 1 : 0;
        });
        popup.querySelector('[data-reorder-empty]').classList.toggle('hidden', visible > 0);
        popup.querySelector('.order-error').textContent = '';
    }

    window.markReorderAsOrdered = function (event) {
        event.preventDefault();
        const form = event.target;
        const checked = form.querySelectorAll('input[name="ordered_medicine[]"]:checked');
        if (!checked.length) {
            form.querySelector('.order-error').textContent = 'Please select at least one medicine.';
            return false;
        }
        checked.forEach((box) => {
            box.closest('[data-reorder-row]').dataset.ordered = '1';
            box.closest('label').outerHTML = '<label class="">Ordered</label>';
        });
        form.querySelector('.order-error').textContent = '';
        swal({ title: 'Medicine marked as ordered.', icon: 'success', timer: 1800, showConfirmButton: false });
        return false;
    };

    /* ---------- Segmented radio filters (label.selected) ---------- */
    document.addEventListener('change', function (event) {
        const radio = event.target;
        if (radio.type !== 'radio') { return; }
        const group = radio.closest('[data-reorder-filter], [data-notification-filter]');
        if (!group) { return; }

        group.querySelectorAll('label').forEach((label) => label.classList.toggle('selected', label.contains(radio)));

        if (group.hasAttribute('data-reorder-filter')) {
            filterReorderRows(radio.value);
        } else {
            document.querySelectorAll('[data-notification-type]').forEach((row) => {
                row.classList.toggle('hidden', radio.value !== 'all' && row.dataset.notificationType !== radio.value);
            });
        }
    });

    /* ---------- ip-consultation-request.blade.php ---------- */
    window.selectIpConsultation = function (id) {
        document.getElementById('selectedConsultationIdRequest').value = id;
    };

    function settleIpConsultation(status, modal) {
        const id = document.getElementById('selectedConsultationIdRequest').value;
        document.querySelectorAll(`[data-ip-consultation="${id}"] [data-ip-status]`).forEach((cell) => { cell.textContent = status; });
        document.querySelector(`#ip-view-${id} [data-ip-footer]`)?.classList.add('hidden');
        hideModal(modal);
        closePopup(document.getElementById(`ip-view-${id}`));
        swal({ title: `Consultation marked as ${status.toLowerCase()}.`, icon: 'success', timer: 1800, showConfirmButton: false });
    }

    const requireValue = (id, title) => {
        if (document.getElementById(id).value.trim()) { return true; }
        swal({ title, icon: 'warning', confirmButtonText: 'Ok' });
        return false;
    };

    window.markAsDispensed = function () {
        if (!requireValue('name', 'Please enter the name.')) { return; }
        if (!requireValue('gphcNumberRequest', 'Please enter the GPhC number.')) { return; }
        settleIpConsultation('Dispensed', '#markAsDispensedPopUp');
    };

    window.markAsCancel = function () {
        if (!requireValue('cancelPharmName', 'Please enter the name.')) { return; }
        if (!requireValue('cancelGphcNumberRequest', 'Please enter the GPhC number.')) { return; }
        if (!requireValue('parkedReason', 'Please select a valid reason.')) { return; }
        settleIpConsultation('Cancelled', '#markAsCancelPopUp');
    };

    /* ---------- Print icons (the live site renders a PDF) ---------- */
    window.printHtml = printHtml;
    function printHtml(title, html) {
        const win = window.open('', '_blank');
        if (!win) { return; }
        win.document.write(`<!DOCTYPE html><html><head><title>${title}</title>
            <style>body{font-family:Poppins,Arial,sans-serif;color:#1B2559;padding:24px}h2{font-size:16px}
            table{width:100%;border-collapse:collapse;font-size:12px}th,td{text-align:left;padding:8px;border-bottom:1px solid #E9EDF7}
            th{color:#68759A;font-weight:500}.hidden,input,button{display:none!important}</style></head>
            <body><h2>${title}</h2>${html}</body></html>`);
        win.document.close();
        win.focus();
        win.print();
    }

    window.printPopupTable = function (el) {
        const popup = el.closest('[data-popup]');
        printHtml(popup.querySelector('h2').textContent.trim(), popup.querySelector('table').outerHTML);
    };

    // Dispensed Consultations > GP Letter: gp-letter.pdf upstream.
    window.printGpLetter = function (el) {
        printHtml('GP Letter', el.closest('[data-popup]').querySelector('[data-letter-body]').innerHTML);
    };

    window.printIpConsultation = function (el) {
        const popup = el.closest('[data-popup]');
        printHtml(popup.querySelector('h2').textContent.trim(), popup.querySelector('.overflow-y-auto').innerHTML);
    };

    /* ---------- admin/includes/patient/patient-info.blade.php ---------- */
    window.showNote = function (url) {
        swal({
            html: `<div class="inline-block mb-2 p-3 rounded-md bg-red-50 text-red-700 border border-red-200 text-left text-sm">
                <b>Note :</b> Due to NHS guidelines, pharmacies can no longer order medicine on a patient's behalf. This option has been disabled. Patients must place orders themselves via the customer panel or app.
            </div>`,
            icon: 'warning',
            confirmButtonColor: '#3085d6',
            confirmButtonText: 'OK',
        }).then((result) => {
            if (result.isConfirmed) { location.href = url; }
        });
    };

    window.updatePatientStatus = function (button, approve) {
        swal({
            title: 'Are you sure?',
            text: approve ? 'Do you want to approve this patient.' : 'Do you want to reject this patient.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Yes',
        }).then((result) => {
            if (!result.isConfirmed) { return; }
            button.closest('[data-patient-status-actions]').remove();
            swal({ title: approve ? 'Patient approved.' : 'Patient rejected.', icon: 'success', timer: 1800, showConfirmButton: false });
        });
    };

    /* ---------- admin/includes/chatinterface.blade.php ---------- */
    window.sendMessage = function () {
        const input = document.getElementById('message');
        const output = document.getElementById('output');
        const text = input?.value.trim();
        if (!text || !output) { return; }
        const now = new Date();
        const time = `${String(now.getHours()).padStart(2, '0')}:${String(now.getMinutes()).padStart(2, '0')}`;
        const row = document.createElement('div');
        row.className = 'flex space-x-3 justify-end mb-2';
        row.innerHTML = `<div><div class="massage text-white text-base bg-gradient-to-t from-[#002B56] to-[#0070D5] px-7 py-1 rounded-3xl rounded-br-none max-w-sm py-3"></div><span class="block text-right mt-1">${time}</span></div><div class="w-10 h-10 overflow-hidden rounded-full"><img src="${output.dataset.senderImage}" alt="" class="w-full h-full object-cover"></div>`;
        row.querySelector('.massage').textContent = text;
        output.appendChild(row);
        output.scrollTop = output.scrollHeight;
        input.value = '';
    };

    document.addEventListener('keydown', function (event) {
        if (event.key === 'Enter' && event.target.id === 'message') {
            event.preventDefault();
            sendMessage();
        }
    });

    /* ---------- Client-side list searches (Livewire search upstream) ---------- */
    document.addEventListener('input', function (event) {
        const input = event.target;
        if (!input.matches('[data-list-search]')) { return; }
        const term = input.value.trim().toLowerCase();
        document.querySelectorAll(input.dataset.listSearch).forEach((row) => {
            row.classList.toggle('hidden', term !== '' && !row.textContent.toLowerCase().includes(term));
        });
    });

    /* ---------- Paginated lists (Livewire WithPagination upstream) ----------
       Page links, the search box (wire:model.live.debounce.300ms) and the
       per-page select fetch the same page with the new query and swap its
       [data-live-region]s, so the list re-renders in place as Livewire's does. */
    const liveRequests = new WeakMap();

    async function liveLoad(root, url) {
        const token = {};
        liveRequests.set(root, token);
        const html = await (await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })).text();
        if (liveRequests.get(root) !== token) { return; }

        const fresh = new DOMParser().parseFromString(html, 'text/html').querySelector(`[data-live="${root.dataset.live}"]`);
        if (!fresh) { window.location.href = url; return; }
        root.querySelectorAll('[data-live-region]').forEach((region) => {
            const replacement = fresh.querySelector(`[data-live-region="${region.dataset.liveRegion}"]`);
            if (replacement) { region.replaceWith(document.importNode(replacement, true)); }
        });

        window.history.replaceState(null, '', url);
        try { window.parent.syncPortalPath?.(window.location.pathname + window.location.search); } catch (e) { /* not in the preview shell */ }
    }

    function liveUrl(root, changes) {
        const url = new URL(window.location.href);
        url.searchParams.delete(root.dataset.livePageName || 'page');
        Object.entries(changes).forEach(([key, value]) => (value ? url.searchParams.set(key, value) : url.searchParams.delete(key)));
        return url.toString();
    }

    document.addEventListener('click', function (event) {
        const link = event.target.closest('[data-live] a[data-live-page]');
        if (!link || event.metaKey || event.ctrlKey || event.shiftKey) { return; }
        event.preventDefault();
        liveLoad(link.closest('[data-live]'), link.href);
    });

    let liveSearchTimer;
    document.addEventListener('input', function (event) {
        const input = event.target;
        if (!input.matches('[data-live] [data-live-search]')) { return; }
        clearTimeout(liveSearchTimer);
        liveSearchTimer = setTimeout(() => {
            const root = input.closest('[data-live]');
            liveLoad(root, liveUrl(root, { search: input.value.trim() }));
        }, 300);
    });

    document.addEventListener('change', function (event) {
        const select = event.target;
        if (!select.matches('[data-live] [data-live-per-page]')) { return; }
        const root = select.closest('[data-live]');
        liveLoad(root, liveUrl(root, { perPage: select.value }));
    });

    document.addEventListener('DOMContentLoaded', function () {
        const output = document.getElementById('output');
        if (output) { output.scrollTop = output.scrollHeight; }
    });
})();
