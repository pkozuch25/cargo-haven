<div>
    <script>
        $(function() {
            // flatpickr

            window.addEventListener('flatpickr', event => {
                $(function () {
                    flatpickr(".flatpickr", {
                        "locale": "pl"
                    });

                    flatpickr(".flatpickr-range", {
                        mode: "range",
                        "locale": "pl"
                    });
                });

            });

            // SweetAlert2
            window.addEventListener('sweetAlert', event => {

                let type = event.detail[0].type
                let title = event.detail[0].title
                let text = event.detail[0].text
                let time = event.detail[0].time

                Swal.fire({
                    position: 'top-end',
                    background: '#1f2937',
                    color: '#ffffff',
                    icon: type,
                    title: title,
                    html: text,
                    showConfirmButton: false,
                    timer: time,
                    focusConfirm: false,
                    returnFocus: false,
                        didOpen: (popup) => {
                        popup.querySelector('input')?.blur();
                        popup.focus();
                    },
                    customClass: {
                    confirmButton: 'btn btn-primary',
                    },
                    buttonsStyling: false
                });

            });

            window.addEventListener('sweetAlertCenter', event => {

                let type = event.detail[0].type
                let title = event.detail[0].title
                let text = event.detail[0].text
                let time = event.detail[0].time

                text = text.replace(/\n/g, "<br />");

                Swal.fire({
                    position: 'center',
                    width: '1200px',
                    background: '#1f2937',
                    color: '#ffffff',
                    icon: type,
                    title: title,
                    html: text,
                    timer: time,
                    focusConfirm: false,
                    returnFocus: false,
                        didOpen: (popup) => {
                        popup.querySelector('input')?.blur();
                        popup.focus();
                    },
                    customClass: {
                    confirmButton: 'btn btn-primary',
                    },
                    buttonsStyling: false,
                    showConfirmButton: true,
                    confirmButtonText: "{{ __('Ok') }}",
                });

            });


            window.addEventListener('sweetAlertConfirm', event => {

                let type = event.detail[0].type;
                let title = event.detail[0].title;
                let text = event.detail[0].text;
                let method = event.detail[0].method;
                let id = event.detail[0].id;

                Swal.fire({
                    icon: type,
                    title: title,
                    html: text,
                    background: '#1f2937',
                    color: '#ffffff',
                    showCancelButton: true,
                    buttonsStyling: true,
                    reverseButtons: true,
                    allowEnterKey:true,
                    allowEscKey:true,
                    focusConfirm: false,
                    returnFocus: false,
                    confirmButtonColor: '#198754',
                        didOpen: (popup) => {
                        popup.querySelector('input')?.blur();
                        popup.focus();
                    },
                    confirmButtonText: "{{ __('Yes') }}",
                    cancelButtonText: "{{ __('Cancel') }}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit(method, id);
                    }
                });

            });

            window.addEventListener('sweetAlertConfirmWithNoButton', event => {
                let type = event.detail[0].type;
                let title = event.detail[0].title;
                let text = event.detail[0].text;
                let method = event.detail[0].method;
                let id = event.detail[0].id;

                Swal.fire({
                    icon: type,
                    title: title,
                    html: text,
                    background: '#1f2937',
                    color: '#ffffff',
                    showCancelButton: true,
                    buttonsStyling: true,
                    reverseButtons: true,
                    allowEnterKey:true,
                    allowEscKey:true,
                    focusConfirm: false,
                    returnFocus: false,
                        didOpen: (popup) => {
                        popup.querySelector('input')?.blur();
                        popup.focus();
                    },
                    confirmButtonText: "{{ __('Yes') }}",
                    cancelButtonText: "{{ __('No') }}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit(method, id);
                    }
                });
            });

            window.addEventListener('sweetAlertConfirmWithNoButtonAction', event => {

                let type = event.detail[0].type;
                let title = event.detail[0].title;
                let text = event.detail[0].text;
                let yes = event.detail[0].yes;
                let no = event.detail[0].no;

                Swal.fire({
                    icon: type,
                    title: title,
                    html: text,
                    background: '#1f2937',
                    color: '#ffffff',
                    showCancelButton: true,
                    buttonsStyling: true,
                    reverseButtons: true,
                    allowEnterKey:true,
                    allowEscKey:true,
                    focusConfirm: false,
                    returnFocus: false,
                        didOpen: (popup) => {
                        popup.querySelector('input')?.blur();
                        popup.focus();
                    },
                    confirmButtonText: "{{ __('Yes') }}",
                    cancelButtonText: "{{ __('No') }}",

                }).then((result) => {
                    if (result.isConfirmed) {
                        window.livewire.emit(yes[0], yes[1]);
                    } else {
                        window.livewire.emit(no[0], no[1]);
                    }
                });
            });

            // Closing modals custom function
            window.addEventListener('closeModal', event => {
                $('.modal').modal('hide');
            });

        })
    </script>
</div>
