class DateRangeValidator {
    constructor(selector) {
        this.inputs = document.querySelectorAll(selector);
        this.init();
    }

    init() {
        this.inputs.forEach(input => {
            input.addEventListener('change', () => this.updateRange(input));
            // Set initial min/max on page load
            this.updateRange(input);
        });
    }

    updateRange(changedInput) {
        const targetSelector = changedInput.dataset.rangeTarget;
        const rangeType = changedInput.dataset.rangeType;
        const targetInput = document.querySelector(targetSelector);

        if (!targetInput) return;

        if (rangeType === 'min') {
            // from_date: set min on to_date
            targetInput.min = changedInput.value || '';
        } else if (rangeType === 'max') {
            // to_date: set max on from_date
            targetInput.max = changedInput.value || '';
        }

        // Correct any invalid current value
        if (targetInput.value) {
            if (rangeType === 'min' && targetInput.value < changedInput.value) {
                targetInput.value = changedInput.value;
            }
            if (rangeType === 'max' && targetInput.value > changedInput.value) {
                targetInput.value = changedInput.value;
            }
        }
    }
}

new DateRangeValidator('.date-range');
