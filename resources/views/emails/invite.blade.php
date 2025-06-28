@component('mail::message')
    # Undangan Pendaftaran

    Anda menerima email ini karena diundang untuk mendaftar ke sistem kami.

    Klik tombol di bawah untuk menyelesaikan proses pendaftaran Anda:

    @component('mail::button', ['url' => $url])
        Daftar Sekarang
    @endcomponent

    Jika Anda tidak menginginkan email ini, Anda bisa mengabaikannya.

    Terima kasih,<br>
    {{ config('app.name') }}
@endcomponent
