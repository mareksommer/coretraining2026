/* Coretraining forms — AJAX submission, validation, spinner */
(function () {
    'use strict';

    var PHONE_RE = /^\+?[0-9]{7,15}$/;

    // ── Helpers ────────────────────────────────────────────────────────────

    function setError(fieldId, msg) {
        var el = document.getElementById(fieldId + '-error');
        var input = document.getElementById(fieldId) || document.querySelector('[name="' + fieldId + '"]');
        if (el) el.textContent = msg || '';
        if (input) {
            if (msg) {
                input.classList.add('is-invalid');
            } else {
                input.classList.remove('is-invalid');
            }
        }
    }

    function clearErrors(form) {
        form.querySelectorAll('.form-error').forEach(function (el) {
            el.textContent = '';
        });
        form.querySelectorAll('.is-invalid').forEach(function (el) {
            el.classList.remove('is-invalid');
        });
    }

    function showAlert(container, type, msg) {
        container.className = 'alert alert--' + type;
        container.textContent = msg;
        container.style.display = 'block';
        container.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }

    function hideAlert(container) {
        container.style.display = 'none';
    }

    function setLoading(btn, loading) {
        if (loading) {
            btn.disabled = true;
            btn.dataset.origText = btn.textContent;
            btn.innerHTML =
                '<svg class="spinner" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">' +
                '<circle cx="12" cy="12" r="10" stroke-opacity=".25"/>' +
                '<path d="M12 2a10 10 0 0 1 10 10" stroke-linecap="round"/>' +
                '</svg>' +
                'Odesílám…';
        } else {
            btn.disabled = false;
            btn.textContent = btn.dataset.origText || 'Odeslat';
        }
    }

    function getFormData(form) {
        var data = {};
        var fd = new FormData(form);
        fd.forEach(function (val, key) {
            data[key] = val;
        });
        return data;
    }

    // ── Validation ─────────────────────────────────────────────────────────

    function validateCommonFields(data, form) {
        var ok = true;

        if (!data.first_name || !data.first_name.trim()) {
            setError('first_name', 'Zadejte jméno.');
            ok = false;
        }
        if (!data.last_name || !data.last_name.trim()) {
            setError('last_name', 'Zadejte příjmení.');
            ok = false;
        }
        if (!data.email || !data.email.trim()) {
            setError('email', 'Zadejte e-mailovou adresu.');
            ok = false;
        } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(data.email.trim())) {
            setError('email', 'Neplatný formát e-mailové adresy.');
            ok = false;
        }
        if (!data.mobile || !data.mobile.trim()) {
            setError('mobile', 'Zadejte telefonní číslo.');
            ok = false;
        } else if (!PHONE_RE.test(data.mobile.trim())) {
            setError('mobile', 'Neplatný formát. Použijte +420XXXXXXXXX nebo 9 číslic.');
            ok = false;
        }
        if (!data.gdpr) {
            setError('gdpr', 'Souhlas se zpracováním osobních údajů je povinný.');
            ok = false;
        }

        return ok;
    }

    // ── Password confirm (registration) ────────────────────────────────────

    function bindPasswordConfirm(form) {
        var pw  = form.querySelector('#password');
        var pw2 = form.querySelector('#password_confirm');
        if (!pw || !pw2) return;

        function check() {
            if (pw.value && pw.value.length < 8) {
                setError('password', 'Heslo musí mít alespoň 8 znaků.');
            } else {
                setError('password', '');
            }
            if (pw2.value && pw.value !== pw2.value) {
                setError('password_confirm', 'Hesla se neshodují.');
            } else {
                setError('password_confirm', '');
            }
        }

        pw.addEventListener('blur', check);
        pw2.addEventListener('blur', check);
        pw2.addEventListener('input', function () {
            if (pw2.value && pw.value !== pw2.value) {
                setError('password_confirm', 'Hesla se neshodují.');
            } else {
                setError('password_confirm', '');
            }
        });
    }

    // ── AJAX submit ─────────────────────────────────────────────────────────

    function submitForm(endpoint, data, btn, alertBox, onSuccess) {
        setLoading(btn, true);
        hideAlert(alertBox);

        fetch(coretrainingData.restUrl + endpoint, {
            method:  'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce':   coretrainingData.nonce,
            },
            body: JSON.stringify(data),
        })
        .then(function (res) { return res.json(); })
        .then(function (json) {
            setLoading(btn, false);
            if (json.success) {
                if (json.redirect) {
                    window.location.href = json.redirect;
                } else {
                    onSuccess(json.message || 'Odesláno.');
                }
            } else {
                showAlert(alertBox, 'error', json.message || 'Nastala chyba. Zkuste to prosím znovu.');
            }
        })
        .catch(function () {
            setLoading(btn, false);
            showAlert(alertBox, 'error', 'Nepodařilo se odeslat formulář. Zkontrolujte připojení a zkuste znovu.');
        });
    }

    // ── Job response form ──────────────────────────────────────────────────

    var jobForm = document.getElementById('job-response-form');
    if (jobForm) {
        var jobAlertBox = document.getElementById('form-message');
        var jobBtn      = jobForm.querySelector('#submit-btn');

        jobForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(jobForm);
            hideAlert(jobAlertBox);

            var data = getFormData(jobForm);
            var ok   = validateCommonFields(data, jobForm);

            var pw = data.password || '';
            if (pw && pw.length < 8) {
                setError('password', 'Heslo musí mít alespoň 8 znaků.');
                ok = false;
            }

            if (!ok) return;

            submitForm('job-response', {
                job_id:        data.job_id,
                first_name:    data.first_name,
                last_name:     data.last_name,
                email:         data.email,
                mobile:        data.mobile,
                password:      pw,
                response_text: data.response_text || '',
                gdpr:          !!data.gdpr,
                website:       data.website || '',
            }, jobBtn, jobAlertBox, function (msg) {
                jobForm.style.display = 'none';
                showAlert(jobAlertBox, 'success', msg);
            });
        });
    }

    // ── Registration form ──────────────────────────────────────────────────

    var regForm = document.getElementById('register-form');
    if (regForm) {
        var regAlertBox = document.getElementById('form-message');
        var regBtn      = regForm.querySelector('#submit-btn');

        bindPasswordConfirm(regForm);

        regForm.addEventListener('submit', function (e) {
            e.preventDefault();
            clearErrors(regForm);
            hideAlert(regAlertBox);

            var data = getFormData(regForm);
            var ok   = validateCommonFields(data, regForm);

            if (!data.cost_center) {
                setError('cost_center', 'Vyberte, jako co chcete pracovat.');
                ok = false;
            }
            if (!data.password || data.password.length < 8) {
                setError('password', 'Heslo musí mít alespoň 8 znaků.');
                ok = false;
            }
            if (data.password !== data.password_confirm) {
                setError('password_confirm', 'Hesla se neshodují.');
                ok = false;
            }

            if (!ok) return;

            submitForm('register', {
                first_name:   data.first_name,
                last_name:    data.last_name,
                email:        data.email,
                mobile:       data.mobile,
                password:     data.password,
                cost_center:  data.cost_center,
                gdpr:         !!data.gdpr,
                website:      data.website || '',
            }, regBtn, regAlertBox, function () {});
        });
    }

})();
