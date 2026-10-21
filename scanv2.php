<?= $this->extend('templates/starting_page_layout'); ?>

<?= $this->section('navaction') ?>
<?php
   $user = auth()->user();
   $dashUrl = '/admin';
   if ($user && $user->inGroup('superadmin')) {
      $dashUrl = '/admin';
   } elseif ($user && $user->inGroup('admin')) {
      $dashUrl = '/admin';
   } elseif ($user && $user->inGroup('kepsek')) {
      $dashUrl = '/admin';
   } elseif ($user && $user->inGroup('guru')) {
      $dashUrl = '/teacher/dashboard';
   } elseif ($user && $user->inGroup('scanner')) {
      $dashUrl = '/scan';
   }
?>
<a href="<?= base_url('/cek-kehadiran'); ?> " class="btn btn-default pull-right pl-3 mr-2">
   <i class="material-icons mr-2">visibility</i>
   Cek Kehadiran
</a>

<a href="<?= base_url('/izin'); ?>" class="btn btn-info pull-right pl-3 mr-2">
   <i class="material-icons mr-2">mail</i>
   Ajukan Izin
</a>

<a href="<?= base_url($dashUrl); ?>" class="btn btn-primary pull-right pl-3">
   <i class="material-icons mr-2">dashboard</i>
   Dashboard
</a>

<a href="<?= base_url('/logout'); ?> " class="btn btn-danger pull-right pl-3">
   <i class="material-icons mr-2">exit_to_app</i>
   Logout
</a>
<?= $this->endSection() ?>

<?= $this->section('content'); ?>
<?php
$oppBtn = '';

