/**
 * CoreTraining — contact + course registration forms
 */
(function () {
    'use strict';

    if (typeof coretrainingForms === 'undefined') {
        return;
    }

    function setStatus(form, message, isError) {
        var status = form.querySelector('[data-form-status]');
        if (!status) {
            return;
        }
        status.textContent = message;
        status.classList.toggle('ct-form__status--error', !!isError);
        status.classList.toggle('ct-form__status--success', !isError);
    }

    function setLoading(form, loading) {
        var btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = loading;
        }
        form.classList.toggle('ct-form--loading', loading);
    }

    function getFormData(form) {
        var data = {};
        var fields = form.querySelectorAll('input, textarea, select');
        fields.forEach(function (field) {
            if (!field.name || field.type === 'submit') {
                return;
            }
            if (field.type === 'checkbox') {
                data[field.name] = field.checked;
            } else {
                data[field.name] = field.value.trim();
            }
        });
        return data;
    }

    function postForm(endpoint, payload) {
        return fetch(coretrainingForms.restUrl + endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-WP-Nonce': coretrainingForms.nonce,
            },
            body: JSON.stringify(payload),
        }).then(function (response) {
            return response.json().then(function (data) {
                return { ok: response.ok, status: response.status, data: data };
            });
        });
    }

    function handleError(result) {
        if (result.data && result.data.message) {
            return result.data.message;
        }
        if (result.status === 429) {
            return coretrainingForms.messages.rateLimit;
        }
        return coretrainingForms.messages.error;
    }

    document.querySelectorAll('[data-contact-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            setLoading(form, true);
            setStatus(form, '', false);

            var data = getFormData(form);
            postForm('/contact', {
                name: data.name,
                email: data.email,
                phone: data.phone || '',
                subject: data.subject || '',
                message: data.message,
                website: data.website || '',
                gdpr_consent: !!data.gdpr_consent,
            })
                .then(function (result) {
                    if (result.ok) {
                        form.reset();
                        setStatus(form, coretrainingForms.messages.success, false);
                    } else {
                        setStatus(form, handleError(result), true);
                    }
                })
                .catch(function () {
                    setStatus(form, coretrainingForms.messages.error, true);
                })
                .finally(function () {
                    setLoading(form, false);
                });
        });
    });

    document.querySelectorAll('[data-course-form]').forEach(function (form) {
        form.addEventListener('submit', function (event) {
            event.preventDefault();
            setLoading(form, true);
            setStatus(form, '', false);

            var data = getFormData(form);
            var courseId = parseInt(form.getAttribute('data-course-id'), 10);

            postForm('/course-registration', {
                course_id: courseId,
                name: data.name,
                email: data.email,
                phone: data.phone,
                address: data.address,
                note: data.note || '',
                website: data.website || '',
                gdpr_consent: !!data.gdpr_consent,
            })
                .then(function (result) {
                    if (result.ok) {
                        form.reset();
                        setStatus(form, coretrainingForms.messages.courseSuccess, false);
                    } else {
                        setStatus(form, handleError(result), true);
                    }
                })
                .catch(function () {
                    setStatus(form, coretrainingForms.messages.error, true);
                })
                .finally(function () {
                    setLoading(form, false);
                });
        });
    });
})();
