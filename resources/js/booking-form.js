export default function bookingForm(config) {
  return {
    // Gunakan data dari objek 'config'
    facilities: config.facilities,
    initialBooking: config.initialBooking,
    availabilityUrl: config.availabilityUrl,
    selectedFacilityId: config.initialBooking?.facility_id || '',
    selectedFacility: {},
    bookingDate: config.initialBooking?.booking_date || '',
    startTime: config.initialBooking?.start_time || null,
    endTime: config.initialBooking?.end_time || null,
    purpose: config.initialBooking?.purpose || '',
    bookedHours: [],
    specialDateInfo: null,
    startTimeError: null,
    endTimeError: null,
    datepicker: null,
    specialDate: null,
    currentOpeningTime: null,
    currentClosingTime: null,
    timeFeedback: {
        startTime: null,
        endTime: null
    },
    isCheckingAvailability: false,

    validationMessages: {
        date: null,
        startTime: null,
        endTime: null
    },
    timeFeedback: {
        startTime: null,
        endTime: null
    },
    purposeError: null,

    init() {
        this.minBookingDate = new Date();
        this.minBookingDate.setDate(this.minBookingDate.getDate() + 1);
        this.minBookingDate = this.minBookingDate.toISOString().split('T')[0];

        this.loadFlatpickrCSS();
        this.validatePurpose();

        if (this.initialBooking) {
            this.$nextTick(async () => {
                await this.updateFacilityDetails();
                if (this.bookingDate) {
                    await this.generateAvailableHours();
                }
            });
        }
    },

    loadFlatpickrCSS() {
        if (!document.getElementById('flatpickr-css')) {
            const link = document.createElement('link');
            link.id = 'flatpickr-css';
            link.rel = 'stylesheet';
            link.href = 'https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css';
            document.head.appendChild(link);
        }
    },
    
    initDatepicker() {
        if (this.datepickerInstance) {
            this.datepickerInstance.open();
            return;
        }
        
        if (typeof flatpickr === 'undefined') {
            const script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/flatpickr';
            script.onload = () => this.setupDatepicker();
            document.head.appendChild(script);
        } else {
            this.setupDatepicker();
        }
    },

    setupDatepicker() {
        this.datepickerInstance = flatpickr(this.$refs.dateInput, {
            minDate: this.minBookingDate,
            dateFormat: "Y-m-d",
            onChange: (selectedDates, dateStr) => {
                this.bookingDate = dateStr;
                this.generateAvailableHours();
            },
            onDayCreate: (dObj, dStr, fp, dayElem) => {
                if (this.selectedFacility.id) {
                    const date = new Date(dayElem.dateObj);
                    const dayOfWeek = date.getDay() === 0 ? 7 : date.getDay();
                    const availableDays = this.selectedFacility.available_days.map(Number);
                    
                    if (!availableDays.includes(dayOfWeek)) {
                        dayElem.classList.add('flatpickr-disabled');
                        dayElem.title = 'Fasilitas tidak beroperasi pada hari ini';
                    }
                }
            }
        });
        
        this.datepickerInstance.open();
    },
    
    async updateFacilityDetails() {
        this.selectedFacility = this.facilities.find(f => f.id == this.selectedFacilityId) || {};
        
        if (!this.initialBooking || this.bookingDate === '') {
            this.bookingDate = '';
            this.startTime = null;
            this.endTime = null;
        }
        
        this.bookedHours = [];
        this.specialDateInfo = null;
    },

    async generateAvailableHours() {
        this.isCheckingAvailability = true;
        this.validationMessages = { date: null, startTime: null, endTime: null }; 
        
        const originalStartTime = this.startTime;
        const originalEndTime = this.endTime;

        this.timeFeedback = { startTime: null, endTime: null }; 

        if (!this.isDateValid()) {
            this.isCheckingAvailability = false;
            return;
        }

        try {
            const url = new URL(this.availabilityUrl, window.location.origin);
            url.searchParams.append('facility_id', this.selectedFacilityId);
            url.searchParams.append('date', this.bookingDate);

            if (this.initialBooking?.id) {
                url.searchParams.append('exclude_booking_id', this.initialBooking.id);
            }

            const response = await fetch(url, {
                headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                credentials: 'include'
            });

            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
            const data = await response.json();
            if (data.error) throw new Error(data.error);

            this.bookedHours = data.bookedSlots || [];
            this.operatingHours = data.operatingHours;

            if (data.specialDate) {
                if (data.specialDate.is_closed) {
                    this.validationMessages.date = `⚠️ Fasilitas tutup pada tanggal ini: ${data.specialDate.reason || 'Libur'}`;
                    this.specialDateInfo = this.validationMessages.date;
                } else {
                    this.specialDateInfo = `📅 Jadwal khusus berlaku hari ini (${data.operatingHours.opening} - ${data.operatingHours.closing})`;
                    this.currentOpeningTime = data.operatingHours.opening;
                    this.currentClosingTime = data.operatingHours.closing;
                }
            } else {
                this.specialDateInfo = null;
                this.currentOpeningTime = data.operatingHours.opening;
                this.currentClosingTime = data.operatingHours.closing;
            }
            
            if (data.specialDate?.is_closed) {
                this.startTime = null;
                this.endTime = null;
            } else {
                 this.$nextTick(() => {
                    this.startTime = originalStartTime;
                    this.endTime = originalEndTime;
                });
            }

        } catch (error) {
            console.error('Error:', error);
            this.validationMessages.date = error.message || 'Gagal memeriksa ketersediaan. Silakan coba lagi.';
            this.specialDateInfo = this.validationMessages.date;
        } finally {
            this.isCheckingAvailability = false;
        }
    },

        validateStartTime() {
            this.timeFeedback.startTime = null; // Cukup reset satu variabel

            if (!this.startTime) return;

            const [startHour, startMinute] = this.startTime.split(':').map(Number);
            const [openHour, openMinute] = this.currentOpeningTime.split(':').map(Number);
            const [closeHour, closeMinute] = this.currentClosingTime.split(':').map(Number);

            const startValue = startHour * 60 + startMinute;
            const openValue = openHour * 60 + openMinute;
            const closeValue = closeHour * 60 + closeMinute;

            let errorFound = false;
            let message = '';

            if (this.bookedHours.includes(this.startTime)) {
                message = '⛔ Waktu ini sudah dipesan';
                errorFound = true;
            } else if (startValue < openValue) {
                message = `⏰ Tidak boleh sebelum ${this.currentOpeningTime}`;
                errorFound = true;
            } else if (startValue >= closeValue) {
                message = `⏰ Tidak boleh setelah ${this.currentClosingTime}`;
                errorFound = true;
            }

            if (errorFound) {
                this.timeFeedback.startTime = { type: 'error', message: message };
                this.startTime = null; // Reset input jika ada error
            } else {
                this.timeFeedback.startTime = { type: 'success', message: '✅ Waktu tersedia' };
                this.updateEndTimeOptions();
            }
        },
        
        validateEndTime() {
            this.validationMessages.endTime = null;
            this.timeFeedback.endTime = null;
            
            if (!this.endTime) return;
            
            const duration = this.duration;
            
            if (duration <= 0) {
                this.validationMessages.endTime = 'Jam selesai harus setelah jam mulai';
                this.timeFeedback.endTime = {
                    type: 'error',
                    message: this.validationMessages.endTime
                };
                this.endTime = null;
            } else if (duration > this.selectedFacility.max_booking_hours) {
                this.validationMessages.endTime = `Durasi maksimal ${this.selectedFacility.max_booking_hours} jam`;
                this.timeFeedback.endTime = {
                    type: 'error',
                    message: this.validationMessages.endTime
                };
            } else {
                this.timeFeedback.endTime = {
                    type: 'success',
                    message: `Durasi: ${duration} jam`
                };
            }
            
            // Additional check for closing time
            if (this.endTime && this.currentClosingTime) {
                const [endHour, endMinute] = this.endTime.split(':').map(Number);
                const [closeHour, closeMinute] = this.currentClosingTime.split(':').map(Number);
                
                const endValue = endHour * 60 + endMinute;
                const closeValue = closeHour * 60 + closeMinute;
                
                if (endValue > closeValue) {
                    this.validationMessages.endTime = `⏰ Tidak boleh setelah ${this.currentClosingTime}`;
                    this.timeFeedback.endTime = {
                        type: 'error',
                        message: this.validationMessages.endTime
                    };
                    this.endTime = null;
                }
            }
        },

        selectStartTime(time) {
            this.startTime = time;
            this.validateStartTime();
        },
        
        formatTime(timeStr) {
            if (!timeStr) return '';
            const [hours, minutes] = timeStr.includes(':') ? timeStr.split(':') : [timeStr, '00'];
            return `${hours.padStart(2, '0')}:${minutes.padStart(2, '0')}`;
        },
        
        updateEndTimeOptions() {
            this.endTime = null;
            this.endTimeError = null;
            
            if (!this.startTime) return;
            
            // Format waktu untuk tampilan user
            const formatDisplayTime = (timeStr) => {
                if (!timeStr) return '';
                const [hours, minutes] = timeStr.split(':');
                return `${hours.padStart(2, '0')}:${minutes || '00'}`;
            };

            // Validasi start time
            if (this.bookedHours.includes(this.startTime)) {
                this.startTimeError = 'Jam ini sudah dipesan. Silakan pilih jam lain.';
                this.startTime = null;
                return;
            }
            
            const startHour = parseInt(this.startTime.split(':')[0]);
            const openingHour = parseInt(this.selectedFacility.opening_time.split(':')[0]);
            const closingHour = parseInt(this.selectedFacility.closing_time.split(':')[0]);
            const openingTimeDisplay = formatDisplayTime(this.selectedFacility.opening_time);
            const closingTimeDisplay = formatDisplayTime(this.selectedFacility.closing_time);
            
            if (startHour < openingHour) {
                this.startTimeError = `Jam mulai tidak boleh sebelum ${openingTimeDisplay}`;
                this.startTime = null;
            } else if (startHour >= closingHour) {
                this.startTimeError = `Jam mulai tidak boleh setelah ${closingTimeDisplay}`;
                this.startTime = null;
            }
        },

        validatePurpose() {
            const trimmedPurpose = this.purpose.trim();
            if (trimmedPurpose.length > 0 && trimmedPurpose.length < 10) {
                this.purposeError = 'Tujuan peminjaman minimal 10 karakter.';
            } else {
                this.purposeError = null;
            }
        },
        
        getNextHour(time) {
            if (!time) return '';
            const [hours] = time.split(':');
            return `${parseInt(hours) + 1}:00`;
        },
        
        getMaxEndTime() {
            if (!this.startTime) return '';
            
            const startHour = parseInt(this.startTime.split(':')[0]);
            const closingHour = parseInt(this.selectedFacility.closing_time.split(':')[0]);
            const maxDuration = this.selectedFacility.max_booking_hours;
            
            return `${Math.min(startHour + maxDuration, closingHour)}:00`;
        },
        
        isDayAvailable(dateString) {
            if (!dateString || !Array.isArray(this.selectedFacility.available_days)) return true;
            
            const date = new Date(dateString + 'T00:00:00');
            const dayOfWeek = date.getDay() === 0 ? 7 : date.getDay();
            const availableDays = this.selectedFacility.available_days.map(Number);
            
            return availableDays.includes(dayOfWeek);
        },
        
        get dateHelperText() {
            if (!this.selectedFacility.id) return '';
            if (this.bookingDate && !this.isDayAvailable(this.bookingDate)) {
                return 'Fasilitas tidak beroperasi pada hari yang Anda pilih.';
            }
            return 'Pemesanan hanya bisa dilakukan paling cepat untuk H-1 (besok).';
        },
        
        isDateValid() {
            return this.bookingDate && this.isDayAvailable(this.bookingDate); // 
        },
        
        get availableStartHours() {
            if (!this.isDateValid()) return [];
            
            const open = parseInt(this.selectedFacility.opening_time.split(':')[0]);
            const close = parseInt(this.selectedFacility.closing_time.split(':')[0]);
            let hours = [];
            
            for (let h = open; h < close; h++) {
                const time = `${h}:00`;
                const isBooked = this.bookedHours.includes(time);
                hours.push({
                    value: time,
                    label: `${String(h).padStart(2, '0')}:00`,
                    disabled: isBooked
                });
            }
            
            return hours;
        },
        
        get duration() {
            if (!this.startTime || !this.endTime) return 0;
            return parseInt(this.endTime.split(':')[0]) - parseInt(this.startTime.split(':')[0]); // 
        },
        
        get isFormComplete() {
            return !!(
                this.selectedFacility.id && 
                this.isDateValid() && 
                this.startTime && 
                this.endTime && 
                this.duration > 0 &&
                this.duration <= this.selectedFacility.max_booking_hours &&
                this.purpose && this.purpose.trim() !== '' &&
                !this.purposeError
            );
        }
    };
};