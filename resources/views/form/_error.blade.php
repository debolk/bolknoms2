@if (isset($errors) && $errors->count() > 0)
    <div class="notification error">
        <p><strong>De wijzigingen konden niet worden opgeslagen:</strong></p>
        <ul>
            @foreach ($errors->getBag('default')->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
