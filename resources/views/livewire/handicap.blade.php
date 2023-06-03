<div class="profile">
    <h2>Dieetwensen</h2>

    <form action="#" wire:submit.prevent="store">
        <p>
            <input
                type="text"
                name="handicap"
                placeholder="Geen wensen"
                wire:model="handicap"
            />
        </p>
        <p>
            <button type="submit">Dieetwensen instellen</button>
            @if ($saved)
                <div class="notification success">
                    Dieetwensen opgeslagen
                </div>
            @endif
        </p>
    </form>
</div>
