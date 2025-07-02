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
        datepicker: null,
        specialDate: null,
        currentOpeningTime: null,
        currentClosingTime: null,
        isCheckingAvailability: false,
    
        // --- State Baru ---
        selectedDuration: null, // State untuk menyimpan durasi yang dipilih (dalam jam)
    
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
                        // Hitung durasi awal jika sedang dalam mode edit
                        if (this.startTime && this.endTime) {
                            this.selectedDuration = parseInt(this.endTime.split(':')[0]) - parseInt(this.startTime.split(':')[0]);
                        }
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
                this.selectedDuration = null; // Reset durasi juga
            }
            
            this.bookedHours = [];
            this.specialDateInfo = null;
        },

        async generateAvailableHours() {
            this.isCheckingAvailability = true;
            this.validationMessages = { date: null, startTime: null, endTime: null };
            
            const originalStartTime = this.startTime;
            const originalEndTime = this.endTime;

            // Reset time selection when date changes
            this.startTime = null;
            this.endTime = null;
            this.selectedDuration = null;
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
                    }
                    this.currentOpeningTime = data.operatingHours.opening;
                    this.currentClosingTime = data.operatingHours.closing;
                } else {
                    this.specialDateInfo = null;
                    this.currentOpeningTime = data.operatingHours.opening;
                    this.currentClosingTime = data.operatingHours.closing;
                }
                
                if (data.specialDate?.is_closed) {
                    this.startTime = null;
                    this.endTime = null;
                    this.selectedDuration = null;
                } else {
                    // Restore original times if they exist (for edit mode)
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
            this.timeFeedback.startTime = null;

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
                this.startTime = null;
            } else {
                this.timeFeedback.startTime = { type: 'success', message: '✅ Waktu tersedia' };
                // DIPERBAIKI: Hapus pemanggilan fungsi yang tidak ada
                // this.updateEndTimeOptions(); 
            }
        },
        
        // DIHAPUS: Seluruh blok kode yang mengambang telah dihapus dari sini.

        selectStartTime(time) {
            // Reset endTime dan durasi setiap kali jam mulai baru dipilih
            this.endTime = null;
            this.selectedDuration = null;
            this.timeFeedback.endTime = null;
    
            this.startTime = time;
            this.validateStartTime();
        },
        
        // DIPERBAIKI: Koma yang salah sebelum fungsi ini sudah dihapus
        selectDuration(duration) {
            this.selectedDuration = duration;
            
            const startHour = parseInt(this.startTime.split(':')[0]);
            const endHour = startHour + duration;
            this.endTime = `${String(endHour).padStart(2, '0')}:00`;

            this.timeFeedback.endTime = {
                type: 'success',
                message: `Durasi: ${duration} jam. Selesai pukul ${this.endTime}.`
            };
        },

        formatTime(timeStr) {
            if (!timeStr) return '';
            const [hours, minutes] = timeStr.includes(':') ? timeStr.split(':') : [timeStr, '00'];
            return `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
        },

        validatePurpose() {
            const trimmedPurpose = this.purpose.trim();
            if (trimmedPurpose.length > 0 && trimmedPurpose.length < 10) {
                this.purposeError = 'Tujuan peminjaman minimal 10 karakter.';
            } else {
                this.purposeError = null;
            }
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
            return this.bookingDate && this.isDayAvailable(this.bookingDate);
        },
        
        get availableStartHours() {
            if (!this.isDateValid() || !this.currentOpeningTime || !this.currentClosingTime) return [];
            
            const open = parseInt(this.currentOpeningTime.split(':')[0]);
            const close = parseInt(this.currentClosingTime.split(':')[0]);
            let hours = [];
            
            for (let h = open; h < close; h++) {
                const time = `${String(h).padStart(2, '0')}:00`;
                const isBooked = this.bookedHours.includes(time);
                hours.push({
                    value: time,
                    label: time,
                    disabled: isBooked
                });
            }
            return hours;
        },
    
        get availableDurations() {
            if (!this.startTime || !this.selectedFacility.max_booking_hours || !this.currentClosingTime) return [];

            const maxDuration = this.selectedFacility.max_booking_hours;
            const startHour = parseInt(this.startTime.split(':')[0]);
            const closingHour = parseInt(this.currentClosingTime.split(':')[0]);

            let durations = [];
            for (let d = 1; d <= maxDuration; d++) {
                const potentialEndHour = startHour + d;

                if (potentialEndHour > closingHour) {
                    break;
                }

                let isBlocked = false;
                for (let h = startHour + 1; h < potentialEndHour; h++) {
                    const checkTime = `${String(h).padStart(2, '0')}:00`;
                    if (this.bookedHours.includes(checkTime)) {
                        isBlocked = true;
                        break;
                    }
                }

                if (isBlocked) {
                    break;
                }
                
                durations.push({
                    duration: d,
                    label: `${d} jam`
                });
            }
            return durations;
        },
          
        get duration() {
            if (!this.startTime || !this.endTime) return 0;
            return parseInt(this.endTime.split(':')[0]) - parseInt(this.startTime.split(':')[0]);
        },
            
        get isFormComplete() {
            return !!(
                this.selectedFacility.id && 
                this.isDateValid() && 
                this.startTime && 
                this.endTime && 
                this.duration > 0 &&
                this.duration <= this.selectedFacility.max_booking_hours &&
                this.purpose && this.purpose.trim().length >= 10 &&
                !this.purposeError &&
                !this.validationMessages.date
            );
        }
    };
};