$waktu == 'Masuk' ? $oppBtn = 'pulang' : $oppBtn = 'masuk';
?>
<div class="main-panel">
   <div class="content pt-2 px-0 px-sm-1 px-md-2">
      <div class="container-fluid px-0 px-md-2">
         <div class="row mx-auto">
            <div class="col-lg-6 col-xxl-5 order-1 order-lg-2">
               <div class="card">
                  <div class="col-10 mx-auto card-header card-header-primary">
                     <div class="row">
                        <div class="col-md-6">
                           <h4 class="card-title"><b>Absen <?= $waktu; ?></b></h4>
                           <p class="card-category text-nowrap">Silahkan tunjukkan QR Code anda</p>
                        </div>
                        <div class="col-md-6 d-flex justify-content-center justify-content-md-end">
                           <a href="<?= base_url("scan/$oppBtn"); ?>" class="btn btn-<?= $oppBtn == 'masuk' ? 'success' : 'warning'; ?> px-3">
                              Absen <?= $oppBtn; ?>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="card-body my-auto px-5">
                     <div class="togglebutton mb-3 text-center">
                        <label>
                           <input type="checkbox" id="toggleKamera" checked>
                           <span class="toggle"></span>
                           <b class="text-dark">Gunakan Kamera (Scan QR)</b>
                        </label>
                        <label class="ml-2">
                           <input type="checkbox" id="toggleRFID">
                           <span class="toggle"></span>
                           <b class="text-dark">RFID</b>
                        </label>
                        <label class="ml-2">
                           <input type="checkbox" id="toggleUSB">
                           <span class="toggle"></span>
                           <b class="text-dark">Scanner QR USB</b>
                        </label>
                     </div>

                     <div id="cameraSection">
                        <h4 class="d-inline">Pilih kamera</h4>

                        <select id="pilihKamera" class="custom-select w-50 ml-2" aria-label="Default select example" style="height: 35px;">
                           <option selected>Select camera devices</option>
                        </select>

                        <br>

                        <div class="row pt-2">
                           <div class="col-sm-12 mx-auto">
                              <div class="previewParent">
                                 <div class="text-center">
                                    <h4 class="d-none w-100" id="searching"><b>Mencari...</b></h4>
                                 </div>
                                 <video id="previewKamera"></video>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>

                  <div class="row mt-3" id="rfidSection" style="display: none;">
                     <div class="col-12 text-center">
                        <div id="rfidStatus" class="mb-2">
                           <span class="badge badge-pill badge-secondary" id="statusBadge">
                              <i class="material-icons" style="font-size: 14px; vertical-align: middle;">usb</i>
                              <span id="statusText">RFID Reader: Mencari...</span>
                           </span>
                        </div>
                        <input type="text" id="rfidInput" class="form-control mx-auto text-center" style="width: 80%; border: 2px solid #9c27b0; border-radius: 5px;" placeholder="Siap Scan Kartu RFID"
                           autofocus autocomplete="off">
                        <small class="text-muted d-block mt-1">Pastikan kotak di atas tetap berwarna ungu saat
                           scan.</small>
                     </div>
                  </div>

                  <div class="row mt-3" id="usbSection" style="display: none;">
                     <div class="col-12 text-center">
                        <div class="mb-2">
                           <span class="badge badge-pill badge-secondary" id="usbStatusBadge">
                              <i class="material-icons" style="font-size: 14px; vertical-align: middle;">usb</i>
                              <span id="usbStatusText">Scanner USB: Tidak aktif</span>
                           </span>
                        </div>
                        <input
                           type="text"
                           id="usbInput"
                           class="form-control mx-auto text-center"
                           style="width: 80%; border: 2px solid #2196f3; border-radius: 5px;"
                           placeholder="Aktifkan mode USB, lalu scan QR Code"
                           autocomplete="off"
                           autocapitalize="off"
                           spellcheck="false"
                           aria-label="Input hasil scan QR USB"
                        >
                        <small class="text-muted d-block mt-1">
                           Arahkan scanner ke QR Code. Pastikan scanner mengirim tombol Enter setelah membaca kode.
                        </small>
                     </div>
                  </div>

                  <div id="hasilScan" class="px-5 pb-4 pt-1"></div>
                  <br>
               </div>
            </div>
            <div class="col-lg-3 col-xxl-3 order-2 order-lg-1">
               <div class="card" id="tips-card">
                  <div class="card-body">
                     <h3 class="mt-2"><b>Tips</b></h3>
                     <ul class="pl-3">
                        <li>Tunjukkan qr code sampai terlihat jelas di kamera</li>
                        <li>Posisikan qr code tidak terlalu jauh maupun terlalu dekat</li>
                     </ul>
                  </div>
               </div>
            </div>
            <div class="col-lg-3 col-xxl-4 order-last">
               <div class="card" id="usage-card">
                  <div class="card-body">
                     <h3 class="mt-2"><b>Penggunaan</b></h3>
                     <ul class="pl-3">
                        <li>Jika berhasil scan maka akan muncul data siswa/guru dibawah preview kamera</li>
                        <li>Klik tombol <b><span class="text-success">Absen masuk</span> / <span class="text-warning">Absen
                                 pulang</span></b> untuk mengubah waktu absensi</li>
                        <li>Untuk melihat data absensi, klik tombol <span class="text-primary"><i class="material-icons" style="font-size: 16px;">dashboard</i> Dashboard</span></li>
                     </ul>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script type="text/javascript" src="<?= base_url('assets/js/plugins/zxing/zxing.min.js') ?>"></script>
