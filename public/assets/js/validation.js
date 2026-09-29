/**
 * Global Form Prevalidation Script
 * Handles real-time feedback and pre-submission checks.
 */

document.addEventListener('DOMContentLoaded', function() {
    // --- GLOBAL AUTO-HINT DICTIONARY ---
    const globalHints = {
        'title': 'Tip: Use keywords in your title to help people discover your video!',
        'price': 'Tip: Check trending content to set a competitive price.',
        'description': 'Tip: Detailed descriptions improve your search ranking.',
        'location': 'Adding a location helps local viewers find your content.',
        'password': 'Use at least 8 characters with a mix of letters and numbers.',
        'email': 'Enter a valid email address for account security.',
        'username': 'Your unique handle on the platform.',
        'category_id': 'Selecting the right category is key for visibility.',
        'name': 'Enter the full name as you want it to appear.',
        'firstname': 'Enter your first name as it appears on official docs.',
        'lastname': 'Enter your last name or family name.',
        'subject': 'Tip: A clear subject helps us respond to your ticket faster.',
        'message': 'Provide as much detail as possible in your message.',
        'amount': 'Enter the numeric value (e.g., 10.00).',
        'mobile': 'Enter your mobile number with country code.',
        'address': 'Provide your full street address.',
        'city': 'The city where you are currently located.',
        'zip': 'Your postal or zip code.',
        'avatar': 'Tip: High-quality profile pictures build trust with your audience.'
    };

    const forms = document.querySelectorAll('form');

    forms.forEach(form => {
        // Add Honeypot protection to all POST forms
        if (form.method.toLowerCase() === 'post') {
            const hpField = document.createElement('div');
            hpField.style.display = 'none';
            hpField.innerHTML = `<input type="text" name="my_name_hp" value="" tabindex="-1" autocomplete="off">`;
            form.appendChild(hpField);
        }

        const inputs = form.querySelectorAll('input, select, textarea');

        inputs.forEach(input => {
            // --- AUTO-HINT INJECTION ---
            const name = input.getAttribute('name');
            const noHint = input.hasAttribute('data-no-hint');

            if (name && globalHints[name] && !noHint) {
                input.addEventListener('focus', function() {
                    // Search the entire component wrapper for a Blade hint
                    const componentWrapper = this.closest('[x-data]');
                    const bladeHint = componentWrapper ? componentWrapper.querySelector('[x-show="focused"]') : null;
                    
                    if (!bladeHint) {
                        showInputHint(this, globalHints[name]);
                    }
                });
                input.addEventListener('blur', function() {
                    removeInputHint(this);
                });
            }

            // ── Enhanced Security & Input Validation ──
            const handleSecurity = (element) => {
                const maliciousPattern = /<script|javascript:|onload=|onerror=|onmouseover=|iframe|object|embed/i;
                if (maliciousPattern.test(element.value)) {
                    showInputError(element, 'Security Alert: Malicious code/HTML blocked!');
                    element.value = element.value.replace(/<script.*?>.*?<\/script>|<script.*?>|javascript:|onload=|onerror=|onmouseover=|iframe|object|embed|<[^>]*>?/gi, '');
                    
                    // Visual "Shame" Animation
                    element.classList.add('animate-shake');
                    setTimeout(() => element.classList.remove('animate-shake'), 500);
                    return true;
                }
                return false;
            };

            input.addEventListener('input', function() {
                if (handleSecurity(this)) return;
                
                if (this.value.trim()) {
                    clearInputError(this);
                }
            });

            input.addEventListener('paste', function() {
                setTimeout(() => handleSecurity(this), 10);
            });
        });

        form.addEventListener('submit', function(e) {
            let hasError = false;
            const requiredInputs = form.querySelectorAll('[required]');
            
            requiredInputs.forEach(input => {
                if (!input.value.trim()) {
                    showInputError(input, 'This field is required');
                    hasError = true;
                } else {
                    clearInputError(input);
                }
            });

            if (hasError) {
                e.preventDefault();
                const firstError = form.querySelector('.border-red-500');
                if (firstError) {
                    firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    firstError.focus();
                }
            }
        });
    });

    function showInputHint(input, message) {
        let hintDiv = input.parentElement.querySelector('.auto-hint-msg');
        if (!hintDiv) {
            hintDiv = document.createElement('div');
            hintDiv.className = 'auto-hint-msg relative w-full mt-2 text-[10px] font-black text-orange-500 uppercase tracking-[0.15em] flex items-start gap-1.5 transition-all duration-300 opacity-0 transform -translate-y-1 leading-relaxed';
            hintDiv.innerHTML = `<span class="material-symbols-rounded text-xs flex-shrink-0 mt-0.5">lightbulb</span> <span class="flex-1">${message}</span>`;
            input.parentElement.appendChild(hintDiv);
            
            // Trigger animation
            setTimeout(() => {
                hintDiv.classList.remove('opacity-0', '-translate-y-1');
                hintDiv.classList.add('opacity-100', 'translate-y-0');
            }, 10);
        }
    }

    function removeInputHint(input) {
        const hintDiv = input.parentElement.querySelector('.auto-hint-msg');
        if (hintDiv) {
            hintDiv.classList.add('opacity-0', '-translate-y-1');
            setTimeout(() => hintDiv.remove(), 300);
        }
    }

    function showInputError(input, message) {
        input.classList.add('border-red-500', 'bg-red-50');
        input.classList.remove('border-gray-200', 'dark:border-white/10');
        
        let errorDiv = input.parentElement.querySelector('.validation-error-msg');
        if (!errorDiv) {
            errorDiv = document.createElement('div');
            errorDiv.className = 'validation-error-msg mt-1 text-xs text-red-500 font-medium flex items-center gap-1';
            errorDiv.innerHTML = `<span class="material-symbols-rounded text-sm">error</span> ${message}`;
            input.parentElement.appendChild(errorDiv);
        }
    }

    function clearInputError(input) {
        input.classList.remove('border-red-500', 'bg-red-50');
        input.classList.add('border-gray-200', 'dark:border-white/10');
        const errorDiv = input.parentElement.querySelector('.validation-error-msg');
        if (errorDiv) errorDiv.remove();
    }
});
