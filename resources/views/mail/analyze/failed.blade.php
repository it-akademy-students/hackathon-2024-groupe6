@component('mail::message')
    # Bonjour

    L'analyse de votre repository a échoué...


    <code>
        <pre>
            {{ $error }}
        </pre>
    </code>

    <font color="#CC5B28"><b>L’équipe CyberPulseParagon</b></font>
@endcomponent
