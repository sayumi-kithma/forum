// Simple client-side validation to improve UX (server-side validation is the real safeguard)

function addValidation(formId) {
    const form = document.getElementById(formId);
    if (!form) return;

    form.addEventListener('submit', function (e) {
        let valid = true;
        const inputs = form.querySelectorAll('input[required], textarea[required]');

        inputs.forEach(function (input) {
            input.classList.remove('is-invalid');
            if (!input.value.trim()) {
                input.classList.add('is-invalid');
                valid = false;
            }
        });

        // Extra check: confirm password match on register form
        const password = form.querySelector('input[name="password"]');
        const confirm = form.querySelector('input[name="confirm_password"]');
        if (password && confirm && password.value !== confirm.value) {
            confirm.classList.add('is-invalid');
            valid = false;
        }

        if (!valid) {
            e.preventDefault();
        }
    });
}

document.addEventListener('DOMContentLoaded', function () {
    addValidation('registerForm');
    addValidation('loginForm');
    addValidation('threadForm');
    addValidation('replyForm');
});
