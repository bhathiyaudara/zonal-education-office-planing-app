import Alpine from 'alpinejs';

window.Alpine = Alpine;

// Toast notification component
Alpine.data('toast', () => ({
    show: false,
    message: '',
    type: 'success',

    init() {
        // Auto-show flash messages
        const successMsg = document.querySelector('meta[name="flash-success"]');
        const errorMsg = document.querySelector('meta[name="flash-error"]');

        if (successMsg) {
            this.showToast(successMsg.content, 'success');
        }
        if (errorMsg) {
            this.showToast(errorMsg.content, 'error');
        }
    },

    showToast(msg, type = 'success') {
        this.message = msg;
        this.type = type;
        this.show = true;
        setTimeout(() => { this.show = false; }, 4000);
    }
}));

// Sidebar toggle
Alpine.data('sidebar', () => ({
    open: false,
    toggle() { this.open = !this.open; },
    close() { this.open = false; },
}));

// Dropdown component
Alpine.data('dropdown', () => ({
    open: false,
    toggle() { this.open = !this.open; },
    close() { this.open = false; },
}));

// Confirm delete
Alpine.data('confirmDelete', () => ({
    showModal: false,
    formAction: '',
    itemName: '',

    open(action, name) {
        this.formAction = action;
        this.itemName = name;
        this.showModal = true;
    },

    close() {
        this.showModal = false;
    },

    submit() {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = this.formAction;

        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrfInput);

        const methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        form.appendChild(methodInput);

        document.body.appendChild(form);
        form.submit();
    }
}));

Alpine.start();
