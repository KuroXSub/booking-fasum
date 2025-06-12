import Alpine from 'alpinejs';
import bookingForm from './booking-form.js';

Alpine.data('bookingForm', bookingForm);

window.Alpine = Alpine;

Alpine.start();