<script type="text/javascript">
   let selectedDeviceId = null;
   let audio = new Audio("<?= base_url('assets/audio/beep.mp3'); ?>");
   const codeReader = new ZXing.BrowserMultiFormatReader();
   const sourceSelect = $('#pilihKamera');

   $(document).on('change', '#pilihKamera', function () {
      selectedDeviceId = $(this).val();

      if (codeReader && $('#toggleKamera').is(':checked')) {
         scanning = false;
         clearTimeout(scannerTimer);
         codeReader.reset();

         setTimeout(() => {
            initScanner();
         }, 300);
      }
   })

   $(document).on('change', '#toggleKamera', function () {
      if (this.checked) {
         $('#cameraSection').slideDown();

         setTimeout(() => {
            initScanner();
         }, 300);
      } else {
         scanning = false;
         clearTimeout(scannerTimer);
         codeReader.reset();
         $('#cameraSection').slideUp();
      }
   });

   $(document).on('change', '#toggleRFID', function () {
      if (this.checked) {
         // RFID dan USB memakai input keyboard, jadi aktifkan satu mode saja.
         $('#toggleUSB').prop('checked', false).trigger('change');
         $('#rfidSection').slideDown();
         $('#rfidInput').focus();
      } else {
         $('#rfidSection').slideUp();
      }
   });

   // Mode QR scanner USB HID Keyboard.
   $(document).on('change', '#toggleUSB', function () {
      if (this.checked) {
         // Matikan RFID dan kamera agar tidak ada dua sumber scan bersamaan.
         $('#toggleRFID').prop('checked', false).trigger('change');

         if ($('#toggleKamera').is(':checked')) {
            $('#toggleKamera').prop('checked', false).trigger('change');
         }

         $('#usbSection').stop(true, true).slideDown();
         $('#usbStatusBadge').removeClass('badge-secondary').addClass('badge-success');
         $('#usbStatusText').text('Scanner USB: Siap');
         $('#usbInput').css('border-color', '#4caf50').val('').trigger('focus');
      } else {
         $('#usbSection').stop(true, true).slideUp();
         $('#usbStatusBadge').removeClass('badge-success').addClass('badge-secondary');
         $('#usbStatusText').text('Scanner USB: Tidak aktif');
      }
   });

   // Terima kode scanner USB saat scanner mengirim tombol Enter.
   $('#usbInput').on('keydown', function (e) {
      if (e.key !== 'Enter' && e.which !== 13) {
         return;
      }

      e.preventDefault();

      const code = $(this).val().trim();
      $(this).val('');

      if (!code) {
         return;
      }

      console.log('QR USB Scanned:', code);
      cekData(code);

      // Siapkan input untuk scan berikutnya.
      setTimeout(() => {
         if ($('#toggleUSB').is(':checked')) {
            $('#usbInput').trigger('focus');
         }
      }, 100);
   });

   // Pertahankan fokus input USB saat mode USB aktif.
   $(document).on('click', function (e) {
      if (
         $('#toggleUSB').is(':checked') &&
         !$(e.target).closest(
            '#usbInput, #pilihKamera, #toggleKamera, #toggleRFID, #toggleUSB'
         ).length
      ) {
         $('#usbInput').trigger('focus');
      }
   });

   const previewParent = document.getElementById('previewParent');
   const preview = document.getElementById('previewKamera');

   let scanning = false;
   let scannerTimer = null;

   function initScanner() {
      if (!$('#toggleKamera').is(':checked')) {
         console.log("Scanner disabled by user toggle.");
         return;
      }

      // Jangan membuat scanner kedua jika scanner sebelumnya masih berjalan.
      if (scanning) {
         console.log("Scanner already running.");
         return;
      }

      codeReader.listVideoInputDevices()
         .then(videoInputDevices => {

            videoInputDevices.forEach(device =>
               console.log(`${device.label}, ${device.deviceId}`)
            );

            if (videoInputDevices.length < 1) {
               alert("Camera not found!");
               return;
            }

            if (selectedDeviceId == null) {
               if (videoInputDevices.length <= 1) {
                  selectedDeviceId = videoInputDevices[0].deviceId;
               } else {
                  selectedDeviceId = videoInputDevices[1].deviceId;
               }
            }

            if (!videoInputDevices.some(device => device.deviceId === selectedDeviceId)) {
               selectedDeviceId = videoInputDevices[0].deviceId;
            }

            sourceSelect.html('');

            videoInputDevices.forEach((element) => {
               const sourceOption = document.createElement('option');

               sourceOption.text = element.label || 'Camera';
               sourceOption.value = element.deviceId;

               if (element.deviceId == selectedDeviceId) {
                  sourceOption.selected = true;
               }

               sourceSelect.append(sourceOption);
            });

            $('#previewParent').removeClass('unpreview');
            $('#previewKamera').removeClass('d-none');
            $('#searching').addClass('d-none');

            scanning = true;

            /*
             * Gunakan API ZXing yang sama dengan program asli,
             * tetapi scanner dibuat satu kali dan tidak di-reset
             * setelah QR berhasil dibaca.
             */
            codeReader.decodeFromVideoDevice(
               selectedDeviceId,
               'previewKamera',
               (result, err) => {

                  if (result) {
                     console.log("QR Code:", result.text);

                     // Cegah QR yang sama terbaca berkali-kali
                     // dalam waktu singkat.
                     if (!scanning) {
                        return;
                     }

                     scanning = false;

                     cekData(result.text);

                     $('#previewKamera').addClass('d-none');
                     $('#previewParent').addClass('unpreview');
                     $('#searching').removeClass('d-none');

                     /*
                      * Jangan reset ZXing.
                      * Setelah jeda, aktifkan kembali penerimaan QR.
                      */
                     clearTimeout(scannerTimer);

                     scannerTimer = setTimeout(() => {
                        $('#previewKamera').removeClass('d-none');
                        $('#previewParent').removeClass('unpreview');
                        $('#searching').addClass('d-none');

                        scanning = true;
                     }, 1500);
                  }

                  // Error pembacaan frame biasa diabaikan.
                  // ZXing tetap melanjutkan proses membaca kamera.
               }
            );
         })
         .catch(err => {
            scanning = false;
            console.error("Camera error:", err);
         });
   }


   if (navigator.mediaDevices) {
      initScanner();
   } else {
      alert('Cannot access camera.');
   }

   async function cekData(code) {
      /*
       * Jangan gunakan jQuery.ajax() di sini.
       *
       * Pada instalasi Anda, request pertama berhasil tetapi request
       * berikutnya menghasilkan:
       * "TypeError: Cannot read properties of undefined (reading 'open')"
       *
       * Pesan tersebut berasal dari jalur AJAX (error callback cekData),
       * bukan dari proses decode QR. Karena itu gunakan fetch() untuk
       * mengirim data presensi tanpa melalui jQuery XHR.
       */
      try {
         const ajaxData = setAjaxData({
            'unique_code': code,
            'waktu': '<?= strtolower($waktu); ?>'
         });

         let postData;

         if (ajaxData instanceof URLSearchParams) {
            postData = ajaxData;
         } else if (typeof ajaxData === 'string') {
            postData = ajaxData;
         } else if (ajaxData && typeof ajaxData === 'object') {
            postData = new URLSearchParams(ajaxData);
         } else {
            // Fallback apabila setAjaxData() pada instalasi ini
            // tidak mengembalikan nilai.
            postData = new URLSearchParams({
               'unique_code': code,
               'waktu': '<?= strtolower($waktu); ?>'
            });
         }

         const response = await fetch("<?= base_url('scan/cek'); ?>", {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
               'X-Requested-With': 'XMLHttpRequest',
               'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8'
            },
            body: postData
         });

         if (!response.ok) {
            throw new Error('HTTP ' + response.status + ' ' + response.statusText);
         }

         const result = await response.text();

         audio.play().catch(() => {});
         console.log(result);
         $('#hasilScan').html(result);

         $('html, body').animate({
            scrollTop: $("#hasilScan").offset().top
         }, 500);

      } catch (error) {
         console.error('Presensi error:', error);
         $('#hasilScan').html(error.message || error);
      }
   }

   function clearData() {
      $('#hasilScan').html('');
   }

   // RFID Listener
   $('#rfidInput').on('keypress', function (e) {
      if (e.which == 13) {
         let code = $(this).val().trim();
         if (code.length > 0) {
            console.log("RFID Scanned: " + code);
            cekData(code);
            $(this).val('');
         }
      }
   });

   $(document).ready(function () {
      const rfidInput = $('#rfidInput');
      const statusBadge = $('#statusBadge');
      const statusText = $('#statusText');

      function updateStatus(focused) {
         if (focused) {
            statusBadge.removeClass('badge-secondary').addClass('badge-success');
            statusText.text('RFID Reader: Siap');
            rfidInput.css('border-color', '#4caf50');
         } else {
            statusBadge.removeClass('badge-success').addClass('badge-secondary');
            statusText.text('RFID Reader: Tidak Fokus (Klik Disini)');
            rfidInput.css('border-color', '#f44336');
         }
      }

      if ($('#toggleRFID').is(':checked')) {
         rfidInput.focus();
         updateStatus(true);
      } else {
         updateStatus(false);
      }

      rfidInput.on('focus', function () {
         updateStatus(true);
      });

      rfidInput.on('blur', function () {
         updateStatus(false);
         // Auto refocus after a short delay if not focusing on other inputs
         setTimeout(() => {
            if ($('#toggleRFID').is(':checked') && !$('#pilihKamera').is(':focus')) {
               rfidInput.focus();
            }
         }, 3000);
      });

      // Ensure focus remains on rfidInput if clicked elsewhere (except camera select)
      $(document).on('click', function (e) {
         if ($('#toggleRFID').is(':checked') && !$(e.target).closest('#pilihKamera, #toggleKamera, #toggleRFID').length) {
            rfidInput.focus();
         }
      });
   });
</script>

<?= $this->endSection(); ?>
