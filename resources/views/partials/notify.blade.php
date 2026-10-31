@if(session()->has('notify'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach(session('notify') as $msg)
                Swal.fire({
                    title: "{{ ucfirst($msg[0]) }}",
                    text: "{{ __($msg[1]) }}",
                    icon: "{{ $msg[0] }}",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                });
            @endforeach
        });
    </script>
@endif

@if(session()->has('success'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: "Success",
                text: "{{ session('success') }}",
                icon: "success",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        });
    </script>
@endif

@if(session()->has('error'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: "Error",
                text: "{{ session('error') }}",
                icon: "error",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        });
    </script>
@endif

@if(session()->has('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: "Info",
                text: "{{ session('info') }}",
                icon: "info",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        });
    </script>
@endif

@if(session()->has('status'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                title: "Info",
                text: "{{ session('status') }}",
                icon: "info",
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
            });
        });
    </script>
@endif

@if($errors->any())
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($errors->all() as $error)
                Swal.fire({
                    title: "Validation Error",
                    text: "{{ $error }}",
                    icon: "error",
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 5000,
                    timerProgressBar: true,
                    background: document.documentElement.classList.contains('dark') ? '#1A1A1A' : '#ffffff',
                    color: document.documentElement.classList.contains('dark') ? '#ffffff' : '#000000',
                });
            @endforeach
        });
    </script>
@endif
