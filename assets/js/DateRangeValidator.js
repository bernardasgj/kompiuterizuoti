class DateRangeValidator {
    constructor(selector) {
        this.inputs = document.querySelectorAll(selector);
        this.init();
    }

    init() {
        this.inputs.forEach(input => {
            input.addEventListener('change', () => this.updateRange(input));
            this.updateRange(input);
        });
    }

    updateRange(changedInput) {
        const targetSelector = changedInput.dataset.rangeTarget;
        const rangeType = changedInput.dataset.rangeType;
        const targetInput = document.querySelector(targetSelector);

        if (!targetInput) return;

        if (rangeType === 'min') {
            targetInput.min = changedInput.value || '';
        } 
        
        if (rangeType === 'max') {
            targetInput.max = changedInput.value || '';
        }

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
